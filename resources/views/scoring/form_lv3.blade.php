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

                    {{-- <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Reaction</h1> --}}
                    <div class="d-flex justify-content-between">
                        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Behavior</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scorring_lv_3">
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

                    @if(request('diklat_participant_id'))
                        @if(isset($scoreLv3) && $scoreLv3 && $scoreLv3->count() > 0)
                        <div class="alert alert-success">
                            Peserta sudah dinilai pada formulir penilaian ini.
                        </div>
                        @else
                        <div class="alert alert-danger">
                            Peserta belum dinilai pada formulir penilaian ini.
                        </div>
                        @endif
                    @endif

                    {{-- TABLE --}}
                    @if($questions != null)
                    <p>Isilah setiap parameter evaluasi dengan rating 1 sampai dengan 5. Perhatikan kriteria setiap nilai rating berikut :</p>
                    <button class="btn btn-primary mb-6" data-bs-toggle="modal" data-bs-target="#instruction_criteria">Kriteria</button>
                    
                    @if($userRole == 1)
                    <div class="accordion accordion-icon-toggle" id="kt_questions">
                        <div class="mb-5 border rounded">
                            <div class="accordion-header py-3 d-flex bg-primary rounded flex justify-content-between px-6" data-bs-toggle="collapse" data-bs-target="#kt_questions_1" aria-expanded="true">
                                <div class="d-flex justify-content-between w-100 pe-10">
                                    <h3 class="fs-4 fw-semibold mb-0 text-white">Parameter Evaluasi</h3>
                                    <h3 class="fs-4 fw-semibold mb-0 text-white">Rating</h3>
                                </div>
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-4 text-white"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div id="kt_questions_1" aria-expanded="true" class="fs-6 collapse show px-10" data-bs-parent="#kt_questions">
                                <table class="table">
                                    @foreach ($questions as $question)
                                    @php
                                        $currentScore = $scoreLv3->where('scoring_lv3_question_id', $question->id)->first();
                                        $s = isset($currentScore->score) ? $currentScore->score : '';
                                    @endphp
                                    <tr>
                                        <td>{{ $question->name }}</td>
                                        <td class="pe-10">
                                            <div class="bg-light-primary rounded p-2 text-center fw-bold">
                                                {{ $s ?: 'Belum dinilai' }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    {{-- Regular form for other roles --}}
                    <form action="{{ url('/scorring/lv3/' . request('diklat_participant_id') . '/store') }}" id="form-scorring" class="form" method="post">
                        @csrf
                        <input type="text" name="diklat_participant_id" value="{{ request('diklat_participant_id') }}" hidden>
                        <div class="accordion accordion-icon-toggle" id="kt_questions">
                            
                            <div class="mb-5 border rounded">
                                <div class="accordion-header py-3 d-flex bg-primary rounded flex justify-content-between px-6" data-bs-toggle="collapse" data-bs-target="#kt_questions_1" aria-expanded="true">
                                    <div class="d-flex justify-content-between w-100 pe-10">
                                        <h3 class="fs-4 fw-semibold mb-0 text-white">Parameter Evaluasi</h3>
                                        <h3 class="fs-4 fw-semibold mb-0 text-white">Rating</h3>

                                    </div>
                                    <span class="accordion-icon">
                                        <i class="ki-duotone ki-arrow-right fs-4 text-white"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
            
                                <div id="kt_questions_1" aria-expanded="true" class="fs-6 collapse show  px-10" data-bs-parent="#kt_questions">
                                    <table class="table">
                                        @foreach ($questions as $question)
                                        @php
                                            $currentScore = $scoreLv3->where('scoring_lv3_question_id', $question->id)->first();
                                            $s = isset($currentScore->score) ? $currentScore->score : '';
                                        @endphp
                                        <tr>
                                            <td>{{ $question->name }}</td>
                                            <td class="pe-10">
                                                <input type="text" name="question[{{ $loop->iteration }}][diklat_participant_id]" value="{{ request('diklat_participant_id') }}" hidden>
                                                <input type="text" name="question[{{ $loop->iteration }}][scoring_lv3_question_id]" value="{{ $question->id }}" hidden>
                                                <select name="question[{{ $loop->iteration }}][score]" {{ auth()->user()->role_id !== 7 ? '' : 'disabled' }} required id="" class="form-control form-control-sm">
                                                    <option value=""></option>
                                                    <option value="1" {{ $s == '1' ? 'selected' : '' }}>1</option>
                                                    <option value="2" {{ $s == '2' ? 'selected' : '' }}>2</option>
                                                    <option value="3" {{ $s == '3' ? 'selected' : '' }}>3</option>
                                                    <option value="4" {{ $s == '4' ? 'selected' : '' }}>4</option>
                                                    <option value="5" {{ $s == '5' ? 'selected' : '' }}>5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            @if (auth()->user()->role_id !== 7)
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            @endif
                        </div>
                    </form>
                    @endif
                    @endif
                </div>
            </div>

            
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="scorring_lv_3">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="">Penilaian Lv 3 {{ $moduleName }}</h5>

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
                                <div class="fs-6 text-gray-700">Copy link dan bagikan kepada atasan bidang unit {{ $diklat ? $diklat->unit->name : '' }}</div>
                                <textarea name="" id="url-lv-3" cols="40" rows="1" class="w-100">{{ $urlLv3 }}</textarea>
                            </div>
                            <button type="button" class="btn btn-primary px-6 align-self-center text-nowrap" id="copy-button" 
                                    onclick="copyToClipboard('3')">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="instruction_criteria">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="">Kriteria Rating</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <h1>Kriteria Rating Penilaian Level 3 - Behavior</h1>
                    <table class="table table-bordered">
                        <tr>
                            <td style="background-color: #3699FF; vertical-align: middle">Rating</td>
                            <td style="background-color: #3699FF; vertical-align: middle">Kriteria</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">5</td>
                            <td>Memahami subjek (materi pelatihan) sepenuhnya dan memanfaatkan pengetahuan/  keterampilan dengan keyakinan penuh di tempat kerja. Membagi subjek (materi  pelatihan) kepada semua personil dalam perusahaan. Menyebarkan pengetahuan/ keterampilan ke seluruh perusahaan.</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">4</td>
                            <td>Memahami subjek (materi pelatihan) secara baik dan memanfaatkan pengetahuan/  keterampilan dengan percaya diri. Membagi subjek (materi pelatihan) kepada orang  lain dalam area tanggung jawabnya. Berusaha mengembangkan pengetahuan  keterampilan kepada rekan kerja atau bawahan.</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">3</td>
                            <td>Pemahaman subjek (materi pelatihan) cukup memuaskan. Memanfaatkan  pengetahuan/ keterampilan tetapi sesekali masih membutuhkan dukungan dan  bimbingan. Berupaya untuk meningkatkan pengetahuan/keterampilan tentang subjek  (materi pelatihan)  serta berdiskusi dengan rekan – rekan kerja atau bawahan.</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">2</td>
                            <td>Pemahaman pada subjek (materi pelatihan) masih sedikit. Implementasi  pengetahuan/ keterampilan yang diperoleh dari pelatihan sangat sedikit. Tidak ada  upaya untuk meningkatkan pengetahuan/keterampilan dan jarang berdiskusi tentang  subjek dengan rekan – rekan kerja atau bawahan.</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle">1</td>
                            <td>Tidak memahami subjek (materi pelatihan) sama sekali. Tidak pernah berdiskusi  tentang subjek dengan rekan – rekan kerja maupun bawahan.</td>
                        </tr>
                    </table>
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
            console.log('loaded');
            
            // Handle form submission with AJAX
            $('#form-scorring').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            text: "Penilaian berhasil disimpan!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            // Reload the current page to refresh the data
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Show error message
                        Swal.fire({
                            text: "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        console.error(xhr);
                    }
                });
            });
            
            // Participant dropdown change
            $('select[name="diklat_participant_id"]').on('change', function() {
                var diklatId = $(this).val();
                var url = "{{ route('.scorring.lv3.{id}', $diklat->id) }}";
                if (diklatId) {
                    url += '?diklat_participant_id=' + diklatId;
                }
                window.location.href = url;
            });
        });
    </script>
@endsection
