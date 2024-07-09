<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at','DESC')->paginate();
        return view('admin.JobCategories.list',compact('categories'));
    }

    public function create()
    {
        return view('admin.JobCategories.create');
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
        $category = new Category;
        $category->name = $request->input('name');
        $category->status = $request->input('status');
        $category->save();

        // Redirect to a success page or back to the form with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.JobCategories.edit',compact('category'));
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
        $category = Category::findOrFail($id);

        // Update the category with validated data
        $category->name = $request->input('name');
        $category->status = $request->input('status');

        // Save the changes
        $category->save();

        // Redirect back with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;

        // Find the category by ID
        $category = Category::find($id);

        // Delete the category
        $category->delete();

        // Return a redirect with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }


}