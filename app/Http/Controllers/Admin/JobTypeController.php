<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobTypeController extends Controller
{
    public function index()
    {
        $jobTypes = JobType::orderBy('created_at','DESC')->paginate();
        return view('admin.JobType.list',compact('jobTypes'));
    }

    public function create()
    {
        return view('admin.JobType.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required', // Assuming status is either 0 (inactive) or 1 (active)
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            // Return back to the form with input and errors
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        // If validation passes, create a new category
        $jobType = new JobType();
        $jobType->name = $request->input('name');
        $jobType->status = $request->input('status');
        $jobType->save();

        // Redirect to a success page or back to the form with a success message
        return redirect()->route('admin.jobTypes.index')->with('success', 'Job Type created successfully.');
    }

    public function edit($id)
    {
        $jobType = JobType::findOrFail($id);
        return view('admin.JobType.edit',compact('jobType'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the category by ID
        $jobType = JobType::findOrFail($id);

        // Update the category with validated data
        $jobType->name = $request->input('name');
        $jobType->status = $request->input('status');

        // Save the changes
        $jobType->save();

        // Redirect back with a success message
        return redirect()->route('admin.jobTypes.index')->with('success', 'Job Type updated successfully.');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;

        // Find the category by ID
        $jobType = JobType::find($id);

        // Delete the category
        $jobType->delete();

        // Return a redirect with a success message
        return redirect()->route('admin.jobTypes.index')->with('success', 'Job Type deleted successfully.');
    }
}