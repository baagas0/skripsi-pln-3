@php
    $moduleName = 'Vendor';
    $moduleRoute = 'vendor';
    use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.main')
@section('title', $moduleName)
@section('content')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body">


                    <div class="mb-10">
                        <label for="exampleFormControlInput1" class="required form-label">Pelatihan</label>
                        <select name="diklat_id" class="form-select" data-placeholder="Pilih pelatihan">
                            <option></option>
                            @foreach ($diklats as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('diklat_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (Auth::user()->role_id == 6)
                        @if ($diklat)
                            <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">
                                {{ $diklat ? $diklat->name : '' }}</h1>

                            <a href="{{ route('.scorring.lv3.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 3 -
                                Behavior</a>
                            <hr>
                            <a href="{{ route('.scorring.lv4.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 4 - Result</a>
                            <hr>
                        @endif
                    @elseif (Auth::user()->role_id == 2)
                        @if ($diklat)
                            <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">
                                {{ $diklat ? $diklat->name : '' }}</h1>

                            <a href="{{ route('.scorring.lv2.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 2 -
                                Learning</a>
                        @endif
                    @else
                        @if ($diklat)
                            <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">
                                {{ $diklat ? $diklat->name : '' }}</h1>

                            {{-- <a href="{{ route('.scorring.lv3.{id}', $diklat->id) }}" class="text-decoration-unsderline text-primary fw-bold fs-4" data-bs-toggle="modal" data-bs-target="#scorring_lv_1">Penilaian 1</a> --}}
                            <a href="{{ route('.scorring.lv1.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 1 -
                                Reaction</a>
                            <hr>
                            <a href="{{ route('.scorring.lv2.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 2 -
                                Learning</a>
                            <hr>
                            <a href="{{ route('.scorring.lv3.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 3 -
                                Behavior</a>
                            <hr>
                            <a href="{{ route('.scorring.lv4.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 4 - Result</a>
                            <hr>
                            <a href="{{ route('.scorring.lv5.{id}', $diklat->id) }}"
                                class="text-decoration-unsderline text-primary fw-bold fs-4">Penilaian Level 5 - Return On
                                Training Investment (ROTI)</a>
                            <hr>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Modal --}}

        </div>
    </div>
@endsection
@section('script')
    <script>
        "use strict";

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            $('select[name="diklat_id"]').on('change', function() {
                var diklatId = $(this).val();
                var url = "{{ route('.scorring') }}";
                if (diklatId) {
                    url += '?diklat_id=' + diklatId;
                }
                window.location.href = url;
            });
        });
    </script>
@endsection
