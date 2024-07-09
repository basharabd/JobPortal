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
                <div class="card border-0 shadow mb-4">
                    <form action="{{ route('account.updateProfile') }}" id="userForm" name="userForm">
                        @csrf
                        @method('put')
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">My Profile</h3>
                            <div class="mb-4">
                                <label for="name" class="mb-2">Name*</label>
                                <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control" value="{{ $user->name }}">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="mb-2">Email*</label>
                                <input type="text" id="email" name="email" placeholder="Enter Email" class="form-control" value="{{ $user->email }}">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="mb-4">
                                <label for="designation" class="mb-2">Designation*</label>
                                <input type="text" id="designation" name="designation" placeholder="Enter Designation" class="form-control" value="{{ $user->designation }}">
                            </div>
                            <div class="mb-4">
                                <label for="mobile" class="mb-2">Mobile*</label>
                                <input type="text" id="mobile" name="mobile" placeholder="Enter Mobile" class="form-control" value="{{ $user->mobile }}">
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    $("#userForm").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("account.updateProfile") }}',
            type: 'put',
            data: $("#userForm").serialize(),
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
                } else {
                    // Remove all invalid classes and error messages
                    $("#name").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    $("#email").removeClass('is-invalid').siblings('.invalid-feedback').html('');

                    // Redirect to profile page
                    window.location.href = '{{ route("account.profile") }}';
                }
            },

        });
    });


    $("#changePasswordForm").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("account.updatePassword") }}',
            type: 'post',
            data: $("#changePasswordForm").serialize(),
            dataType: 'json',
            success: function(response) {
                // Clear previous error messages
                $('.invalid-feedback').html('');
                $('.form-control').removeClass('is-invalid');

                if (response.status === false) {
                    var errors = response.errors;

                    // old password field
                    if (errors.old_password) {
                        $("#old_password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.old_password[0]);
                    } else {
                        $("#old_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }

                    // new password field
                    if (errors.new_password) {
                        $("#new_password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.new_password[0]);
                    } else {
                        $("#new_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }

                     // confirm password field
                     if (errors.confirm_password) {
                        $("#confirm_password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.confirm_password[0]);
                    } else {
                        $("#confirm_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    }
                } else {
                    // Remove all invalid classes and error messages
                    $("#old_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    $("#new_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');
                    $("#confirm_password").removeClass('is-invalid').siblings('.invalid-feedback').html('');

                    // Redirect to profile page
                    window.location.href = '{{ route("account.profile") }}';
                }
            },

        });
    });
</script>
@endsection
