@extends('layouts.main_scorring')
@section('content')
<div class="card card-flush w-md-650px py-5">
    <div class="card-body py-15 py-lg-20 text-center">
        <!--begin::Logo-->
        <div class="mb-7">
            <img alt="Logo" src="{{ asset('assets/media/logo-pln.png') }}" class="h-40px" />
        </div>
        <!--end::Logo-->

        <div class="mb-10">
            <h1 class="fw-bolder text-gray-900 mb-3">Your Training Programs</h1>
            <div class="fw-semibold text-muted mb-7">Please select a training program to fill out the assessment form</div>
        
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="min-w-200px ps-4 rounded-start">Training Program</th>
                            <th class="min-w-125px">Status</th>
                            <th class="min-w-150px">Assessment</th>
                            <th class="min-w-100px text-end pe-4 rounded-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableDiklat as $participant)
                        <tr>
                            <td>
                                <span class="text-dark fw-bold text-hover-primary fs-6">{{ $participant->diklat->name }}</span>
                                <span class="text-muted fw-semibold text-muted d-block fs-7">
                                    Letter Number: {{ $participant->diklat->letter_number }}
                                </span>
                            </td>
                            <td>
                                @if($participant->diklat->status_monitoring == 'Selesai')
                                    <span class="badge badge-light-success">Completed</span>
                                @elseif($participant->diklat->status_monitoring == 'Berlangsung')
                                    <span class="badge badge-light-warning">In Progress</span>
                                @else
                                    <span class="badge badge-light-primary">{{ $participant->diklat->status_monitoring }}</span>
                                @endif
                            </td>
                            <td>
                                @if($participant->scoreLv1->count() > 0)
                                    <span class="badge badge-success">
                                        <i class="ki-duotone ki-check-circle fs-6 me-1"></i>Submitted
                                    </span>
                                @else
                                    <span class="badge badge-light-danger">
                                        <i class="ki-duotone ki-cross-circle fs-6 me-1"></i>Not Submitted
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($participant->scoreLv1->count() > 0)
                                    {{-- <a href="{{ route('form.lv1.form', ['hash_slug' => encrypt_custom($participant->diklat->slug)]) }}" 
                                       class="btn btn-icon btn-light-primary btn-sm">
                                        <i class="ki-duotone ki-eye fs-2"></i>
                                    </a> --}}
                                    -
                                @else
                                    <a href="{{ route('form.lv1.form', ['hash_slug' => encrypt_custom($participant->diklat->slug)]) }}" 
                                       class="btn btn-primary btn-sm">
                                        Fill Form
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="btn btn-light">Logout</a>
        </div>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // JavaScript functionality for this page
    });
</script>
@endsection