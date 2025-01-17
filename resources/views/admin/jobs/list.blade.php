@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            @include('admin.sidebar')
            <div class="col-lg-9">
                @include('front.message')

                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <h3 class="fs-4 mb-1">Jobs</h3>
                            <a href="{{ route('job.saveJob') }}" class="btn btn-primary mb-2">Post a Job</a>

                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>

                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @forelse($jobs as $job)
                                    <tr class="active">
                                        <td>
                                            <div class="job-name fw-500">{{ $job->id }}</div>
                                        </td>
                                        <td>
                                            <div class="job-name fw-500">{{ $job->title }}</div>
                                            <p>Applicants :{{ $job->applications->count() }}</p>
                                        </td>
                                        <td>
                                            <div class="job-name fw-500">{{ $job->user->name }}</div>
                                        </td>
                                        <td>
                                            <div class="job-name fw-500">{{ \Carbon\Carbon::parse($job->created_at)->format('d, M Y') }}</div>
                                        </td>

                                        <td>
                                            @if($job->status == 1)
                                            <p class="text-success">Active</p>
                                            @else
                                            <p class="text-danger">Block</p>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-dots float-end">
                                                <button data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{route('admin.jobs.edit',$job->id)}}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                    <li><a class="dropdown-item" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No jobs found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $jobs->links() }} <!-- Pagination links -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
    function deleteJob(id)
    {
        if(confirm("Are you sure you want to delete?"))
        {
            $.ajax({
                url:'{{route("admin.job.destroy")}}',
                type:'delete',
                data:{id:id},
                dataType:'json',
                success:function(response){
                    window.location.href="{{route('admin.jobs')}}";
                }


            });

        }

    }

</script>

@endsection
