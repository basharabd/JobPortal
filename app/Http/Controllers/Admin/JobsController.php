<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    public function index()
    {
        $jobs = Job::orderBy('created_at','DESC')->with(['user','applications'])->paginate(10);
        return view ('admin.jobs.list',compact('jobs'));

    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $categories  = Category::orderBy('name','ASC')->get();
        $jobTypes = JobType::orderBy('name','ASC')->get();
        return view ('admin.jobs.edit',compact('job','categories','jobTypes'));
    }

    public function update(Request $request ,$id)
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
            $job->status=$request->status;
            $job->is_featured= (!empty($request->is_featured))?$request->is_featured :0;

            $job->save();

            session()->flash('success', 'Job Updated Successfully.');

            return response()->json(['status' => true, 'errors' => []]);

        }else{
            return response()->json(['status' => false, 'errors' => $validator->errors()]);

        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $job = Job::find($id);
        if($job == null){

            session()->flash('error','Either Job Deleted Or  Not Found!');
            return response()->json(['status'=>false]);
        }


        $job->delete();
        session()->flash('success','Job Deleted Successfully!');
            return response()->json(['status'=>true]);

    }
}