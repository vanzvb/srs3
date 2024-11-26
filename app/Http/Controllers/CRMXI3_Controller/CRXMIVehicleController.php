<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
use App\Models\CRMXI3_Model\CRMXIAddress;
use Illuminate\Http\Request;
use App\Models\CRMXI3_Model\CRXMIVehicle;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\CRMXI3_Model\CRXMIVehicleOwner;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\Models\LogVehicleHist;

class CRXMIVehicleController extends Controller
{
    private function insertLogVehicles($action)
    {
        $log = new LogVehicleHist();
        $log->action_by = auth()->user()->name;
        $log->action = $action;
        $log->save();
    }

    public function vehicleList(Request $req, $account_id)
    {
        $vehicles = CRXMIVehicle::
            // with(['vehicleOwner.categories' => function ($query){
            //     $query->select('id', 'name');
            // }])
            // ->with(['vehicleOwner.subcats' => function ($query){
            //     $query->select('id', 'name');
            // }])
            // ->with(['vehicleOwner.hoas' => function ($query){
            //     $query->select('id', 'name');
            // }])
            // ->with(['vehicleOwner.hoaTypes' => function ($query){
            //     $query->select('id', 'name');
            // }])
            // ->with(['vehicleOwner.vos' => function ($query){
            //     $query->select('id', 'name');
            // }])
            join('crmxi3_vehicle_owners as owner', 'owner.vehicle_id', '=', 'crmxi3_vehicles.id')
            ->leftJoin('crmxi3_address as acc_address', 'acc_address.id', '=', 'crmxi3_vehicles.address_id')
            ->leftJoin('crmxi3_categories as category', 'category.id', '=', 'acc_address.category_id')
            ->leftJoin('crmxi3_subcat as subcat', 'subcat.id', '=', 'acc_address.sub_category_id')
            ->leftJoin('crmxi3_hoas as hoas', 'hoas.id', '=', 'acc_address.hoa')
            ->leftJoin('crmxi3_hoa_types as hoa_type', 'hoa_type.id', '=', 'acc_address.hoa_type')
            ->leftJoin('crmxi3_vehicle_ownership_status as vos', 'vos.id', '=', 'crmxi3_vehicles.vehicle_ownership_status_id')
            ->leftJoin('crmx_bl_city as city', 'acc_address.city', '=', 'city.bl_id')
            ->where('crmxi3_vehicles.account_id', $account_id)
            ->select(
                'crmxi3_vehicles.id as vehicle_id',
                'crmxi3_vehicles.address_id',
                'crmxi3_vehicles.plate_no',
                'crmxi3_vehicles.brand',
                'crmxi3_vehicles.series',
                'crmxi3_vehicles.year_model',
                'crmxi3_vehicles.color',
                'crmxi3_vehicles.type',
                'crmxi3_vehicles.sticker_date',
                'crmxi3_vehicles.old_sticker_year',
                'crmxi3_vehicles.old_sticker_no',
                'crmxi3_vehicles.new_sticker_no',
                'crmxi3_vehicles.status',
                'crmxi3_vehicles.or_path',
                'crmxi3_vehicles.cr_path',
                'crmxi3_vehicles.orID',
                'crmxi3_vehicles.crID',
                'crmxi3_vehicles.req1',
                'crmxi3_vehicles.cr',
                'crmxi3_vehicles.red_tag',
                'owner.id as owner_id',
                'owner.firstname',
                'owner.middlename',
                'owner.lastname',
                'acc_address.street',
                'acc_address.building_name',
                'acc_address.subdivision_village',
                'acc_address.blk_lot',
                'acc_address.block',
                'acc_address.lot',
                'acc_address.house_number',
                'acc_address.city',
                'city.description as city_name',
                'acc_address.zipcode',
                'acc_address.category_id',
                'category.name as category_name',
                'acc_address.sub_category_id',
                'subcat.name as subcat_name',
                'acc_address.hoa',
                'hoas.name as hoa_name',
                'acc_address.hoa_type',
                'hoa_type.name as hoa_type_name',
                'crmxi3_vehicles.vehicle_ownership_status_id',
                'vos.name as vos_name',
                'owner.email',
                'owner.main_contact',
                'owner.secondary_contact',
                'owner.tertiary_contact'
            )->distinct();

        if ($req->has('to_search') && $req->filled('to_search')) {
            $to_search = $req->input('to_search');
            $vehicles = $vehicles->where(function ($query) use ($to_search) {
                $lowerKeyword = mb_strtolower($to_search, 'UTF-8');
                $likeKeyword = '%' . $lowerKeyword . '%';
                $query->whereRaw('LOWER(crmxi3_vehicles.plate_no) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(owner.firstname) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(owner.middlename) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(owner.lastname) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(acc_address.block) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(acc_address.lot) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(acc_address.house_number) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(acc_address.street) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(city.description) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(category.name) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(subcat.name) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(hoas.name) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(hoa_type.name) LIKE ?', [$likeKeyword])
                    ->orWhereRaw('LOWER(vos.name) LIKE ?', [$likeKeyword]);
                // ->orWhereHas('vehicleOwner', function ($q) use ($likeKeyword) {
                //     $q->where(function($query) use ($likeKeyword) {
                //         $query->where(DB::raw("LOWER(crmxi3_vehicle_owners.firstname)"), 'like', $likeKeyword)
                //         ->orWhere(DB::raw("LOWER(crmxi3_vehicle_owners.middlename)"), 'like', $likeKeyword)
                //         ->orWhere(DB::raw("LOWER(crmxi3_vehicle_owners.lastname)"), 'like', $likeKeyword);
                //     });
                // })
                // ->orWhereHas('vehicleOwner.categories', function ($q) use ($likeKeyword) {
                //     $q->where(function($query) use ($likeKeyword) {
                //         $query->where(DB::raw("LOWER(crmxi3_categories.name)"), 'like', $likeKeyword);
                //     });
                // })
                // ->orWhereHas('vehicleOwner.subcats', function ($q) use ($likeKeyword) {
                //     $q->where(function($query) use ($likeKeyword) {
                //         $query->where(DB::raw("LOWER(crmxi3_subcat.name)"), 'like', $likeKeyword);
                //     });
                // })
                // ->orWhereHas('vehicleOwner.hoas', function ($q) use ($likeKeyword) {
                //     $q->where(function($query) use ($likeKeyword) {
                //         $query->where(DB::raw("LOWER(crmxi3_hoas.name)"), 'like', $likeKeyword);
                //     });
                // })
                // ->orWhereHas('vehicleOwner.hoaTypes', function ($q) use ($likeKeyword) {
                //     $q->where(function($query) use ($likeKeyword) {
                //         $query->where(DB::raw("LOWER(crmxi3_hoa_types.name)"), 'like', $likeKeyword);
                //     });
                // })
                // ->orWhereHas('vehicleOwner.vos', function ($q) use ($likeKeyword) {
                //     $q->where(function($query) use ($likeKeyword) {
                //         $query->where(DB::raw("LOWER(crmxi3_vehicle_ownership_status.name)"), 'like', $likeKeyword);
                //     });
                // });
            });
        }
        return DataTables::eloquent($vehicles)
            ->addColumn('address', function ($vehicles) {
                // return $vehicles->blk_lot . ', ' . $vehicles->street . ($vehicles->building_name ? ', ' . $vehicles->building_name : '') . ($vehicles->subdivision_village ? ', ' . $vehicles->subdivision_village : '') . ($vehicles->hoa_name ? ', ' . $vehicles->hoa_name : '') . ($vehicles->city_name ? ', ' . $vehicles->city_name : '') . ($vehicles->zipcode ? ', ' . $vehicles->zipcode : '');
                return ($vehicles->block ? 'Blk ' . $vehicles->block : '') . ($vehicles->lot ? ' Lot ' . $vehicles->lot : '') . ($vehicles->house_number ? ', ' . $vehicles->house_number . ', ' : '') . ' ' . $vehicles->street . ($vehicles->building_name ? ', ' . $vehicles->building_name : '') . ($vehicles->hoa_name ? '' : ($vehicles->subdivision_village ? ', ' . $vehicles->subdivision_village : '')) . ($vehicles->hoa_name ? ', ' . $vehicles->hoa_name : '') . ($vehicles->city_name ? ', ' . $vehicles->city_name : '') . ($vehicles->zipcode ? ', ' . $vehicles->zipcode : '');
            })
            ->addColumn('previous_owner', function ($vehicles) {
                return '';
            })
            ->addIndexColumn()
            ->make(true);
    }
    // public function testInsert(Request $req)
    // {
    //     dd($req->all());
    // }

