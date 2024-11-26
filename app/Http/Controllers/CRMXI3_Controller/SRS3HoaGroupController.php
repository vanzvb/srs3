<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CRMXI3_Model\SRS3HoaGroup;


class SRS3HoaGroupController extends Controller
{
    /**
     * Display the listings for SPC 3.0 Hoa Group.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $hoas = DB::select('SELECT * FROM crmxi3_hoas');
        $hoatypes = DB::select('SELECT * FROM crmxi3_hoa_types');
        $groups = DB::table('srs3_hoa_groups')
            ->join('crmxi3_hoas', 'srs3_hoa_groups.hoa', '=', 'crmxi3_hoas.id')  // Join with crmxi3_hoas on hoa_id
            ->select(
                'srs3_hoa_groups.group_id',
                'srs3_hoa_groups.name',
                'srs3_hoa_groups.id as hoa_group_id',  // ID of the hoa group entry
                'crmxi3_hoas.id as hoa_id',  // ID from crmxi3_hoas
                'crmxi3_hoas.name as hoa_name'  // Additional column from crmxi3_hoas (if needed)
            )
            ->get()
            ->groupBy('group_id')  // Group by group_id
            ->map(function ($items) {
                // Get the first item for common group details
                $group = $items->first();
                return [
                    'group_id' => $group->group_id,
                    'name' => $group->name,
                    'hoa' => $items->map(function ($item) {
                        // Map each hoa in the group
                        return [
                            'id' => $item->hoa_group_id,  // Use the ID of srs3_hoa_groups entry
                            'hoa_id' => $item->hoa_id,  // ID from crmxi3_hoas
                            'hoa_name' => $item->hoa_name,  // Name or any additional info from crmxi3_hoas
                        ];
                    })->values()->toArray(),  // Convert the collection to an array
                ];
            })
            ->values();  // Re-index the array
        // ->toArray();  // Convert to array
        return view('crmxi3.spc3_hoa_group', [
            'hoa_groups' => $groups,
            'hoas' => $hoas,
            'hoatypes' => $hoatypes
        ]);
    }

    /**
     * Insert or update the HOA group
     *
     * @param Request $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hoa_group_insert(Request $req)
    {
        // For Patch 11/11/24

        // Validate the request
        $req->validate([
            'group_name' => [
                'required',
                'string',
                'max:255',
                $req->current_hoa_group_id ? 'unique:srs3_hoa_groups,name,' . $req->current_hoa_group_id . ',group_id'
                    : 'unique:srs3_hoa_groups,name'
            ],
            'assigned_hoa_list' => ['required', 'array'],
            'assigned_hoa_list.*' => ['required'],
            'current_hoa_group_id' => ['nullable', 'integer']
        ], [
            'assigned_hoa_list.*.required' => 'The HOA list is required.'
        ]);

        try {
            // Get the request data
            $data = $req->all();

            // Remove duplicates from the assigned HOA list
            $data['assigned_hoa_list'] = array_unique($data['assigned_hoa_list']);

            // Get the last group ID
            $lastGroupId = DB::table('srs3_hoa_groups')
                ->orderBy('group_id', 'desc')
                ->value('group_id') ?? 0;

            // Start a transaction
            DB::transaction(function () use ($data, $lastGroupId) {
                // Get the existing HOA list for the current group
                $existing_hoas = DB::table('srs3_hoa_groups')
                    ->where('group_id', $data['current_hoa_group_id'])
                    ->pluck('hoa')
                    ->toArray(); // Converts collection to an array for easier comparison

                $new_hoas = $data['assigned_hoa_list']; // HOAs being assigned to the group

                // First, handle any new HOAs to be inserted
                foreach ($new_hoas as $hoa) {
                    if ($data['current_hoa_group_id']) {
                        // If the group already exists, check if this HOA is assigned
                        if (!in_array($hoa, $existing_hoas)) {
                            // Insert the new HOA into the group
                            DB::table('srs3_hoa_groups')->insert([
                                'group_id' => $data['current_hoa_group_id'],
                                'hoa' => $hoa,
                                'name' => $data['group_name'],
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    } else {
                        // If no group exists, create a new group and insert the HOA
                        $new_group_id = $lastGroupId + 1;
                        DB::table('srs3_hoa_groups')->insert([
                            'group_id' => $new_group_id,
                            'name' => $data['group_name'],
                            'hoa' => $hoa,
                            'created_at' => now(),
                            'created_by' => Auth::id()
                        ]);
                    }
                }

                // Next, handle the update of the group name for the existing group
                if ($data['current_hoa_group_id']) {
                    DB::table('srs3_hoa_groups')
                        ->where('group_id', $data['current_hoa_group_id'])
                        ->update([
                            'name' => $data['group_name'],
                            'updated_at' => now()
                        ]);
                }

                // Finally, handle the deletion of any HOA that was removed from the group
                foreach ($existing_hoas as $existing_hoa) {
                    if (!in_array($existing_hoa, $new_hoas)) {
                        DB::table('srs3_hoa_groups')
                            ->where('group_id', $data['current_hoa_group_id'])
                            ->where('hoa', $existing_hoa)
                            ->delete();
                    }
                }
            });

            // Redirect back with success message
            return redirect()
                ->back()
                ->withInput()
                ->with(['success' => 'Successfully Saved']);
        } catch (\Exception $th) {
            // Redirect back with error message
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed.');
        }
    }
}
