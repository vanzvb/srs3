<?php

namespace App\Http\Controllers;

use App\Models\SPCCategory;
use App\Models\SPCSubCat;
use Illuminate\Http\Request;
use App\Models\SrsRequirement;
use App\Models\SrsRequirementFile;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function index()
    {   
        // $this->authorize('access', SPCSubCat::class);

        $required_files = SrsRequirement::all();
        $categories = SPCCategory::all();

        return view('sub_categories.sub_category_index', compact('required_files', 'categories'));
    }

    public function list()
    {
        // $this->authorize('access', SPCSubCat::class);

        if(!request()->ajax()) {
            abort(404);
        }

        $sub_categories = SPCSubCat::with([
            'category',
            'requiredFiles'
        ])
        ->orderBy('category_id', 'desc')
        ->get();

        return response()->json([
            'data' => $sub_categories
        ]);
    }

    public function show($id)
    {   
        // $this->authorize('access', SPCSubCat::class);

        if(!request()->ajax()) {
            abort(404);
        }

        $sub_category = SPCSubCat::findOrFail($id)
        ->load([
            'category',
            'requiredFiles'
        ]);

        return response()->json([
            'data' => $sub_category
        ]);
    }

    public function store(Request $request)
    {
        // $this->authorize('create', SPCSubCat::class);

        if(!request()->ajax()) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required',
            'category' => 'required|exists:spc_categories,id',
            'status' => 'required',
            'required_files' => 'nullable|exists:srs_requirements,id|array'
        ]);

        try {    
            DB::transaction(function () use ($data) {
                $sub_category = SPCSubCat::create([
                    'category_id' => $data['category'],
                    'name' => $data['name'],
                    'status' => $data['status']
                ]);

                if(isset($data['required_files'])) {
                    $sub_category->requiredFiles()->sync($data['required_files']);
                }
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Sub Category created successfully'
        ]);
    }

    public function edit(Request $request)
    {
        // $this->authorize('update', SPCSubCat::class);

        if(!request()->ajax()) {
            abort(404);
        }

        $sub_category = SPCSubCat::findOrFail($request->id);

        $data = $request->validate([
            'name' => 'required',
            'category' => 'required|exists:spc_categories,id',
            'status' => 'required',
            'required_files' => 'nullable|exists:srs_requirements,id|array'
        ]);

        try {    
            DB::transaction(function () use ($sub_category, $data) {
                $sub_category->update([
                    'category_id' => $data['category'],
                    'name' => $data['name'],
                    'status' => $data['status']
                ]);

                if(isset($data['required_files'])) {
                    $sub_category->requiredFiles()->sync($data['required_files']);
                } else {
                    $sub_category->requiredFiles()->detach();
                }
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'data' => $sub_category
        ]);
    }

    public function destroy($id)
    {
        // $this->authorize('delete', SPCSubCat::class);

        if(!request()->ajax()) {
            abort(404);
        }

        $sub_category = SPCSubCat::findOrFail($id);

        try {
            $sub_category->requiredFiles()->detach();
            $sub_category->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Sub Category deleted successfully'
        ]);
    }
}
