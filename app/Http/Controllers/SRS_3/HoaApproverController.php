<?php

namespace App\Http\Controllers\SRS_3;

use App\Http\Controllers\Controller;
use App\Jobs\SRS_3\SendApprovedNotificationJob;
use App\Jobs\SRS_3\SendRejectedNotificationJob;
use Carbon\Carbon;
use App\Models\SRS3_Model\SrsRequest;
use App\Models\LogHoaHist;
use Illuminate\Http\Request;
use App\Models\SrsAppointment;
use App\Models\SRS3_Model\SrsRequestStatus;
use App\Models\SrsRequestsArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\URL;

class HoaApproverController extends Controller
{
    /**
     * Show the HOA Approver Index Page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // $this->authorize('accessHoaApproval', SrsRequest::class);

        return view('hoa_approvers3.hoa_approvers_index');
    }

    /**
     * Return the List API of the SRS Requests
     *
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        // $this->authorize('accessHoaApproval', SrsRequest::class);

        if (!$request->ajax()) {
            abort(404);
        }

        $tableName = 'srs3_requests';
        $december = Carbon::now()->subYear()->month(12)->startOfMonth();

        $srsQuery = SrsRequest::query();

        $requests = $srsQuery->with('stats')
            ->when($request->type == 0, function ($query) {
                return $query->withTrashed();
            })
            ->when($request->type == 1, function ($query) {
                return $query->whereIn('status', [0, 1])
                    ->where(function ($query) {
                        $query->where('status', '<', 1)
                            ->orWhere(function ($query) {
                                $query->where('status', 1)
                                    ->whereHas('stats', function ($q) {
                                        $q->where('name', 'Approval - Admin');
                                    });
                            });
                    });
            })
            ->when($request->type == 2, function ($query) {
                return $query->where('status', '<', 2)
                    ->where('admin_approved', 0);
            })
            ->when($request->type == 3, function ($query) {
                return $query->onlyTrashed();
            })
            ->when($request->type == 4, function ($query) {
                return $query->where('status', 4);
            })
            ->select('request_id', 'first_name', 'last_name', 'status', 'created_at')
            ->whereIn('hoa_id', auth()->user()->hoa->pluck('id')->toArray())
            // ->where('hoa_id', auth()->user()->hoa_id ?? 0)
            ->where('created_at', '>=', $december);

        $datatable = DataTables::of($requests)
            ->filterColumn('requestor', function ($query, $keyword) use ($tableName) {
                $sql = "CONCAT(" . $tableName . ".first_name,' '," . $tableName . ".last_name)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?", ["%$keyword%"]);
            })
            ->filterColumn('request_id', function ($query, $keyword) {
                $query->whereRaw("request_id LIKE ?", ["%$keyword%"]);
            })
            ->addColumn('request_id', function ($request) {
                return '<a data-id="' . $request->request_id . '" class="view_request" href="/hoa-approvers3/' . $request->request_id . '">' . $request->request_id . '</a>';
            })
            ->addColumn('requestor', function ($request) {
                return $request->first_name . ' ' . $request->last_name;
            })
            ->editColumn('created_at', function ($request) {
                return $request->created_at->format('M d, Y h:i A');
            })
            ->editColumn('status', function ($request) {
                if ($request->trashed()) {
                    return 'Rejected';
                }

                if ($request->status == 61) {
                    return 'Rejected by Admin';
                } else if ($request->status == 62) {
                    return 'Rejected by HOA';
                }

                return $this->getStatus($request->status, $request->stats->where('name', 'Approval - Admin')->isNotEmpty());
            })
            ->rawColumns(['request_id'])
            ->make(true);

        return $datatable;
    }

    /**
     * Get the status of the request
     *
     * @param $status
     * @param $adminApproved
     * @return string
     */
    public function getStatus($status, $adminApproved)
    {
        switch ($status) {
            case 0:
                return 'Pending Approval';
                break;
            case 1:
                if ($adminApproved) {
                    return 'Approved by Admin';
                }
                return 'Approved by Enclave President';
                break;
            case 2:
                return 'Approved - Pending Appointment';
                break;
            case 3:
                return 'Appoinment Set';
                break;
            case 4:
                return 'Closed';
                break;
            case 5:
                return 'Archived';
                break;
        }
    }

    /**
     * Get the category name
     *
     * @param $categoryId
     * @return string
     */
    private function getCategoryName($categoryId)
    {
        switch ($categoryId) {
            case 1:
                return 'resident';
                break;
            case 2:
                return 'non-resident';
                break;
            case 3:
                return 'commercial';
                break;
        }
    }

