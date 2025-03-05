<?php

namespace App\Http\Controllers;

use App\Models\ProgrammingCategory;
use App\Models\ProgrammingQuestions;
use Illuminate\Http\Request;

class ProgrammingQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu_title = "Programming";
        $breadcrumb_title = "Programming Questions";  
        $question_list = ProgrammingQuestions::select('id', 'question_text')
        ->with(['programming_category:id,category_name']) 
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgrammingQuestions $programmingQuestions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgrammingQuestions $programmingQuestions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgrammingQuestions $programmingQuestions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgrammingQuestions $programmingQuestions)
    {
        //
    }
}
