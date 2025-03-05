<?php

namespace App\Http\Controllers;

use App\Models\McqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class McqCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_title = "MCQ Management";
        $breadcrumb_title = "MCQ Category";
        $mcqs_list = McqCategory::get();
        return view('mcqs_category.all_mcqs_category',compact('menu_title','breadcrumb_title','mcqs_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            McqCategory::create(['category_name' => $request->input('category_name')]);
            Session::flash('success', 'Category saved successfully');
            return redirect()->route('mcqs-category.index');

        } catch (QueryException $e) {
            
            if ($e->getCode() == 23000) {
                $errorMessage = 'The category name already exists.';
            } else {
                $errorMessage = 'There was an error saving the category. Please try again.';
            }
            Log::error('Error saving category: ' . $e->getMessage());
            Session::flash('error', $errorMessage);           
            return redirect()->route('mcqs-category.index');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            McqCategory::where('id',$id)->update(['category_name' => $request->input('category_name')]);

            Session::flash('success', 'Category updated successfully');
            return redirect()->route('mcqs-category.index');

        } catch (QueryException $e) {
            
            if ($e->getCode() == 23000) {
                $errorMessage = 'The category name already exists.';
            } else {
                $errorMessage = 'There was an error saving the category. Please try again.';
            }
            Log::error('Error saving category: ' . $e->getMessage());
            Session::flash('error', $errorMessage);           
            return redirect()->route('mcqs-category.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        McqCategory::where(['id'=>$id])->delete();
        return response()->json(['message' => 'Category deleted successfully', 'success' =>true], 200);
    }
}
