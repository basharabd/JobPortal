@extends('front.layouts.app')

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="{{ route('account.processRegistration') }}" method="post" name="registrationForm" id="registrationForm">
                        @csrf <!-- Add CSRF token here -->
                        <div class="mb-3">
                            <label for="name" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p class="invalid-feedback"></p>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p class="invalid-feedback"></p>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p class="invalid-feedback"></p>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter Confirm Password">
                            <p class="invalid-feedback"></p>
                        </div>
                        <button class="btn btn-primary mt-2" type="submit">Register</button>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mx-3">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close"></button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('customJs')
<script>
    $("#registrationForm").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("account.processRegistration") }}',
            type: 'post',
            data: $("#registrationForm").serialize(),
            dataType: 'json',
            success: function(response) {
                // Clear previous error messages
                $('.invalid-feedback').html('');
                $('.form-control').removeClass('is-invalid');

                if (response.status === false) {
                    var errors = response.errors;

                    // Name field
                    if (errors.name) {
                        $("#name").addClass('is-invalid').siblings('.invalid-feedback').html(errors.name[0]);
                    } else {
                        $("#name").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }

                    // Email field
                    if (errors.email) {
                        $("#email").addClass('is-invalid').siblings('.invalid-feedback').html(errors.email[0]);
                    } else {
                        $("#email").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }

                    // Password field
                    if (errors.password) {
                        $("#password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.password[0]);
                    } else {
                        $("#password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }

                    // Confirm Password field
                    if (errors.confirm_password) {
                        $("#confirm_password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.confirm_password[0]);
                    } else {
                        $("#confirm_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }
                } else {
                    // Remove all invalid classes and error messages
                    $("#name").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    $("#email").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    $("#password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    $("#confirm_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');

                    // Redirect to login page
                    window.location.href = '{{ route("account.login") }}';
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred. Please try again.');
            }
        });
    });
</script>
@endsection
