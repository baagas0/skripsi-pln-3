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

                    <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Learning</h1>
                    
                    @if(request('diklat_participant_id'))
                        @if(isset($scoreLv2) && $scoreLv2 && $scoreLv2->count() > 0)
                        <div class="alert alert-success">
                            Peserta sudah dinilai pada formulir penilaian ini.
                        </div>
                        @else
                        <div class="alert alert-danger">
                            Peserta belum dinilai pada formulir penilaian ini.
                        </div>
                        @endif
                    @endif

                    @if (auth()->user()->role_id !== 7)
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ url('scorring/lv2/' . $diklat->id . '/store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-10">
                                        <label for="exampleFormControlInput1" class="required form-label">Nama Peserta</label>
                                        <select name="diklat_participant_id" required class="form-select" data-control="select2"  data-placeholder="Pilih Nama Peserta">
                                            <option></option>
                                            @foreach ($participants as $item)
                                                <option value="{{ $item->id }}">{{ $item->employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-10">
                                        <label for="pre-test" class="required form-label">Nilai Pre-Test</label>
                                        <input type="number" max="100" required class="form-control" name="pretest_score" id="pre-test" placeholder="Nilai Pre-Test" />
                                    </div>
                                    <div class="col-md-6 mb-10">
                                        <label for="post-test" class="required form-label">Nilai Post-Test</label>
                                        <input type="number" max="100" required class="form-control" name="posttest_score" id="post-test" placeholder="Nilai Post-Test" />
                                    </div>
                                </div>
                                <div class="">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#importModal">Import Excel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table id="kt_datatable" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    {{-- <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_datatable .form-check-input" value="1" />
                                        </div>
                                    </th> --}}
                                    <th>Name</th>
                                    <th>Pre Test</th>
                                    <th>Post Test</th>
                                    <th>Peningkatan Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Scores</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Excel File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls">
                        </div>
                        <div class="alert alert-info">
                            Download template <a href="{{ url('scorring/lv2/' . $diklat->id . '/template') }}">here</a>
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
                        url: `${base_url}/scorring/lv2/{{ $diklat->id }}/data`,
                        data: {
                            diklat_id: {{ $diklat->id }},
                        }
                    },
                    columns: [
                        {
                            data: 'name'
                        },
                        {
                            data: 'pretest_score'
                        },
                        {
                            data: 'posttest_score'
                        },
                        {
                            data: 'diff_score',
                            render: function(data, type, row) {
                                let color = '';
                                if (parseInt(data) < 0) {
                                    color = 'danger';
                                } else if (parseInt(data) > 0) {
                                    color = 'success';
                                } else {
                                    color = 'primary';
                                }

                                let icon = '';
                                if (parseInt(data) < 0) {
                                    icon = `
                                        <span class="svg-icon svg-icon-danger" style="margin-left: 0.5rem"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Navigation/Angle-double-down.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <title>Stockholm-icons / Navigation / Angle-double-down</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs/>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M8.2928955,3.20710089 C7.90237121,2.8165766 7.90237121,2.18341162 8.2928955,1.79288733 C8.6834198,1.40236304 9.31658478,1.40236304 9.70710907,1.79288733 L15.7071091,7.79288733 C16.085688,8.17146626 16.0989336,8.7810527 15.7371564,9.17571874 L10.2371564,15.1757187 C9.86396402,15.5828377 9.23139665,15.6103407 8.82427766,15.2371482 C8.41715867,14.8639558 8.38965574,14.2313885 8.76284815,13.8242695 L13.6158645,8.53006986 L8.2928955,3.20710089 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 8.499997) scale(-1, -1) rotate(-90.000000) translate(-12.000003, -8.499997) "/>
                                                <path d="M6.70710678,19.2071045 C6.31658249,19.5976288 5.68341751,19.5976288 5.29289322,19.2071045 C4.90236893,18.8165802 4.90236893,18.1834152 5.29289322,17.7928909 L11.2928932,11.7928909 C11.6714722,11.414312 12.2810586,11.4010664 12.6757246,11.7628436 L18.6757246,17.2628436 C19.0828436,17.636036 19.1103465,18.2686034 18.7371541,18.6757223 C18.3639617,19.0828413 17.7313944,19.1103443 17.3242754,18.7371519 L12.0300757,13.8841355 L6.70710678,19.2071045 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(12.000003, 15.499997) scale(-1, -1) rotate(-360.000000) translate(-12.000003, -15.499997) "/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    `;
                                } else if (parseInt(data) > 0) {
                                    icon = `
                                        <span class="svg-icon svg-icon-success" style="margin-left: 0.5rem"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Navigation/Angle-double-up.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <title>Stockholm-icons / Navigation / Angle-double-up</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs/>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M8.2928955,10.2071068 C7.90237121,9.81658249 7.90237121,9.18341751 8.2928955,8.79289322 C8.6834198,8.40236893 9.31658478,8.40236893 9.70710907,8.79289322 L15.7071091,14.7928932 C16.085688,15.1714722 16.0989336,15.7810586 15.7371564,16.1757246 L10.2371564,22.1757246 C9.86396402,22.5828436 9.23139665,22.6103465 8.82427766,22.2371541 C8.41715867,21.8639617 8.38965574,21.2313944 8.76284815,20.8242754 L13.6158645,15.5300757 L8.2928955,10.2071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 15.500003) scale(-1, 1) rotate(-90.000000) translate(-12.000003, -15.500003) "/>
                                                <path d="M6.70710678,12.2071104 C6.31658249,12.5976347 5.68341751,12.5976347 5.29289322,12.2071104 C4.90236893,11.8165861 4.90236893,11.1834211 5.29289322,10.7928968 L11.2928932,4.79289682 C11.6714722,4.41431789 12.2810586,4.40107226 12.6757246,4.76284946 L18.6757246,10.2628495 C19.0828436,10.6360419 19.1103465,11.2686092 18.7371541,11.6757282 C18.3639617,12.0828472 17.7313944,12.1103502 17.3242754,11.7371577 L12.0300757,6.88414142 L6.70710678,12.2071104 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(12.000003, 8.500003) scale(-1, 1) rotate(-360.000000) translate(-12.000003, -8.500003) "/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    `;
                                }
                                return `<span class="badge badge-light-${color}">${parseInt(data)} ${icon}</span>`;
                            }
                        },
                    ],
                    columnDefs: []
                });

                table = dt.$;

                // Re-init functions on every table re-draw
                dt.on('draw', function() {
                    // initToggleToolbar();
                    // toggleToolbars();
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

            // Public methods
            return {
                init: function() {
                    console.log('init')
                    initDatatable();
                    handleSearchDatatable();
                },
                refresh: function() {
                    dt.draw();
                }
            }
        }();

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            console.log('loaded')
            KTDatatablesServerSide.init();

            $('#importForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                
                $.ajax({
                    url: "{{ url('scorring/lv2/' . $diklat->id . '/import') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            toastr.success(response.message);
                            $('#importModal').modal('hide');
                            $('#importForm')[0].reset();
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
    </script>
@endsection
