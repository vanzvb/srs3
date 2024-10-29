<?php

namespace App\Http\Controllers\srs3;

use App\Http\Controllers\Controller;
use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\CRMXI3_Model\CRMXIHoaType;
use App\Models\CRMXI3_Model\CRMXISubcat;
use App\Models\SPCCategory;
use App\Models\SPCSubCat;
use App\Models\SRS3_Model\SPC3Subcat;
use App\Models\SRS3_Model\SRS3Requirement;
use App\Models\SrsRequirement;
use App\Models\SrsRequirementFile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function index()
    {
        // $this->authorize('access', SPCSubCat::class);

        $required_files = SrsRequirement::all();

        // same as category
        $categories = SPCCategory::all();

        // DB::enableQueryLog();
        $spc3_subcat = SPC3Subcat::orderBy('id', 'desc')->get();
        // dd(DB::getQueryLog())
        $CRMXICategories = CRMXICategory::all();
        $CRMXISubCategories = CRMXISubcat::all();
        $CRMXIHoas = CRMXIHoa::all();
        $CRMXIHoaTypes = CRMXIHoaType::all();

        return view('sub3_categories.sub_category_index', compact(
            'required_files',
            'categories',
            'spc3_subcat',
            'CRMXICategories',
            'CRMXISubCategories',
            'CRMXIHoas',
            'CRMXIHoaTypes'
        ));
    }

    public function list()
    {
        // $this->authorize('access', SPCSubCat::class);

        if (!request()->ajax()) {
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

        // if(!request()->ajax()) {
        //     abort(404);
        // }

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
        try {

            // dd($request);
            $request->validate([
                'category_modal' => 'required|exists:crmxi3_categories,id',
                'subcat_category' => 'nullable',
                'name_modal' => 'required',
                'hoa_type' => 'nullable',
                'hoas' => 'nullable',
                'required_files' => 'nullable|array',
                'add_subcat_status' => 'required',
            ]);

            DB::transaction(function () use ($request) {
               
                $spcSubCat = SPC3Subcat::create([
                    'category_id' => $request->category_modal, 
                    'sub_category_id' => $request->subcat_category,
                    'hoa_id' => $request->hoas,
                    'hoa_type_id' => $request->hoa_type,
                    'name' => $request->name_modal,
                    'status' => $request->add_subcat_status,
                ]);
                if ($request->required_files) {
                    $spcSubCat->requiredFiles()->attach($request->required_files);
                }
            });

            return redirect()->back()->with('success', 'Item added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Failed to add item: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        // $this->authorize('update', SPCSubCat::class);

        // if(!request()->ajax()) {
        //     abort(404);
        // }

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

                if (isset($data['required_files'])) {
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

        if (!request()->ajax()) {
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
