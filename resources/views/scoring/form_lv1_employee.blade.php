@extends('layouts.main_scorring')
@section('content')
@if(isset($employee))
<!-- Skip the verification step for logged-in users -->
<div id="container-employee" class="d-none card card-flush w-md-650px py-5">
@else
<div id="container-employee" class="card card-flush w-md-650px py-5">
@endif
    <div class="card-body py-15 py-lg-20">
        
        <form action="#" id="form-employee" class="form" method="post">
            <h1 class="fw-bolder text-gray-900 mb-10">{{ $diklat->name }}</h1>
            
            <div class="fv-row mb-10">
                <label for="exampleFormControlInput1" class="required form-label">NIP</label>
                <input type="text" name="nip" class="form-control" placeholder="Masukan NIP"/>
            </div>
            <div class="fv-row mb-10">
                <label for="exampleFormControlInput1" class="required form-label">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="form-control" placeholder=""/>
            </div>

            <div class="mb-0">
                <button class="btn btn-sm btn-primary" id="submit-employee" data-kt-indicator="off">
                    <span class="indicator-label">Lanjutkan</span>
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </form>
        
    </div>
</div>

@if(isset($employee))
<!-- Show the scoring form directly for logged-in users -->
@if(isset($hasSubmitted) && $hasSubmitted)
<div id="container-form" class="d-none card card-flush w-md-650px overflow-auto py-5" style="height: 90vh">
@else
<div id="container-form" class="card card-flush w-md-650px overflow-auto py-5" style="height: 90vh">
@endif
@else
<div id="container-form" class="d-none card card-flush w-md-650px overflow-auto py-5" style="height: 90vh">
@endif
    <div class="card-body py-15 py-lg-20">
        
        <form action="#" id="form-scorring" class="form" method="post">
            <h1 class="fw-bolder text-gray-900 ">{{ $diklat->name }}</h1>
            <p class="mb-10">Skala yang digunakan dalam mengevaluasi setiap aspek dari program pelatihan</p>

            <table class="table table-bordered">
                <tr>
                    <td>1 = Kurang</td>
                    <td>2 = Cukup</td>
                    <td>3 = Baik</td>
                    <td>4 = Sangat Baik</td>
                    <td>5 = Luar Biasa</td>
                </tr>
            </table>

            <!--begin::Accordion-->
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
                                <tr>
                                    <td style="text-align: left">{{ $item->name }}</td>
                                    <td class="d-flex justify-content-end gap-3">
                                        <input type="hidden" class="hidden" name="question[{{ $item->id }}][scoring_lv1_question_id]" value="{{ $item->id }}"/>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required value="1" name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_1_{{ $item->id }}"/>
                                            <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">1</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required value="2" name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_2_{{ $item->id }}"/>
                                            <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">2</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required value="3" name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_3_{{ $item->id }}"/>
                                            <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">3</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required value="4" name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_4_{{ $item->id }}"/>
                                            <label class="form-check-label" for="form_{{ $item->id }}_{{ $item->id }}">4</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required value="5" name="question[{{ $item->id }}][score]" id="form_{{ $item->id }}_5_{{ $item->id }}"/>
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
            <!--end::Accordion-->

            <div class="mb-0">
                <button class="btn btn-sm btn-primary" id="submit-scorring" data-kt-indicator="off">
                    <span class="indicator-label">Lanjutkan</span>
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </form>
        
    </div>
</div>

