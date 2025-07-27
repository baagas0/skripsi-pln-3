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

                    <div class="d-flex justify-content-between">
                        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Reaction</h1>
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scorring_lv_1">
                                <i class="ki-duotone ki-share">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Share Link
                            </button>
                            <button class="btn btn-warning" id="sendReminder" data-kt-indicator="off">
                                {{-- <i class="ki-duotone ki-book">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Reminder --}}

                                <span class="indicator-label">
                                    <i class="ki-duotone ki-book">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    Reminder
                                </span>
                                <span class="indicator-progress">Sedang Mengirim...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mb-10">
                        <label for="exampleFormControlInput1" class="required form-label">Nama Peserta</label>
                        <select name="diklat_participant_id" required class="form-select"  data-placeholder="Pilih Nama Peserta">
                            <option></option>
                            @foreach ($participants as $item)
                                <option value="{{ $item->id }}" {{ request('diklat_participant_id') == $item->id ? 'selected' : '' }}>{{ $item->employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TABLE --}}
                    @if($questions != null)
                    <form action="{{ url('/scorring/lv1/' . request('diklat_participant_id') . '/store') }}" id="form-scorring" class="form" method="post">
                        @csrf

                        <p class="mb-">Skala yang digunakan dalam mengevaluasi setiap aspek dari program pelatihan</p>

                        <table class="table table-bordered">
                            <tr>
                                <td>1 = Kurang</td>
                                <td>2 = Cukup</td>
                                <td>3 = Baik</td>
                                <td>4 = Sangat Baik</td>
                                <td>5 = Luar Biasa</td>
                            </tr>
                        </table>
                        
                        <input type="text" name="diklat_participant_id" value="{{ request('diklat_participant_id') }}" hidden>

                        <div class="accordion accordion-icon-toggle" id="kt_questions">
                        @foreach ($questions as $group => $question)    
                        <div class="mb-5 border rounded">
                            <div class="accordion-header py-3 d-flex {{ $loop->iteration == 1 ? '' : 'collapsed' }} bg-primary rounded flex justify-content-between px-6" data-bs-toggle="collapse" data-bs-target="#kt_questions_{{ $loop->iteration }}" aria-expanded="true">
                                <h3 class="fs-4 fw-semibold mb-0 text-white">{{ $group }}</h3>
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-4 text-white"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>

                            <div id="kt_questions_{{ $loop->iteration }}" aria-expanded="true" class="fs-6 collapse {{ $loop->iteration == 1 ? 'show' : '' }}  px-10" data-bs-parent="#kt_questions">
                                <table class="table">
                                    @foreach ($question as $item)
                                    @php
                                        $currentValue = $scoreLv1 ? $scoreLv1->where('scoring_lv1_question_id', $item->id)->first() : null;
                                        $currentValue = $currentValue ? $currentValue->score : null;
                                    @endphp
                                        <tr>
                                            <td style="text-align: left">{{ $item->name }}</td>
                                            <td class="d-flex justify-content-end gap-3">
                                                <input type="hidden" class="hidden" name="question[{{ $item->id }}][scoring_lv1_question_id]" value="{{ $item->id }}"/>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input disabled class="form-check-input" type="radio" required value="1" {{ $currentValue == '1' ? 'checked' : '' }} name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_1_{{ $item->id }}"/>
                                                    <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">1</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input disabled class="form-check-input" type="radio" required value="2" {{ $currentValue == '2' ? 'checked' : '' }} name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_2_{{ $item->id }}"/>
                                                    <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">2</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input disabled class="form-check-input" type="radio" required value="3" {{ $currentValue == '3' ? 'checked' : '' }} name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_3_{{ $item->id }}"/>
                                                    <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">3</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input disabled class="form-check-input" type="radio" required value="4" {{ $currentValue == '4' ? 'checked' : '' }} name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_4_{{ $item->id }}"/>
                                                    <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">4</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input disabled class="form-check-input" type="radio" required value="5" {{ $currentValue == '5' ? 'checked' : '' }} name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_5_{{ $item->id }}"/>
                                                    <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">5</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @endforeach
                        </div>

                        {{-- <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>

                        </div> --}}
                    </form>
                    @endif
                </div>
            </div>

            
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="scorring_lv_1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="">Penilaian Lv 1 {{ $moduleName }}</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed min-w-lg-600px flex-shrink-0 p-6">
                        <i class="ki-outline ki-devices-2 fs-2tx text-primary me-4 mt-1"></i>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-semibold">
                                <div class="fs-6 text-gray-700">Copy link dan bagikan kepada karyawan unit {{ $diklat ? $diklat->unit->name : '' }}</div>
                                <textarea name="" id="url-lv-1" cols="10" rows="1" class="w-100">{{ $urlLv1 }}</textarea>
                            </div>
                            <button type="button" class="btn btn-primary px-6 align-self-center text-nowrap" id="copy-button" 
                                    onclick="copyToClipboard('1')">
                                Copy
                            </button>
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

        function copyToClipboard(level = '1') {

            $(`#url-lv-${level}`).select();

            try {
                document.execCommand('copy');
            } catch (error) {
                console.error(error);
            }
        }

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            console.log('loaded')
            KTUtil.onDOMContentLoaded(function() {
                $('select[name="diklat_participant_id"]').on('change', function() {
                    var diklatId = $(this).val();
                    var url = "{{ route('.scorring.lv1.{id}', $diklat->id) }}";
                    if (diklatId) {
                        url += '?diklat_participant_id=' + diklatId;
                    }
                    window.location.href = url;
                });
            });

            $('#sendReminder').on('click', function(e) {
                document.querySelector('#sendReminder').setAttribute('data-kt-indicator', 'on');
                document.querySelector('#sendReminder').disabled = true;
                $.ajax({
                    url: "{{ url('/scorring/lv1/' . $diklat->id . '/send-reminder') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            toastr.success(response.message);
                        }

                        document.querySelector('#sendReminder').removeAttribute('data-kt-indicator');
                        document.querySelector('#sendReminder').disabled = false;
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON?.message) {
                            toastr.error(xhr.responseJSON.message);
                        }

                        document.querySelector('#sendReminder').removeAttribute('data-kt-indicator');
                        document.querySelector('#sendReminder').disabled = false;
                    }
                });
            });
        });
    </script>
@endsection
