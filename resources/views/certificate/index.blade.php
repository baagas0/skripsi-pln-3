@php
$moduleName = 'Sertifikat';
$moduleRoute = 'certificate';
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
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="black" />
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
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <a href="javascript:;" class="btn btn-danger me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_import">
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
                            Import
                        </a>

                        <!--begin::Add data-->
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
                                <th>Nama Pegawai</th>
                                <th>Diklat</th>
                                <th>Vendor</th>
                                <th>Fungsi</th>
                                <th>No. Sertifikat</th>
                                <th>Jenis Sertifikat</th>
                                <th>Tanggal Sertifikat</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>File</th>
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
                    <div class="card-body p-9">
                        {{-- <div class="row mb-6">
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
                        </div> --}}
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Diklat</label>
                            <div class="col-lg-8 fv-row">
                                <select name="diklat_id" id="diklat_id" class="form-select" data-control="select2"
                                    data-placeholder="Pilih Diklat">
                                    <option></option>
                                    @foreach ($diklats as $diklat)
                                    <option value="{{ $diklat->id }}" data-type="{{ $diklat->diklat_type }}">{{
                                        $diklat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Pegawai</label>
                            <div class="col-lg-8 fv-row">
                                <select name="employee_id" class="form-select" data-control="select2"
                                    data-placeholder="Pilih Pegawai">
                                    <option></option>
                                    @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Vendor</label>
                            <div class="col-lg-8 fv-row">
                                <select name="vendor_id" class="form-select" data-control="select2"
                                    data-placeholder="Pilih Vendor">
                                    <option></option>
                                    @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Fungsi</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="fungsi" class="form-control" placeholder="Fungsi" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Nomor Sertifikat</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="certificate_number" class="form-control"
                                    placeholder="Nomor Sertifikat" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Jenis Sertifikat</label>
                            <div class="col-lg-8 fv-row">
                                <select name="certificate_type" class="form-select" data-control="select2"
                                    data-placeholder="Pilih Jenis Sertifikat">
                                    <option></option>
                                    <option value="Pelatihan">Pelatihan</option>
                                    <option value="Pelatihan & Sertifikasi">Pelatihan & Sertifikasi</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Tanggal Sertifikat</label>
                            <div class="col-lg-8 fv-row">
                                <input type="date" name="certificate_date" class="form-control" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Tanggal Kadaluarsa</label>
                            <div class="col-lg-8 fv-row">
                                <input type="date" name="certificate_expire" class="form-control" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">File Sertifikat</label>
                            <div class="col-lg-8 fv-row">
                                <input type="file" name="certificate_path" class="form-control" accept=".pdf" />
                                <div class="form-text">Format: PDF, Max: 5MB</div>
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

<div class="modal fade" tabindex="-1" id="kt_modal_import">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="kt_export_form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Import Certificates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Diklat for Template</label>
                        <select id="template_diklat_id" class="form-select" data-control="select2"
                            data-placeholder="Select Diklat">
                            <option></option>
                            @foreach($diklats as $diklat)
                            <option value="{{ $diklat->id }}">{{ $diklat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <button type="button" id="downloadTemplate" class="btn btn-light-primary">Download
                            Template</button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    "use strict";
    $('#diklat_id').on('change', function() {
    // Get selected option
    const selectedOption = $(this).find('option:selected');
    
    // Get diklat type from data attribute
    // const diklatType = selectedOption.data('type');
    
    // // Update the diklat_type field
    // if (diklatType) {
    //     $('#diklat_type').val(diklatType);
        
    //     // Check if diklatType is Lower or Pelatihan (case insensitive)
    //     const lowerType = diklatType.toLowerCase();
    //     const isLowerOrPelatihan = lowerType === 'lower' || lowerType === 'pelatihan';
        
    //     // Disable or enable expire date field based on certificate type
    //     if (isLowerOrPelatihan) {
    //         // Disable expire date field and clear its value
    //         $('input[name="certificate_expire"]').prop('disabled', true);
    //         $('input[name="certificate_expire"]').val('');
    //         // Add visual indication that field is disabled
    //         $('input[name="certificate_expire"]').addClass('bg-light');
    //     } else {
    //         // Enable expire date field
    //         $('input[name="certificate_expire"]').prop('disabled', false);
    //         $('input[name="certificate_expire"]').removeClass('bg-light');
    //     }
    // } else {
    //     $('#diklat_type').val('');
    //     // Default: enable expire date field
    //     $('input[name="certificate_expire"]').prop('disabled', false);
    //     $('input[name="certificate_expire"]').removeClass('bg-light');
    // }
});

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
                            data: 'employee.name'
                        },
                        {
                            data: 'diklat.name'
                        },
                        {
                            data: 'vendor.name'
                        },
                        {
                            data: 'fungsi'
                        },
                        {
                            data: 'certificate_number'
                        },
{data: 'certificate_type'},
                        {
                            data: 'certificate_date',
                            render: function(data) {
                                return data ? moment(data).format('DD MMMM YYYY') : '-';
                            }
                        },
                        {
                            data: 'certificate_expire',
                            render: function(data) {
                                if (!data) return '-';
                                
                                const expireDate = moment(data);
                                const now = moment();
                                const daysUntilExpire = expireDate.diff(now, 'days');
                                
                                let badgeClass = 'badge-success';
                                if (daysUntilExpire < 0) {
                                    badgeClass = 'badge-danger';
                                } else if (daysUntilExpire <= 30) {
                                    badgeClass = 'badge-warning';
                                }

                                return `<span class="badge ${badgeClass}">
                                    ${expireDate.format('DD MMMM YYYY')}
                                    ${daysUntilExpire < 0 ? '(Expired)' : 
                                    daysUntilExpire <= 30 ? `(${daysUntilExpire} days left)` : ''}
                                </span>`;
                            }
                        },
                        // {
                        //     data: 'certificate_path',
                        //     render: function(data) {
                        //         return data ? `<a href="${base_url}/${data}" target="_blank" class="btn btn-sm btn-light-primary">View</a>` : '-';
                        //     }
                        // },
                        // {
                        //     data: 'certificate_path',
                        //     render: function(data, type, row) {
                        //         if (data) {
                        //             return `<a href="${base_url}${data}" target="_blank" class="btn btn-sm btn-light-primary">View</a>`;
                        //         } else {
                        //             return `
                        //                 <div class="d-flex align-items-center">
                        //                     <input type="file" class="form-control form-control-sm me-2 certificate-file" 
                        //                         data-id="${row.id}" accept=".pdf" style="width: 200px">
                        //                     <button class="btn btn-sm btn-light-primary upload-certificate" 
                        //                             data-id="${row.id}">Upload</button>
                        //                 </div>
                        //             `;
                        //         }
                        //     }
                        // },
                        {
    data: 'certificate_path',
    render: function(data, type, row) {
        if (data) {
            // Encode URL untuk menangani karakter khusus dalam nama file
            const encodedPath = data.split('/').map(part => encodeURIComponent(part)).join('/');
            return `<a href="${base_url}${encodedPath}" target="_blank" class="btn btn-sm btn-light-primary">View</a>`;
        } else {
            return `
                <div class="d-flex align-items-center">
                    <input type="file" class="form-control form-control-sm me-2 certificate-file" 
                        data-id="${row.id}" accept=".pdf" style="width: 200px">
                    <button class="btn btn-sm btn-light-primary upload-certificate" 
                            data-id="${row.id}">Upload</button>
                </div>
            `;
        }
    }
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
                                            <a href="${base_url}/{{ $moduleRoute }}/download/${row.id}" class="menu-link px-3">
                                                Download
                                            </a>
                                        </div>
                                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
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
                                        @endif
                                    </div>`;
                            }
                        },    {
        targets: 5, // Sesuaikan index untuk kolom certificate_type
        render: function(data) {
            if (!data) return '-';
            
            const status = {
                'Sertifikasi': {
                    'title': 'Sertifikasi',
                    'class': 'badge-light-primary'
                },
                'Pelatihan': {
                    'title': 'Pelatihan',
                    'class': 'badge-light-info'
                },
                'Pelatihan & Sertifikasi': {
                    'title': 'Pelatihan & Sertifikasi',
                    'class': 'badge-light-success'
                }
            };

            return `<span class="badge ${status[data]?.class || 'badge-light'}">${status[data]?.title || data}</span>`;
        }
    },
                    
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

            var handleUpdateFileCertificate = () => {
                $('#kt_datatable').on('click', '.upload-certificate', function(e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    const fileInput = $(this).closest('div').find('.certificate-file')[0];
                    
                    if (!fileInput.files.length) {
                        toastr.error('Please select a file first');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('certificate_path', fileInput.files[0]);
                    formData.append('_token', csrf_token);
                    
                    $.ajax({
                        url: `${base_url}/certificate/upload-file/${id}`,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status === 200) {
                                toastr.success('File uploaded successfully');
                                KTDatatablesServerSide.refresh();
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON?.errors) {
                                xhr.responseJSON.errors.forEach(error => {
                                    toastr.error(error);
                                });
                            }
                        }
                    });
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
                    handleUpdateFileCertificate();
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
                        diklat_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Diklat is required'
                                }
                            }
                        },
                        employee_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Employee is required'
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
                        fungsi: {
                            validators: {
                                notEmpty: {
                                    message: 'Function is required'
                                }
                            }
                        },
                        certificate_number: {
                            validators: {
                                notEmpty: {
                                    message: 'Certificate number is required'
                                }
                            }
                        },
                        certificate_date: {
                            validators: {
                                notEmpty: {
                                    message: 'Certificate date is required'
                                }
                            }
                        },
                        certificate_expire: {
                            validators: {
                                // notEmpty: {
                                //     message: 'Expiry date is required',
                                //     // Tambahkan callback untuk memeriksa kondisi
                                //     callback: function() {
                                //         // Jika tipe Lower atau Pelatihan, validasi ini tidak diperlukan
                                //         const diklatType = $('#diklat_type').val().toLowerCase();
                                //         alert(diklatType);
                                //         return !(diklatType === 'pelatihan');
                                //     }
                                // }
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
            $('#diklat_id').on('change', function() {
    // Get selected option
    const selectedOption = $(this).find('option:selected');
    
    
    // Update the diklat_type field
    // if (diklatType) {
    //     $('#diklat_type').val(diklatType);
    // } else {
    //     $('#diklat_type').val('');
    // }
});

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
                $('select[name="diklat_id"]').val(data.diklat_id).trigger('change');
                $('select[name="employee_id"]').val(data.employee_id).trigger('change');
                $('select[name="vendor_id"]').val(data.vendor_id).trigger('change');
                $('input[name="fungsi"]').val(data.fungsi);
                $('input[name="certificate_number"]').val(data.certificate_number);
                $('input[name="certificate_date"]').val(data.certificate_date);
                $('select[name="certificate_type"]').val(data.certificate_type).trigger('change');
                
                // Handle expire date based on certificate_type
                if (data.certificate_type === 'Pelatihan') {
                    $('input[name="certificate_expire"]').prop('disabled', true);
                    $('input[name="certificate_expire"]').val('');
                    $('input[name="certificate_expire"]').addClass('bg-light');
                } else {
                    $('input[name="certificate_expire"]').prop('disabled', false);
                    $('input[name="certificate_expire"]').val(data.certificate_expire);
                    $('input[name="certificate_expire"]').removeClass('bg-light');
                }

                // Handle file display
                if (data.certificate_path) {
                    const encodedPath = data.certificate_path.split('/').map(part => encodeURIComponent(part)).join('/');
                    const fileInfo = `<div class="mt-2" id="file-info">
                        <a href="${base_url}${encodedPath}" target="_blank" class="btn btn-sm btn-light-primary">
                            View Current Certificate
                        </a>
                    </div>`;
                    $('input[name="certificate_path"]').after(fileInfo);
                }

                $('#kt_modal').modal('show');
            }
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
                            
                            const expireDate = $('input[name="certificate_expire"]').val();

                            var id = $('input[name="id"]').val();
                            var method = id ? 'POST' : 'POST';
                            var url = id ? `${base_url}/{{ $moduleRoute }}/update/${id}` :
                                `${base_url}/{{ $moduleRoute }}/store`;

                            var formData = new FormData(form);
                            if (id) {
                                formData.append('_method', method);
                            }
                            // if (!isNeedExpireDate) {
                            //     formData.delete('certificate_expire');
                            //     // Kirim nilai null untuk certificate_expire
                            //     formData.append('certificate_expire', '');
                            // }

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
                // Reset the form
                form.reset();
                
                // Reset modal title
                $('.modal-title').html("Tambah Sertifikat");
                
                // Reset hidden ID field
                $('input[name="id"]').val("");
                
                // Remove existing file preview
                $('#file-info').remove();
                
                // Reset all select2 dropdowns
                $('select[name="diklat_id"]').val(null).trigger('change');
                $('select[name="employee_id"]').val(null).trigger('change');
                $('select[name="vendor_id"]').val(null).trigger('change');
                
                // Reset date inputs
                $('input[name="certificate_date"]').val("");
                $('input[name="certificate_expire"]').val("");
                
                // Reset text inputs
                $('input[name="fungsi"]').val("");
                $('input[name="certificate_number"]').val("");
                
                // Reset file input
                $('input[name="certificate_path"]').val("");
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
            
                            // Set form values
                            $('input[name="id"]').val(data.id);
                            $('select[name="diklat_id"]').val(data.diklat_id).trigger('change');
                            $('select[name="employee_id"]').val(data.employee_id).trigger('change');
                            $('select[name="vendor_id"]').val(data.vendor_id).trigger('change');
                            $('input[name="fungsi"]').val(data.fungsi);
                            $('input[name="certificate_number"]').val(data.certificate_number);
                            $('input[name="certificate_date"]').val(data.certificate_date);
                            $('input[name="certificate_expire"]').val(data.certificate_expire);
                            $('select[name="certificate_type"]').val(data.certificate_type).trigger('change');
            
                            // If there's an existing certificate file, show it
                            // if (data.certificate_path) {
                            //     const fileInfo = `<div class="mt-2" id="file-info">
                            //         <a href="${base_url}${data.certificate_path}" target="_blank" class="btn btn-sm btn-light-primary">
                            //             View Current Certificate
                            //         </a>
                            //     </div>`;
                            //     $('input[name="certificate_path"]').after(fileInfo);
                            // }
                            if (data.certificate_path) {
    const encodedPath = data.certificate_path.split('/').map(part => encodeURIComponent(part)).join('/');
    const fileInfo = `<div class="mt-2" id="file-info">
        <a href="${base_url}${encodedPath}" target="_blank" class="btn btn-sm btn-light-primary">
            View Current Certificate
        </a>
    </div>`;
    $('input[name="certificate_path"]').after(fileInfo);
}
            
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
            
            $('#downloadTemplate').on('click', function() {
                const diklatId = $('#template_diklat_id').val();
                if (!diklatId) {
                    toastr.error('Please select a diklat first');
                    return;
                }
                window.location.href = `${base_url}/certificate/template/${diklatId}`;
            });
            
            $('#kt_export_form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                
                $.ajax({
                    url: "{{ route($moduleRoute.'.import') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            toastr.success(response.message);
                            $('#kt_modal_import').modal('hide');
                            $('#kt_export_form')[0].reset();
                            KTDatatablesServerSide.refresh();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON?.errors) {
                            xhr.responseJSON.errors.forEach(error => {
                                toastr.error(error);
                            });
                        }
                    }
                });
            });
        });
        // Add event handler for certificate_type change
$('select[name="certificate_type"]').on('change', function() {
    const certificateType = $(this).val();
    const isTrainingOnly = certificateType === 'Pelatihan';
    
    if (isTrainingOnly) {
        $('input[name="certificate_expire"]').prop('disabled', true);
        $('input[name="certificate_expire"]').val('');
        $('input[name="certificate_expire"]').addClass('bg-light');
    } else {
        $('input[name="certificate_expire"]').prop('disabled', false);
        $('input[name="certificate_expire"]').removeClass('bg-light');
    }
});
</script>
@endsection