    public function insertVehicle(Request $req)
    {
        // return $req;
        // dd($req);
        // dd($req->all());
        // dd($req->only('toPass[0][plate]'));
        try {
            $vehicles = $req->toPass;
            $account_id = $req->account_id;

            // Initialize arrays to store image names
            $cr_images = [];
            $or_images = [];
            $name_vehicle_pic_image = [];
            $front_license_image = [];
            $back_license_image = [];


            // $this->validate($req, [
            //     'or' => 'nullable',
            //     'cr' => 'nullable',
            //     'vehicle_pic' => 'nullable'
            // ]);
            $this->validate($req, [
                'front_license.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'or.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'cr.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'vehicle_pic.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'back_license.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            DB::transaction(function () use ($req, $vehicles, $account_id, $cr_images, $or_images, $name_vehicle_pic_image, $front_license_image, $back_license_image) {
                for ($i = 0; $i < count($vehicles); $i++) {
                    $current_vehicle_id = $vehicles[$i]['current_vehicle_id'] ?? null;
                    $current_owner_id = $vehicles[$i]['current_owner_id'] ?? null;

                    // Check if the vehicle and owner already exists
                    if ($current_vehicle_id && $current_owner_id) {
                        // $hoatype = explode("-", $vehicles[$i]['hoa_type']);
                        DB::table('crmxi3_vehicles')
                            ->where('id', $vehicles[$i]['current_vehicle_id'])
                            ->update([
                                'address_id' => $vehicles[$i]['owner_address'],
                                'plate_no' => $vehicles[$i]['plate'],
                                'orID' => $vehicles[$i]['orID'],
                                'crID' => $vehicles[$i]['crID'],
                                'brand' => $vehicles[$i]['brand'],
                                'series' => $vehicles[$i]['vehicle_series'],
                                'year_model' => $vehicles[$i]['year_model'],
                                'color' => $vehicles[$i]['color'],
                                'type' => $vehicles[$i]['type'],
                                'old_sticker_no' => $vehicles[$i]['sticker_no'],
                                'old_sticker_year' => $vehicles[$i]['sticker_year'],
                                'status' => 1,
                                'vehicle_ownership_status_id' => $vehicles[$i]['vos'],
                                'updated_at' => date('Y-m-d H:i:s'),
                                // 'created_by' => Auth::id()
                            ]);


                        // Insert into crmxi3_vehicle_owners
                        DB::table('crmxi3_vehicle_owners')
                            ->where('id', $vehicles[$i]['current_owner_id'])
                            ->update([
                                'firstname' => $vehicles[$i]['first_name'],
                                'middlename' => $vehicles[$i]['middle_name'],
                                'lastname' => $vehicles[$i]['last_name'],
                                // 'street' => $vehicles[$i]['street'],
                                // 'building_name' => $vehicles[$i]['building_name'],
                                // 'subdivision_village' => $vehicles[$i]['subdivision'],
                                // 'blk_lot' => $vehicles[$i]['blk_lot'],
                                // 'block' => $vehicles[$i]['block'],
                                // 'lot' => $vehicles[$i]['lot'],
                                // 'house_number' => $vehicles[$i]['house_number'],
                                // 'city' => $vehicles[$i]['city'],
                                // 'zipcode' => $vehicles[$i]['zip_code'],
                                // 'category_id' => $vehicles[$i]['category_id'],
                                // 'sub_category_id' => $vehicles[$i]['sub_category_id'],
                                // 'hoa' => $vehicles[$i]['hoa'],
                                // 'hoa_type' => $hoatype[0],
                                'email' => $vehicles[$i]['email'],
                                'main_contact' => $vehicles[$i]['main_contact'],
                                'secondary_contact' => $vehicles[$i]['secondary_contact'],
                                // 'tertiary_contact' => $vehicles[$i]['tertiary_contact'],
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                        $this->insertLogVehicles('Updated CRM Vehicles, ACCOUNT ID ' . $account_id . ', plate_no: ' . $vehicles[$i]['plate'] . ', owner: ' . $vehicles[$i]['first_name'] . ' ' . $vehicles[$i]['middle_name'] . ' ' . $vehicles[$i]['last_name']);
                    } else {
                        // Process the 'cr' files
                        // dd($req->file("toPass.$i.cr"));
                        if ($req->file("toPass.$i.cr")) {
                            // foreach ($req->file("toPass.$i.cr") as $docu2) {
                            $docu2 = $req->file("toPass.$i.cr");
                            $name_cr = time() . rand(1, 100) . '.webp';
                            try {
                                $img = Image::make($docu2);
                                $img->resize(600, 600, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->encode('webp')->save(public_path('crm_model/cr') . '/' . $name_cr);
                            } catch (\Exception $e) {
                                $docu2->storeAs('crm_model/cr', $name_cr);
                            }

                            $cr_images[] = $name_cr;
                            // }
                        }

                        // Process the 'or' files
                        if ($req->file("toPass.$i.or")) {
                            // foreach ($req->file("toPass.$i.or") as $docu2) {
                            $docu2 = $req->file("toPass.$i.or");
                            $name_or = time() . rand(1, 100) . '.webp';
                            try {
                                $img = Image::make($docu2);
                                $img->resize(600, 600, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->encode('webp')->save(public_path('crm_model/or') . '/' . $name_or);
                            } catch (\Exception $e) {
                                $docu2->storeAs('crm_model/or', $name_or);
                            }

                            $or_images[] = $name_or;
                            // }
                        }

                        // Process the 'vehicle_pic' files
                        if ($req->file("toPass.$i.vehicle_pic")) {
                            // foreach ($req->file("toPass.$i.vehicle_pic") as $docu2) {
                            $docu2 = $req->file("toPass.$i.vehicle_pic");
                            $name_vehicle_pic = time() . rand(1, 100) . '.webp';
                            try {
                                $img = Image::make($docu2);
                                $img->resize(600, 600, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->encode('webp')->save(public_path('crm_model/vehicle_picture') . '/' . $name_vehicle_pic);
                            } catch (\Exception $e) {
                                $docu2->storeAs('crm_model/vehicle_picture', $name_vehicle_pic);
                            }

                            $name_vehicle_pic_image[] = $name_vehicle_pic;
                            // }
                        }

                        // Process the 'front_license' files
                        if ($req->file("toPass.$i.front_license")) {
                            // foreach ($req->file("toPass.$i.front_license") as $docu2) {
                            $docu2 = $req->file("toPass.$i.front_license");
                            $front_license = time() . rand(1, 100) . '.webp';
                            try {
                                $img = Image::make($docu2);
                                $img->resize(600, 600, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->encode('webp')->save(public_path('crm_model/front_license') . '/' . $front_license);
                            } catch (\Exception $e) {
                                $docu2->storeAs('crm_model/front_license', $front_license);
                            }

                            $front_license_image[] = $front_license;
                            // }
                        }

                        // Process the 'back_license' files
                        if ($req->file("toPass.$i.back_license")) {
                            // foreach ($req->file("toPass.$i.back_license") as $docu2) {
                            $docu2 = $req->file("toPass.$i.back_license");
                            $back_license = time() . rand(1, 100) . '.webp';
                            try {
                                $img = Image::make($docu2);
                                $img->resize(600, 600, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->encode('webp')->save(public_path('crm_model/back_license') . '/' . $back_license);
                            } catch (\Exception $e) {
                                $docu2->storeAs('crm_model/back_license', $back_license);
                            }

                            $back_license_image[] = $back_license;
                            // }
                        }
                        // Insert into crmxi3_vehicles
                        // $hoatype = explode("-", $vehicles[$i]['hoa_type']);
                        $vehicleId = DB::table('crmxi3_vehicles')->insertGetId([
                            'account_id' => $account_id,
                            'address_id' => $vehicles[$i]['owner_address'],
                            'plate_no' => $vehicles[$i]['plate'],
                            'orID' => $vehicles[$i]['orID'],
                            'crID' => $vehicles[$i]['crID'],
                            'brand' => $vehicles[$i]['brand'],
                            'series' => $vehicles[$i]['vehicle_series'],
                            'year_model' => $vehicles[$i]['year_model'],
                            'color' => $vehicles[$i]['color'],
                            'type' => $vehicles[$i]['type'],
                            'old_sticker_no' => $vehicles[$i]['sticker_no'],
                            'old_sticker_year' => $vehicles[$i]['sticker_year'],
                            'status' => 1,
                            'req1' => $or_images[$i] ?? null,
                            'cr' => $cr_images[$i] ?? null,
                            'vehicle_ownership_status_id' => $vehicles[$i]['vos'] ?? null,
                            'vehicle_picture' => $name_vehicle_pic_image[$i] ?? null,
                            'assoc_crm' => 1,
                            // 'created_by' => Auth::id()
                        ]);

                        // Insert into crmxi3_vehicle_owners
                        DB::table('crmxi3_vehicle_owners')->insert([
                            'vehicle_id' => $vehicleId,
                            // 'address_id' => $vehicles[$i]['owner_address'],
                            'firstname' => $vehicles[$i]['first_name'],
                            'middlename' => $vehicles[$i]['middle_name'],
                            'lastname' => $vehicles[$i]['last_name'],
                            // 'street' => $vehicles[$i]['street'],
                            // 'building_name' => $vehicles[$i]['building_name'],
                            // 'subdivision_village' => $vehicles[$i]['subdivision'],
                            // 'blk_lot' => $vehicles[$i]['blk_lot'],
                            // 'block' => $vehicles[$i]['block'],
                            // 'lot' => $vehicles[$i]['lot'],
                            // 'house_number' => $vehicles[$i]['house_number'],
                            // 'city' => $vehicles[$i]['city'],
                            // 'zipcode' => $vehicles[$i]['zip_code'],
                            // 'category_id' => $vehicles[$i]['category_id'],
                            // 'sub_category_id' => $vehicles[$i]['sub_category_id'],
                            // 'vehicle_ownership_status_id' => $vehicles[$i]['vos'],
                            // 'hoa' => $vehicles[$i]['hoa'],
                            // 'hoa_type' => $hoatype[0],
                            'email' => $vehicles[$i]['email'],
                            'main_contact' => $vehicles[$i]['main_contact'],
                            'secondary_contact' => $vehicles[$i]['secondary_contact'],
                            // 'tertiary_contact' => $vehicles[$i]['tertiary_contact'],
                            'front_license' => $front_license_image[$i] ?? null,
                            'back_license' => $back_license_image[$i] ?? null,
                            'created_by' => Auth::id()
                        ]);

                        $this->insertLogVehicles('Inserted CRM Vehicles, ACCOUNT ID ' . $account_id . ', plate_no: ' . $vehicles[$i]['plate'] . ', owner: ' . $vehicles[$i]['first_name'] . ' ' . $vehicles[$i]['middle_name'] . ' ' . $vehicles[$i]['last_name']);
                    }
                }
            });
            return redirect()->back()->withInput()->with('success', $req->toPass[0]['current_vehicle_id'] && $req->toPass[0]['current_owner_id'] ? 'Successfully Edited' : 'Successfully Added');
        } catch (\Exception $th) {
            return redirect()->back()->withInput()->with('error', 'Failed.');
        }
    }

    public function checkExistingPlateNo($plate_no)
    {
        // Normalize the plate number in PHP
        $normalized_plate_no = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $plate_no));

        // Query the database
        $check_plate_no = DB::table('crmxi3_vehicles')
            ->select(DB::raw("UPPER(REGEXP_REPLACE(plate_no, '[^a-zA-Z0-9]', '')) AS normalized_plate_no"))
            ->where(DB::raw("UPPER(REGEXP_REPLACE(plate_no, '[^a-zA-Z0-9]', ''))"), $normalized_plate_no)
            ->get();

        // Return the result as JSON
        return response()->json($check_plate_no);
    }


    // For Patch 11-19-24
    public function deleteVehicle(Request $req)
    {
        $id = $req->vehicle_id;

        // Find the vehicle id
        $vehicle = CRXMIVehicle::findOrFail($id);

        // Find the vehicle owner
        $vehicle_owner = CRXMIVehicleOwner::query()
            ->where('vehicle_id', $id)->firstOrFail();

        try {
            $message = 'CRMXI3 Vehicle ID ' . $vehicle->id . ' with plate number ' . $vehicle->plate_no . ' has been deleted by ' . auth()->user()->name;

            // Wrap in transaction
            DB::transaction(function () use ($vehicle, $vehicle_owner, $message) {
                // Log the action
                $this->insertLogVehicleHistory($vehicle, $message);

                // Delete the vehicle owner
                $vehicle_owner->delete();

                // Delete the vehicle
                $vehicle->delete();
            });

            // Return success message
            return response()->json([
                'status' => 'success',
                'message' => 'Vehicle successfully deleted.'
            ]);
        } catch (\Exception $e) {
            // Return error message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete vehicle.'
            ], 500);
        }
    }

    public function deleteAddress(Request $req)
    {
        // Find the address id
        $address = CRMXIAddress::findOrFail($req->address_id);

        // Check if the address is associated with a vehicle
        $vehicle = CRXMIVehicle::where('address_id', $address->id)->get();

        try {
            $message = 'CRMXI3 Address ID ' . $address->id . ' has been deleted by ' . auth()->user()->name;

            DB::transaction(function () use ($address, $vehicle, $message) {
                // Log the action
                $this->insertLogAddressHistory($address, $message);

                // Delete the vehicle
                if ($vehicle->count() > 0) {
                    $vehicle->each(function ($v) {
                        $vehMessage = 'CRMXI3 Vehicle ID ' . $v->id . ' with plate number ' . $v->plate_no . ' has been deleted by ' . auth()->user()->name;

                        // Check if the address is associated with a vehicle
                        $vehicle_owner = CRXMIVehicleOwner::where('vehicle_id', $v->id)->first();

                        // Delete the vehicle owner
                        if ($vehicle_owner) {
                            $vehicle_owner->delete();
                        }

                        // Log the action
                        $this->insertLogVehicleHistory($v, $vehMessage);

                        // Delete the vehicle
                        $v->delete();
                    });
                }

                // Delete the address
                $address->delete();
            });

            // Return success message
            return response()->json([
                'status' => 'success',
                'message' => 'Address successfully deleted.'
            ]);
        } catch (\Exception $e) {
            // Return error message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete address.'
            ], 500);
        }
    }

    public function insertLogVehicleHistory($vehicle, $action = null)
    {
        // Log the action
        DB::table('logs3_vehicle_hist')->insert([
            'account_id' => $vehicle->account_id,
            'vehicle_id' => $vehicle->id,
            'reason' => $action,
            'action_by' => auth()->user()->name,
            'created_at' => now()
        ]);
    }

    public function insertLogAddressHistory($address, $action = null)
    {
        // Log the action
        DB::table('logs3_address_hist')->insert([
            'account_id' => $address->account_id,
            'address_id' => $address->id,
            'reason' => $action,
            'action_by' => auth()->user()->name,
            'created_at' => now()
        ]);
    }
    // End of Patch 11-19-24
}
