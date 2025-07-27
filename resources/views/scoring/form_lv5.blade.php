@php
    $moduleName = 'Vendor';
    $moduleRoute = 'vendor';
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

                    <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Return of Training Investment</h1>

                    <form action="" id="form_cost" class="border rounded">
                        <div class="bg-primary p-3 rounded">
                            <h3 class="fw-bold mb-0 text-white">Cost of Training</h3>
                        </div>
                        <!--begin::Repeater-->
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Cost:</label>
                                    <input type="text" name="cost" {{ auth()->user()->role_id !== 7 ? '' : 'disabled' }} id="cost" class="form-control mb-2 mb-md-0" placeholder="Total (Rp.)" value="{{ $cost }}" />
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    @if (auth()->user()->role_id !== 7)
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--end::Repeater-->


                    </form>

                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <form action="" id="form_result" class="border rounded mt-10">
                                <div class="bg-primary p-3 rounded">
                                    <h3 class="fw-bold mb-0 text-white">Result</h3>
                                </div>
                                <!--begin::Repeater-->
                                <div class="p-3">
                                    <div class="input-group mb-3">
                                        <input type="text" readonly id="result" class="form-control" {{ auth()->user()->role_id !== 7 ? '' : 'disabled' }} placeholder="Result" value="{{ $roti }}" aria-label="Result" aria-describedby="basic-addon2"/>
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>

                                    <a href="/report?diklat_ids[]={{ $diklat ? $diklat->id : '' }}&levels[]=all">
                                        <button type="button" class="btn btn-success"> Print Report</button>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>


    <script>
        "use strict";

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            console.log('loaded')            

            $('#form_cost').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: `${base_url}/scorring/lv5/{{ $diklat->id }}/roti`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            $('#result').val(response.data.roti);
                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            })
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON?.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(
                                key => {
                                    toastr.error(xhr.responseJSON
                                        .errors[key][0]);
                                });
                        }
                    }
                });
            });
        });
    </script>
@endsection
