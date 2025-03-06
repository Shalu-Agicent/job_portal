<?php

namespace App\Http\Controllers;

use App\Models\ProgrammingCategory;
use App\Models\ProgrammingQuestions;
use App\Models\ProgrammingSets;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
