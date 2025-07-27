@extends('layouts.app_login')

@section('content')
<div class="flex-row-fluid d-flex flex-center p-6">
    <!--begin::Wrapper-->
    <div class="flex-center p-10 shadow bg-body rounded w-100 w-md-550px mx-auto ms-xl-20 my-auto">
        <!--begin::Form-->
        <form class="form" novalidate="novalidate" id="kt_free_trial_form" method="POST" action="{{ route('login') }}">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-8">
                <!--begin::Title-->
                <h1 class="text-dark mb-2">Sign In</h1>
                <!--end::Title-->
                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4">Insert your Email and Password
                    {{-- <a href="#" class="link-primary fw-bolder">FAQ</a>. --}}
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            @error('email')
                <!--begin::Alert-->
                <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                    <!--begin::Icon-->
                    <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"></rect>
                            <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"></rect>
                        </svg>
                    </span>
                    <!--end::Icon-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <!--begin::Title-->
                        <h4 class="fw-bold">Unauthorize</h4>
                        <!--end::Title-->
                        <!--begin::Content-->
                        {{-- <span>adsasdad</span> --}}
                        <span>{{ $message }}</span>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Close-->
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <span class="svg-icon svg-icon-1 svg-icon-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </button>
                    <!--end::Close-->
                </div>
                <!--end::Alert-->
            @enderror
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <label class="form-label fw-bolder text-dark fs-6">Email</label>
                <input class="form-control form-control-solid" type="email" placeholder="" name="email" autocomplete="off" />
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-7 fv-row" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="mb-1">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">Password</label>
                    <!--end::Label-->
                    <!--begin::Input wrapper-->
                    <div class="position-relative mb-3">
                        <input class="form-control form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                            <i class="bi bi-eye-slash fs-2"></i>
                            <i class="bi bi-eye fs-2 d-none"></i>
                        </span>
                    </div>
                    <!--end::Input wrapper-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Input group=-->
            <!--begin::Row-->
            <div class="text-center pb-lg-0 pb-8">
                <button type="button" id="kt_free_trial_submit" class="btn btn-lg btn-primary fw-bolder mb-5">
                    <span class="indicator-label">Sign-In</span>
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            <!--end::Row-->
            {{-- <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div> --}}
            {{-- <a href="{{ route('auth.google') }}" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5"><img alt="Logo" src="assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3" />Continue with Google</a> --}}
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
@endsection