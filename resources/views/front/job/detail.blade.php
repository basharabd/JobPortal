@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('job.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                <div class="jobs_content">
                                    <a href="#">
                                        <h4>{{ $job->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p><i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p><i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now {{ $count == 1 ? 'saved-job' : '' }}">
                                    <a class="heart_mark" href="javascript:void(0)" onclick="saveJob({{ $job->id }})"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Job Description</h4>
                            <p>{!! nl2br($job->description) !!}</p>
                        </div>

                        @if($job->responsibility)
                        <div class="single_wrap">
                            <h4>Responsibility</h4>
                            <p>{!! nl2br($job->responsibility) !!}</p>
                        </div>
                        @endif

                        @if($job->qualifications)
                        <div class="single_wrap">
                            <h4>Qualifications</h4>
                            <p>{!! nl2br($job->qualifications) !!}</p>
                        </div>
                        @endif

                        @if($job->benefits)
                        <div class="single_wrap">
                            <h4>Benefits</h4>
                            <p>{!! nl2br($job->benefits) !!}</p>
                        </div>
                        @endif

                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            @auth
                            <a href="javascript:void(0);" onclick="saveJob({{ $job->id }})" class="btn btn-secondary">Save</a>
                            <a href="javascript:void(0);" onclick="applyJob({{ $job->id }})" class="btn btn-primary">Apply</a>
                            @else
                            <a href="javascript:void(0);" class="btn btn-primary disabled">Login to Save</a>
                            <a href="javascript:void(0);" class="btn btn-primary disabled">Login to Apply</a>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Job Summery</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Published on: <span>{{\Carbon\Carbon::parse($job->created_at)->format('d , M Y')}}</span></li>
                                <li>Vacancy: <span>{{$job->vacancy}}</span></li>
                                @if(!empty($job->salary))
                                <li>Salary: <span>{{$job->salary}}</span></li>
                                @endif
                                <li>Location: <span>{{$job->location}}</span></li>
                                <li>Job Nature: <span>{{$job->jobType->name}}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Name: <span>{{$job->company_name}}</span></li>
                                @if(!empty($job->company_location))
                                <li>Locaion: <span>{{$job->company_location}}</span></li>
                                @endif
                                @if(!empty($job->company_website))
                                <li>Webite: <span><a href="{{$job->company_website}}">{{$job->company_website}}</a></span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
    @if(auth()->id() == $job->user_id)
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                <div class="jobs_content">
                                    <a href="#">
                                        <h4>Applicants</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile No</th>
                                        <th scope="col">Applied Date</th>

                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($applications->isNotEmpty())
                                    @foreach($applications as $application)
                                    <tr class="active">
                                        <td>
                                            <div class="job-name fw-500">{{ $application->user->name }}</div>
                                        </td>
                                        <td>
                                            <div class="info1">{{ $application->user->email }}</div>
                                        </td>

                                        <td>
                                            <div class="info1">{{ $application->user->mobile }}</div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d, M Y') }}</td>


                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4" class="text-center">No applications found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{$applications->links()}} <!-- Pagination links -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth
</section>
@endsection


@section('customJs')

<script type="text/javascript">
    // apply Job
    function applyJob(id) {
        if (confirm("Are you sure you want to apply on this job?")) {
            $.ajax({
                url: '{{route("job.apply")}}'
                , type: 'post'
                , data: {
                    id: id
                }
                , dataType: 'json'
                , success: function(response) {
                    window.location.href = "{{url()->current()}}";
                }

            });

        }

    }

    //save job

    function saveJob(id) {
        $.ajax({
            url: '{{route("job.saved")}}'
            , type: 'post'
            , data: {
                id: id
            }
            , dataType: 'json'
            , success: function(response) {
                window.location.href = "{{url()->current()}}";
            }

        });

    }

</script>

@endsection
