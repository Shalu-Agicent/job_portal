<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\InterviewStatus;
use App\Models\JobApplicant;
use App\Models\Jobs;
use App\Models\SubscriptionPlans;
use App\Models\EmployerCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Mail\OTPVerificationMail;
use App\Models\CountryPhoneCode;
use App\Models\EmployerSubscription;
use App\Models\Industry;
use App\Models\SubIndustry;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmployerController extends Controller
{   
    /**
     * login page view function
     *
     * @return void
     */
    public function index(){
        return view('login');
    }
    

    /**
     * registration page view
     *
     * @return void
     */
    public function register(){  
        $subscriptions_list = SubscriptionPlans::all();
        $industry_list = Industry::all();
        $country_phone_code = CountryPhoneCode::all();
        return view('register', compact('subscriptions_list','industry_list','country_phone_code'));
    }

    /**
     * save Employer information step 1
     *
     * @param Request $request
     * @return void
     */
    public function save_Employer(Request $request){

        $validated = $request->validate([
            'employer_name' => 'required',
            'employer_email' => 'required|email|unique:employers',
            'password' => 'required|min:8',
            'employer_phone' => 'required',
            'phone_code' => 'required',
        ], [
            'employer_name.required' => 'Username is required.',
            'employer_email.required' => 'Email is required.',
            'employer_email.email' => 'Please provide a valid email address.',
            'employer_email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'employer_phone.required' => 'Contact Number is required.',
            'phone_code.required' => 'Phone Code is required.',
        ]);

        $email_otp = rand(100000, 999999);
        // $phone_otp = rand(100000, 999999);
        $phone_otp = '123456';
        $employer_name = $request->input('employer_name'); 
        $employer_phone = $request->input('employer_phone'); 

        $Employer = new Employer();
        $Employer->employer_name = $request->input('employer_name');
        $Employer->employer_email = $request->input('employer_email');
        $Employer->employer_phone = $request->input('employer_phone');
        $Employer->password = $request->input('password'); 
        $Employer->phone_code = $request->input('phone_code'); 
        $Employer->email_otp = $email_otp;
        $Employer->phone_otp = $phone_otp;      
        $Employer->completed_steps = 1; 
        $Employer->save();      
        
        // Get the inserted ID
        $insertedId = $Employer->id;
        // Mail::to($employer_phone)->send(new OTPVerificationMail($email_otp, $employer_name));

        $employer_info = Employer::find($insertedId);
        return response()->json([
            'success' => true,
            'data' => $employer_info,
            'message' => 'Your account has been created successfully! An OTP has been sent to your registered email address and phone number for verification.'
        ]);
        
    }

    /**
     * verify employer email and phone otp step 2
     *
     * @param Request $request
     * @return void
     */
    public function verify_employer(Request $request){

        // Validate OTPs in the request
        $validated = $request->validate([
            'email_otp' => 'required',
            'phone_otp' => 'required'
        ], [
            'email_otp.required' => 'Email OTP is required.',
            'phone_otp.required' => 'Phone OTP is required.',
        ]);
    
        // Get employer ID from the request
        $employer_id = $request->input('registered_user_id');
        $employer_info = Employer::find($employer_id); // Retrieve employer by ID
    
        // Check if the employer exists
        if (!$employer_info) {
            return response()->json([
                'success' => false,
                'message' => 'Employer not found.'
            ], 404);
        }
    
        // Get the OTPs from the employer record
        $stored_email_otp = $employer_info->email_otp;
        $stored_phone_otp = $employer_info->phone_otp;
    
        // Check if the submitted OTPs match the stored OTPs
        if ($request->input('email_otp') !== $stored_email_otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email OTP.'
            ], 400);
        }
    
        if ($request->input('phone_otp') !== $stored_phone_otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone OTP.'
            ], 400);
        }
    
        // If OTPs are valid, update the employer record with the verification status
        Employer::where('id', $employer_id)->update([
            'email_verified' => 1,
            'employer_phone_verify' => 1,
            'completed_steps' => 2,
        ]);
    
        // Fetch updated employer info (to return after update)
        $employer_info = Employer::find($employer_id);
    
        // Return success response
        return response()->json([
            'success' => true,
            'data' => $employer_info,
            'message' => 'Your account has been verified successfully. OTPs for email and phone have been validated.'
        ]);
    }

    /**
     * function for save company information step-3
     *
     * @param Request $request
     * @return void
     */
    public function save_company_info(Request $request){

        $validated = $request->validate([
            'company_name' => 'required',
            'industry_id' => 'required',
            'sub_industry_id' => 'required',
            'company_size' => 'required',
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'office_address' => 'required', 
        ], [
            'company_name.required' => 'Company Name is required.',
            'industry_id.required' => 'Industry is required.',
            'sub_industry_id.required' => 'Sub Industry is required.',
            'company_size.required' => 'Company Size is required.',
            'company_logo.required' => 'Company Logo is required.',
            'company_logo.image' => 'Company Logo must be an image file.',
            'company_logo.mimes' => 'Company Logo must be a jpeg, png, jpg, gif, or svg file.',
            'office_address.required' => 'Corportate Address is required.',
        ]);

        $company_logo_path = null;
        if ($request->hasFile('company_logo')) {
            $company_logo_path = $request->file('company_logo')->store('company_logo', 'public'); // Store in the 'public/resumes' folder
        }   
        $employer_id = $request->input('registered_user_id');

        EmployerCompany::create([
            'employer_id' => $employer_id,
            'company_name' => $request->input('company_name'),
            'company_logo' => $company_logo_path,
            'industry_id' => $request->input('industry_id'),
            'sub_industry_id' => $request->input('sub_industry_id'),
            'company_size' => $request->input('company_size'),
            'corporate_address' => $request->input('office_address'),
        ]);

        Employer::where('id', $employer_id)->update(['completed_steps' => 3]);

        $employer_info = Employer::find($employer_id);
        return response()->json([
            'success' => true,
            'data' => $employer_info,
            'message' => 'Company Details saved successfull.'
        ]);
    }


    /**
     * authenticate Employer for login
     *
     * @param Request $request
     * @return void
     */
    public function auth_Employer(Request $request){   

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],
        [
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);
    
        if (Auth::attempt(['employer_email' => $request->email, 'password' => $request->password])) {
            $employer = Auth::user(); 
            $completed_step = $employer->completed_steps;
            if (in_array($completed_step, [1, 2, 3])) {
                $already_registered_id = $employer->id;
                $subscriptions_list = SubscriptionPlans::all();
                return view('register', compact('subscriptions_list','completed_step','already_registered_id'));
            }
            return redirect()->route('dashboard');
        }

        Session::flash('error', 'The provided credentials do not match our records.');
        return redirect()->route('login');
    }


    /**
     * dashboard page view
     *
     * @return void
     */
    public function dashboard(){   
        $menu_title = "Dashboard";
        $breadcrumb_title = "Dashboard";
        return view('dashboard.dashboard',compact('menu_title','breadcrumb_title'));
    }

    /**
     * logout function
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * function showing hiring page
     *
     * @return void
     */
    public function hiring(){
        $job_list = Jobs::select(
            'id',
            'title',
            DB::raw('CONCAT(SUBSTRING_INDEX(description, " ", 15), " ...") AS description'),
            'location',
            'salary_range',
            'employment_type',
            'status'
        )->where('status','Open')->get();
        return view('hiring',compact('job_list'));
    }

    /**
     * function forr view all open job list
     *
     * @param String $jobId
     * @return void
     */
    public function view_job(String $jobId){
        $job_details = Jobs::findOrFail($jobId); 
        return view('view_job',compact('job_details'));
    }

    /**
     * function save job application
     *
     * @param Request $request
     * @return void
     */
    public function save_application(Request $request){
        try {
            $validated = $request->validate([
                'user_name' => 'required',
                'email' => 'required|email|unique:Employers',
                'phone_number' => 'required',
                'resume' => 'required|file|mimes:pdf,docx|max:10240', 
            ], [
                'user_name.required' => 'Username is required.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email is already registered.',
                'phone_number.required' => 'Password is required.',
                'resume.required' => 'Resume is required'
            ]);
             // Handle file upload if present
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public'); // Store in the 'public/resumes' folder
            }
            $status_id = 0;
            $status = InterviewStatus::where('status_title','Pending')->first();
            if ($status) {
                $status_id = $status->id;
            }

            $job_applicant = new JobApplicant();
            $job_applicant->user_name = $request->input('user_name');
            $job_applicant->email = $request->input('email');
            $job_applicant->phone_number = $request->input( 'phone_number'); 
            $job_applicant->resume = $resumePath; 
            $job_applicant->cover_letter = $request->input('cover_letter');
            $job_applicant->job_id = $request->input('job_id'); 
            $job_applicant->status = $status_id; 
            $job_applicant->save();
    
            Session::flash('success', 'Application submitted successfully.');        
            return redirect()->route('view-job', ['id' => $request->input('job_id')]);

        } catch (ValidationException $e) {
            Session::flash('error', 'There was an issue with your submission. Please try again.');   
            return redirect()->route('view-job', ['id' => $request->input('job_id')])
            ->withErrors($e->errors())  
            ->withInput(); 
        }          
    }

    /**
     * Undocumented function
     *
     * @param String $plan_id
     * @param String $employer_id
     * @return void
     */
    public function plan_process(String $plan_id, String $employer_id){
        $selected_plan = SubscriptionPlans::findOrFail($plan_id);
        return view('payments.payment_page', compact('selected_plan','employer_id'));
    }   

    /**
     * function for get employes data using id
     *
     * @param String $employer_id
     * @return void
     */
    public function get_employer(String $employer_id){
        $employer_info = Employer::findOrFail($employer_id);
        return response()->json([
            'success' => true,
            'data' => $employer_info,
        ]);
    }

    /**
     * function for get sub industry using industry id
     *
     * @param String $industry_id
     * @return void
     */
    public function sub_industry(String $industry_id){
        $industry_info = SubIndustry::where('industry_id', $industry_id)->get();
        return response()->json([
            'success' => true,
            'data' => $industry_info,
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function profile(){
        $menu_title = "Contact";
        $breadcrumb_title = "Profile";

        $employer_id = Auth::user()->id;
        $employer_data = Employer::findOrFail($employer_id);
        $company_data = EmployerCompany::where('employer_id',$employer_id)->first();

        $user_last_subscription = EmployerSubscription::join('subscription_plans', 'employer_subscriptions.plan_id', '=', 'subscription_plans.id')
        ->where(['employer_subscriptions.status' => 'Active', 'employer_subscriptions.employer_id' => $employer_id])
        ->orderBy('employer_subscriptions.id', 'desc')
        ->first(['employer_subscriptions.*', 'subscription_plans.plan_name','subscription_plans.features', 'subscription_plans.price','subscription_plans.duration_type','employer_subscriptions.status']);

        $industry_list = Industry::all();   
        $subIndustry_list = SubIndustry::where('industry_id',$company_data['industry_id'])->get();

        $country_phone_code = CountryPhoneCode::all();
        return view('dashboard.profile',compact('menu_title','breadcrumb_title','employer_data','company_data','user_last_subscription','industry_list','country_phone_code','subIndustry_list'));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function update_employer(Request $request){    
                
        try {
            $validated = $request->validate([
                'employer_name' => 'required',
                'employer_email' => 'required|email',
                'phone_code' => 'required',
                'employer_phone' => 'required',
            ], [
                'employer_name.required' => 'Employer Name is required.',
                'employer_email.required' => 'Email is required.',
                'employer_email.email' => 'Please provide a valid email address.',
                'phone_code.required' => 'Phone Code is required.',
                'employer_phone.required' => 'Phone Number is required.',
            ]);
            
            $employer_id = $request->input('employer_id');
             // Handle file upload if present
             $profilePath = null;
             if ($request->hasFile('profile_image')) {               
                 $profilePath = $request->file('profile_image')->store('profile_image', 'public');
             }

            Employer::where('id',$employer_id)->update([
                'employer_name' => $request->input('employer_name'),
                'employer_email' => $request->input('employer_email'),
                'employer_phone' => $request->input('employer_phone'),
                'phone_code' => $request->input('phone_code'),
                'image' => $profilePath
            ]);                      
    
            Session::flash('success', 'Basic Info update successfully.');        
            return redirect()->route('profile');

        } catch (ValidationException $e) {
            Session::flash('error', 'There was an issue with your submission. Please try again.');   
            return redirect()->route('profile')
            ->withErrors($e->errors())  
            ->withInput(); 
        }     
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function update_company_info(Request $request){
        try {
            $validated = $request->validate([
                'company_name' => 'required',
                'industry_id' => 'required',
                'sub_industry_id' => 'required',
                'company_size' => 'required',
                'corporate_address' => 'required', 
            ], [
                'company_name.required' => 'Company Name is required.',
                'industry_id.required' => 'Industry is required.',
                'sub_industry_id.required' => 'Sub Industry is required.',
                'company_size.required' => 'Company Size is required.',
                'corporate_address.required' => 'Corportate Address is required.',
            ]);
    
            
            $company_id = $request->input('company_id');
             // Handle file upload if present
             $company_logoPath = null;
             if ($request->hasFile('company_logo')) {               
                 $company_logoPath = $request->file('company_logo')->store('company_logo', 'public');
             }

             EmployerCompany::where('id',$company_id)->update([
                'company_name' => $request->input('company_name'),
                'industry_id' => $request->input('industry_id'),
                'sub_industry_id' => $request->input('sub_industry_id'),
                'company_size' => $request->input('company_size'),
                'corporate_address' => $request->input('corporate_address'),
                'company_logo' => $company_logoPath
            ]);                      
    
            Session::flash('success', 'Company Info Update Successfully.');        
            return redirect()->route('profile');

        } catch (ValidationException $e) {
            Session::flash('error', 'There was an issue with your submission. Please try again.');   
            return redirect()->route('profile')
            ->withErrors($e->errors())  
            ->withInput(); 
        }    
    }
}   
