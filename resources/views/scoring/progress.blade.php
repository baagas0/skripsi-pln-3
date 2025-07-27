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

                    @if ($diklat)
                        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }}</h1>

                        {{-- <a href="{{ route('.scorring.lv3.{id}', $diklat->id) }}" class="text-decoration-unsderline text-primary fw-bold fs-4" data-bs-toggle="modal" data-bs-target="#scorring_lv_1">Penilaian 1</a> --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('.scorring.lv1.{id}', $diklat->id) }}"
                                class="text-primary fw-bold fs-4">Penilaian Level 1 - Reaction</a>

                            <div class="d-flex align-items-center gap-3" style="width: 22%; cursor: pointer" data-bs-toggle="modal" data-bs-target="#modal_scorring_lv_1">
                                <div class="h-8px mx-3 bg-light-primary rounded" style="width: 100%">
                                    <div class="bg-primary rounded h-8px" role="progressbar"
                                        style="width: {{ $percentageProgressLv1 }}%;"
                                        aria-valuenow="{{ $percentageProgressLv1 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="fw-bold fs-6 mb-0 text-gray-500">{{ number_format($percentageProgressLv1, 2) }}%</p>
                            </div>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('.scorring.lv2.{id}', $diklat->id) }}"
                                class="text-primary fw-bold fs-4">Penilaian Level 2 - Learning</a>
                            <div class="d-flex align-items-center gap-3" style="width: 22%; cursor: pointer" data-bs-toggle="modal" data-bs-target="#modal_scorring_lv_2">
                                <div class="h-8px mx-3 bg-light-primary rounded" style="width: 100%">
                                    <div class="bg-primary rounded h-8px" role="progressbar"
                                        style="width: {{ $percentageProgressLv2 }}%;"
                                        aria-valuenow="{{ $percentageProgressLv2 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="fw-bold fs-6 mb-0 text-gray-500">{{ number_format($percentageProgressLv2, 2) }}%</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('.scorring.lv3.{id}', $diklat->id) }}"
                                class="text-primary fw-bold fs-4">Penilaian Level 3 - Behavior</a>
                            <div class="d-flex align-items-center gap-3" style="width: 22%; cursor: pointer" data-bs-toggle="modal" data-bs-target="#modal_scorring_lv_3">
                                <div class="h-8px mx-3 bg-light-primary rounded" style="width: 100%">
                                    <div class="bg-primary rounded h-8px" role="progressbar"
                                        style="width: {{ $percentageProgressLv3 }}%;"
                                        aria-valuenow="{{ $percentageProgressLv3 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="fw-bold fs-6 mb-0 text-gray-500">{{ number_format($percentageProgressLv3, 2) }}%</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('.scorring.lv4.{id}', $diklat->id) }}"
                                class="text-primary fw-bold fs-4">Penilaian Level 4 - Result</a>
                            <div class="d-flex align-items-center gap-3" style="width: 22%; cursor: pointer" data-bs-toggle="modal" data-bs-target="#modal_scorring_lv_4">
                                <div class="h-8px mx-3 bg-light-primary rounded" style="width: 100%">
                                    <div class="bg-primary rounded h-8px" role="progressbar"
                                        style="width: {{ $percentageProgressLv4 }}%;"
                                        aria-valuenow="{{ $percentageProgressLv4 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="fw-bold fs-6 mb-0 text-gray-500">{{ number_format($percentageProgressLv4, 2) }}%</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="" class="text-primary fw-bold fs-4">Penilaian Level 5 - Return On Training
                                Investment (ROTI)</a>
                            <div class="d-flex align-items-center gap-3" style="width: 22%">
                                <div class="h-8px mx-3 bg-light-primary rounded" style="width: 100%">
                                    <div class="bg-primary rounded h-8px" role="progressbar"
                                        style="width: {{ $percentageProgressLv5 }}%;"
                                        aria-valuenow="{{ $percentageProgressLv5 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="fw-bold fs-6 mb-0 text-gray-500">{{ number_format($percentageProgressLv5, 2) }}%</p>
                            </div>
                        </div>
                        <hr>
                    @endif
                </div>
            </div>

            {{-- Modal --}}
            <div class="modal fade" tabindex="-1" id="modal_scorring_lv_1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Penilaian Level 1 - Reaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Sudah Mengisi</td>
                                    <td>Belum Mengisi</td>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($list1['already'] as $item)
                                            <p>{{ $loop->iteration }}. {{ $item->employee->name }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($list1['not_yet'] as $item)
                                            <p>{{ $loop->iteration }}. {{ $item->employee->name }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" id="modal_scorring_lv_2">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Penilaian Level 2 - Learning</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Sudah Dinilai</td>
                                    <td>Belum Dinilai</td>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($list2['already'] as $item)
                                            <p>{{ $loop->iteration }}. {{ $item->employee->name }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($list2['not_yet'] as $item)
                                            <p>{{ $loop->iteration }}. {{ $item->employee->name }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" id="modal_scorring_lv_3">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Penilaian Level 3 - Behavior</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Sudah Dinilai</td>
                                    <td>Belum Dinilai</td>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($list3['already'] as $item)
                                            <p>{{ $loop->iteration }}. {{ $item->employee->name }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($list3['not_yet'] as $item)
                                            <p>{{ $loop->iteration }}. {{ $item->employee->name }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" id="modal_scorring_lv_4">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Penilaian Level 4 - Result</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Bidang yang sudah dinilai:</h5>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($list4['already'] as $item)
                                            <li class="list-group-item">
                                                <span class="fw-bold">{{ $item->name }}</span>
                                                @if(isset($list4['employee_data'][$item->id]))
                                                    <div class="mt-2 ps-3">
                                                        <span class="text-muted">Peserta yang dinilai:</span>
                                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                                            @foreach($list4['employee_data'][$item->id] as $participant)
                                                                <span class="badge badge-light-primary">{{ $participant->employee->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Bidang yang belum dinilai:</h5>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($list4['not_yet'] as $item)
                                            <li class="list-group-item">{{ $item->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                var url = "{{ route('.scorring.progress') }}";
                if (diklatId) {
                    url += '?diklat_id=' + diklatId;
                }
                window.location.href = url;
            });
        });
    </script>
@endsection
