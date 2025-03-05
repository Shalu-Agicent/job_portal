<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\SubscriptionPlans;
use App\Models\Transactions;
use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{   
    

    public function process_payment(Request $request){       
        try {

            // $employer_info = Employer::find($request->employer_id);
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $responseJson = $stripe->charges->create([
                'amount' => 1*100,
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Test Payment',
            ]); 

            if($responseJson->status=='succeeded'){
                // ------- save data in transaction table -----
                $transaction = new Transactions();
                $transaction->employer_id = $request->employer_id;
                $transaction->charge_id = $responseJson->id;
                $transaction->amount = $responseJson->amount;
                $transaction->currency = $responseJson->currency;
                $transaction->status = $responseJson->status;
                $transaction->payment_method = $responseJson->payment_method;
                $transaction->card_brand = $responseJson->payment_method_details->card->brand;;
                $transaction->card_last4 = $responseJson->payment_method_details->card->last4;;
                $transaction->created_at = date('Y-m-d H:i:s', $responseJson->created);
                $transaction->balance_transaction_id = $responseJson->balance_transaction;
                $transaction->save();   


                // --- subscription plan deatils in User Subscription table -----
                $subscription_id = $request->plan_id;
            
                $subscription_plan = SubscriptionPlans::findOrFail($subscription_id); 
                $start_date = Carbon::now();
                if ($subscription_plan->duration_type === 'Month') {
                    $end_date = $start_date->copy()->addMonths($subscription_plan->duration);
                } elseif ($subscription_plan->duration_type === 'Year') {
                    $end_date = $start_date->copy()->addYears($subscription_plan->duration);
                }

                $new_subscription = new EmployerSubscription([
                    'plan_id' => $subscription_id,
                    'employer_id' => $request->employer_id,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => 'Active',
                    'payment_method' => $responseJson->payment_method, 
                ]);

                $new_subscription->save();
                Employer::where('id', $request->employer_id)->update([
                    'completed_steps' => 4,
                    'subscription' => 1,
                ]);

                session()->flash('success', 'Payment successful!');
                return redirect()->route('payment-success');
            }else{
                
                $errorMessage = 'Payment failed. Status: ' . $responseJson->status;
                session()->flash('error', $errorMessage);
                return redirect()->route('payment-failure');
            }
            
        } catch (\Exception $e) {

            session()->flash('error', $e->getMessage());
            return redirect()->route('payment-failure');
        }
    }

    public function payment_success(){
        return view('payments.payment_success');
    }

    public function payment_failure(){
        return view('payments.payment_failure');
    }
    
}
