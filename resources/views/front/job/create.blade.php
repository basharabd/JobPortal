@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            @include('front.layouts.sidebar')
            <div class="col-lg-9">
                @include('front.message')
                <form action="{{ route('job.saveJob') }}" method="post" id="createJobForm" name="createJobForm">
                    @csrf
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="title" class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" id="title" name="title" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="category_id" class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="type_job_id" class="mb-2">Job Nature<span class="req">*</span></label>
                                    <select name="type_job_id" id="type_job_id" class="form-select">
                                        <option value="">Select a Job Nature</option>
                                        @if($jobTypes->isNotEmpty())
                                        @foreach($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="vacancy" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="salary" class="mb-2">Salary</label>
                                    <input type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="location" class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="Location" id="location" name="location" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="form-control textarea" name="description" id="description" cols="5" rows="5" placeholder="Description"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-4">
                                <label for="benefits" class="mb-2">Benefits</label>
                                <textarea class="form-control textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-4">
                                <label for="responsibility" class="mb-2">Responsibility</label>
                                <textarea class="form-control textarea" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-4">
                                <label for="qualifications" class="mb-2">Qualifications</label>
                                <textarea class="form-control textarea" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="experience" class="mb-2">Experience<span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-select">
                                    <option value="">Select Experience</option>
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                    <option value="4">4 Years</option>
                                    <option value="5">5 Years</option>
                                    <option value="6">6 Years</option>
                                    <option value="7">7 Years</option>
                                    <option value="8">8 Years</option>
                                    <option value="9">9 Years</option>
                                    <option value="10">10 Years</option>
                                    <option value="10_plus">10+ Years</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="keywords" class="mb-2">Keywords<span class="req">*</span></label>
                                <input type="text" placeholder="Keywords" id="keywords" name="keywords" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="company_name" class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="company_location" class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Location" id="company_location" name="company_location" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="website" class="mb-2">Website</label>
                                <input type="text" placeholder="Website" id="website" name="company_website" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Save Job</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#createJobForm").submit(function(e) {
        e.preventDefault();
       $("button[type='submit']").prop('disabled', true);

        $.ajax({
            url: '{{ route("job.saveJob") }}',
            type: 'POST',
            data: $("#createJobForm").serialize(),
            dataType: 'json',
            success: function(response) {
                $("button[type='submit']").prop('disabled', false);

                // Clear previous error messages
                $('.invalid-feedback').html('');
                $('.form-control').removeClass('is-invalid');

                if (response.status === false) {
                    var errors = response.errors;

                    // Title field
                    if (errors.title) {
                        $("#title").addClass('is-invalid').siblings('.invalid-feedback').html(errors.title[0]);
                    }

                    // Category field
                    if (errors.category_id) {
                        $("#category_id").addClass('is-invalid').siblings('.invalid-feedback').html(errors.category_id[0]);
                    }

                    // Job Nature field
                    if (errors.type_job_id) {
                        $("#type_job_id").addClass('is-invalid').siblings('.invalid-feedback').html(errors.type_job_id[0]);
                    }

                    // Vacancy field
                    if (errors.vacancy) {
                        $("#vacancy").addClass('is-invalid').siblings('.invalid-feedback').html(errors.vacancy[0]);
                    }

                    // Location field
                    if (errors.location) {
                        $("#location").addClass('is-invalid').siblings('.invalid-feedback').html(errors.location[0]);
                    }

                    // Description field
                    if (errors.description) {
                        $("#description").addClass('is-invalid').siblings('.invalid-feedback').html(errors.description[0]);
                    }

                    // Company Name field
                    if (errors.company_name) {
                        $("#company_name").addClass('is-invalid').siblings('.invalid-feedback').html(errors.company_name[0]);
                    }

                } else {
                    // Remove all invalid classes and error messages
                    $("#createJobForm").find('.form-control').removeClass('is-invalid').siblings('.invalid-feedback').html('');

                    // Redirect to profile page
                    window.location.href = '{{ route("job.myJob") }}';
                }
            }
        });
    });
</script>
@endsection