@if(isset($hasSubmitted) && $hasSubmitted)
<div id="container-thanks" class="card card-flush w-md-650px py-5">
@else
<div id="container-thanks" class="d-none card card-flush w-md-650px py-5">
@endif
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
@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Initialize the form validation
        const form = document.getElementById('form-employee');
        const submitButton = form.querySelector('#submit-employee');

        const form_scorring = document.getElementById('form-scorring');
        const submitButtonScorring = form_scorring.querySelector('#submit-scorring');

        @if(isset($employee))
        // For logged-in users, we already have the NIP from the server
        let nip = "{{ $employee->nip }}";
        let birth_date = "{{ $employee->birth_date }}";
        
        // If user is logged in, automatically show the appropriate form
        $('#container-employee').addClass('d-none');
        @if(isset($hasSubmitted) && $hasSubmitted)
        // If form has been submitted, show the thank you page
        $('#container-form').addClass('d-none');
        $('#container-thanks').removeClass('d-none');
        @else
        // Otherwise, show the assessment form
        $('#container-form').removeClass('d-none');
        @endif
        @else
        // For direct access users, check if they've already verified in this session
        let nip = sessionStorage.getItem('verified_nip');
        let birth_date = sessionStorage.getItem('verified_birth_date');
        
        // If we have verification data in session storage, skip the verification step
        if (nip && birth_date) {
            $('#container-employee').addClass('d-none');
            $('#container-form').removeClass('d-none');
        }
        @endif
        
        form.addEventListener('submit', async function(e) {
            e?.preventDefault();
            
            try {
                let validator = FormValidation.formValidation(
                    form,
                    {
                        fields: {
                            'nip': {
                                validators: {
                                    notEmpty: {
                                        message: 'NIP is required'
                                    },
                                }
                            },
                            'birth_date': {
                                validators: {
                                    notEmpty: {
                                        message: 'Tanggal Lahir is required'
                                    },
                                    date: {
                                        format: 'YYYY-MM-DD',
                                        message: 'The date is not valid'
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger({
                                event: {
                                    password: false
                                }  
                            }),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: '.fv-row',
                                eleInvalidClass: '',
                                eleValidClass: ''
                            })
                        }			 
                    }
                );
    
                const statusValidation = await validator.validate();
                if (statusValidation === 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;

                    var formData = new FormData(form);
                    formData.append('_token', csrf_token);

                    $.ajax({
                        url: `${base_url}/form/check/{{ $hash_slug }}`,
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            const data = response;
                            console.log(data);
                            if (response.status) {
                                toastr.success(data.message);
                                nip = formData.get('nip');
                                // Make sure we store the birth_date in YYYY-MM-DD format
                                birth_date = formData.get('birth_date');

                                // Store verification data in sessionStorage to persist across page reloads
                                sessionStorage.setItem('verified_nip', nip);
                                sessionStorage.setItem('verified_birth_date', birth_date);

                                $('#container-employee').addClass('d-none');
                                $('#container-form').removeClass('d-none');
                            } else {
                                toastr.error(data.message);
                            }

                            submitButton.setAttribute('data-kt-indicator', 'off');
                            submitButton.disabled = false
                        },
                        error: function(xhr, status, error) {
                            submitButton.setAttribute('data-kt-indicator', 'off');
                            submitButton.disabled = false;
                            toastr.error("Sorry, looks like there are some errors detected, please try again.");
                        }
                    });
                } else {
                    Swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            } catch (error) {
                console.log(error)
            }
        });

        form_scorring.addEventListener('submit', async function (e) {
            e?.preventDefault();

            Swal.fire({
                text: "Are you sure you want to submit form?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, submit!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    submitButtonScorring.setAttribute('data-kt-indicator', 'on');
                    submitButtonScorring.disabled = true;
                    
                    var formData = new FormData(form_scorring);
                    @if(isset($employee))
                    // For authenticated users, use the NIP from the server
                    formData.append('nip', "{{ $employee->nip }}");
                    formData.append('birth_date', "{{ $employee->birth_date }}");
                    @else
                    // For non-authenticated users, use the NIP and birth_date from the form or session storage
                    formData.append('nip', nip);
                    formData.append('birth_date', birth_date);
                    @endif
                    formData.append('_token', csrf_token);

                    $.ajax({
                        url: `${base_url}/form/lv1/{{ $hash_slug }}/submit`,
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status === 200) {
                                // Clear the sessionStorage to prevent resubmission
                                sessionStorage.removeItem('verified_nip');
                                sessionStorage.removeItem('verified_birth_date');
                                
                                $('#container-employee').addClass('d-none');
                                $('#container-form').addClass('d-none');
                                $('#container-thanks').removeClass('d-none');
                            }

                            submitButtonScorring.setAttribute('data-kt-indicator', 'off');
                            submitButtonScorring.disabled = false
                        },
                        error: function(xhr, status, error) {
                            submitButtonScorring.setAttribute('data-kt-indicator', 'off');
                            submitButtonScorring.disabled = false;
                            console.log(xhr);
                            console.log(status);
                            (xhr?.responseJSON?.errors || []).forEach(function (item) {
                                toastr.error(item);
                            });
                            
                            if (!xhr?.responseJSON?.errors) {
                                toastr.error("Sorry, looks like there are some errors detected, please try again.");
                            }
                        }
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your data has not been submited!",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    });
</script>
@endsection