@php
    $moduleName = 'Bidang';
    $moduleRoute = 'area';
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
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack mb-5">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                                </svg>
                            </span>
                            <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Cari Area atau Unit" />
                        </div>
                        <!--end::Search-->

                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                            <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal" onclick="KTForm.resetForm();">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                    </svg>
                                </span>
                                Tambah {{ $moduleName }}
                            </a>
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Datatable-->
                    <div class="table-responsive">
                        <table id="kt_datatable" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>Nama {{ $moduleName }}</th>
                                    <th>Unit</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            </tbody>
                        </table>
                    </div>
                    <!--end::Datatable-->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" tabindex="-1" id="kt_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah {{ $moduleName }}</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                </div>
                <!--begin::Form-->
                <form id="kt_data_form" class="form" action="#" method="post">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="card-body p-9">
                            <!-- Name -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama {{ $moduleName }}</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" class="form-control" placeholder="Nama {{ $moduleName }}" />
                                </div>
                            </div>

                            <!-- Unit -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Unit</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="unit_id" class="form-select" data-control="select2" data-placeholder="Pilih Unit">
                                        <option></option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="kt_data_submit">Simpan</button>
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
    
    var baseUrl = "{{ url('/') }}";
    var csrf_token = "{{ csrf_token() }}";

    // DataTable handling
    var KTDatatablesServerSide = function() {
        var table;
        var dt;

        var initDatatable = function() {
            dt = $("#kt_datatable").DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                pageLength: 10,
                order: [[0, 'asc']],
                stateSave: true,
                ajax: {
                    url: "{{ route($moduleRoute . '.data') }}",
                },
                columns: [
                    { data: 'name' },
                    { data: 'unit_name' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            table = dt.$;

            dt.on('draw', function() {
                handleEditRows();
                handleDeleteRows();
                KTMenu.createInstances();
            });
        }

        var handleSearchDatatable = function() {
            const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
            filterSearch?.addEventListener('keyup', function(e) {
                dt.search(e.target.value).draw();
            });
        }

        var handleEditRows = () => {
            $(document).on('click', '[data-kt-docs-table-filter="edit_row"]', function(e) {
                e.preventDefault();
                const id = $(this).data("id");
                KTForm.detail(id);
            });
        }

        var handleDeleteRows = () => {
            $(document).on('click', '[data-kt-docs-table-filter="delete_row"]', function(e) {
                e.preventDefault();
                const id = $(this).data("id");

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus data ini?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Tidak, batal",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: `${baseUrl}/{{ $moduleRoute }}/destroy/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: csrf_token
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    }).then(function() {
                                        dt.draw();
                                    });
                                }
                            },
                            error: function(xhr) {
                                if (xhr.responseJSON?.message) {
                                    Swal.fire({
                                        text: xhr.responseJSON.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });
        }

        return {
            init: function() {
                initDatatable();
                handleSearchDatatable();
                handleEditRows();
                handleDeleteRows();
            },
            refresh: function() {
                dt.draw();
            }
        }
    }();

    // Form handling
    var KTForm = function() {
        var form;
        var submitButton;
        var validation;

        var initValidation = function() {
            validation = FormValidation.formValidation(form, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Nama area wajib diisi'
                            }
                        }
                    },
                    unit_id: {
                        validators: {
                            notEmpty: {
                                message: 'Unit wajib dipilih'
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

        var handleForm = function() {
            submitButton.addEventListener('click', function(e) {
                e.preventDefault();

                validation.validate().then(function(status) {
                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');
                        submitButton.disabled = true;

                        var id = $('input[name="id"]').val();
                        var url = id ? `${baseUrl}/{{ $moduleRoute }}/update/${id}` : `${baseUrl}/{{ $moduleRoute }}/store`;

                        var formData = new FormData(form);

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
                                            KTDatatablesServerSide.refresh();
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;

                                if (xhr.responseJSON?.errors) {
                                    Object.keys(xhr.responseJSON.errors).forEach(key => {
                                        toastr.error(xhr.responseJSON.errors[key][0]);
                                    });
                                }
                            }
                        });
                    }
                });
            });
        }

        var resetForm = function() {
            form.reset();
            $('.modal-title').html("Tambah {{ $moduleName }}");
            $('input[name="id"]').val("");
            $('select').val(null).trigger('change');
        }

        var detailForm = function(id) {
            $.ajax({
                url: `${baseUrl}/{{ $moduleRoute }}/show/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.status === 200) {
                        const data = response.data;

                        resetForm();
                        $('.modal-title').html("Edit {{ $moduleName }}");

                        $('input[name="id"]').val(data.id);
                        $('input[name="name"]').val(data.name);
                        $('select[name="unit_id"]').val(data.unit_id).trigger('change');

                        $('#kt_modal').modal('show');
                    }
                }
            });
        }

        return {
            init: function() {
                form = document.querySelector('#kt_data_form');
                submitButton = form.querySelector('#kt_data_submit');

                initValidation();
                handleForm();
            },
            detail: detailForm,
            resetForm: resetForm
        }
    }();

    // Initialize on document ready
    KTUtil.onDOMContentLoaded(function() {
        KTDatatablesServerSide.init();
        KTForm.init();
    });
</script>
@endsection