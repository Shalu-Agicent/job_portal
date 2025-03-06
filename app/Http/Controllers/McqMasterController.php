<?php

namespace App\Http\Controllers;

use App\Models\McqCategory;
use App\Models\McqMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class McqMasterController extends Controller
{   
    public $user_id;
    public function __construct()
    {
        $this->user_id = Auth::user()->id;
    }
    /**
     * Display a listing of the resource.   
     */
    public function index()
    {
        $menu_title = "MCQ Management";
        $breadcrumb_title = "MCQ";  
        $mcqs_list = McqMaster::select('id', 'question_text', 'mcq_category_id')
        ->with(['category:id,category_name']) 
        ->orderBy('mcq_category_id', 'asc') 
        ->get(); 
        $mcq_category_list = McqCategory::all();
        return view('mcqs.all_mcqs',compact('menu_title','breadcrumb_title','mcqs_list','mcq_category_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu_title = "MCQ Management";
        $breadcrumb_title = "Create MCQ";
        $mcq_category_list = McqCategory::all();
        return view('mcqs.create_mcqs',compact('menu_title','breadcrumb_title','mcq_category_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required', 
            'option_d' => 'required', 
            'correct_answer' => 'required|in:A,B,C,D',
            'mcq_category_id' => 'required',
        ], [
            'question_text.required' => 'The question text is required.',
            'question_text.string' => 'The question text must be a valid string.',
            'option_a.required' => 'Option A is required.',
            'option_b.required' => 'Option B is required.',
            'option_c.required' => 'Option C is required.',
            'option_d.required' => 'Option D is required.',
            'correct_answer.required' => 'You must select a correct answer.',
            'correct_answer.in' => 'The correct answer must be one of the following: A, B, C, or D.',
            'mcq_category_id.required' => 'Please select a category for the MCQ.',
        ]);
        
        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }    

        McqMaster::create([
            'question_text' => $request->input('question_text'),
            'option_a' => $request->input('option_a'),
            'option_b' => $request->input('option_b'),
            'option_c' => $request->input('option_c'),
            'option_d' => $request->input('option_d'),
            'correct_answer' => $request->input('correct_answer'),
            'mcq_category_id' => $request->input('mcq_category_id'),
        ]);
        return response()->json(['success'=>true,'message' => 'Saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $data = McqMaster::with(['category:id,category_name']) 
                              ->findOrFail($id);
        return response()->json(['success'=>true,'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $menu_title = "MCQ Management";
        $breadcrumb_title = "Edit MCQ";
        $mcq_category_list = McqCategory::all();
        $mcq_data = McqMaster::findOrFail($id);
        return view('mcqs.edit_mcqs',compact('menu_title','breadcrumb_title','mcq_category_list','mcq_data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $mcq_id)
    {
        $validatedData = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required', 
            'option_d' => 'required', 
            'correct_answer' => 'required|in:A,B,C,D',
            'mcq_category_id' => 'required',
        ], [
            'question_text.required' => 'The question text is required.',
            'question_text.string' => 'The question text must be a valid string.',
            'option_a.required' => 'Option A is required.',
            'option_b.required' => 'Option B is required.',
            'option_c.required' => 'Option C is required.',
            'option_d.required' => 'Option D is required.',
            'correct_answer.required' => 'You must select a correct answer.',
            'correct_answer.in' => 'The correct answer must be one of the following: A, B, C, or D.',
            'mcq_category_id.required' => 'Please select a category for the MCQ.',
        ]);
        
        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        } 

        McqMaster::where('id', $mcq_id)->update([
            'question_text' => $request->input('question_text'),
            'option_a' => $request->input('option_a'),
            'option_b' => $request->input('option_b'),
            'option_c' => $request->input('option_c'),
            'option_d' => $request->input('option_d'),
            'correct_answer' => $request->input('correct_answer'),
            'mcq_category_id' => $request->input('mcq_category_id'),
        ]);
        return response()->json(['success' =>true,'message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        McqMaster::where(['id'=>$id])->delete();
        return response()->json(['message' => 'Deletion Successfully. ','success'=>true], 200);
    }

    /**
     * function for import mcqs in table using csv file.
     *
     * @param Request $request
     * @return void
     */
    public function import(Request $request){

        $mcq_category_id = $request->input('mcq_category_id');
        $csvFile = $request->file('mcq_import_file');
        $csvData = file_get_contents($csvFile->getRealPath());
        $rows = explode("\n", $csvData);
        array_shift($rows);  

        $header_title = array('question_text','option_a','option_b','option_c','option_d','correct_answer');
       
        foreach ($rows as $row) {
            if (!empty($row)) { 
                $data = str_getcsv($row);
                $rowAssoc = array_combine($header_title, $data);
                $rowAssoc['mcq_category_id'] = $mcq_category_id;

                // Validation rules
                $validator = Validator::make($rowAssoc, [
                    'question_text' => 'required',
                    'option_a' => 'required',
                    'option_b' => 'required',
                    'option_c' => 'required',
                    'option_d' => 'required',
                    'correct_answer' => 'required|in:A,B,C,D',
                ]);

                if ($validator->fails()) {                
                    $errors[] = [
                        'row' => $rowAssoc,
                        'errors' => $validator->errors()->all(), 
                    ];
                
                    Log::error('Validation failed for row: ', [
                        'row' => $rowAssoc,
                        'errors' => $validator->errors()->all(),
                    ]);
                    continue; // Skip inserting this invalid row
                }

                // If validation passes, create the record
                McqMaster::create($rowAssoc);
            }
        }

        // If you want to return or handle the validation errors after the loop
        if (!empty($errors)) {
            $total_rows = count($errors);
            Session::flash('error', "Oops! $total_rows row(s) did not pass validation. Please check and try again.");
         
            return redirect()->route('mcqs.index');
        }

        Session::flash('success','All rows were processed successfully.');           
        return redirect()->route('mcqs.index');
    }

    /**
     * function for export data 
     *
     * @return void
     */
    public function export()
    {
       
        $columns = ['Question', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer','Category Name'];
            $response = new StreamedResponse(function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            McqMaster::with(['category:id,category_name'])
            ->chunk(1000, function ($data) use ($handle) {
                foreach ($data as $row) {
                    fputcsv($handle, [
                        $row->question_text,
                        $row->option_a,
                        $row->option_b,
                        $row->option_c,
                        $row->option_d,
                        $row->correct_answer,
                        $row->category->category_name 
                    ]);
                }
            });    
            fclose($handle); 
        });
           
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="mcq_list.csv"');    
        return $response;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function fetch_question(Request $request){
        $categoryIds = $request->input('category_ids');
        $questions = McqMaster::select('id','question_text', 'mcq_category_id')->whereIn('mcq_category_id', $categoryIds)->get();
        return response()->json(['data' => $questions,'success'=> true ]);
    }
    
}