    /**
     * Show the SRS Request
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        // $this->authorize('accessHoaApproval', SrsRequest::class);
        
        // check if url has archived parameter
        if (request()->segment(3) == 'archived') {
            $year = request()->segment(4);
            $tableName = 'srs_requests_archive_' . $year;

            if (!Schema::hasTable($tableName)) {
                abort(404);
            }

            $srsQuery = SrsRequestsArchive::fromTable($tableName);
        } else {
            $srsQuery = SrsRequest::query();
        }
        
        // Query the specific request with the necessary relationships
        $srsRequest = $srsQuery->with([
            // 'vehicles',
            'appointment',
            'appointment.timeslot',
            'files.requirement' => function ($q) {
                $q->select('id', 'description');
            },
            'vehicles3' => function ($q) {
                $q->select(
                    'id',
                    'srs_request_id',
                    'req_type',
                    'old_sticker_no',
                    'plate_no',
                    'brand',
                    'series',
                    'year_model',
                    'color',
                    'or_path',
                    'cr_path',
                    'cr',
                    'cr_from_crm',
                    'plate_no_remarks',
                    'color_remarks',
                    'req1',
                    'type'
                );
            },
            'statuses',
            'category3' => function ($q) {
                $q->select('id', 'name');
            },
            'subCategory3' => function ($q) {
                $q->select('id', 'name');
            },
            'hoa3' => function ($q) {
                $q->select('id', 'name', 'type');
            },

            'hoa3.hoaType' => function ($q) {
                $q->select('id', 'name');
            },
            'customer3' => function ($q) {
                $q->select(
                    'crm_id',
                    'customer_id',
                    'red_tag',
                    'reason_of_tag',
                );
            },
            'invoice' => function ($q) {
                $q->select('id', 'invoice_no');
            },
            'customer3.redTags' => function ($q) {
                $q->whereNull('status')
                    ->orWhere('status', 0)
                    ->orderBy('date_created', 'desc');
            }
        ])
            ->withTrashed()
            ->findOrFail($id);

        // Check if the request is not part of the HOA's list of requests or if the request is a non-resident request
        if (!in_array($srsRequest->hoa_id, auth()->user()->hoa->pluck('id')->toArray()) || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
            abort(404);
        }

        $vehicles = [];

        // Loop through the vehicles and push the necessary data to the $vehicles array
        foreach ($srsRequest->vehicles3 as $key => $vehicle) {

            $vehicles[] = [
                'key' => $key + 1,
                'req_type' => $vehicle->req_type ? 'Renewal' : 'New',
                'old_sticker_no' => $vehicle->old_sticker_no,
                'type' => $vehicle->type,
                'plate_no' => $vehicle->plate_no . ($vehicle->req_type == 1 && $vehicle->plate_no_remarks ? '<br> <b>[New: ' . $vehicle->plate_no_remarks . ']</b>' : ''),
                'brand' => $vehicle->brand,
                'series' => $vehicle->series,
                'year_model' => $vehicle->year_model,
                'color' => $vehicle->color . ($vehicle->req_type == 1 && $vehicle->color_remarks ? '<br> <b>[New: ' . $vehicle->color_remarks . ']</b>' : ''),
                'or' => $vehicle->or_path ? '<a data-value="/hoa-approvers/srs/uploads/' . $vehicle->or_path . '" data-type="' . (isset($vehicle->req1) && explode('.', $vehicle->req1)[1] == 'pdf' ? 'pdf' : 'img') . '" href="#" class="modal_img">OR</a>' : '',
                'cr' => $vehicle->cr || $vehicle->cr_path ? '<a data-value="' . ($vehicle->cr_from_crm ? 'crm_model/cr/' . $vehicle->cr : '/hoa-approvers/srs/uploads/' . $vehicle->cr_path) . '" data-type="' . ($vehicle->cr ? (explode('.', $vehicle->cr)[1] == 'pdf' ? 'pdf' : 'img') : 'img') . '" href="#" class="modal_img">CR</a>' : ''
            ];
        }

        $files = [];

        // Loop through the files and push the necessary data to the $files array
        foreach ($srsRequest->files as $key => $file) {
            $imgType = explode('.', $file->name)[1] == 'pdf' ? 'pdf' : 'img';
            $files[] = '<a data-value="/hoa-approvers/srs/uploads/' . $file->req_path . '" data-type="' . $imgType . '" href="#" class="modal_img">' . $file->requirement->description . '</a>';
        }

        // Get the statuses of the request
        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                ->where('srs3_requests.request_id', $srsRequest->request_id);
        }])->get();

        // Initialize the routes array with the first status of the request
        // $routes = [
        //     '<tr>
        //         <td style="background: #f2f7f9; color: #1e3237;text-align: left;min-width: 150px !important;"><label>Initiated</label></td>
        //         <td style="background: #f2f7f9; color: #1e3237;min-width: 150px !important;text-align: left;">' . htmlspecialchars($srsRequest->first_name . ' ' . $srsRequest->last_name) . '</td>
        //         <td style="background: #f2f7f9; color: #1e3237;max-width: 100px !important;">' . $srsRequest->created_at->format('m/d/Y h:i A') . '</td>
        //         <td style="background: #f2f7f9; color: #1e3237;max-width: 200px !important;text-align: left;"></td>
        //     </tr>'
        // ];

        // Routes without who
        $routes = [
            '<tr>
                <td style="background: #f2f7f9; color: #1e3237;text-align: left;min-width: 150px !important;"><label>Initiated</label></td>
                <td style="background: #f2f7f9; color: #1e3237;max-width: 100px !important;">' . $srsRequest->created_at->format('m/d/Y h:i A') . '</td>
                <td style="background: #f2f7f9; color: #1e3237;max-width: 200px !important;text-align: left;"></td>
            </tr>'
        ];

        // Loop through the statuses and push the necessary data to the $routes array
        foreach ($statuses as $status) {
            $row = '<tr>
                        <td style="text-align: left;min-width: 150px !important;"><label>' . $status->name . '</label></td>
                    ';

            // Check if the status has requests
            if ($status->requests->isNotEmpty()) {
                $row .= '<td style="min-width: 150px !important;text-align: left;">' . $status->requests[0]->pivot->action_by . '</td>
                         <td style="max-width: 100px !important;">' . $status->requests[0]->pivot->created_at->format('m/d/Y h:i A') . '</td>
                        ';
                // Check if the status is 'Appointment Set' and has an appointment
                if ($status->name == 'Appointment Set' && $srsRequest->appointment) {
                    $cell = '<td style="max-width: 200px !important;text-align: left;"> Appointment<br>
                            ' . $srsRequest->appointment->date->format('M d, Y') . '<br>
                            ' . $srsRequest->appointment->timeslot->time->format('h:i A');

                    // Check if the user can reset the appointment and if the request has no invoice
                    if (auth()->user()->can('reset', SrsAppointment::class) && !$srsRequest->invoice) {
                        $cell .= '<br><a data-value="' . $srsRequest->request_id . '" href="#" id="reset_appt_btn">Reset Appointment</a>';
                    }

                    $cell .= '</td>';

                    $row .= $cell;
                } else {
                    $row .= '<td style="max-width: 200px !important;text-align: left;"></td>';
                }
            }
            // If the status has no requests
            else {
                // Check if the status is 'Approval - Enclave President' and the request has no HOA
                if ($status->name == "Approval - Enclave President" && (!$srsRequest->hoa || $srsRequest->hoa->type != 0)) {
                    $row .= '<td style="min-width: 150px !important;text-align: left;">N/A</td>
                         <td style="max-width: 100px !important;">N/A</td>
                        ';
                } else {
                    $row .= '<td style="min-width: 150px !important;text-align: left;"></td>
                         <td style="max-width: 100px !important;"></td>
                        ';
                }
                $row .= '<td style="max-width: 200px !important;text-align: left;"></td>';
            }


            $row .= '</tr>';

            $routes[] = $row;
        }

        // Check if the request is trashed
        $adminApproved = $statuses->firstWhere('name', 'Approval - Admin')->requests->isNotEmpty();

        // Get the status of the request
        $status = $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved);

        return view('hoa_approvers3.hoa_approvers_show', compact('srsRequest', 'vehicles', 'files', 'routes', 'status'));
    }

    /**
     * Show the attachment file
     *
     * @param $id
     * @param $date
     * @param $name
     * @param $hoa
     * @param $category
     * @return mixed
     */
    public function showFile($id, $date, $name, $hoa, $category)
    {
        // $this->authorize('accessHoaApproval', SrsRequest::class);

        $dateTime = Carbon::parse($date);

        $path = storage_path('app/bffhai/' . $dateTime->format('Y') . '/' . $hoa . '/' . $this->getCategoryName($category) . '/' . $dateTime->format('m') . '/' . $name . '/' . $id);

        if (file_exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } else {
            abort(404);
        }
    }

