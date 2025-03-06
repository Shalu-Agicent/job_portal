<?php

namespace App\Http\Controllers;

use App\Models\ProgrammingCategory;
use App\Models\ProgrammingQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammingQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_title = "Programming";
        $breadcrumb_title = "Programming Questions";  
        $question_list = ProgrammingQuestions::with('programming_category:id,category_name')
        ->select('id', 'question_text', 'programming_cat_id') 
        ->orderBy('programming_cat_id', 'asc') 
        ->get();
        return view('programming.all_question',compact('menu_title','breadcrumb_title','question_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu_title = "Programming";
        $breadcrumb_title = "Create Questions";
        $programming_category_list = ProgrammingCategory::all();
        return view('programming.create_questions',compact('menu_title','breadcrumb_title','programming_category_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation rules
        $validatedData =  Validator::make($request->all(),[
            'programming_cat_id' => 'required',
            'question_text' => 'required',
        ], [
            'programming_cat_id.required' => 'Programming Category is required.',
            'question_text.required' => 'Question is required.', 
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }    

        ProgrammingQuestions::create([
            'programming_cat_id' => $request->input('programming_cat_id'),
            'question_text' => $request->input('question_text'),
        ]);
        return response()->json(['message' => 'Question saves successfully','success' =>true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $question_id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $question_id)
    {
        $menu_title = "Programming Management";
        $breadcrumb_title = "Edit Questions";     
        $programming = ProgrammingQuestions::findOrFail($question_id);   
        $programming_category_list = ProgrammingCategory::all();
        return view('programming.edit_questions', compact('menu_title', 'breadcrumb_title', 'programming','programming_category_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $question_id)
    {
        // Validation rules
        $validatedData =  Validator::make($request->all(),[
            'programming_cat_id' => 'required',
            'question_text' => 'required',
        ], [
            'programming_cat_id.required' => 'Programming Category is required.',
            'question_text.required' => 'Question is required.', 
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }       
        
        ProgrammingQuestions::where('id', $question_id)->update([
            'programming_cat_id' => $request->input('programming_cat_id'),
            'question_text' => $request->input('question_text'),
        ]);
        return response()->json(['message' => 'Question updated successfully',
        'success' =>true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $question_id)
    {
        ProgrammingQuestions::where(['id'=>$question_id])->delete();
        return response()->json(['message' => 'Question deleted successfully', 'success' =>true], 200);
    }
}
