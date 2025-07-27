@php
$moduleName = 'Tagihan';
$moduleRoute = 'proccess-vip';
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
                <!--begin::Stepper-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-5">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">

                        <!--begin::Add data-->
                        @if(auth()->user()->role_id == 2)
                        <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal"
                            onclick="KTForm.resetForm();">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                        transform="rotate(-90 11.364 20.364)" fill="black" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            Tambah {{ $moduleName }}
                        </a>
                        @endif
                        <!--end::Add data-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Wrapper-->

                <div class="row g-5 g-xl-8">
                    @forelse ($data as $item)
                    <div class="col-xl-12">
                        <div
                            class="card bgi-no-repeat bgi-position-y-top bgi-position-x-end statistics-widget-1 card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="#"
                                            class="card-title fw-bold text-muted text-hover-primary fs-4">Submission ID:
                                            {{ $item->submission_id }}</a>
                                        <div class="fw-bold text-primary mt-6  fs-5">{{ $item->diklat->name }}</div>
                                        <p class="text-gray-900-75 fw-semibold m-0">Nomor Surat/Penugasan: {{
                                            $item->diklat->letter_number }}</p>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        @if(auth()->user()->role_id == 2)
                                            <button class="btn btn-sm btn-light-primary me-2"
                                                onclick="KTForm.detail({{ $item->id }})">
                                                <i class="ki-outline ki-pencil fs-5"></i> Edit
                                            </button>

                                            <button class="btn btn-sm btn-light-danger me-2"
                                                onclick="deleteData({{ $item->id }})">
                                                <i class="ki-outline ki-trash fs-5"></i> Delete
                                            </button>

                                            @if($item->status == 'belum tertagih')
                                            <button class="btn btn-sm btn-light-info"
                                                onclick="updateStatus({{ $item->id }}, 'sudah tertagih')">Konfirmasi
                                                Penagihan</button>
                                            @elseif($item->status == 'sudah tertagih')
                                            <button class="btn btn-sm btn-light-warning"
                                                onclick="updateStatus({{ $item->id }}, 'sudah dibayar')">Konfirmasi
                                                Pembayaran</button>
                                            @elseif($item->status == 'sudah dibayar')
                                            <button class="btn btn-sm btn-light-success">Sudah Dibayar</button>
                                            @endif
                                        @endif
                                        @if(auth()->user()->role_id == 1)
                                            <div class="d-flex align-items-center">
                                                @if($item->status == 'belum tertagih')
                                                    <span class="badge badge-light-warning fs-7 fw-bold">
                                                        <i class="ki-outline ki-timer fs-7 me-1"></i>Belum Tertagih
                                                    </span>
                                                @elseif($item->status == 'sudah tertagih')
                                                    <span class="badge badge-light-info fs-7 fw-bold">
                                                        <i class="ki-outline ki-check-square fs-7 me-1"></i>Sudah Tertagih
                                                    </span>
                                                @elseif($item->status == 'sudah dibayar')
                                                    <span class="badge badge-light-success fs-7 fw-bold">
                                                        <i class="ki-outline ki-check-circle fs-7 me-1"></i>Sudah Dibayar
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card card-dashed flex-center min-h-200px p-6">
                            <div class="card-body d-flex flex-column justify-content-center text-center">
                                <!-- Empty state illustration -->
                                <div class="mb-10">
                                    <img src="{{ asset('assets/media/illustrations/sigma-1/4.png') }}" class="mw-200px" alt="Empty data">
                                </div>
                                
                                <!-- Empty state message -->
                                <div class="fw-semibold fs-3 text-gray-600 mb-5">Belum ada data tagihan</div>
                                <div class="text-gray-500 mb-6">Belum ada tagihan yang tersedia saat ini</div>
                                
                                <!-- Add button if the user has permission -->
                                @if(auth()->user()->role_id == 2)
                                <a href="javascript:;" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal"
                                    onclick="KTForm.resetForm();">
                                    <i class="ki-outline ki-plus-square fs-4"></i>
                                    Tambah {{ $moduleName }} Baru
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="kt_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah {{ $moduleName }}</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Form-->
            <form id="kt_data_form" class="form" action="#" method="post">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <!--begin::Card body-->
                    <div class="card-body  p-9">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Submission ID</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="submission_id" class="form-control"
                                    placeholder="Submission ID" />
                            </div>
                        </div>

                        <div class="row mb-6" id="container_diklat_id">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Nomor Surat/Penugasan</label>
                            <div class="col-lg-8 fv-row">
                                <select name="diklat_id" class="form-select" data-control="select2"
                                    data-placeholder="Pilih Nomor Surat/Penugasan">
                                    <option></option>
                                    @foreach ($diklats as $diklat)
                                    <option value="{{ $diklat->id }}">{{ $diklat->letter_number }}</option>
                                    @endforeach
                                </select>
                                <p id="diklat_name" class="fw-2"></p>
                            </div>
                        </div>

                    </div>
                    <!--end::Card body-->
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kt_data_submit">Simpan</button>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="kt_modal_export">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="">Export {{ $moduleName }}</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Form-->
            <form id="kt_export_form" class="form" action="" method="post">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <!--begin::Card body-->
                    <div class="card-body  p-9">
                        <!--end::Input group-->


                        <div class="modal-footer">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="">Submit</button>
                        </div>
                    </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    "use strict";

        // Form handling class
        var KTForm = function() {
            var form;
            var submitButton;
            var validation;

            // Form validation rules
            var initValidation = function() {
                validation = FormValidation.formValidation(form, {
                    fields: {
                        submission_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Submission ID is required'
                                }
                            }
                        },
                        diklat_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Diklat is required'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                });
            }

            // Handle form submission
            var handleForm = function() {
                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    validation.validate().then(function(status, tes) {
                        var id = $('input[name="id"]').val();
                        var method = id ? 'POST' : 'POST';
                        if (status === 'Valid') {
                            submitButton.setAttribute('data-kt-indicator', 'on');
                            submitButton.disabled = true;

                            var url = id ? `${base_url}/{{ $moduleRoute }}/update/${id}` :
                                `${base_url}/{{ $moduleRoute }}/store`;

                            var formData = new FormData(form);
                            if (id) {
                                formData.append('_method', method);
                            }

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;

                                    if (response.status === 200) {
                                        Swal.fire({
                                            text: response.message,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function(result) {
                                            if (result.isConfirmed) {
                                                $('#kt_modal').modal('hide');
                                                window.location.reload();
                                            }
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;

                                    if (xhr.responseJSON?.errors) {
                                        Object.keys(xhr.responseJSON.errors).forEach(
                                            key => {
                                                toastr.error(xhr.responseJSON
                                                    .errors[key][0]);
                                            });
                                    }
                                }
                            });
                        }
                    });
                });
            }

            // Reset form
            var resetForm = function() {
                const diklat = @json($diklats);
                form.reset();
                $('.modal-title').html("Tambah {{ $moduleName }}");
                // $('#container_diklat_id').show();
                $('input[name="id"]').val("");

                $('select[name="diklat_id"]').empty();
                diklat.forEach(function(item) {
                    $('select[name="diklat_id"]').append(
                        `<option value="${item.id}">${item.letter_number}</option>`
                    );
                });
                $('select[name="diklat_id"]').val(null).trigger('change');
                $('#diklat_name').text('');
            }

            // Load data for editing
            var detailForm = function(id) {
                $.ajax({
                    url: `${base_url}/{{ $moduleRoute }}/show/${id}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 200) {
                            const data = response.data;

                            resetForm();
                            $('.modal-title').html("Edit {{ $moduleName }}");

                            $('input[name="id"]').val(data.id);
                            $('input[name="submission_id"]').val(data.submission_id);

                            // Add option to diklat_id
                            // $('#container_diklat_id').hide();
                            // $('select[name="diklat_id"]').append(
                            //     `<option value="${data.diklat_id}" selected>${data.diklat.letter_number}</option>`
                            // );
                            $('select[name="diklat_id"]').val(data.diklat_id).trigger('change');

                            $('#kt_modal').modal('show');
                        }
                    }
                });
            }

            // Public methods
            return {
                init: function() {
                    form = document.querySelector('#kt_data_form');
                    submitButton = form.querySelector('#kt_data_submit');

                    initValidation();
                    handleForm();

                    $('select[name="diklat_id"]').on('change', function() {
                        const diklatId = $(this).val();
                        const diklats = @json($diklats);

                        const diklat = diklats.find(diklat => diklat.id == diklatId);
                        $('#diklat_name').text(diklat ? diklat.name : '');
                        console.log(diklatId, diklats);
                    });
                },
                detail: detailForm,
                resetForm: resetForm
            }
        }();

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            KTForm.init();
        });

        function deleteData(id) {
    Swal.fire({
        text: "Are you sure you want to delete this data?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${base_url}/proccess-vip/destroy/${id}`,
                type: 'DELETE',
                data: {
                    _token: csrf_token
                },
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire({
                            text: "Data deleted successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON?.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(key => {
                            toastr.error(xhr.responseJSON.errors[key][0]);
                        });
                    }
                }
            });
        }
    });
}

        function updateStatus(id, status) {
            Swal.fire({
                text: "Are you sure you want to update this status?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, update!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${base_url}/{{ $moduleRoute }}/update-status/${id}`,
                        type: 'POST',
                        data: {
                            _token: csrf_token,
                            status: status
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                Swal.fire({
                                    text: "Status updated successfully!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON?.errors) {
                                Object.keys(xhr.responseJSON.errors).forEach(key => {
                                    toastr.error(xhr.responseJSON.errors[key][0]);
                                });
                            }
                        }
                    });
                }
            });
        }
</script>
@endsection