    /**
     * Approve the request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hoaApproved(Request $request)
    {
        // $this->authorize('accessHoaApproval', SrsRequest::class);
        dd('Heelo');
        // Query SrsRequest with the request_id and does not have a status of 2
        $srsRequest = SrsRequest::where('request_id', $request->request_id)
            ->whereDoesntHave('statuses', function ($query) {
                $query->where('status_id', 2);
            })
            ->first();

        // Check if the request is not part of the HOA's list of requests or if the request is a non-resident request
        if (!in_array($srsRequest->hoa_id, auth()->user()->hoa->pluck('id')->toArray()) || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
            abort(404);
        }

        $actionBy = auth()->user()->email;
        $status = 1;

        // SRS Request Statuses are as follows:
        // Documentations:
        // 0 - Pending Approval
        // 1 - Approved by Enclave President
        // 2 - Approved - Pending Appointment
        // 3 - Appointment Set
        // 4 - Closed
        // 5 - Archived

        // Check if the request has a status of 1 // Approved by Enclave President
        if ($srsRequest->status == 1) {
            $this->sendRequestApprovedEmail($srsRequest);
            $status = 2;
        }

        // Update the status of the request
        $srsRequest->status = $status;
        $srsRequest->save();

        // Attach the status of the request
        $srsRequest->statuses()->attach(2, ['action_by' => $actionBy]);

        // Get the statuses of the request
        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                ->where('srs3_requests.request_id', $srsRequest->request_id);
        }])->get();

        // Check if the request is trashed
        $adminApproved = $statuses->firstWhere('name', 'Approval - Admin')->requests->isNotEmpty();
        $status = $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved);

        // Log the action
        LogHoaHist::create([
            'action_by' => auth()->user()->name,
            'action' => 'HOA Approved Request ID: ' . $srsRequest->request_id,
            'ip_address' => $request->ip()
        ]);

        // Return the response
        return response()->json([
            'status' => 1,
            'msg' => 'Request Approved!',
            'status_text' => $status,
            'action_by' => $actionBy,
            'updated_at' => $srsRequest->updated_at->format('m/d/Y h:i A')
        ]);
    }

    /**
     * Reject the request
     *
     * @param Request $request
     * @param SrsRequest $srsRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function hoaReject(Request $request, SrsRequest $srsRequest)
    {
        // $this->authorize('accessHoaApproval', SrsRequest::class);

        // Check if the request is not an ajax request
        if (!$request->ajax()) {
            return back();
        }

        // Check if the request is not part of the HOA's list of requests or if the request is a non-resident request
        if (!in_array($srsRequest->hoa_id, auth()->user()->hoa->pluck('id')->toArray()) || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
            abort(404);
        }

        // Validate the request
        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        // Get the email of the user
        $actionBy = auth()->user()->email;

        // Get the email of the HOA
        $srsRequest->load(['hoa']);

        $requestId = $srsRequest->request_id;
        $email = $srsRequest->email;

        $hoaEmails = [];

        // Check if the HOA has an email
        if ($srsRequest->hoa) {
            // Check if emailAdd1 is not null
            if ($srsRequest->hoa->emailAdd1) {
                $hoaEmails[] = $srsRequest->hoa->emailAdd1;
            }

            // Check if emailAdd2 is not null
            if ($srsRequest->hoa->emailAdd2) {
                $hoaEmails[] = $srsRequest->hoa->emailAdd2;
            }

            // Check if emailAdd3 is not null
            if ($srsRequest->hoa->emailAdd3) {
                $hoaEmails[] = $srsRequest->hoa->emailAdd3;
            }
        }

        // Update the status of the request
        $srsRequest->status = 62; // Rejected by HOA
        $srsRequest->reject_role = 10; // HOA
        $srsRequest->rejected_by = $actionBy; // HOA
        $srsRequest->reject_reason = $data['reason'];

        // Save the request
        $srsRequest->save();

        // Delete the request
        $srsRequest->delete();

        // Send the rejected email
        dispatch(new SendRejectedNotificationJob($srsRequest, $srsRequest->email, $data['reason'], $hoaEmails));

        // Get the statuses of the request
        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                ->where('srs3_requests.request_id', $srsRequest->request_id);
        }])->get();

        // Check if the request is trashed
        $adminApproved = $statuses->firstWhere('name', 'Approval - Admin')->requests->isNotEmpty();
        $status = $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved);

        // Log the action
        LogHoaHist::create([
            'action_by' => auth()->user()->name,
            'action' => 'HOA Rejected Request ID: ' . $srsRequest->request_id . ' Reason: ' . $data['reason'],
            'ip_address' => $request->ip()
        ]);

        // Return the response
        return response()->json([
            'status' => 1,
            'msg' => 'Request Rejected!',
            'status_text' => $status,
            'action_by' => $actionBy,
            'updated_at' => $srsRequest->updated_at->format('m/d/Y h:i A'),
            'reject_reason' => $data['reason']
        ]);
    }

    /**
     * Send the approved email
     *
     * @param SrsRequest $srsRequest
     * @return void
     */
    private function sendRequestApprovedEmail($srsRequest)
    {
        // Encrypt the request_id and create a temporary signed route
        $srn = Crypt::encrypt($srsRequest->request_id);
        $url = URL::temporarySignedRoute('request.appointment', now()->addDays(3), ['key' => $srn]);

        // Mail::to($srsRequest->email)->send(new RequestApproved($srsRequest, $url));

        // Send the approved email
        dispatch(new SendApprovedNotificationJob($srsRequest, $srsRequest->email, $url));
    }
}
