@php
    $moduleName = 'User';
    $moduleRoute = 'user';
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
                            <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Cari" />
                        </div>
                        <!--end::Search-->

                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                            @if (auth()->user()->role_id !== 7)
                            <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal" onclick="KTForm.resetForm();">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                    </svg>
                                </span>
                                Tambah {{ $moduleName }}
                            </a>
                            @endif
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Datatable-->
                    <div class="table-responsive">
                        <table id="kt_datatable" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Unit</th>
                                    <th>Area</th>
                                    <th>Vendor</th>
                                    <th>Manage Units</th>
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
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Pengguna" />
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="email" name="email" class="form-control" placeholder="Email" />
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Password</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="password" name="password" class="form-control" placeholder="Password" />
                                    <div class="form-text password-hint d-none">Kosongkan jika tidak ingin mengubah password</div>
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Role</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="role_id" class="form-select" data-control="select2" data-placeholder="Pilih Role">
                                        <option></option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Unit (for HTD and HoE) -->
                            <div class="row mb-6 unit-field d-none">
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

                            <!-- Vendor (for Vendor role) -->
                            <div class="row mb-6 vendor-field d-none">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Vendor</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="vendor_id" class="form-select" data-control="select2" data-placeholder="Pilih Vendor">
                                        <option></option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Area (for PIC Area and SRM) -->
                            <div class="row mb-6 area-field d-none">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Area</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="area_id" class="form-select" data-control="select2" data-placeholder="Pilih Area">
                                        <option></option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }} ({{ $area->unit->name ?? 'No Unit' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Manage Units (for Super Admin) -->
                            <div class="row mb-6 manage-units-field d-none">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Manage Units</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="manage_unit_ids[]" class="form-select" data-control="select2" data-placeholder="Pilih Unit" multiple>
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
                order: [[0, 'desc']],
                stateSave: true,
                ajax: {
                    url: "{{ route($moduleRoute . '.data') }}",
                },
                columns: [
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'role_name' },
                    { data: 'unit_name' },
                    { data: 'area_name' },
                    { data: 'vendor_name' },
                    { data: 'manage_units' },
                    { data: null }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-end',
                        render: function(data, type, row) {
                            @if (auth()->user()->role_id === 7)
                                return '';
                            @endif
                            return `
                                <div class="d-flex gap-3">
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="edit_row" data-id="${row.id}">
                                        Edit
                                    </a>
                                    <a href="#" class="btn btn-light btn-active-light-danger btn-sm btn-outline" data-kt-docs-table-filter="delete_row" data-id="${row.id}">
                                        Delete
                                    </a>
                                </div>
                            `;
                        }
                    }
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
                    text: "Are you sure you want to delete this item?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
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
                                Swal.fire({
                                    text: "Item deleted successfully!",
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
                                message: 'Name is required'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email is required'
                            },
                            emailAddress: {
                                message: 'Please enter a valid email address'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required'
                            },
                            stringLength: {
                                min: 6,
                                message: 'Password must be at least 6 characters'
                            }
                        }
                    },
                    role_id: {
                        validators: {
                            notEmpty: {
                                message: 'Role is required'
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

        var handleRoleChange = function() {
            $('select[name="role_id"]').on('change', function() {
                const roleId = $(this).val();
                
                // Hide all role-specific fields
                $('.unit-field, .vendor-field, .area-field, .manage-units-field').addClass('d-none');
                
                // Show appropriate field based on role
                switch(roleId) {
                    case '1': // HTD
                    case '6': // HoE
                        $('.unit-field').removeClass('d-none');
                        break;
                    case '2': // Vendor
                        $('.vendor-field').removeClass('d-none');
                        break;
                    case '3': // PIC  Bidang
                    case '4': // SRM (Senior Manager)
                        $('.area-field').removeClass('d-none');
                        break;
                    case '7': // Manager
                        $('.manage-units-field').removeClass('d-none');
                        break;
                    case '8': // Vice President
                        $('.manage-units-field').removeClass('d-none');
                        break;
                }
            });
        }

        var handleForm = function() {
            submitButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Dynamic validation based on edit mode
                const isEdit = !!$('input[name="id"]').val();
                if (isEdit) {
                    validation.disableValidator('password');
                } else {
                    validation.enableValidator('password');
                }

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
            $('.unit-field, .vendor-field, .area-field, .manage-units-field').addClass('d-none');
            $('.password-hint').addClass('d-none');
            validation.enableValidator('password');
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
                        $('.password-hint').removeClass('d-none');

                        $('input[name="id"]').val(data.id);
                        $('input[name="name"]').val(data.name);
                        $('input[name="email"]').val(data.email);
                        $('select[name="role_id"]').val(data.role_id).trigger('change');

                        // Set role-specific fields
                        if (data.unit_id) {
                            $('select[name="unit_id"]').val(data.unit_id).trigger('change');
                        }
                        if (data.vendor_id) {
                            $('select[name="vendor_id"]').val(data.vendor_id).trigger('change');
                        }
                        if (data.area_id) {
                            $('select[name="area_id"]').val(data.area_id).trigger('change');
                        }
                        if (data.manage_unit_ids) {
                            $('select[name="manage_unit_ids[]"]').val(data.manage_unit_ids).trigger('change');
                        }

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
                handleRoleChange();
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