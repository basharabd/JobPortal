<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SaveJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function create()
    {
        $categories  = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.job.create',compact('categories','jobTypes'));
    }

    public function saveJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:200',
            'category_id' => 'required',
            'type_job_id' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name'=>'required|min:3|max:75',
        ]);

        if ($validator->passes())
         {
            $job =new Job();
            $job->title=$request->title;
            $job->category_id=$request->category_id;
            $job->type_job_id=$request->type_job_id;
            $job->user_id=Auth::user()->id;
            $job->vacancy=$request->vacancy;
            $job->salary=$request->salary;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualifications=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->company_website;
            $job->save();

            session()->flash('success', 'Job Add Successfully.');

            return response()->json(['status' => true, 'errors' => []]);

        }else{
            return response()->json(['status' => false, 'errors' => $validator->errors()]);

        }
    }

    public function myJob()
    {
        $jobs = Job:: with('jobType', 'category')  // Including the category relationship
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at','DESC')
            ->paginate(10);
        return view('front.job.my_job', compact('jobs'));
    }

    public function edit(Request $request,$id)
    {
        $categories  = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();
        $job = Job::where(['user_id'=>Auth::user()->id ,'id'=>$id])->first();
        return view('front.job.edit',compact('categories','jobTypes','job'));
    }

    public function updateJob(Request $request ,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:200',
            'category_id' => 'required',
            'type_job_id' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name'=>'required|min:3|max:75',
        ]);

        if ($validator->passes())
         {
            $job =Job::find($id);
            $job->title=$request->title;
            $job->category_id=$request->category_id;
            $job->type_job_id=$request->type_job_id;
            $job->user_id=Auth::user()->id;
            $job->vacancy=$request->vacancy;
            $job->salary=$request->salary;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualifications=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->company_website;
            $job->save();

            session()->flash('success', 'Job Updated Successfully.');

            return response()->json(['status' => true, 'errors' => []]);

        }else{
            return response()->json(['status' => false, 'errors' => $validator->errors()]);

        }
    }
    public function deleteJob(Request $request)
    {
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if (!$job) {
            session()->flash('error', 'Either Job Deleted Or Not Found!.');
            return response()->json(['status' => false]);
        }

        $job->delete();

        session()->flash('success', 'Job Deleted Successfully!.');
        return response()->json(['status' => true]);
    }


    public function index(Request $request)
    {
        // Extract request parameters
        $keywords = $request->input('keywords');
        $location = $request->input('location');
        $category = $request->input('category');
        $jobTypeArray = $request->input('job_type', []);
        $experience = $request->input('experience');
        $sort = $request->input('sort', 1);

        // Ensure jobTypeArray is an array
        if (is_string($jobTypeArray)) {
            $jobTypeArray = explode(',', $jobTypeArray);
        }

        // Build the query
        $jobs = Job::where('status', 1);

        // Filter by keywords
        if (!empty($keywords)) {
            $jobs->where(function ($query) use ($keywords) {
                $query->where('title', 'like', '%' . $keywords . '%')
                      ->orWhere('keywords', 'like', '%' . $keywords . '%');
            });
        }

        // Filter by location
        if (!empty($location)) {
            $jobs->where('location', $location);
        }

        // Filter by category
        if (!empty($category)) {
            $jobs->where('category_id', $category);
        }

        // Filter by job type
        if (!empty($jobTypeArray)) {
            $jobs->whereIn('type_job_id', $jobTypeArray);
        }

        // Filter by experience
        if (!empty($experience)) {
            $jobs->where('experience', $experience);
        }

        // Sort jobs
        $orderDirection = $sort == 0 ? 'ASC' : 'DESC';
        $jobs->orderBy('created_at', $orderDirection);

        // Fetch results with relations and paginate
        $jobs = $jobs->with(['jobType', 'category'])->paginate(10);

        // Fetch categories and job types
        $categories = Category::where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();

        // Return view with data
        return view('front.job.index', compact('categories', 'jobTypes', 'jobs', 'jobTypeArray'));
    }

    public function detail($id)
    {
        $job = Job::with('jobType', 'category')->where(['id'=>$id , 'status'=>1])->first();
        if($job == null)
        {
            abort(404);

        }


        $count=0;
        if(Auth::user())
        {

            $count = SaveJob::where([
                'user_id'=>Auth::user()->id,
                'job_id'=>$id
            ])->count();
        }

        // Fetch Applicants

       $applications = JobApplication::where('job_id',$id)->with('user')->paginate(10);


       return view('front.job.detail' , compact('job','count','applications'));
    }

    public function applyJop(Request $request)
    {
        $id =  $request->id;
        $job = Job::where('id',$id)->first();

        // if job does not  found in db
        if($job == null)
        {
            session()->flash('error', 'Job dose not exist.');
            return response()->json(['status' => false, 'message' => 'Job dose not exist!']);
        }

        // you can not apply  on  your own job

        $employer_id = $job->user_id;
        if($employer_id == Auth::user()->id)
        {
            session()->flash('error', 'You can not apply on your own job!.');
            return response()->json(['status' => false, 'message' => 'You can not apply on your own job!']);
        }

        // you can not apply on a job twice
        $jobApplicationCount = JobApplication::where([
            'user_id'=>Auth::user()->id,
            'job_id'=>$id
        ])->count();

        if($jobApplicationCount > 0)
        {
            $message = "You already applied on this job!";
            session()->flash('error', $message);
            return response()->json(['status' => false, 'message' => $message]);

        }



        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();


        // Send Notification Email To Employer
        $employer = User::where('id',$employer_id)->first();

        $mailData=[
            'employer'=>$employer,
            'user'=>Auth::user(),
            'job'=>$job,

        ];

        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));



        $message = "You Have Successfully Applied!";
        session()->flash('success', $message);
        return response()->json(['status' => true, 'message' => $message]);

    }


    public function myJobApplications()
    {
        $jobApplications = JobApplication::
                           where('user_id',Auth::user()->id)
                           ->with('job','job.jobType','job.applications')
                           ->orderBy('created_at','DESC')

                           ->paginate(10);

        return view('front.job.my_job_application',compact('jobApplications'));
    }

    public function removeJob(Request $request)
    {
        try {
            $jobApplication = JobApplication::where('id', $request->id)
                                ->where('user_id', Auth::user()->id)
                                ->first();

            if (!$jobApplication) {
                return response()->json(['status' => false, 'message' => 'Job application not found.']);
            }

            // Delete the job application
            $jobApplication->delete();

            return response()->json(['status' => true, 'message' => 'Job application removed successfully.']);
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Failed to remove job application: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to remove job application.']);
        }
    }


    public function savedJob(Request $request)
    {
        $id = $request->id;

        $job = Job::find($id);

        if($job == null)
        {
            session()->flash('error','Job not found!');
            return response()->json(['status'=>false]);
        }

        // Check user already saved the job

        $count = SaveJob::where([
            'user_id'=>Auth::user()->id,
            'job_id'=>$id
        ])->count();

        if($count>0)
        {
            session()->flash('error','You already applied on this job!.');
            return response()->json(['status'=>false]);
        }

        $savedJob = new SaveJob();
        $savedJob->job_id =$id;
        $savedJob->user_id=Auth::user()->id;
        $savedJob->save();

        session()->flash('success','You have successfully  save the job!.');
        return response()->json(['status'=>true]);






    }







}