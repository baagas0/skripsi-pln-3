@php
    $moduleName = 'Vendor';
    $moduleRoute = 'vendor';
@endphp
@extends('layouts.main')
@section('title', $moduleName)

@section('additional_styles')
<style>
    .employee-item {
        width: 48%;
        margin-bottom: 10px;
    }
    @media (max-width: 768px) {
        .employee-item {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body">

                    <div class="d-flex justify-content-between">
                        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Result</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scorring_lv_4">
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
                    

                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="required form-label">Bidang</label>
                        <select name="area_id" required class="form-select"  data-placeholder="Pilih Bidang">
                            <option></option>
                            @foreach ($areas as $item)
                                <option value="{{ $item->id }}" {{ request('area_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(!$scoreLv4 && request('area_id'))
                    <div class="alert alert-danger">
                        Bidang belum dinilai formulir penilaian ini.
                    </div>
                    @endif
                    
                    @if(request('area_id') && $participants->count() > 0)
                        <div class="alert alert-custom alert-notice alert-light-success mb-5 selected-employees-display">
                            {{-- <div class="alert-icon">
                                <i class="ki-duotone ki-check-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div> --}}
                            <div class="alert-text">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fs-5 fw-bold">Peserta yang dipilih untuk penilaian:</div>
                                    @if(!$scoreLv4)
                                    <button type="button" class="btn btn-sm btn-light-danger deselect-all-employees">
                                        <i class="ki-duotone ki-cross-circle fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Batal Pilih Semua
                                    </button>
                                    @endif
                                </div>
                                <div class="mt-3 selected-employees-list">
                                    <div class="row">
                                        @foreach($participants as $participant)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-check-custom form-check-solid {{ in_array($participant->employee_id, $selectedEmployees ?? []) ? 'bg-light-success rounded' : '' }}">
                                                <input class="form-check-input employee-checkbox" type="checkbox" 
                                                    value="{{ $participant->employee_id }}" 
                                                    {{ in_array($participant->employee_id, $selectedEmployees ?? []) ? 'checked' : '' }}
                                                    {{ $scoreLv4 ? 'disabled' : '' }}
                                                    name="employee_ids[]" 
                                                    id="employee_{{ $participant->employee_id }}" />
                                                <label class="form-check-label d-flex align-items-center" for="employee_{{ $participant->employee_id }}">
                                                    <div class="symbol symbol-30px me-3">
                                                        <div class="symbol-label {{ in_array($participant->employee_id, $selectedEmployees ?? []) ? 'bg-success text-white' : 'bg-light-primary text-primary' }} fw-bold">
                                                            {{ substr($participant->employee->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold d-block">{{ $participant->employee->name }}</span>
                                                        <small class="text-muted">{{ $participant->employee->nip }}</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(request('area_id'))
                    <table class="table table-bordered">
                        <tr>
                            <td>1 = Tidak Berdampak</td>
                            <td>2 = Kurang Berdampak</td>
                            <td>3 = Cukup Berdampak</td>
                            <td>4 = Berdampak</td>
                            <td>5 = Sangat Berdampak</td>
                        </tr>
                    </table>

                    <form id="form_scoring_lv4" class="border rounded p-3 mb-6">
                        <table class="table w-100">
                            <tr>
                                <td>1.</td>
                                <td>Apakah program pelatihan ini memberi dampak positif terhadap Unit Anda ?</td>
                                <td class="d-flex justify-content-end gap-3">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" required {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}  value="1" {{ $scoreLv4 && $scoreLv4->score_positive == '1' ? 'checked' : '' }} name="score_positive" id="score_positive_1"/>
                                        <label class="form-check-label" for="score_positive_1">1</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" required {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="2" {{ $scoreLv4 && $scoreLv4->score_positive == '2' ? 'checked' : '' }} name="score_positive" id="score_positive_2"/>
                                        <label class="form-check-label" for="score_positive_2">2</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" required {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="3" {{ $scoreLv4 && $scoreLv4->score_positive == '3' ? 'checked' : '' }} name="score_positive" id="score_positive_3"/>
                                        <label class="form-check-label" for="score_positive_3">3</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" required {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="4" {{ $scoreLv4 && $scoreLv4->score_positive == '4' ? 'checked' : '' }} name="score_positive" id="score_positive_4"/>
                                        <label class="form-check-label" for="score_positive_4">4</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" required {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="5" {{ $scoreLv4 && $scoreLv4->score_positive == '5' ? 'checked' : '' }} name="score_positive" id="score_positive_5"/>
                                        <label class="form-check-label" for="score_positive_5">5</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td colspan="2">
                                    Saya telah melihat/merasakan dampak pada aspek berikut ini sebagai hasil penerapan apa yang telah peserta (bawahan) pelajari.

                                    @php
                                        $impacts = [
                                            'Peningkatan produktivitas',
                                            'Peningkatan kepercayaan diri sendiri',
                                            'Penguatan hubungan antara rekan-rekan kerja',
                                            'Perbaikan kualitas',
                                            'Peningkatan kepuasan pelanggan',
                                            'Penghormatan yang lebih dari rekan sejawat',
                                            'Organisasi/Unit kerja yang lebih baik',
                                        ];
                                        $selectedImpacts = $scoreLv4 ? $scoreLv4->impacts : [];
                                    @endphp
                                    <div class="row mt-3">
                                        @foreach ($impacts as $key => $impact)
                                        @php
                                            $isChecked = in_array($impact, $selectedImpacts ?? []);
                                        @endphp
                                        <div class="col-4 mt-3">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} name="impacts[]" {{ $isChecked ? 'checked' : '' }} value="{{ $impact }}" id="impact_{{ $key+1 }}"/>
                                                <label class="form-check-label" for="impact_{{ $key+1 }}">
                                                    {{ $impact }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-4 mt-3">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} name="impacts[]" value="lainnya" id="impact_8"/>
                                                <label class="form-check-label" for="impact_8">
                                                    Lainnya
                                                    <input type="text" disabled>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        @if(auth()->user()->role_id == 6)
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                        @endif
                    </form>
                    

                    <form action="" id="form_tangible" class="border rounded">
                        <div class="bg-primary p-3 rounded">
                            <h3 class="fw-bold mb-0 text-white">Tangible Benefit</h3>
                        </div>
                        <!--begin::Repeater-->
                        <div class="p-3">
                            <div id="kt_docs_repeater_basic" class="mb-6">
                                <!--begin::Form group-->
                                <div class="form-group">
                                    <div data-repeater-list="kt_docs_repeater_basic">
                                        @foreach ($tangibles as $tangible)
                                        <div data-repeater-item>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Category:</label>
                                                    <select name="category" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} class="form-control category-select" id="category">
                                                        <option value=""></option>
                                                        <option {{ $tangible->category === 'Peningkatan Penjualan' ? 'selected' : '' }}>Peningkatan Penjualan</option>
                                                        <option {{ $tangible->category === 'Peningkatan kualitas secara keseluruhan' ? 'selected' : '' }}>Peningkatan kualitas secara keseluruhan</option>
                                                        <option {{ $tangible->category === 'Peningkatan daya saing' ? 'selected' : '' }}>Peningkatan daya saing</option>
                                                        <option {{ $tangible->category === 'Peningkatan produktifitas per staf' ? 'selected' : '' }}>Peningkatan produktifitas per staf</option>
                                                        <option {{ $tangible->category === 'Peningkatan profitabilitas' ? 'selected' : '' }}>Peningkatan profitabilitas</option>
                                                        <option {{ $tangible->category === 'Meningkatkan kepuasan pelanggan' ? 'selected' : '' }}>Meningkatkan kepuasan pelanggan</option>
                                                        <option {{ $tangible->category === 'Peningkatan hubungan personel' ? 'selected' : '' }}>Peningkatan hubungan personel</option>
                                                        <option {{ $tangible->category === 'Catatan keselamatan yang di tingkatkan' ? 'selected' : '' }}>Catatan keselamatan yang di tingkatkan</option>
                                                        <option {{ $tangible->category === 'Kepatuhan terhadap peraturan' ? 'selected' : '' }}>Kepatuhan terhadap peraturan</option>
                                                        <option {{ $tangible->category === 'Memperluas jangkauan tugas pekerja' ? 'selected' : '' }}>Memperluas jangkauan tugas pekerja</option>
                                                        <option {{ $tangible->category === 'Memenuhi kekurangan tenaga kerja yang berkualifikasi' ? 'selected' : '' }}>Memenuhi kekurangan tenaga kerja yang berkualifikasi</option>
                                                        <option {{ $tangible->category === 'Implementasi ide baru' ? 'selected' : '' }}>Implementasi ide baru</option>
                                                        <option {{ strpos($tangible->category, 'Lainnya:') === 0 ? 'selected' : '' }}>Lainnya</option>
                                                    </select>
                                                    <div class="mt-2 other-category-container" style="{{ strpos($tangible->category, 'Lainnya:') === 0 ? '' : 'display: none;' }}">
                                                        <input type="text" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} name="other_category" placeholder="Kategori lainnya..." 
                                                               class="form-control other-category-input" 
                                                               value="{{ strpos($tangible->category, 'Lainnya:') === 0 ? substr($tangible->category, 8) : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Cost:</label>
                                                    <input type="text" name="cost" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} class="form-control mb-2 mb-md-0" placeholder="Total (Rp.)" value="{{ $tangible->cost }}" />
                                                </div>
                                                <div class="col-md-4">
                                                    {{-- <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                        Delete
                                                    </a> --}}
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if(count($tangibles) == 0)
                                        <div data-repeater-item>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Category:</label>
                                                    <select name="category" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} class="form-control category-select" id="category">
                                                        <option value=""></option>
                                                        <option>Peningkatan Penjualan</option>
                                                        <option>Peningkatan kualitas secara keseluruhan</option>
                                                        <option>Peningkatan daya saing</option>
                                                        <option>Peningkatan produktifitas per staf</option>
                                                        <option>Peningkatan profitabilitas</option>
                                                        <option>Meningkatkan kepuasan pelanggan</option>
                                                        <option>Peningkatan hubungan personel</option>
                                                        <option>Catatan keselamatan yang di tingkatkan</option>
                                                        <option>Kepatuhan terhadap peraturan</option>
                                                        <option>Memperluas jangkauan tugas pekerja</option>
                                                        <option>Memenuhi kekurangan tenaga kerja yang berkualifikasi</option>
                                                        <option>Implementasi ide baru</option>
                                                        <option>Lainnya</option>
                                                    </select>
                                                    <div class="mt-2 other-category-container">
                                                        <input type="text" name="other_category" placeholder="Kategori lainnya..." 
                                                               class="form-control other-category-input" 
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Cost:</label>
                                                    <input type="text" name="cost" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} class="form-control mb-2 mb-md-0" placeholder="Total (Rp.)" />
                                                </div>
                                                <div class="col-md-4">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!--end::Form group-->
    
                                <!--begin::Form group-->
                                <div class="form-group mt-5">
                                    @if(auth()->user()->role_id == 6)
                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                        <i class="ki-duotone ki-plus fs-3"></i>
                                        Add
                                    </a>
                                    @endif
                                </div>
                                <!--end::Form group-->
                            </div>
                            @if(auth()->user()->role_id == 6)
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            @endif
                        </div>
                        <!--end::Repeater-->


                    </form>
                    @endif
                </div>
            </div>

            
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="scorring_lv_4">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="">Penilaian Lv 4 {{ $moduleName }}</h5>

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
                                <textarea name="" id="url-lv-4" cols="40" rows="1" class="w-100">{{ $urlLv4 }}</textarea>
                            </div>
                            <button type="button" class="btn btn-primary px-6 align-self-center text-nowrap" id="copy-button" 
                                    onclick="copyToClipboard('4')">
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
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('resources/js/scoring-lv4.js') }}"></script>

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

        function setupCategorySelects() {
            $('.category-select').on('change', function() {
                const otherInput = $(this).closest('.form-group').find('.other-category-container');
                
                if ($(this).val() === 'Lainnya') {
                    otherInput.slideDown();
                } else {
                    otherInput.slideUp();
                }
            });
            
            // Initialize on page load
            $('.category-select').each(function() {
                if ($(this).val() === 'Lainnya') {
                    $(this).closest('.form-group').find('.other-category-container').show();
                }
            });
        }

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            console.log('loaded')
            setupCategorySelects();

            // Handle select/deselect all employees
            $('.select-all-employees').on('click', function(e) {
                e.preventDefault();
                $('.employee-checkbox:not(:disabled)').prop('checked', true);
            });
            
            $('.deselect-all-employees').on('click', function(e) {
                e.preventDefault();
                $('.employee-checkbox:not(:disabled)').prop('checked', false);
            });

            $('select[name="area_id"]').on('change', function() {
                var diklatId = $(this).val();
                var url = "{{ route('.scorring.lv4.{id}', $diklat->id) }}";
                if (diklatId) {
                    url += '?area_id=' + diklatId;
                }
                window.location.href = url;
            });

            $('#kt_docs_repeater_basic').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    // $(this).find()
                    Inputmask("Rp. 999.999.999,99", {
                        "numericInput": true
                    }).mask('[data-control="currency"]');
                    setupCategorySelects();
                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

            $('#form_scoring_lv4').on('submit', function(e) {
                e.preventDefault();
                
                // Collect employee IDs from checkboxes
                var employeeIds = [];
                $('.employee-checkbox:checked').each(function() {
                    employeeIds.push($(this).val());
                });
                
                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('area_id', '{{ request('area_id') }}');
                
                // Append employee IDs to form data
                if (employeeIds.length > 0) {
                    employeeIds.forEach(function(id) {
                        formData.append('employee_ids[]', id);
                    });
                }
                
                $.ajax({
                    url: "{{ route('scorring.lv4.{id}.store', $diklat->id) }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            Toast.fire({
                                icon: 'success',
                                title: response.success
                            });
                            
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong'
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON?.errors) {
                            Object.keys(xhr.responseJSON.errors).forEach(
                                key => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: xhr.responseJSON.errors[key][0]
                                    });
                                });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong'
                            });
                        }
                    }
                });
            });

            $('#form_tangible').on('submit', function(e) {
                e.preventDefault();
                // Process form data before submission
                $(this).find('.category-select').each(function() {
                    if ($(this).val() === 'Lainnya') {
                        const customValue = $(this).closest('.form-group').find('.other-category-input').val();
                        // Format the category as "Lainnya: [custom text]" so you can detect it on the server
                        if (customValue) {
                            $(this).val('Lainnya: ' + customValue);
                        }
                    }
                });
                
                const formData = $(this).serializeArray();

                console.log(formData);

                const formDataTang = formData.map((x) => {
                    if (x.name.includes('other_category') && x.value) {
                        return {
                            name: x.name.replace('other_category', 'category'),
                            value: `Lainnya: ${x.value}`
                        }
                    }
                    return x;
                })
                console.log('formDataTang', formDataTang)

                const formData2 = new FormData(this);
                formData2.append('tangible', formDataTang);
                formData2.append('_token', '{{ csrf_token() }}');
                formData2.append('area_id', '{{ request('area_id') }}');

                formDataTang.forEach(element => {
                    formData2.append(element.name, element.value);
                });

                $.ajax({
                    url: `${base_url}/scorring/lv4/{{ $diklat->id }}/store-tangible`,
                    type: 'POST',
                    data: formData2,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if (response.status === 200) {
                            Swal.fire({
                                text: response.success,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then((result) => {
                                // Reload the page to reflect the submitted data
                                location.reload();
                            });
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

            // Function to update employee counter
            function updateEmployeeCounter() {
                const checkedCount = $('.employee-checkbox:checked').length;
                $('.employee-counter').text(checkedCount + ' dipilih');
            }
            
            // Initialize counter
            updateEmployeeCounter();
            
            // Update counter when checkboxes change
            $('.employee-checkbox').on('change', function() {
                updateEmployeeCounter();
            });
            
            // Handle select/deselect all employees
            $('.select-all-employees').on('click', function(e) {
                e.preventDefault();
                $('.employee-checkbox:not(:disabled)').prop('checked', true);
                updateEmployeeCounter();
            });
            
            $('.deselect-all-employees').on('click', function(e) {
                e.preventDefault();
                $('.employee-checkbox:not(:disabled)').prop('checked', false);
                updateEmployeeCounter();
            });
        });
    </script>
@endsection
