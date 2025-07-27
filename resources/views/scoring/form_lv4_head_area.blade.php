@extends('layouts.main_scorring')

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
    
    /* Highlight required participant selection */
    .card-header.bg-light-primary h3 span.text-danger {
        font-size: 1.2em;
        vertical-align: middle;
    }
    
    /* Add an animation for the required indicators */
    @keyframes pulse {
        0% { opacity: 0.7; }
        50% { opacity: 1; }
        100% { opacity: 0.7; }
    }
    
    /* Apply the animation to required fields */
    .employee-checkbox-required-notice {
        animation: pulse 2s infinite;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div id="container-form" class="card card-flush w-md-650px overflow-auto py-5" style="height: 90vhss">
    <div class="card-body py-15 py-lg-20">
        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Result</h1>

        <div class="mb-3 form-group text-start">
            <label for="exampleFormControlInput1" class="required form-label">Bidang</label>
            <select name="area_id" required class="form-select"  data-placeholder="Pilih Bidang">
                <option></option>
                @foreach ($areas as $item)
                    <option value="{{ $item->id }}" {{ request('area_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        
        @if(request('area_id') && $participants->count() > 0)
        <div class="card bg-light mb-5 mt-5">
            <div class="card-header bg-light-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold text-primary m-0">
                        <i class="ki-duotone ki-profile-circle fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Pilih Peserta Pelatihan: <span class="text-danger">*</span>
                    </h3>
                    @if(!$scoreLv4)
                    <div>
                        <span class="badge badge-light-info employee-counter me-2">0 dipilih</span>
                        <button type="button" class="btn btn-sm btn-light-primary select-all-employees">Pilih Semua</button>
                        <button type="button" class="btn btn-sm btn-light-danger deselect-all-employees">Batal Pilih</button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body p-5">
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
                <div class="row mt-2">
                    <div class="col-12">
                        <small class="text-danger fw-bold employee-checkbox-required-notice">* Wajib pilih minimal satu peserta pelatihan</small>
                    </div>
                </div>
            </div>
        </div>
        
        @if($selectedEmployees && count($selectedEmployees) > 0)
        <div class="alert alert-custom alert-notice alert-light-success mb-5 text-start selected-employees-display">
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
                <div class="d-flex flex-wrap gap-2 mt-3 selected-employees-list">
                    @foreach($participants as $participant)
                        @if(in_array($participant->employee_id, $selectedEmployees))
                        <div class="d-flex align-items-center bg-light-primary rounded p-2 employee-item" data-id="{{ $participant->employee_id }}">
                            <div class="symbol symbol-25px me-2">
                                <div class="symbol-label bg-primary text-white fw-bold">
                                    {{ substr($participant->employee->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-bold">{{ $participant->employee->name }}</span>
                                <small class="d-block text-muted">{{ $participant->employee->nip }}</small>
                            </div>
                            @if(!$scoreLv4)
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger remove-employee" data-id="{{ $participant->employee_id }}">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @endif

        <div id="form-lv-4" class="{{ request('area_id') ? '' : 'd-none' }}">
            @if($scoreLv4)
            {{-- <div class="alert alert-custom alert-notice alert-light-warning mb-5">
                <div class="alert-icon">
                    <i class="ki-duotone ki-information-5 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                </div>
                <div class="alert-text">
                    <strong>Form dalam mode tampilan:</strong> Anda telah mengisi formulir ini sebelumnya dan tidak dapat melakukan perubahan.
                </div>
            </div> --}}
            @endif
            <table class="table table-bordered">
                <tr>
                    <td>1 = Tidak Berdampak</td>
                    <td>2 = Kurang Berdampak</td>
                    <td>3 = Cukup Berdampak</td>
                    <td>4 = Berdampak</td>
                    <td>5 = Sangat Berdampak</td>
                </tr>
            </table>
    
            <form id="form_scoring_lv4" class="" {{ $scoreLv4 ? 'disabled="disabled"' : '' }}>
                <div class="border rounded p-3 mb-6">
                    <table class="table w-100">
                        <tr>
                            <td>1.</td>
                            <td class="text-start">Apakah program pelatihan ini memberi dampak positif terhadap Unit Anda ?</td>
                            <td class="d-flex justify-content-end gap-3">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" required value="1" {{ $scoreLv4 && $scoreLv4->score_positive == '1' ? 'checked' : '' }} {{ $scoreLv4 ? 'disabled' : '' }} name="score_positive" id="score_positive_1"/>
                                    <label class="form-check-label" for="score_positive_1">1</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" required value="2" {{ $scoreLv4 && $scoreLv4->score_positive == '2' ? 'checked' : '' }} {{ $scoreLv4 ? 'disabled' : '' }} name="score_positive" id="score_positive_2"/>
                                    <label class="form-check-label" for="score_positive_2">2</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" required value="3" {{ $scoreLv4 && $scoreLv4->score_positive == '3' ? 'checked' : '' }} {{ $scoreLv4 ? 'disabled' : '' }} name="score_positive" id="score_positive_3"/>
                                    <label class="form-check-label" for="score_positive_3">3</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" required value="4" {{ $scoreLv4 && $scoreLv4->score_positive == '4' ? 'checked' : '' }} {{ $scoreLv4 ? 'disabled' : '' }} name="score_positive" id="score_positive_4"/>
                                    <label class="form-check-label" for="score_positive_4">4</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" required value="5" {{ $scoreLv4 && $scoreLv4->score_positive == '5' ? 'checked' : '' }} {{ $scoreLv4 ? 'disabled' : '' }} name="score_positive" id="score_positive_5"/>
                                    <label class="form-check-label" for="score_positive_5">5</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td colspan="2" class="text-start">
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
                                @endphp
                                <div class="row mt-3">
                                    @foreach ($impacts as $key => $impact)
                                    <div class="col-4 mt-3">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="impacts[]" 
                                                value="{{ $impact }}" 
                                                {{ $scoreLv4 && in_array($impact, $scoreLv4->impacts ?? []) ? 'checked' : '' }}
                                                {{ $scoreLv4 ? 'disabled' : '' }}
                                                id="impact_{{ $key+1 }}"/>
                                            <label class="form-check-label" for="impact_{{ $key+1 }}">
                                                {{ $impact }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="col-4 mt-3">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input lainnya-checkbox" type="checkbox" 
                                                {{ $scoreLv4 && (in_array('lainnya', $scoreLv4->impacts ?? []) || Str::contains(implode(',', $scoreLv4->impacts ?? []), 'lainnya:')) ? 'checked' : '' }}
                                                {{ $scoreLv4 ? 'disabled' : '' }}
                                                id="impact_8"/>
                                            <label class="form-check-label d-flex gap-3" for="impact_8">
                                                Lainnya
                                                @php
                                                    $lainnyaText = '';
                                                    if ($scoreLv4 && is_array($scoreLv4->impacts)) {
                                                        foreach ($scoreLv4->impacts as $impact) {
                                                            if (Str::startsWith($impact, 'lainnya:')) {
                                                                $lainnyaText = substr($impact, 8); // Remove 'lainnya:' prefix
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <input type="text" class="lainnya-input" 
                                                    {{ $scoreLv4 ? 'disabled' : '' }} 
                                                    value="{{ $lainnyaText }}">
                                                <input type="hidden" name="impacts[]" class="lainnya-hidden-input" 
                                                    value="{{ $lainnyaText ? 'lainnya:'.$lainnyaText : '' }}">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="bg-primary p-3 rounded">
                    <h3 class="fw-bold mb-0 text-white">Tangible Benefit</h3>
                </div>
                <!--begin::Repeater-->
                <div class="p-3">
                    <div id="kt_docs_repeater_basic" class="mb-6">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div data-repeater-list="kt_docs_repeater_basic">
                                @if(count($tangibles) > 0)
                                    @foreach ($tangibles as $tangible)
                                    <div data-repeater-item>
                                        <div class="form-group row">
                                            <div class="col-md-5">
                                                <label class="form-label">Category:</label>
                                                <select name="category" class="form-control category-select" id="category" {{ $scoreLv4 ? 'disabled' : '' }}>
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
                                                    <input type="text" name="other_category" placeholder="Kategori lainnya..." 
                                                        class="form-control other-category-input" 
                                                        {{ $scoreLv4 ? 'disabled' : '' }}
                                                        value="{{ strpos($tangible->category, 'Lainnya:') === 0 ? substr($tangible->category, 8) : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Cost:</label>
                                                <input type="text" name="cost" class="form-control mb-2 mb-md-0" placeholder="Total (Rp.)" data-control="currency" value="{{ $tangible->cost }}" {{ $scoreLv4 ? 'disabled' : '' }} />
                                            </div>
                                            <div class="col-md-3">
                                                @if(!$scoreLv4)
                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    Delete
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <div data-repeater-item>
                                    <div class="form-group row">
                                        <div class="col-md-5">
                                            <label class="form-label">Category:</label>
                                            <select name="category" class="form-control category-select" id="category" {{ $scoreLv4 ? 'disabled' : '' }}>
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
                                            <div class="mt-2 other-category-container" style="display: none;">
                                                <input type="text" name="other_category" placeholder="Kategori lainnya..." 
                                                       class="form-control other-category-input" 
                                                       {{ $scoreLv4 ? 'disabled' : '' }}
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Cost:</label>
                                            <input type="text" name="cost" class="form-control mb-2 mb-md-0" placeholder="Total (Rp.)" {{ $scoreLv4 ? 'disabled' : '' }} />
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8 {{ $scoreLv4 ? 'd-none' : '' }}">
                                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
                                @endif
                            </div>
                        </div>

                        <!--begin::Form group-->
                        <div class="form-group mt-5">
                            @if(!$scoreLv4)
                            <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add
                            </a>
                            @endif
                        </div>
                        <!--end::Form group-->
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    @if(!$scoreLv4)
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-check-circle fs-2 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Kirim Penilaian
                    </button>
                    @else
                    <div class="alert alert-custom alert-light-success py-2 px-5">
                        <div class="alert-icon">
                            <i class="ki-duotone ki-shield-tick fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="alert-text fw-bold">
                            Penilaian sudah disubmit sebelumnya
                        </div>
                    </div>
                    @endif
                </div>
            </form>
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
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('resources/js/scoring-lv4.js') }}"></script>
    <script>
        function setupCategorySelects() {
            $('.category-select').on('change', function() {
                const otherInput = $(this).closest('.form-group').find('.other-category-container');
                
                if ($(this).val() === 'Lainnya') {
                    otherInput.slideDown();
                    // Focus on the other input for better UX
                    setTimeout(function() {
                        otherInput.find('input').focus();
                    }, 300);
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

        // Function to handle lainnya (other) option
        function setupLainnyaCheckbox() {
            // Initial setup based on checkbox state
            const lainnyaCheckbox = $('.lainnya-checkbox');
            const lainnyaInput = $('.lainnya-input');
            const lainnyaHiddenInput = $('.lainnya-hidden-input');
            
            // Set initial visibility based on checkbox
            if (lainnyaCheckbox.is(':checked')) {
                lainnyaInput.prop('disabled', {{ $scoreLv4 ? 'true' : 'false' }});
            } else {
                lainnyaInput.prop('disabled', true);
                lainnyaHiddenInput.val('');
            }
            
            // Handle checkbox change
            lainnyaCheckbox.on('change', function() {
                if ($(this).is(':checked')) {
                    lainnyaInput.prop('disabled', false).focus();
                    const currentValue = lainnyaInput.val();
                    lainnyaHiddenInput.val('lainnya:' + currentValue);
                } else {
                    lainnyaInput.prop('disabled', true);
                    lainnyaHiddenInput.val('');
                }
            });
            
            // Handle text input change
            lainnyaInput.on('input', function() {
                if (lainnyaCheckbox.is(':checked')) {
                    const inputValue = $(this).val();
                    lainnyaHiddenInput.val('lainnya:' + inputValue);
                    console.log('Lainnya input changed:', inputValue, 'Hidden value:', 'lainnya:' + inputValue);
                }
            });
        }

        KTUtil.onDOMContentLoaded(function() {
            console.log('loaded');
            setupCategorySelects();
            setupLainnyaCheckbox();
            
            // Define base URL for AJAX requests
            const base_url = '{{ url("") }}';

            // Initialize UI based on URL parameters
            if (new URLSearchParams(window.location.search).get('area_id')) {
                $('#container-form').css('height', 'auto');
            }
            
            // Initialize currency masks for existing fields
            Inputmask("Rp. 999.999.999,99", {
                "numericInput": true
            }).mask($('[data-control="currency"]'));

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
                var areaId = $(this).val();
                if (areaId) {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Memuat data...',
                        html: 'Mengambil data peserta pelatihan',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Refresh the page with the selected area ID
                    var currentUrl = window.location.href;
                    var baseUrl = currentUrl.split('?')[0];
                    window.location.href = baseUrl + '?area_id=' + areaId;
                }
            });

            $('#kt_docs_repeater_basic').repeater({
                initEmpty: {{ count($tangibles) == 0 ? 'true' : 'false' }},

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    // Initialize currency mask on new items
                    Inputmask("Rp. 999.999.999,99", {
                        "numericInput": true
                    }).mask($(this).find('[data-control="currency"]'));

                    // Setup category selects for new items
                    setupCategorySelects();

                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

            $('#form_scoring_lv4').on('submit', function(e) {
                e.preventDefault();

                // Immediately check for employee selection before anything else
                var employeeChecked = $('.employee-checkbox:checked').length > 0;
                if (!employeeChecked) {
                    Swal.fire({
                        title: "Peserta Tidak Dipilih",
                        text: "Silakan pilih minimal satu peserta pelatihan",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    $('html, body').animate({
                        scrollTop: $('.employee-checkbox').first().offset().top - 100
                    }, 500);
                    return false;
                }

                // Check if form has already been submitted (based on scoreLv4 variable in backend)
                if ({{ $scoreLv4 ? 'true' : 'false' }}) {
                    Swal.fire({
                        text: "Anda telah mengisi formulir ini sebelumnya. Tidak dapat mengirimkan kembali.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return false;
                }
                
                // Check if at least one employee is selected (required)
                var employeeChecked = $('.employee-checkbox:checked').length > 0;
                if (!employeeChecked) {
                    Swal.fire({
                        text: "Silakan pilih minimal satu peserta pelatihan",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    $('html, body').animate({
                        scrollTop: $('.employee-checkbox').first().offset().top - 100
                    }, 500);
                    return false;
                }

                // Process category selects for "Other" option
                console.log('Starting to process category selects');
                $(this).find('[data-repeater-item]').each(function(index) {
                    const categorySelect = $(this).find('.category-select');
                    const otherCategoryInput = $(this).find('.other-category-input');
                    
                    console.log(`Item ${index}:`, {
                        categorySelectValue: categorySelect.val(),
                        otherCategoryInputValue: otherCategoryInput.val(),
                        otherCategoryInputVisible: otherCategoryInput.is(':visible')
                    });
                    
                    if (categorySelect.val() === 'Lainnya') {
                        // Get the other category value, default to empty string if not provided
                        const otherValue = otherCategoryInput.val() ? otherCategoryInput.val().trim() : '';
                        const combinedValue = otherValue ? 'Lainnya: ' + otherValue : 'Lainnya';
                        
                        // Get the proper name for the repeater field
                        // The structure is kt_docs_repeater_basic[index][category]
                        const properName = `kt_docs_repeater_basic[${index}][category]`;
                        
                        // Create a hidden input that will be sent with the form with the proper name
                        const hiddenInput = $('<input>', {
                            type: 'hidden',
                            name: properName,
                            value: combinedValue
                        });
                        
                        // Disable the original select to prevent it from being submitted with an empty value
                        categorySelect.prop('disabled', true);
                        
                        // Append the hidden input to the form
                        $(this).append(hiddenInput);
                        
                        // Log for debugging
                        console.log('Processed "Other" category:', {
                            originalSelect: categorySelect.attr('name'),
                            indexInRepeater: index,
                            properName: properName,
                            combinedValue: combinedValue,
                            originalValue: otherValue
                        });
                    }
                });
                
                // Process cost inputs to ensure proper numeric format for validation
                $(this).find('[data-repeater-item]').each(function(index) {
                    const costInput = $(this).find('input[name="cost"]');
                    
                    if (costInput.length) {
                        // Get the raw value with currency format
                        const rawValue = costInput.val();
                        
                        // Convert from "Rp. 999.999.999,99" format to a clean number
                        // Remove currency symbol, thousand separators, and convert comma to dot for decimal
                        let cleanValue = rawValue.replace(/[^\d,]/g, '').replace(',', '.');
                        
                        // Get the proper name for the repeater field cost
                        const properName = `kt_docs_repeater_basic[${index}][cost]`;
                        
                        // Create a hidden input with the numeric value for validation
                        const numericCostInput = $('<input>', {
                            type: 'hidden',
                            name: properName,
                            value: cleanValue
                        });
                        
                        // Temporarily disable the original input to prevent it from being submitted
                        costInput.prop('disabled', true);
                        
                        // Append the hidden input
                        $(this).append(numericCostInput);
                        
                        // Log for debugging
                        console.log('Processed cost input:', {
                            original: rawValue,
                            cleaned: cleanValue,
                            properName: properName
                        });
                    }
                });
                
                // Collect employee IDs from checkboxes
                var employeeIds = [];
                $('.employee-checkbox:checked').each(function() {
                    employeeIds.push($(this).val());
                });
                
                // Log the selected employee IDs to console for debugging
                console.log('Selected Employee IDs:', employeeIds);
                
                const areaId = $('select[name="area_id"]').val();
                const formData = new FormData(this);
                formData.append('area_id', areaId);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Clear any existing employee_ids fields to prevent duplicates
                if (formData.getAll('employee_ids[]').length > 0) {
                    for (let i = formData.getAll('employee_ids[]').length - 1; i >= 0; i--) {
                        formData.delete('employee_ids[]');
                    }
                }
                
                // Append employee IDs to form data - ensure this is included
                employeeIds.forEach(function(id) {
                    formData.append('employee_ids[]', id);
                });

                $.ajax({
                    url: `${base_url}/form/lv4/{{ $diklat->id }}/submit`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            $('#container-form').addClass('d-none');
                            $('#container-thanks').removeClass('d-none');
                            Swal.fire({
                                text: response.success || "Penilaian berhasil disimpan",
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
                            
                            // Log success for debugging
                            console.log('Form submitted successfully:', response);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            Swal.fire({
                                text: xhr.responseJSON?.error || "Anda telah mengisi formulir ini sebelumnya",
                                icon: "warning",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-warning"
                                }
                            });
                            
                            // Reload the page after 2 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else if (xhr.responseJSON?.errors) {
                            // Handle specific validation errors
                            if (xhr.responseJSON.errors.employee_ids) {
                                // Employee selection errors
                                Swal.fire({
                                    title: "Peserta Wajib Dipilih",
                                    text: xhr.responseJSON.errors.employee_ids[0] || "Silakan pilih minimal satu peserta pelatihan",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                                $('html, body').animate({
                                    scrollTop: $('.employee-checkbox').first().offset().top - 100
                                }, 500);
                            } else if (xhr.responseJSON.errors['kt_docs_repeater_basic.0.category'] || 
                                       xhr.responseJSON.errors['kt_docs_repeater_basic.0.cost']) {
                                // Tangible benefits validation errors
                                Swal.fire({
                                    title: "Error Tangible Benefit",
                                    text: "Pastikan semua field kategori dan biaya telah diisi dengan benar",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                                $('html, body').animate({
                                    scrollTop: $('#kt_docs_repeater_basic').offset().top - 100
                                }, 500);
                                
                                // Log the validation errors for debugging
                                console.error('Tangible validation errors:', xhr.responseJSON.errors);
                            } else {
                                // Other validation errors
                                Object.keys(xhr.responseJSON.errors).forEach(
                                    key => {
                                        toastr.error(xhr.responseJSON
                                            .errors[key][0]);
                                    });
                            }
                        } else {
                            toastr.error("Terjadi kesalahan saat mengirim formulir");
                            // Log the complete error for debugging
                            console.error('Form submission error:', xhr);
                        }
                    }
                });
            });

            // Function to update employee counter
            function updateEmployeeCounter() {
                const checkedCount = $('.employee-checkbox:checked').length;
                $('.employee-counter').text(checkedCount + ' dipilih');
            }
            
            // Initialize counter on page load
            setTimeout(function() {
                updateEmployeeCounter();
            }, 100);
            
            // Update counter when checkboxes change
            $('.employee-checkbox').on('change', function() {
                updateEmployeeCounter();
            });
            
            // Handle select/deselect all employees
            $('.select-all-employees').on('click', function(e) {
                e.preventDefault();
                $('.employee-checkbox:not(:disabled)').prop('checked', true);
                updateEmployeeCounter();
                
                // Apply visual indicator to all selected employees
                $('.employee-checkbox:checked').closest('.form-check').addClass('bg-light-success rounded');
                $('.employee-checkbox:checked').siblings('label').find('.symbol-label').removeClass('bg-light-primary text-primary').addClass('bg-success text-white');
            });
            
            $('.deselect-all-employees').on('click', function(e) {
                e.preventDefault();
                $('.employee-checkbox:not(:disabled)').prop('checked', false);
                updateEmployeeCounter();
                
                // Remove visual indicator from all deselected employees
                $('.form-check').removeClass('bg-light-success rounded');
                $('.symbol-label.bg-success').removeClass('bg-success text-white').addClass('bg-light-primary text-primary');
            });
            
            // Add visual indicator when checkbox is clicked individually
            $('.employee-checkbox').on('change', function() {
                if($(this).is(':checked')) {
                    $(this).closest('.form-check').addClass('bg-light-success rounded');
                    $(this).siblings('label').find('.symbol-label').removeClass('bg-light-primary text-primary').addClass('bg-success text-white');
                } else {
                    $(this).closest('.form-check').removeClass('bg-light-success rounded');
                    $(this).siblings('label').find('.symbol-label').removeClass('bg-success text-white').addClass('bg-light-primary text-primary');
                }
            });
        });
    </script>
@endsection