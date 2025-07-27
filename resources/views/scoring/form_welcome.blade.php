@extends('layouts.main_scorring')
@section('content')
<div class="card card-flush w-md-650px py-5">
    <div class="card-body py-15 py-lg-20">
        <!--begin::Logo-->
        <div class="mb-7">
            <img alt="Logo" src="{{ asset('assets/media/logo-pln.png') }}" class="h-40px" />
        </div>
        <!--end::Logo-->
        
        @if($hash_slug == 'none')
            <!--begin::Title-->
            <h1 class="fw-bolder text-gray-900 mb-5">No Training Assignments</h1>
            <!--end::Title-->
            <!--begin::Text-->
            <div class="fw-semibold fs-6 text-gray-500 mb-7">You are not currently assigned to any training sessions.</div>
            <!--end::Text-->
            <!--begin::Link-->
            <div class="mb-0 text-center">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-primary">Back to Login</a>
            </div>
            <!--end::Link-->
        @else
            <!--begin::Title-->
            <h1 class="fw-bolder text-gray-900 mb-5">{{ $diklat->name }}</h1>
            <!--end::Title-->
            <!--begin::Text-->
            <div class="fw-semibold fs-6 text-gray-500 mb-7">
                You've been invited to complete an assessment form for this training program.
                <br>
                <strong>Unit:</strong> {{ $diklat->unit->name }}
            </div>
            <!--end::Text-->
            <!--begin::Illustration-->
            <div class="mb-7">
                <img src="{{ asset('assets/media/auth/welcome.png') }}" class="mw-100 mh-300px theme-light-show" alt="" />
                <img src="{{ asset('assets/media/auth/welcome-dark.png') }}" class="mw-100 mh-300px theme-dark-show" alt="" />
            </div>
            <!--end::Illustration-->
            <!--begin::Link-->
            <div class="mb-0 text-center">
                <a href="{{ route('form.lv1.form', $hash_slug) }}" class="btn btn-sm btn-primary">Continue to Assessment</a>
                @if(!Auth::check() && !Auth::guard('employee')->check())
                <div class="mt-4 text-muted">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </div>
                @endif
            </div>
            <!--end::Link-->
        @endif
    </div>
</div>
@endsection