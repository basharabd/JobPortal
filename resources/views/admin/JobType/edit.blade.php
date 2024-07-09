@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Job Types</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            @include('front.layouts.sidebar')
            <div class="col-lg-9">
                @include('front.message')
                <form action="{{ route('admin.jobTypes.update', $jobType->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Edit Job Types</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="name" class="mb-2">Job Types Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Types Name" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $jobType->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="status" class="mb-2">Status<span class="req">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="1" {{ old('status', $jobType->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $jobType->status) == 0 ? 'selected' : '' }}>Block</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Update Job Types</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
