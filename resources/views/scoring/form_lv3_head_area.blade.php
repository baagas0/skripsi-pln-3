@extends('layouts.main_scorring')
@section('content')
<div id="container-form" class="card card-flush w-md-650px overflow-auto py-5" style="height: {{ $questions != null ? '90vh' : '' }};">
    <div class="card-body py-15 py-lg-20">
        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Behavior</h1>

        <div class="mb-3 form-group text-start">
            <label for="exampleFormControlInput1" class="required form-label">Bidang</label>
            <select name="area_id" required class="form-select"  data-placeholder="Pilih Bidang">
                <option></option>
                @foreach ($areas as $item)
                    <option value="{{ $item->id }}" {{ request('area_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 form-group text-start">
            <label for="exampleFormControlInput1" class="required form-label">Peserta</label>
            <select name="diklat_participant_id" required class="form-select"  data-placeholder="Pilih Nama Peserta">
                <option></option>
                @foreach ($selectedParticipants as $item)
                    <option value="{{ $item->id }}" {{ request('diklat_participant_id') == $item->id ? 'selected' : '' }}>{{ $item->employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="form-lv-3">
            @if($questions != null)
            <p>Isilah setiap parameter evaluasi dengan rating 1 sampai dengan 5. Perhatikan kriteria setiap nilai rating berikut :</p>
            <button class="btn btn-primary mb-6" data-bs-toggle="modal" data-bs-target="#instruction_criteria">Kriteria</button>
            
            @if($userRole == 1)
            {{-- Read-only mode for HTD admin (role_id 1) --}}
            <div class="alert alert-custom alert-notice alert-light-info mb-5">
                <div class="alert-icon">
                    <i class="ki-duotone ki-information-5 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                </div>
                <div class="alert-text">
                    <strong>Mode Tampilan:</strong> Sebagai HTD admin, Anda hanya dapat melihat penilaian Level 3 tanpa melakukan perubahan.
                </div>
            </div>
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
                                <td class="text-start">{{ $question->name }}</td>
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
            <form action="#" id="form_scoring_lv3" class="form" method="post">
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
                                    <td class="text-start">{{ $question->name }}</td>
                                    <td class="pe-10">
                                        <input type="text" name="question[{{ $loop->iteration }}][diklat_participant_id]" value="{{ request('diklat_participant_id') }}" hidden>
                                        <input type="text" name="question[{{ $loop->iteration }}][scoring_lv3_question_id]" value="{{ $question->id }}" hidden>
                                        <select name="question[{{ $loop->iteration }}][score]" required id="" class="form-control form-control-sm">
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
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>

                </div>
            </form>
            @endif
            @endif
        </div>
    </div>
</div>
<div id="container-thanks" class="d-none card card-flush w-md-650px py-5">
    <div class="card-body py-15 py-lg-20">
        <!--begin::Logo-->
        <div class="mb-7">
            <a href="index.html" class="">
                <img alt="Logo" src="{{ asset('assets/media/logo-pln.png') }}" class="h-40px" />
            </a>
        </div>
        <!--end::Logo-->
        <!--begin::Title-->
        <h1 class="fw-bolder text-gray-900 mb-5">Terimakasih</h1>
        <!--end::Title-->
        <!--begin::Text-->
        <div class="fw-semibold fs-6 text-gray-500 mb-7">Anda telah mengisi form penilaian <b class="text-black">{{ $diklat->name }}</b>.</div>
        <!--end::Text-->
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
        const participants = @json($participants);

        function reRenderParticipants(areaId) {
            const select = $('select[name="diklat_participant_id"]');
            select.empty();
            select.append('<option></option>');
            participants.filter((x) => x.employee.area_id == areaId).forEach(function(participant) {
                select.append('<option value="' + participant.id + '">' + participant.employee.name + '</option>');
            });
        }

        KTUtil.onDOMContentLoaded(function() {
            console.log('loaded')

            $('select[name="area_id"]').on('change', function() {
                var areaId = $(this).val();
                if (areaId) {
                    reRenderParticipants(areaId);
                }
            });

            $('select[name="diklat_participant_id"]').on('change', function() {
                var diklatId = $(this).val();
                var areaId = $('select[name="area_id"]').val();
                var url = "{{ $urlLv3 }}";
                if (diklatId && areaId) {
                    url += '?diklat_participant_id=' + diklatId + '&area_id=' + areaId;
                }
                window.location.href = url;
            });

            $('#form_scoring_lv3').on('submit', function(e) {
                e.preventDefault();

                console.log('submit form');
                
                const areaId = $('select[name="area_id"]').val();
                const formData = new FormData(this);
                formData.append('area_id', areaId);
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: `${base_url}/form/lv3/{{ $diklat->id }}/submit`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            $('#container-form').addClass('d-none');
                            $('#container-thanks').removeClass('d-none');
                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            })
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON?.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(
                                key => {
                                    toastr.error(xhr.responseJSON
                                        .errors[key][0]);
                                });
                        }
                    }
                });
            });
        });
    </script>
@endsection