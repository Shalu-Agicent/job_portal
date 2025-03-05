<?php

namespace App\Http\Controllers;

use App\Models\JobApplicant;
use App\Models\Jobs;
use App\Models\SubscriptionPlans;
use App\Models\EmployerSubscription;
use App\Models\McqSets;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class JobsController extends Controller
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
        $menu_title = "Jobs Management";
        $breadcrumb_title = "Jobs";
        $job_list = Jobs::all();
        return view('jobs.all_jobs',compact('menu_title','breadcrumb_title','job_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        $menu_title = "Jobs Management";
        $breadcrumb_title = "Create Jobs";
        $assesment_list = McqSets::select('id','set_title')->get();
        return view('jobs.create_job',compact('menu_title','breadcrumb_title','assesment_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {      
        // Validation rules
        $validatedData =  Validator::make($request->all(),[
            'title' => 'required|string',
            'employment_type' => 'required|in:Full-time,Part-time,Contract',
            'location' => 'required',
            'status' => 'required|in:Open,Closed',
            'posted_at' => 'required|date',
            'salary_range' => 'required',
            'description' => 'required',
            'assessment' => 'required',
        ], [
            'title.required' => 'The job title is required.',
            'title.string' => 'The job title must be a valid string.',        
            'employment_type.required' => 'Please select an employment type.',
            'employment_type.in' => 'The employment type must be one of the following: Full-time, Part-time, or Contract.',
            'location.required' => 'Please specify the location of the job.',
            'status.required' => 'Please select the job status.',
            'status.in' => 'The status must be either Open or Closed.',            
            'posted_at.required' => 'Please provide the date when the job was posted.',
            'posted_at.date' => 'The posting date must be a valid date.',            
            'salary_range.required' => 'Please specify the salary range.',            
            'description.required' => 'The job description is required.',
            'assessment.required' => 'Please select an assessment.',
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }    

        Jobs::create([
            'title' => $request->input('title'),
            'employment_type' => $request->input('employment_type'),
            'location' => $request->input('location'),
            'status' => $request->input('status'),
            'created_by' => $this->user_id,
            'posted_at' => $request->input('posted_at'),
            'salary_range' => $request->input('salary_range'),
            'description' => $request->input('description'),
            'requirements' => $request->input('requirements'),
            'assessment' => $request->input('assessment'),
        ]);
        return response()->json(['message' => 'Job posted successfully','success' =>true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $job_id)
    {
        $menu_title = "Jobs Management";
        $breadcrumb_title = "View Jobs";   

        $job = Jobs::join('mcqs_sets', 'jobs.assessment', '=', 'mcqs_sets.id')
           ->where('jobs.id', $job_id)
           ->select('jobs.*', 'mcqs_sets.set_title')
           ->first();
       
        return view('jobs.view_jobs', compact('menu_title', 'breadcrumb_title', 'job'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $job_id)
    {
        $menu_title = "Jobs Management";
        $breadcrumb_title = "Edit Jobs";     
        $jobs = Jobs::findOrFail($job_id);   
        $assesment_list = McqSets::select('id','set_title')->get();
        return view('jobs.edit_jobs', compact('menu_title', 'breadcrumb_title', 'jobs','assesment_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $job_id)
    {       
        
        // Validation rules
        $validatedData =  Validator::make($request->all(),[
            'title' => 'required|string',
            'employment_type' => 'required|in:Full-time,Part-time,Contract',
            'location' => 'required',
            'status' => 'required|in:Open,Closed',
            'posted_at' => 'required|date',
            'salary_range' => 'required',
            'description' => 'required',
            'assessment' => 'required',
        ], [
            'title.required' => 'The job title is required.',
            'title.string' => 'The job title must be a valid string.',        
            'employment_type.required' => 'Please select an employment type.',
            'employment_type.in' => 'The employment type must be one of the following: Full-time, Part-time, or Contract.',
            'location.required' => 'Please specify the location of the job.',            
            'status.required' => 'Please select the job status.',
            'status.in' => 'The status must be either Open or Closed.',            
            'posted_at.required' => 'Please provide the date when the job was posted.',
            'posted_at.date' => 'The posting date must be a valid date.',            
            'salary_range.required' => 'Please specify the salary range.',            
            'description.required' => 'The job description is required.',
            'assessment.required' => 'Please select an assessment.',
        ]);

        if($validatedData->fails()){           
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validatedData->errors()  
            ], 422);  
        }       
        
        Jobs::where('id', $job_id)->update([
            'title' => $request->input('title'),
            'employment_type' => $request->input('employment_type'),
            'location' => $request->input('location'),
            'status' => $request->input('status'),
            'created_by' => $this->user_id,
            'posted_at' => $request->input('posted_at'),
            'salary_range' => $request->input('salary_range'),
            'description' => $request->input('description'),
            'requirements' => $request->input('requirements'),
            'assessment' => $request->input('assessment'),
        ]);
        return response()->json(['message' => 'Job updated successfully', 'success' =>true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {        
        Jobs::where(['id'=>$id])->delete();
        return response()->json(['message' => 'Job deleted successfully', 'success' =>true], 200);
    }

    /**
     * function for get job applicant 
     *
     * @return void
     */
    public function applicant(String $job_id){  
        $menu_title = "Jobs Management";
        $breadcrumb_title = "Job Applicant";  
        $applicants_list = JobApplicant::where('job_id',$job_id)
            ->with('Interview_status')->get(); 
        return view('jobs.job_applicant', compact('menu_title', 'breadcrumb_title', 'applicants_list'));
    }

    /**
     * Undocumented function
     *
     * @param String $id
     * @return void
     */
    public function get_applicant(String $id){
        try {
            $applicant = JobApplicant::findOrFail($id);   
            return response()->json([
                'success' => true,
                'data' => $applicant
            ], 200);  // 200 OK status
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found.'
            ], 404);  // 404 Not Found status
        }  
    }

    /**
     * function for Subscription plan list
     *
     * @return void
     */
    public function subscriptions(){
        $menu_title = "Subscriptions";
        $breadcrumb_title = "Subscriptions"; 
        $user = Auth::user();
        $user_id = $user->id;

        $subscriptions_list = SubscriptionPlans::all();   
        $user_last_subscription = EmployerSubscription::join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
        ->where(['user_subscriptions.status' => 'Active', 'user_subscriptions.user_id' => $user_id])
        ->orderBy('user_subscriptions.id', 'desc')
        ->first(['user_subscriptions.*', 'subscription_plans.plan_name','subscription_plans.features']); 

  
        return view('jobs.subscriptions_list', compact('menu_title', 'breadcrumb_title', 'subscriptions_list','user_last_subscription'));
    }

    /**
     * Undocumented function
     *
     * @param String $subscription_id
     * @return void
     */
   
    public function buy_subscription(String $subscription_id) {

        if (Auth::check()) {
            $user = Auth::user();
            $user_id = $user->id;
            $user_list = EmployerSubscription::where(['user_id' => $user_id, 'status' => 'Active'])->get();
    
            $subscription_plan = SubscriptionPlans::findOrFail($subscription_id);    
            if ($user_list->isEmpty()) {
                $start_date = Carbon::now();
                if ($subscription_plan->duration_type === 'Month') {
                    $end_date = $start_date->copy()->addMonths($subscription_plan->duration);
                } elseif ($subscription_plan->duration_type === 'Year') {
                    $end_date = $start_date->copy()->addYears($subscription_plan->duration);
                } else {
                    return response()->json(['error' => 'Invalid duration type.'], 400);
                }
                $new_subscription = new EmployerSubscription([
                    'plan_id' => $subscription_plan->id,
                    'user_id' => $user_id,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => 'Active',
                    'payment_method' => 'Credit Card', 
                ]);

                $new_subscription->save();
                session()->flash('success', 'Subscription purchased successfully.');
                return redirect()->route('dashboard');
            } else {
                session()->flash('error', 'You already have an active subscription.');
                return redirect()->route('subscriptions');
            }
    
        } else {          
            // session()->flash('error', 'You already have an active subscription.');
            return redirect()->route('subscriptions');
        }
    }
    

}
