@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">My Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            @include('front.layouts.sidebar')

            <div class="col-lg-9">
                @include('front.message')

                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <h3 class="fs-4 mb-1">My Jobs</h3>
                            <a href="{{ route('job.create') }}" class="btn btn-primary mb-3">Post a Job</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Job Created</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($jobs->isNotEmpty())
                                        @foreach($jobs as $job)
                                            <tr class="active">
                                                <td>
                                                    <div class="job-name fw-500">{{ $job->title }}</div>
                                                    <div class="info1">{{ $job->jobType->name }} . {{ $job->location }}</div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</td>
                                                <td>0 Applications</td>
                                                <td>
                                                    <div class="job-status text-capitalize">
                                                        {{ $job->status == 1 ? 'Active' : 'Block' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="{{ route('job.detail', $job->id) }}"><i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('job.edit', $job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                            <li><a class="dropdown-item" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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
<script>
    function deleteJob(jobId) {
        if (confirm("Are You Sure You Want To Delete!?")) {
            $.ajax({
                url: '{{ route("job.delete") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    jobId: jobId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        window.location.href = '{{ route("job.myJob") }}';
                    } else {
                        alert('Error deleting job.');
                    }
                }
            });
        }
    }
</script>
@endsection


