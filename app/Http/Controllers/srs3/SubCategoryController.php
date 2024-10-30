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

    public function store(Request $request)
    {
        // $this->authorize('create', SPCSubCat::class);
        try {

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
    try {
        // Find the subcategory by ID
        $sub_category = SPC3Subcat::findOrFail($request->id);

        $request->validate([
            'category_modal' => 'required|exists:crmxi3_categories,id',
            'subcat_category' => 'nullable',
            'name_modal' => 'required',
            'hoa_type' => 'nullable',
            'hoas' => 'nullable',
            'required_files' => 'nullable|array',
            'sub_status' => 'required',
        ]);

        DB::transaction(function () use ($request, $sub_category) {
            // Update the subcategory details
            $sub_category->update([
                'category_id' => $request->category_modal,
                'sub_category_id' => $request->subcat_category,
                'hoa_id' => $request->hoas,
                'hoa_type_id' => $request->hoa_type,
                'name' => $request->name_modal,
                'status' => $request->sub_status,
            ]);

            // Sync the required files (attach if new, detach if removed)
            if ($request->has('required_files')) {
                $sub_category->requiredFiles()->sync($request->required_files);
            } else {
                $sub_category->requiredFiles()->detach();
            }
        });

        return redirect()->back()->with('success', 'Item updated successfully!');
    } catch (QueryException $e) {
        return redirect()->back()->with('error', 'Failed to update item: ' . $e->getMessage());
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
    }

    }

    public function destroy($id)
    {
        // $this->authorize('delete', SPCSubCat::class);
        try {
            $subCategory = SPC3Subcat::findOrFail($id);
            $subCategory->requiredFiles()->detach(); // Detach any related files if necessary
            $subCategory->delete();
    
            return redirect()->back()->with('success', 'Item deleted successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}
