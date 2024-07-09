<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index()
    {
        $categories  = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();
        $newCategories = Category::where('status',1)->orderBy('name','ASC')->get();
        $featured_jobs = Job::with('jobType')->where('status',1)->orderBy('created_at','DESC')->where('is_featured',1)->take(6)->get();
        $latest_jobs = Job::with('jobType')->where('status',1)->orderBy('created_at','DESC')->take(6)->get();



        return view('front.home' , compact('categories','featured_jobs','latest_jobs','newCategories'));
    }


   

}