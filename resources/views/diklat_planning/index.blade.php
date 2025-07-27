@php
    $moduleName = 'Perencanaan Diklat';
    $moduleRoute = 'diklat-planning';
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
                            <a href="javascript:;" class="btn btn-danger me-3" data-bs-toggle="modal"
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
                            @if (auth()->user()->role_id == 3)
                                <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal" onclick="KTForm.resetForm();">
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
                                    Tambah {{ $moduleName }}
                                </a>
                            @endif
                            <!--end::Add data-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Heading-->
                    @if (!isset($years) || !$years->count())
                        <div class="card-px text-center pt-15 pb-15">
                            <!--begin::Title-->
                            <h2 class="fs-2x fw-bold mb-0">Data {{ $moduleName }} tidak ditemukan</h2>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <p class="text-gray-500 fs-4 fw-semibold py-7">Tambahkan data realisasi diklat</p>
                            <!--end::Description-->
                            <!--begin::Action-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Illustration-->
                        <div class="text-center pb-15 px-5">
                            <img src="assets/media/illustrations/sketchy-1/2.png" alt=""
                                class="mw-100 h-200px h-sm-325px" />
                        </div>
                        <!--end::Illustration-->
                    @endif

                    <!--begin::Accordion-->
                    <div class="accordion accordion-icon-toggle" id="kt_accordion_2">
                        @foreach ($years as $year)
                            <div class="mb-5">
                                <!--begin::Header-->
                                <div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#kt_accordion_2_item_{{ $year->year }}">
                                    <span class="accordion-icon">
                                        <i class="ki-duotone ki-arrow-right fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <h3 class="fs-4 fw-semibold mb-0 ms-4">{{ $year->year }}</h3>
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div id="kt_accordion_2_item_{{ $year->year }}" class="fs-6 collapse ps-10"
                                    data-bs-parent="#kt_accordion_2">
                                    <!--begin::Datatable-->
                                    <div class="table-responsive">
                                        <table id="datatable-{{ $year->year }}"
                                            class="table align-middle table-row-dashed fs-6 gy-5 datatable-{{ $year->year }}">
                                            <thead>
                                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th>Nama</th>
                                                    <th>Tahun</th>
                                                    <th>Estimasi Tanggal Mulai</th>
                                                    <th>Estimasi Tanggal Selesai</th>
                                                    <th>Nama Vendor</th>
                                                    <th>Jumlah Peserta</th>
                                                    <th>Unit</th>
                                                    <th>Jumlah Biaya</th>
                                                    <th>Jenis Diklat</th>
                                                    <th>Disetujui SRM</th>
                                                    <th>Catatan</th>
                                                    <th class="text-end min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-600 fw-bold">
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Datatable-->
                                </div>
                                <!--end::Body-->
                            </div>
                        @endforeach
                    </div>
                    <!--end::Accordion-->



                    <!--begin::Datatable-->
                    {{-- <div class="table-responsive">

                    <table id="kt_datatable" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>Nama</th>
                                <th>Tahun</th>
                                <th>Estimasi Tanggal Mulai</th>
                                <th>Estimasi Tanggal Selesai</th>
                                <th>Nama Vendor</th>
                                <th>Jumlah Peserta</th>
                                <th>Unit</th>
                                <th>Jumlah Biaya</th>
                                <th>Disetujui HTD</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">
                        </tbody>
                    </table>
                </div> --}}
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
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Diklat</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Nama Diklat" />
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Tahun</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="number" name="year" class="form-control" placeholder="Tahun" />
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Estimasi Tanggal Mulai</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="date" name="estimate_start_date" class="form-control" />
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Estimasi Tanggal
                                    Selesai</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="date" name="estimate_end_date" class="form-control" />
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
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Jumlah Peserta</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="number" name="count_of_participant" class="form-control"
                                        placeholder="Jumlah Peserta" />
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Unit</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="unit_id" class="form-select" data-control="select2"
                                        data-placeholder="Pilih Unit">
                                        <option></option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Total Biaya</label>
                                <div class="col-lg-8 fv-row">
                                    <input type="number" name="total_cost" class="form-control"
                                        placeholder="Total Biaya" />
                                </div>
                            </div>

                            <!-- Editable fields not in planning -->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Bidang</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="area_ids[]" class="form-select" data-control="select2"
                                        data-placeholder="Pilih Bidang" multiple>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Jenis Diklat</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="diklat_type" class="form-select" data-control="select2"
                                        data-placeholder="Pilih Jenis Diklat">
                                        <option></option>
                                        <option value="Pelatihan">Pelatihan</option>
                                        <option value="Pelatihan & Sertifikasi">Pelatihan & Sertifikasi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Kategori Tangible
                                    Benefit</label>
                                <div class="col-lg-8 fv-row">
                                    <select name="tangible_benefit_categories[]" class="form-select"
                                        data-control="select2" data-placeholder="Pilih Kategori Tangible Benefit"
                                        multiple>
                                        <option value="Penghematan Biaya Bahan">Penghematan Biaya Bahan</option>
                                        <option value="Pengurangan Biaya Project">Pengurangan Biaya Project</option>
                                        <option value="Penghematan Waktu">Penghematan Waktu</option>
                                        <option value="Penurunan Biaya Pembelian">Penurunan Biaya Pembelian</option>
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
    <div class="modal fade" tabindex="-1" id="kt_modal_htd_approve">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">HTD Approval Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="kt_htd_approve_form" class="form">
                    <div class="modal-body">
                        <input type="hidden" name="planning_id" id="planning_id">
                        <div class="fv-row mb-3">
                            <label class="required fw-bold fs-6 mb-2">Notes</label>
                            <textarea name="notes" class="form-control" rows="4" placeholder="Enter approval notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="kt_htd_approve_submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
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
                <form id="kt_export_form" class="form" action="{{ route('diklat-planning.export') }}" method="get">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <!--begin::Card body-->
                        <div class="card-body  p-9">
                            <!--end::Input group-->

                            <select name="year" id="year" class="form-select" data-control="select2">
                                <option value="all">Semua Tahun</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->year }}">{{ $year->year }}</option>
                                @endforeach
                            </select>

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
            var tables = {};
            var dt = {};

            // Initialize DataTable
            var initDatatable = function() {
                @foreach ($years as $year)
                    tables['{{ $year->year }}'] = $('.datatable-{{ $year->year }}').DataTable({
                        responsive: true,
                        searchDelay: 500,
                        processing: true,
                        serverSide: true,
                        pageLength: 10,
                        order: [
                            [0, 'desc']
                        ],
                        stateSave: true,
                        ajax: {
                            url: "{{ route($moduleRoute . '.data') }}",
                            data: function(d) {
                                d.year = '{{ $year->year }}';
                            }
                        },
                        columns: [{
                                data: 'name'
                            },
                            {
                                data: 'year'
                            },
                            {
                                data: 'estimate_start_date'
                            },
                            {
                                data: 'estimate_end_date'
                            },
                            {
                                data: 'vendor.name'
                            },
                            {
                                data: 'count_of_participant'
                            },
                            {
                                data: 'unit.name'
                            },
                            {
                                data: 'total_cost'
                            },
                            {
                                data: 'diklat_type'
                            },
                            {
                                data: 'approve_by_htd'
                            },
                            {
                                data: 'notes'
                            },
                            {
                                data: null
                            }
                        ],
                        columnDefs: [{
                                targets: [2, 3],
                                render: function(data) {
                                    return moment(data).format('DD MMM YYYY');
                                }
                            },
                            {
                                targets: 7,
                                render: function(data) {
                                    return new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    }).format(data);
                                }
                            },
                            {
                                targets: 7,
                                render: function(data) {
                                    // Add null/undefined check
                                    if (!data) return '-';

                                    const status = {
                                        'Pelatihan': {
                                            'title': 'Pelatihan',
                                            'class': 'badge-light-primary'
                                        },
                                        'Pelatihan & Sertifikasi': {
                                            'title': 'Pelatihan & Sertifikasi',
                                            'class': 'badge-light-success'
                                        }
                                    };

                                    // Add fallback if status not found
                                    if (!status[data]) {
                                        return `<span class="badge badge-light">${data}</span>`;
                                    }

                                    return `<span class="badge ${status[data].class}">${status[data].title}</span>`;
                                }
                            },
                            {
                                targets: 8, // Index kolom diklat_type
                                render: function(data) {
                                    if (!data) return '-';

                                    const status = {
                                        'Pelatihan': {
                                            'title': 'Pelatihan',
                                            'class': 'badge-light-primary'
                                        },
                                        'Pelatihan & Sertifikasi': {
                                            'title': 'Pelatihan & Sertifikasi',
                                            'class': 'badge-light-success'
                                        }
                                    };

                                    return `<span class="badge ${status[data]?.class || 'badge-light'}">${status[data]?.title || data}</span>`;
                                }
                            },
                            {
                                targets: 9,
                                render: function(data) {
                                    const status = {
                                        2: {
                                            'title': 'Disetujui HTD',
                                            'class': 'badge-light-success'
                                        },
                                        1: {
                                            'title': 'Disetujui SRM',
                                            'class': 'badge-light-primary'
                                        },
                                        0: {
                                            'title': 'Menunggu',
                                            'class': 'badge-light-warning'
                                        }
                                    };
                                    return `<span class="badge ${status[data].class}">${status[data].title}</span>`;
                                }
                            },
                            {
                                targets: 5,
                                render: function(data) {
                                    return new Intl.NumberFormat('id-ID').format(data);
                                }
                            },
                            {
                                targets: -1,
                                data: null,
                                orderable: false,
                                className: 'text-end',
                                render: function(data, type, row) {
                                    let handleApprove = '';
                                    @if (auth()->user()->role_id == 4)
                                        if (row.approve_by_htd == 0) {
                                            handleApprove = `
                                        <a href="#" class="btn btn-warning btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="approve_row" data-id="${row.id}">
                                            Approve
                                        </a>
                                    `;
                                        }
                                    @endif
                                    @if (auth()->user()->role_id == 1) // HTD Admin role
                                        if (row.approve_by_htd == 1) {
                                            handleApprove = `
                                        <a href="#" class="btn btn-warning btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="approve_htd_row" data-id="${row.id}">
                                            Approve HTD
                                        </a>
                                    `;
                                        }
                                    @endif

                                    let handleButton = '';
                                    @if (auth()->user()->role_id == 1)
                                        if (!row.locked_at) {
                                            handleButton = `
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="edit_row" data-id="${row.id}">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-light btn-active-light-danger btn-sm btn-outline" data-kt-docs-table-filter="delete_row" data-id="${row.id}">
                                            Delete
                                        </a>
                                    `;
                                        }
                                    @endif

                                    @if (auth()->user()->role_id == 2)
                                        if (!row.locked_at) {
                                            handleButton = `
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="edit_row" data-id="${row.id}">
                                            Edit
                                        </a>
                                    `;
                                        }
                                    @endif

                                    @if (auth()->user()->role_id == 3)
                                        if (!row.locked_at) {
                                            handleButton = `
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="edit_row" data-id="${row.id}">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-light btn-active-light-danger btn-sm btn-outline" data-kt-docs-table-filter="delete_row" data-id="${row.id}">
                                            Delete
                                        </a>
                                    `;
                                        }
                                    @endif

                                    let handleLock = '';
                                    @if (auth()->user()->role_id == 1)
                                        handleLock = `
    <a href="#" class="btn ${row.locked_at ? 'btn-success' : 'btn-danger'} btn-active-light-${row.locked_at ? 'success' : 'danger'} btn-sm btn-outline" data-kt-docs-table-filter="lock_row" data-id="${row.id}" data-locket="${row.locked_at || ''}">
        ${row.locked_at ? '<i class="fas fa-lock-open"></i> Unlock' : '<i class="fas fa-lock"></i> Lock'}
    </a>
`;
                                    @endif
                                    return `
                                    <div class="d-flex gap-3">
                                        ${handleApprove}
                                        ${handleLock}
                                        ${handleButton}
                                    </div>
                                `;
                                }
                            }
                        ]
                    });

                    // Handle accordion show event
                    $('#kt_accordion_2_item_{{ $year->year }}').on('shown.bs.collapse', function() {
                        tables['{{ $year->year }}'].columns.adjust();
                        dt = tables['{{ $year->year }}'];
                        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
                        filterSearch.value = '';
                        dt.search('').draw();
                        console.log('show {{ $year->year }}');
                    });
                @endforeach
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
                // Remove any existing event handlers
                $(document).off('click', '[data-kt-docs-table-filter="edit_row"]');
                
                // Add new event handler
                $(document).on('click', '[data-kt-docs-table-filter="edit_row"]', function(e) {
                    e.preventDefault();
                    const id = $(this).data("id");
                    // Show the modal
                    $('#kt_modal').modal('show');
                    // Call the detail function to load the data
                    KTForm.detail(id);
                });
            }

            // Handle delete button click 
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
            }

            // Handle lock button click 
            var handleApproveRows = () => {
                $(document).on('click', '[data-kt-docs-table-filter="approve_row"]', function(e) {
                    e.preventDefault();
                    const id = $(this).data("id");
                    const locked_at = $(this)[0].dataset.locket;
                    console.log('locked_at', locked_at, $(this));

                    Swal.fire({
                        text: "Are you sure you want to approve this item?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, Approve!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: `${base_url}/{{ $moduleRoute }}/approve/${id}`,
                                type: 'post',
                                data: {
                                    _token: csrf_token
                                },
                                success: function(response) {
                                    Swal.fire({
                                        text: "Diklat planning approve successfully!",
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

            // Handle lock button click 
            var handleLockRows = () => {
                $(document).on('click', '[data-kt-docs-table-filter="lock_row"]', function(e) {
                    e.preventDefault();
                    const id = $(this).data("id");
                    const locked_at = $(this)[0].dataset.locket;
                    const isLocked = locked_at !== '';

                    Swal.fire({
                        text: `Are you sure you want to ${isLocked ? 'unlock' : 'lock'} this item?`,
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: `Yes, ${isLocked ? 'unlock' : 'lock'}!`,
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: `btn fw-bold btn-${isLocked ? 'success' : 'danger'}`,
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: `${base_url}/{{ $moduleRoute }}/${isLocked ? 'unlock' : 'lock'}/${id}`,
                                type: 'post',
                                data: {
                                    _token: csrf_token
                                },
                                success: function(response) {
                                    Swal.fire({
                                        text: `Diklat planning ${isLocked ? 'unlocked' : 'locked'} successfully!`,
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
            // var handleApproveHtdRows = () => {
            //     $(document).on('click', '[data-kt-docs-table-filter="approve_htd_row"]', function(e) {
            //         e.preventDefault();
            //         const id = $(this).data("id");

            //         Swal.fire({
            //             text: "Are you sure you want to approve this item as HTD?",
            //             icon: "warning",
            //             showCancelButton: true,
            //             buttonsStyling: false,
            //             confirmButtonText: "Yes, Approve!",
            //             cancelButtonText: "No, cancel",
            //             customClass: {
            //                 confirmButton: "btn fw-bold btn-success",
            //                 cancelButton: "btn fw-bold btn-active-light-primary"
            //             }
            //         }).then(function(result) {
            //             if (result.value) {
            //                 $.ajax({
            //                     url: `${base_url}/{{ $moduleRoute }}/approve-htd/${id}`,
            //                     type: 'post',
            //                     data: {
            //                         _token: csrf_token
            //                     },
            //                     success: function(response) {
            //                         Swal.fire({
            //                             text: "Diklat planning approved by HTD successfully!",
            //                             icon: "success",
            //                             buttonsStyling: false,
            //                             confirmButtonText: "Ok!",
            //                             customClass: {
            //                                 confirmButton: "btn fw-bold btn-primary"
            //                             }
            //                         }).then(function() {
            //                             dt.draw();
            //                         });
            //                     }
            //                 });
            //             }
            //         });
            //     });
            // }

            // Public methods
            return {
                init: function() {
                    initDatatable();
                    handleSearchDatatable();
                    handleEditRows();
                    handleDeleteRows();
                    handleApproveHtdRows();
                    handleApproveRows();
                    handleLockRows();
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
                validation = FormValidation.formValidation(
                    form, {
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
                                    },
                                    stringLength: {
                                        min: 4,
                                        max: 4,
                                        message: 'Year must be exactly 4 digits'
                                    },
                                    between: {
                                        min: (new Date().getFullYear() - 1),
                                        max: (new Date().getFullYear() + 5),
                                        message: 'Year must be between last year and 5 years from now'
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
                            count_of_participant: {
                                validators: {
                                    notEmpty: {
                                        message: 'Count of participant is required'
                                    },
                                    numeric: {
                                        message: 'Must be numeric'
                                    },
                                    greaterThan: {
                                        min: 1,
                                        message: 'Must be greater than 0'
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
                            total_cost: {
                                validators: {
                                    notEmpty: {
                                        message: 'Total cost is required'
                                    },
                                    numeric: {
                                        message: 'Must be numeric'
                                    },
                                    greaterThan: {
                                        min: 0,
                                        message: 'Must be greater than or equal to 0'
                                    }
                                }
                            },
                            diklat_type: {
                                validators: {
                                    notEmpty: {
                                        message: 'Jenis diklat is required'
                                    }
                                }
                            },
                            'area_ids[]': {
                                validators: {
                                    notEmpty: {
                                        message: 'At least one area must be selected'
                                    }
                                }
                            },
                            'tangible_benefit_categories[]': {
                                validators: {
                                    notEmpty: {
                                        message: 'At least one tangible benefit category is required'
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

                            // Handle tangible benefit categories
                            const tangibleCategories = $('select[name="tangible_benefit_categories[]"]').val();
                            formData.delete('tangible_benefit_categories[]');
                            if (tangibleCategories) {
                                tangibleCategories.forEach(category => {
                                    formData.append('tangible_benefit_categories[]', category);
                                });
                            }

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.status === 200) {
                                        Swal.fire({
                                            text: response.message,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function(result) {
                                            if (result.isConfirmed) {
                                                // Reset form
                                                resetForm();
                                                $('#kt_modal').modal('hide');
                                                dt.draw();
                                            }
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;

                                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                                        let errorMessages = [];
                                        for (let key in xhr.responseJSON.errors) {
                                            errorMessages.push(xhr.responseJSON.errors[key][0]);
                                        }
                                        Swal.fire({
                                            text: errorMessages.join('\n'),
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            text: "An error occurred. Please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                text: "Please fill in all required fields",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
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

                            // Fill in form data
                            $('input[name="id"]').val(data.id);
                            $('input[name="name"]').val(data.name);
                            $('input[name="year"]').val(data.year);
                            $('input[name="estimate_start_date"]').val(data.estimate_start_date);
                            $('input[name="estimate_end_date"]').val(data.estimate_end_date);
                            $('select[name="vendor_id"]').val(data.vendor_id).trigger('change');
                            $('input[name="count_of_participant"]').val(data.count_of_participant);
                            $('select[name="unit_id"]').val(data.unit_id).trigger('change');
                            $('input[name="total_cost"]').val(data.total_cost);

                            if (data.diklat_type) {
                                $('select[name="diklat_type"]').val(data.diklat_type).trigger('change');
                            }

                            // Handle areas
                            if (data.areas && Array.isArray(data.areas)) {
                                $('select[name="area_ids[]"]').val(data.areas.map(a => a.id)).trigger('change');
                            }

                            // Handle tangible benefit categories
                            if (data.tangible_benefit_categories) {
                                let categories = data.tangible_benefit_categories;
                                if (typeof categories === 'string') {
                                    try {
                                        categories = JSON.parse(categories);
                                    } catch (e) {
                                        console.error('Failed to parse tangible benefit categories:', e);
                                        categories = [];
                                    }
                                }
                                if (Array.isArray(categories)) {
                                    setTimeout(() => {
                                        $('select[name="tangible_benefit_categories[]"]').val(categories).trigger('change');
                                    }, 100);
                                }
                            } else {
                                $('select[name="tangible_benefit_categories[]"]').val([]).trigger('change');
                            }

                            $('#kt_modal').modal('show');
                        } else {
                            toastr.error('Failed to load data. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to load data. Please try again.');
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
        var KTHTDApproval = function() {
            var form;
            var submitButton;
            var modal;

            // Private functions
            var initForm = function() {
                form = document.querySelector('#kt_htd_approve_form');
                submitButton = document.querySelector('#kt_htd_approve_submit');
                modal = new bootstrap.Modal(document.querySelector('#kt_modal_htd_approve'));

                // Handle form submission
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;

                    // Get form data
                    const planningId = document.querySelector('#planning_id').value;
                    const notes = form.querySelector('[name="notes"]').value;

                    // Perform Ajax request
                    $.ajax({
                        url: `${base_url}/diklat-planning/approve-htd/${planningId}`,
                        type: 'POST',
                        data: {
                            _token: csrf_token,
                            notes: notes
                        },
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
                                }).then(function() {
                                    modal.hide();
                                    form.reset();
                                    KTDatatablesServerSide.refresh();
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
                });
            }

            return {
                init: function() {
                    initForm();
                },
                show: function(planningId) {
                    document.querySelector('#planning_id').value = planningId;
                    modal.show();
                }
            }
        }();
        KTUtil.onDOMContentLoaded(function() {
            KTHTDApproval.init();
        });
        var handleApproveHtdRows = () => {
            $(document).on('click', '[data-kt-docs-table-filter="approve_htd_row"]', function(e) {
                e.preventDefault();
                const id = $(this).data("id");
                KTHTDApproval.show(id);
            });
        }
    </script>
@endsection
