<?php

namespace App\Http\Controllers;

use App\Models\ProgrammingCategory;
use App\Models\ProgrammingQuestions;
use App\Models\ProgrammingSets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammingSetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_title = "Programming Management";
        $breadcrumb_title = "Programming Sets";
        $sets_list = ProgrammingSets::select('id', 'set_title')->get();
        return view('programming_sets.all_sets',compact('menu_title','breadcrumb_title','sets_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu_title = "Programming Management";
        $breadcrumb_title = "Programming Sets";

        $language_cat_list = ProgrammingCategory::whereIn('id', ProgrammingQuestions::distinct('programming_cat_id')->pluck('programming_cat_id'))
        ->select('id', 'category_name')
        ->orderBy('id', 'asc')
        ->get();    
        return view('programming_sets.create_sets',compact('menu_title','breadcrumb_title','language_cat_list'));
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
            'progrmming_questions' => 'required',
        ], [
            // Custom error messages
            'set_title.required' => 'The set title is mandatory and cannot be empty.',            
            'category_ids.required' => 'Categorys are required. Please select at least one category.',
            'progrmming_questions.required' => 'Please select atleat one question.',
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }  

        ProgrammingSets::create([
            'set_title' => $request->input('set_title'),
            'category_ids' => json_encode($request->input('category_ids')),
            'progrmming_questions' => json_encode($request->input('progrmming_questions')),
        ]);
        return response()->json(['message' => 'Set Created successfully','success' =>true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu_title = "Programming Management";
        $breadcrumb_title = "Programming Sets";
        $data = ProgrammingSets::findOrFail($id);
        if(!empty($data)){
            $category_ids = explode(',',json_decode($data['category_ids']));           
            $programming_questions = explode(',',json_decode($data['progrmming_questions']));            
            $programming_category = ProgrammingCategory::select('id','category_name')->whereIn('id',$category_ids)->get()->toArray();

            foreach ($programming_category as $key => $value) {
               $question_list = ProgrammingQuestions::where('programming_cat_id',$value['id'])->whereIn('id',$programming_questions)->get()->toArray();
               $programming_category[$key]['question_list'] = $question_list;
            }
            $data['category_question_list'] = $programming_category;
        }

        // dd($data->toArray());
        // exit;
        return view('programming_sets.show_sets',compact('menu_title','breadcrumb_title','data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu_title = "Programming Management";
        $breadcrumb_title = "Edit Programming Sets";
        $data = ProgrammingSets::findOrFail($id);

        $mcq_category_list = ProgrammingCategory::whereIn('id', ProgrammingQuestions::distinct('programming_cat_id')->pluck('programming_cat_id'))
        ->select('id', 'category_name')
        ->orderBy('id', 'asc')
        ->get();

        if(!empty($data)){
            $data['category_ids'] = explode(',',json_decode($data['category_ids']));                
        }
        return view('programming_sets.edit_sets',compact('menu_title','breadcrumb_title','data','mcq_category_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $setsId)
    {
         // Validation rules
         $validatedData =  Validator::make($request->all(),[
            'set_title' => 'required',
            'category_ids' => 'required',
            'progrmming_questions' => 'required',
        ], [
            // Custom error messages
            'set_title.required' => 'The set title is mandatory and cannot be empty.',            
            'category_ids.required' => 'Category is required. Please select at least one category.',
            'progrmming_questions.required' => 'The questions field cannot be left empty.',
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }  
      
        ProgrammingSets::where('id',$setsId)->update([
            'set_title' => $request->input('set_title'),
            'category_ids' => json_encode($request->input('category_ids')),
            'progrmming_questions' => json_encode($request->input('progrmming_questions')),
        ]);
        return response()->json(['message' => 'Set updated successfully','success' =>true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProgrammingSets::where(['id'=>$id])->delete();
        return response()->json(['message' => 'Set deleted successfully', 'success' =>true], 200);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function set_programming_question(Request $request){
        $set_id = $request->input('set_id');
        $data = ProgrammingSets::findOrFail($set_id);
        $categoryIds = explode(',',json_decode($data['category_ids']));  

        $questions = ProgrammingQuestions::select('id','question_text', 'programming_cat_id')->whereIn('programming_cat_id', $categoryIds)->get();
        return response()->json(['data' => $questions,'success'=> true ]);
    }



}
