<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\SaveJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class AccountController extends Controller
{
     // This Method  Will Show User Registration Page
    public function registration()
    {
        return view('front.account.registration');

    }

    // This Method Will Save A User
    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users|email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes()) {
            // Save the user to the database (add your user creation logic here)

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success','You Have Registered Successfully');


            return response()->json([
                'status' => true,
                'errors' =>[]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }




    // This Method  Will Show User Login Page
    public function login()
    {
        return view('front.account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->passes())
        {
            if(Auth::attempt(['email' =>$request->email,'password' =>$request->password]))
            {
                return redirect()->route('account.profile');

            }else{
                return redirect()->route('account.login')->with('error','Either Email/Password is Incorrect');

            }
        }else{
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));

        }


    }

    public function profile()
    {
        $id= Auth::user()->id;
        $user = User::where('id',$id)->first();
        return view('front.account.profile' , compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id',
        ]);
        if ($validator->passes())
        {
            $user = User::find($id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->mobile=$request->mobile;
            $user->designation=$request->designation;
            $user->save();
            session()->flash('success','Profile Update Successfully.');

            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);

        }


    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePic(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->passes()) {
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;
            $image->move(public_path('/profile_pic/'), $imageName);

            // create new image instance (800 x 600)
            $sourcePath= public_path('/profile_pic/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

             // Delete Old Profile Pic
             File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
             File::delete(public_path('/profile_pic/'.Auth::user()->image));


            User::where('id', $id)->update(['image' => $imageName]);

            session()->flash('success', 'Profile Picture Updated Successfully.');

            return response()->json(['status' => true, 'errors' => []]);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function savedJobs()
    {
        $savedJobs = SaveJob::where(['user_id'=>Auth::user()->id])
                     ->with('job','job.jobType','job.applications')
                     ->orderBy('created_at','DESC')
                     ->paginate(10);

        return view('front.job.saved_jobs',compact('savedJobs'));

    }

    public function removeSavedJob(Request $request)
    {
        try {
            $savedJobs = SaveJob::where('id', $request->id)
                                ->where('user_id', Auth::user()->id)
                                ->first();

            if (!$savedJobs) {
                session()->flash('error', 'Job not found.');

                return response()->json(['status' => false]);
            }
            // Delete the job application
            SaveJob::find($request->id)->delete();
            session()->flash('success', 'Job removed successfully.');

            return response()->json(['status' => true, 'success' => 'Job removed successfully.']);

        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Failed to remove job application: ' . $e->getMessage());
            return response()->json(['status' => false, 'error' => 'Failed to remove job.']);
        }
    }


    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }

       if(Hash::check($request->old_password,Auth::user()->password)==false)
       {
        session()->flash('error', 'Your Old Password is incorrect!.');

        return response()->json([
            'status' => true,
        ]);

       }

       $user = User::find(Auth::user()->id);
       $user->password = Hash::make($request->new_password);
       $user->save();

       session()->flash('success', 'Password Updated Successfully!.');

        return response()->json([
            'status' => true,
        ]);



    }

    // Show the forgot password form
    public function forgotPassword()
    {
        return view('front.account.forgot_password');
    }

    // Process the forgot password request
    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(10);

        // Remove any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert the new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send email with the reset password link
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to change your password',
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success', 'Reset Password email has been sent to your inbox!');
    }

    // Show the reset password form
    public function resetPassword($tokenString)
    {
        $token = DB::table('password_reset_tokens')->where('token', $tokenString)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid Token!');
        }

        return view('front.account.reset_password', compact('tokenString'));
    }

    // Process the reset password request
    public function processResetPassword(Request $request)
    {
        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid Token!');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword', $request->token)->withErrors($validator);
        }

        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Remove the token after password reset
        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        return redirect()->route('account.login')->with('success', 'You have successfully changed your password');
    }



}