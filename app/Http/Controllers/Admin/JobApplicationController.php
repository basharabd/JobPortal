<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::orderBy('created_at','DESC')
                                        ->with(['job','user','employer'])
                                        ->paginate(10);
        return view('admin.JobApplication.list',compact('applications'));

    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $JobApplication = JobApplication::find($id);
        if($JobApplication == null){

            session()->flash('error','Either Job Application deleted or  Not Found!');
            return response()->json(['status'=>false]);
        }


        $JobApplication->delete();
        session()->flash('success','JobApplication Deleted Successfully!');
            return response()->json(['status'=>true]);

    }

}