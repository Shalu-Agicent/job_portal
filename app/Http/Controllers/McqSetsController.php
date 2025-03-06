<?php

namespace App\Http\Controllers;

use App\Models\McqCategory;
use App\Models\McqMaster;
use App\Models\McqSets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class McqSetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_title = "MCQ Management";
        $breadcrumb_title = "MCQ Sets";
        $sets_list = McqSets::select('id', 'set_title')->get();
        return view('mcqs_sets.all_sets',compact('menu_title','breadcrumb_title','sets_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $menu_title = "MCQ Management";
        $breadcrumb_title = "MCQ Sets";
        $mcq_category_list = McqCategory::whereIn('id', McqMaster::distinct('mcq_category_id')->pluck('mcq_category_id'))
        ->select('id', 'category_name')
        ->orderBy('id', 'asc')
        ->get();    
        return view('mcqs_sets.create_sets',compact('menu_title','breadcrumb_title','mcq_category_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {       
        // Validation rules
        $validatedData =  Validator::make($request->all(),[
            'set_title' => 'required',
            'category_ids' => 'required',
            'mcqs_questions' => 'required',
        ], [
            // Custom error messages
            'set_title.required' => 'The set title is mandatory and cannot be empty.',            
            'category_ids.required' => 'Category IDs are required. Please select at least one category.',
            'mcqs_questions.required' => 'The questions field cannot be left empty.',
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }  

        McqSets::create([
            'set_title' => $request->input('set_title'),
            'category_ids' => json_encode($request->input('category_ids')),
            'mcqs_questions' => json_encode($request->input('mcqs_questions')),
        ]);
        return response()->json(['message' => 'Set Created successfully','success' =>true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {   
        $menu_title = "MCQ Management";
        $breadcrumb_title = "MCQ Sets";
        $data = McqSets::findOrFail($id);
        if(!empty($data)){
            $category_ids = explode(',',json_decode($data['category_ids']));           
            $mcqs_questions = explode(',',json_decode($data['mcqs_questions']));            
            $mcqs_category = McqCategory::select('id','category_name')->whereIn('id',$category_ids)->get()->toArray();

            foreach ($mcqs_category as $key => $value) {
               $question_list = McqMaster::where('mcq_category_id',$value['id'])->whereIn('id',$mcqs_questions)->get()->toArray();
               $mcqs_category[$key]['question_list'] = $question_list;
            }
            $data['category_question_list'] = $mcqs_category;
        }
        return view('mcqs_sets.show_sets',compact('menu_title','breadcrumb_title','data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $menu_title = "MCQ Management";
        $breadcrumb_title = "Edit MCQ Sets";
        $data = McqSets::findOrFail($id);
        $mcq_category_list = McqCategory::whereIn('id', McqMaster::distinct('mcq_category_id')->pluck('mcq_category_id'))
        ->select('id', 'category_name')
        ->orderBy('id', 'asc')
        ->get();

        if(!empty($data)){
            $data['category_ids'] = explode(',',json_decode($data['category_ids']));                
        }
        return view('mcqs_sets.edit_sets',compact('menu_title','breadcrumb_title','data','mcq_category_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $mcqSetsId)
    {
        // Validation rules
        $validatedData =  Validator::make($request->all(),[
            'set_title' => 'required',
            'category_ids' => 'required',
            'mcqs_questions' => 'required',
        ], [
            // Custom error messages
            'set_title.required' => 'The set title is mandatory and cannot be empty.',            
            'category_ids.required' => 'Category IDs are required. Please select at least one category.',
            'mcqs_questions.required' => 'The questions field cannot be left empty.',
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }  
      
        McqSets::where('id',$mcqSetsId)->update([
            'set_title' => $request->input('set_title'),
            'category_ids' => json_encode($request->input('category_ids')),
            'mcqs_questions' => json_encode($request->input('mcqs_questions')),
        ]);
        return response()->json(['message' => 'Set updated successfully','success' =>true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        McqSets::where(['id'=>$id])->delete();
        return response()->json(['message' => 'Set deleted successfully', 'success' =>true], 200);
    }


    public function mcqs_set_question(Request $request){
        $set_id = $request->input('set_id');
        $data = McqSets::findOrFail($set_id);
        $categoryIds = explode(',',json_decode($data['category_ids']));  
        $questions = McqMaster::select('id','question_text', 'mcq_category_id')->whereIn('mcq_category_id', $categoryIds)->get();
        return response()->json(['data' => $questions,'success'=> true ]);
    }

}
