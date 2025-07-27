@php
    $moduleName = 'Proccess VIP';
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
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-docs-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Cari" />
                        </div>
                        <!--end::Search-->

                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                            <!--begin::Add data-->
                            <a href="javascript:;" class="d-none btn btn-danger me-3" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export">
                                <!--begin::Svg Icon | path: assets/media/icons/duotune/arrows/arr082.svg-->
                                <span class="svg-icon svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.5"
                                            d="M12.5657 9.63427L16.75 5.44995C17.1642 5.03574 17.8358 5.03574 18.25 5.44995C18.6642 5.86416 18.6642 6.53574 18.25 6.94995L12.7071 12.4928C12.3166 12.8834 11.6834 12.8834 11.2929 12.4928L5.75 6.94995C5.33579 6.53574 5.33579 5.86416 5.75 5.44995C6.16421 5.03574 6.83579 5.03574 7.25 5.44995L11.4343 9.63427C11.7467 9.94669 12.2533 9.94668 12.5657 9.63427Z"
                                            fill="black" />
                                        <path
                                            d="M12.5657 15.6343L16.75 11.45C17.1642 11.0357 17.8358 11.0357 18.25 11.45C18.6642 11.8642 18.6642 12.5357 18.25 12.95L12.7071 18.4928C12.3166 18.8834 11.6834 18.8834 11.2929 18.4928L5.75 12.95C5.33579 12.5357 5.33579 11.8642 5.75 11.45C6.16421 11.0357 6.83579 11.0357 7.25 11.45L11.4343 15.6343C11.7467 15.9467 12.2533 15.9467 12.5657 15.6343Z"
                                            fill="black" />
                                    </svg></span>
                                <!--end::Svg Icon-->
                                Export
                            </a>
                            <!--end::Add data-->

                            <!--begin::Add data-->
                            <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal"
                                onclick="KTForm.resetForm();">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                            rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                Tambah APD
                            </a>
                            <!--end::Add data-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-docs-table-toolbar="selected">
                            <div class="fw-bolder me-5">
                                <span class="me-2" data-kt-docs-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger" data-kt-docs-table-select="delete_selected">Hapus
                                Data yang Dipilih</button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Datatable-->
                    <div class="table-responsive">

                        <table id="kt_datatable" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>Submission ID</th>
                                    <th>Diklat</th>
                                    <th>Status</th>
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
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Submission ID" />
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Diklat</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="diklat_id" class="form-select" data-control="select2"
                                        data-placeholder="Pilih Diklat">
                                        <option></option>
                                        @foreach ($diklats as $diklat)
                                            <option value="{{ $diklat->id }}">{{ $diklat->name }}</option>
                                        @endforeach
                                    </select>
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

        // Class definition for DataTable handling
        var KTDatatablesServerSide = function() {
            var table;
            var dt;

            // Initialize DataTable
            var initDatatable = function() {
                dt = $("#kt_datatable").DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ],
                    stateSave: true,
                    select: {
                        style: 'os',
                        selector: 'td:first-child',
                        className: 'row-selected'
                    },
                    ajax: {
                        url: "{{ route($moduleRoute . '.data') }}",
                    },
                    columns: [
                        {
                            data: 'submission_id'
                        },
                        {
                            data: 'diklat.name'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: null
                        }
                    ],
                    columnDefs: [
                        {
                            targets: -1,
                            data: null,
                            orderable: false,
                            className: 'text-end',
                            render: function(data, type, row) {
                                return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                    </svg>
                                </span>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-docs-table-filter="edit_row" data-id="${row.id}">
                                        Edit
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-docs-table-filter="delete_row" data-id="${row.id}">
                                        Delete
                                    </a>
                                </div>
                            </div>`;
                            }
                        }
                    ]
                });

                table = dt.$;

                // Re-init functions on every table re-draw
                dt.on('draw', function() {
                    // initToggleToolbar();
                    // toggleToolbars();
                    handleEditRows();
                    handleDeleteRows();
                    KTMenu.createInstances();
                });
            }

            // Search functionality
            var handleSearchDatatable = function() {
                const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
                filterSearch?.addEventListener('keyup', function(e) {
                    dt.search(e.target.value).draw();
                });
            }

            // Handle edit button click
            var handleEditRows = () => {
                const editButtons = document.querySelectorAll('[data-kt-docs-table-filter="edit_row"]');

                editButtons.forEach(d => {
                    d.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = $(this).data("id");
                        KTForm.detail(id);
                    });
                });
            }

            // Handle delete button click 
            var handleDeleteRows = () => {
                const deleteButtons = document.querySelectorAll('[data-kt-docs-table-filter="delete_row"]');

                deleteButtons.forEach(d => {
                    d.addEventListener('click', function(e) {
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
                                    url: `${base_url}/{{ $moduleRoute }}/destroy/${id}`,
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
                });
            }

            // Public methods
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

        // Form handling class
        var KTForm = function() {
            var form;
            var submitButton;
            var validation;

            // Form validation rules
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
                        year: {
                            validators: {
                                notEmpty: {
                                    message: 'Year is required'
                                },
                                numeric: {
                                    message: 'Year must be numeric'
                                }
                            }
                        },
                        estimate_start_date: {
                            validators: {
                                notEmpty: {
                                    message: 'Start date is required'
                                }
                            }
                        },
                        estimate_end_date: {
                            validators: {
                                notEmpty: {
                                    message: 'End date is required'
                                }
                            }
                        },
                        vendor_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Vendor is required'
                                }
                            }
                        },
                        unit_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Unit is required'
                                }
                            }
                        },
                        count_of_participant: {
                            validators: {
                                notEmpty: {
                                    message: 'Participant count is required'
                                },
                                numeric: {
                                    message: 'Must be numeric'
                                }
                            }
                        },
                        total_cost: {
                            validators: {
                                notEmpty: {
                                    message: 'Total cost is required'
                                },
                                numeric: {
                                    message: 'Must be numeric'
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

                    validation.validate().then(function(status) {
                        if (status === 'Valid') {
                            submitButton.setAttribute('data-kt-indicator', 'on');
                            submitButton.disabled = true;

                            var id = $('input[name="id"]').val();
                            var method = id ? 'POST' : 'POST';
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
                                                KTDatatablesServerSide
                                            .refresh();
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
                form.reset();
                $('.modal-title').html("Add New Planning");
                $('input[name="id"]').val("");
                $('select').val(null).trigger('change');
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
                            $('input[name="name"]').val(data.name);
                            $('input[name="year"]').val(data.year);
                            $('input[name="estimate_start_date"]').val(data.estimate_start_date);
                            $('input[name="estimate_end_date"]').val(data.estimate_end_date);
                            $('select[name="vendor_id"]').val(data.vendor_id).trigger('change');
                            $('select[name="unit_id"]').val(data.unit_id).trigger('change');
                            $('input[name="count_of_participant"]').val(data.count_of_participant);
                            $('input[name="total_cost"]').val(data.total_cost);

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
