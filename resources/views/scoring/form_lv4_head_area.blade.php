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
    
    /* Formula display styles */
    .component-summary {
        transition: all 0.3s ease;
    }
    
    .component-summary:hover {
        box-shadow: 0 0.5rem 1.5rem 0.5rem rgba(0, 0, 0, 0.075);
    }
    
    .formula-display {
        line-height: 1.8;
    }
    
    .formula-display .badge {
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div id="container-form" class="card card-flush w-md-650px overflow-auto py-5" style="height: 90vhss">
    <div class="card-body py-15 py-lg-20">
        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">{{ $diklat ? $diklat->name : '' }} - Result</h1>
         <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#guide">
                                Guide
                            </button>
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
                <!--begin::Hierarchical 3-level input-->
                <div class="p-3">
                    <div id="tangible-benefit-container" class="mb-6">
                        <!-- Level 1: Category Selection -->
                        <div id="level1-categories" class="form-group">
                            @if(count($tangibles) > 0)
                                @foreach ($tangibles as $key => $tangible)
                                <div class="category-item mb-5 border rounded p-4">
                                    <h5 class="mb-3">Category {{ $key + 1 }}</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-9">
                                            <label class="form-label">Category:</label>
                                            <select name="kt_docs_repeater_basic[{{ $key }}][category]" class="form-control category-select level1-select" {{ $scoreLv4 ? 'disabled' : '' }}>
                                                <option value=""></option>
                                                <option {{ $tangible->category === 'Penghematan Biaya Bahan' ? 'selected' : '' }}>Penghematan Biaya Bahan</option>
                                                <option {{ $tangible->category === 'Pengurangan Biaya Project' ? 'selected' : '' }}>Pengurangan Biaya Project</option>
                                                <option {{ $tangible->category === 'Penghematan Waktu' ? 'selected' : '' }}>Penghematan Waktu</option>
                                                <option {{ $tangible->category === 'Penurunan Biaya Pembelian' ? 'selected' : '' }}>Penurunan Biaya Pembelian</option>
                                                <option {{ strpos($tangible->category, 'Lainnya:') === 0 ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <div class="mt-2 other-category-container" style="{{ strpos($tangible->category, 'Lainnya:') === 0 ? '' : 'display: none;' }}">
                                                <input type="text" name="kt_docs_repeater_basic[{{ $key }}][other_category]" placeholder="Kategori lainnya..." 
                                                    class="form-control other-category-input" 
                                                    {{ $scoreLv4 ? 'disabled' : '' }}
                                                    value="{{ strpos($tangible->category, 'Lainnya:') === 0 ? substr($tangible->category, 8) : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            @if(!$scoreLv4)
                                            <a href="javascript:;" class="btn btn-sm btn-light-danger mt-8 delete-category">
                                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                Delete
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Display total cost - this is hidden input that will be submitted -->
                                    <input type="hidden" name="kt_docs_repeater_basic[{{ $key }}][cost]" class="category-final-cost" value="{{ $tangible->cost }}" />
                                    
                                    <!-- Final calculated cost display -->
                                    <div class="d-flex justify-content-end my-3">
                                        <div class="bg-light-success px-4 py-2 rounded">
                                            <strong>Total Cost: <span class="category-cost-display">Rp. {{ number_format($tangible->cost, 0, ',', '.') }}</span></strong>
                                        </div>
                                    </div>
                                    
                                    <!-- Component display summary -->
                                    <div class="component-summary bg-light rounded p-3 mb-3">
                                        <strong class="d-block mb-2">
                                            <i class="ki-duotone ki-calculator fs-4 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Calculation Breakdown:
                                        </strong>
                                        <div class="formula-display text-muted">
                                            <!-- Component formula will be displayed here -->
                                        </div>
                                    </div>
                                    
                                    <!-- Level 2: Component Section -->
                                    <div class="level2-components">
                                        @if(isset($tangible->componentGroups) && count($tangible->componentGroups) > 0)
                                            @foreach($tangible->componentGroups as $componentName => $details)
                                                <div class="component-item border rounded p-3 mb-3">
                                                    <div class="component-header mb-2">
                                                        <h6 class="mb-2">Component: {{ $componentName }}</h6>
                                                        @if(!$scoreLv4)
                                                        <button type="button" class="btn btn-sm btn-light-danger delete-component" style="float: right;">
                                                            <i class="ki-duotone ki-trash fs-5"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="component-name-input mb-3">
                                                        <input type="text" class="form-control component-name" 
                                                               value="{{ $componentName }}" 
                                                               placeholder="Component name"
                                                               {{ $scoreLv4 ? 'disabled' : '' }} />
                                                    </div>
                                                    
                                                    <!-- Level 3: Input Items -->
                                                    <div class="level3-inputs">
                                                        @foreach($details as $index => $detail)
                                                        <div class="input-item mb-2">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <input type="text" class="form-control input-title" 
                                                                           value="{{ $detail->sub_component_name ?? 'Input ' . ($index + 1) }}" 
                                                                           placeholder="Input name"
                                                                           {{ $scoreLv4 ? 'disabled' : '' }} />
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="number" step="0.01" class="form-control input-price" 
                                                                           value="{{ $detail->price }}" 
                                                                           placeholder="0.00"
                                                                           {{ $scoreLv4 ? 'disabled' : '' }} />
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-select input-operator" {{ $scoreLv4 ? 'disabled' : '' }}>
                                                                        <option value="add" {{ $detail->operator === '+' ? 'selected' : '' }}>+ Add</option>
                                                                        <option value="subtract" {{ $detail->operator === '-' ? 'selected' : '' }}>- Subtract</option>
                                                                        <option value="multiply" {{ $detail->operator === '*' ? 'selected' : '' }}>× Multiply</option>
                                                                        <option value="divide" {{ $detail->operator === '/' ? 'selected' : '' }}>÷ Divide</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3 text-end">
                                                                    @if(!$scoreLv4)
                                                                    <button type="button" class="btn btn-sm btn-light-danger delete-input">
                                                                        <i class="ki-duotone ki-trash fs-5"></i> Delete
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    
                                                    <!-- Component total display -->
                                                    <div class="d-flex justify-content-end mt-3">
                                                        <div class="bg-light-info px-3 py-2 rounded">
                                                            <strong>Component Total: <span class="component-total">Rp. {{ isset($tangible->componentGroups[$componentName]->subtotal) ? number_format($tangible->componentGroups[$componentName]->subtotal, 0, ',', '.') : '0' }}</span></strong>
                                                        </div>
                                                    </div>
                                                    
                                                    @if(!$scoreLv4)
                                                    <!-- Add Input Button -->
                                                    <div class="text-center mt-3">
                                                        <button type="button" class="btn btn-sm btn-light-success add-input">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            Add Calculation Input
                                                        </button>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                        <!-- Empty state will be here when no components exist -->
                                    </div>

                                    <!-- Add Component Button -->
                                    @if(!$scoreLv4)
                                    <div class="text-center my-3">
                                        <button type="button" class="btn btn-sm btn-light-info add-component">
                                            <i class="ki-duotone ki-plus fs-3"></i>
                                            Add Component
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="category-item mb-5 border rounded p-4">
                                    <h5 class="mb-3">Category 1</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-9">
                                            <label class="form-label">Category:</label>
                                            <select name="kt_docs_repeater_basic[0][category]" class="form-control category-select level1-select" {{ $scoreLv4 ? 'disabled' : '' }}>
                                                <option value=""></option>
                                                <option>Penghematan Biaya Bahan</option>
                                                <option>Pengurangan Biaya Project</option>
                                                <option>Penghematan Waktu</option>
                                                <option>Penurunan Biaya Pembelian</option>
                                                <option>Lainnya</option>
                                            </select>
                                            <div class="mt-2 other-category-container" style="display: none;">
                                                <input type="text" name="kt_docs_repeater_basic[0][other_category]" placeholder="Kategori lainnya..." 
                                                    class="form-control other-category-input" 
                                                    {{ $scoreLv4 ? 'disabled' : '' }}
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            @if(!$scoreLv4)
                                            <a href="javascript:;" class="btn btn-sm btn-light-danger mt-8 delete-category">
                                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                Delete
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Display total cost - this is hidden input that will be submitted -->
                                    <input type="hidden" name="kt_docs_repeater_basic[0][cost]" class="category-final-cost" value="0" />
                                    
                                    <!-- Final calculated cost display -->
                                    <div class="d-flex justify-content-end my-3">
                                        <div class="bg-light-success px-4 py-2 rounded">
                                            <strong>Total Cost: <span class="category-cost-display">Rp. 0</span></strong>
                                        </div>
                                    </div>
                                    
                                    <!-- Component display summary -->
                                    <div class="component-summary bg-light rounded p-3 mb-3">
                                        <strong class="d-block mb-2">
                                            <i class="ki-duotone ki-calculator fs-4 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Calculation Breakdown:
                                        </strong>
                                        <div class="formula-display text-muted">
                                            No components added yet
                                        </div>
                                    </div>
                                    
                                    <!-- Level 2: Component Section -->
                                    <div class="level2-components">
                                        <!-- Empty state - Component content will be added by JavaScript -->
                                    </div>

                                    <!-- Add Component Button -->
                                    @if(!$scoreLv4)
                                    <div class="text-center my-3">
                                        <button type="button" class="btn btn-sm btn-light-info add-component">
                                            <i class="ki-duotone ki-plus fs-3"></i>
                                            Add Component
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Add Category Button -->
                        @if(!$scoreLv4)
                        <div class="text-center mt-5">
                            <button type="button" class="btn btn-sm btn-light-primary" id="add-category">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add Category
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                <!--end::Hierarchical 3-level input-->
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

<div class="modal fade" tabindex="-1" id="guide">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="">Referensi Input Tangible Benefits</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title align-items-start flex-column">
                    Penghematan Biaya Bahan
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Referensi:</strong></p>
                <ul>
                    <li>Komponen 1 = Penurunan Bahan x harga bahan x +add (input lain) = ________</li>
                    <li>Komponen 2 = Penurunan Bahan x harga bahan x +add (input lain) = ________</li>
                </ul>
                <p>+Add (Komponen lain jika ada)</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title align-items-start flex-column">
                    Pengurangan Biaya Project
                    <h6>
            </div>
            <div class="card-body">
                <p><strong>Referensi:</strong></p>
                <ul>
                    <li>Pemanfaatan sumber daya yang lebih efisien = Jumlah orang yang terlibat project x upah x +add (input lain) = ________</li>
                    <li>Proyek yang tepat waktu, sesuai anggaran dan cakupan = Jumlah tambahan anggaran/denda yang terabaikan x +add (input lain) = ________</li>
                    <li>Peningkatan dalam pelacakan proyek klien yang lebih baik = Biaya tambahan yang terabaikan x +add (input lain) = ________</li>
                </ul>
                <p>+Add (Komponen lain jika ada)</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title align-items-start flex-column">

                    Penghematan Waktu
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Referensi:</strong></p>
                <ul>
                    <li>Penghematan waktu operasi = Jam yang dihemat x gaji x +add (input lain) = ________</li>
                    <li>Penghematan waktu pengawasan = Jam yang dihemat x gaji x +add (input lain) = ________</li>
                </ul>
                <p>+Add (Komponen lain jika ada)</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title align-items-start flex-column">
                    Lainnya
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Referensi:</strong></p>
                <ul>
                    <li>Komponen 1 = (Faktor Pengali) x (Faktor Pengali) x +add (input lain) = ________</li>
                    <li>Komponen 2 = (Faktor Pengali) x (Faktor Pengali) x +add (input lain) = ________</li>
                </ul>
                <p>+Add (Komponen lain jika ada)</p>
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
    <script src="{{ asset('resources/js/tangible-calculation.js') }}"></script>
    <script>
        // Setup the category select handling for "Lainnya" option
        function setupCategorySelects() {
            $('.category-select').off('change').on('change', function() {
                const categoryItem = $(this).closest('.category-item');
                const componentsContainer = categoryItem.find('.level2-components');
                const selected = $(this).val();
                componentsContainer.empty();

                // Show/hide other-category input
                if (selected === 'Lainnya') {
                    categoryItem.find('.other-category-container').show();
                } else {
                    categoryItem.find('.other-category-container').hide();
                }

                // Mapping kategori ke komponen dan sub-komponen
                const mapping = {
                    'Penghematan Biaya Bahan': [
                        {
                            name: 'Komponen 1',
                            subs: ['Penurunan bahan', 'Harga Bahan']
                        }
                    ],
                    'Pengurangan Biaya Project': [
                        {
                            name: 'Pemanfaatan sumber daya yang lebih efisien',
                            subs: ['Jumlah orang yang terlibat project', 'Upah']
                        },
                        {
                            name: 'Proyek yang tepat waktu sesuai anggaran dan cakupan',
                            subs: ['Jumlah tambahan anggaran', 'Denda yang terabaikan']
                        },
                        {
                            name: 'Peningkatan dalam pelacakan proyek klien yang lebih baik',
                            subs: ['Biaya tambahan yang terabaikan']
                        }
                    ],
                    'Penghematan Waktu': [
                        {
                            name: 'Penghematan waktu operasi',
                            subs: ['Jam yang dihemat', 'Gaji']
                        },
                        {
                            name: 'Penghematan waktu pengawasan',
                            subs: ['Jam yang dihemat', 'Gaji']
                        }
                    ],
                    'Penurunan Biaya Pembelian': [
                        {
                            name: 'Komponen 1',
                            subs: ['Jumlah Pembelian', 'Nominal penghematan']
                        }
                    ]
                };

                // if (mapping[selected]) {
                //     mapping[selected].forEach(function(comp, compIdx) {
                //         // Tambahkan komponen
                //         const categoryIndex = $('.category-item').index(categoryItem);
                //         const componentIndex = compIdx;
                //         const componentHtml = `
                //         <div class="component-item mt-4 mb-3 border border-dashed p-3 bg-light-primary">
                //             <div class="row mb-3">
                //                 <div class="col-md-9">
                //                     <label class="form-label">Component Name:</label>
                //                     <input type="text" class="form-control component-name" value="${comp.name}" name="component_name[${categoryIndex}][${componentIndex}]" />
                //                 </div>
                //                 <div class="col-md-3 text-end">
                //                     <button type="button" class="btn btn-sm btn-light-danger mt-8 delete-component" onclick="deleteComponentNew(this)">
                //                         <i class="ki-duotone ki-trash fs-5"></i> Delete
                //                     </button>
                //                 </div>
                //             </div>
                //             <div class="level3-inputs">
                //             </div>
                //             <div class="text-center my-3">
                //                 <button type="button" class="btn btn-sm btn-light-warning add-input">
                //                     <i class="ki-duotone ki-plus fs-3"></i> Add Input
                //                 </button>
                //             </div>
                //             <div class="d-flex justify-content-end">
                //                 <div class="bg-light-info px-3 py-2 rounded">
                //                     Component Total: <strong class="component-total">Rp. 0</strong>
                //                 </div>
                //             </div>
                //         </div>`;
                //         componentsContainer.append(componentHtml);
                //         // Tambahkan sub-komponen
                //         const newComponent = componentsContainer.children('.component-item:last');
                //         const inputsContainer = newComponent.find('.level3-inputs');
                //         comp.subs.forEach(function(sub, subIdx) {
                //             const inputHtml = `
                //             <div class="input-item mb-3 pt-3 border-top">
                //                 <div class="row align-items-center">
                //                     <div class="col-md-3">
                //                         <label class="form-label">Title:</label>
                //                         <input type="text" class="form-control input-title" value="${sub}" name="input_title[${categoryIndex}][${componentIndex}][${subIdx}]" />
                //                     </div>
                //                     <div class="col-md-3">
                //                         <label class="form-label">Price:</label>
                //                         <input type="number" step="0.01" class="form-control input-price" placeholder="0.00" 
                //                                name="input_price[${categoryIndex}][${componentIndex}][${subIdx}]" />
                //                     </div>
                //                     <div class="col-md-3">
                //                         <label class="form-label">Operator:</label>
                //                         <select class="form-select input-operator" name="input_operator[${categoryIndex}][${componentIndex}][${subIdx}]">
                //                             <option value="add">+ Add</option>
                //                             <option value="subtract">- Subtract</option>
                //                             <option value="multiply">× Multiply</option>
                //                             <option value="divide">÷ Divide</option>
                //                         </select>
                //                     </div>
                //                     <div class="col-md-3 text-end">
                //                         <button type="button" class="btn btn-sm btn-light-danger delete-input">
                //                             <i class="ki-duotone ki-trash fs-5"></i> Delete
                //                         </button>
                //                     </div>
                //                 </div>
                //             </div>`;
                //             inputsContainer.append(inputHtml);
                //         });
                //         // Initialize decimal inputs with Inputmask
                //         if (typeof Inputmask !== 'undefined') {
                //             Inputmask("999.999.999,99", {
                //                 "numericInput": true
                //             }).mask(newComponent.find('.decimal-input'));

                //             // Add event handler for price changes and operator changes
                //             newComponent.find('.decimal-input, .input-operator').on('input change', function() {
                //                 recalculateComponentTotal($(this).closest('.component-item'));
                //                 recalculateCategoryTotal(categoryItem);
                //                 updateFormulaDisplay(categoryItem);
                //             });
                //         }
                //     });
                // } else if(selected === 'Lainnya') {
                //     // Biarkan user menambah manual
                // }
                recalculateCategoryTotal(categoryItem);
                updateFormulaDisplay(categoryItem);
            });
            // Initialize on page load
            $('.category-select').each(function() {
                const categoryItem = $(this).closest('.category-item');
                if ($(this).val() === 'Lainnya') {
                    categoryItem.find('.other-category-container').show();
                } else {
                    categoryItem.find('.other-category-container').hide();
                }
            });
        }
        
        // Function to add a new component (Level 2)
        function addComponent(button) {
            const categoryItem = $(button).closest('.category-item');
            const componentsContainer = categoryItem.find('.level2-components');
            const categoryIndex = $('.category-item').index(categoryItem);
            const componentIndex = componentsContainer.children('.component-item').length;
            
            const componentHtml = `
                <div class="component-item mt-4 mb-3 border border-dashed p-3 bg-light-primary">
                    <div class="row mb-3">
                        <div class="col-md-9">
                            <label class="form-label">Component Name:</label>
                            <input type="text" class="form-control component-name" placeholder="Enter component name" 
                                  name="component_name[${categoryIndex}][${componentIndex}]" />
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-sm btn-light-danger mt-8 delete-component">
                                <i class="ki-duotone ki-trash fs-5"></i> Delete
                            </button>
                        </div>
                    </div>
                    
                    <div class="level3-inputs">
                        <!-- Calculation inputs will be added here -->
                    </div>
                    
                    <div class="text-center my-3">
                        <button type="button" class="btn btn-sm btn-light-warning add-input">
                            <i class="ki-duotone ki-plus fs-3"></i> Add Input
                        </button>
                    </div>
                    
                    <!-- Component total (calculated from all inputs) -->
                    <div class="d-flex justify-content-end">
                        <div class="bg-light-info px-3 py-2 rounded">
                            Component Total: <strong class="component-total">Rp. 0</strong>
                        </div>
                    </div>
                </div>
            `;
            
            componentsContainer.append(componentHtml);
            
            // Attach event handlers for the new component
            const newComponent = componentsContainer.children('.component-item:last');
            
            // Add component name change handler to update formula display
            newComponent.find('.component-name').on('change', function() {
                recalculateCategoryTotal(categoryItem);
            });
            
            // Add input button handler
            newComponent.find('.add-input').on('click', function() {
                addInput($(this));
            });
            
            // Delete component button handler
            newComponent.find('.delete-component').on('click', function() {
                deleteComponent($(this));
            });
            
            // Add the first input automatically
            addInput(newComponent.find('.add-input'));
            
            // Update total calculation and formula display
            recalculateAllTotals();
        }
        
        // Function to add a new calculation input (Level 3)
        function addInput(button) {
            const componentItem = $(button).closest('.component-item');
            const inputsContainer = componentItem.find('.level3-inputs');
            const categoryItem = componentItem.closest('.category-item');
            const categoryIndex = $('.category-item').index(categoryItem);
            const componentIndex = categoryItem.find('.level2-components').children('.component-item').index(componentItem);
            const inputIndex = inputsContainer.children('.input-item').length;
            
            const inputHtml = `
                <div class="input-item mb-3 pt-3 border-top">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="form-label">Title:</label>
                            <input type="text" class="form-control input-title" placeholder="Title" 
                                  name="input_title[${categoryIndex}][${componentIndex}][${inputIndex}]" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price:</label>
                            <input type="number" step="0.01" class="form-control input-price" placeholder="0.00" 
                                   name="input_price[${categoryIndex}][${componentIndex}][${inputIndex}]" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Operator:</label>
                            <select class="form-select input-operator" name="input_operator[${categoryIndex}][${componentIndex}][${inputIndex}]">
                                <option value="add">+ Add</option>
                                <option value="subtract">- Subtract</option>
                                <option value="multiply">× Multiply</option>
                                <option value="divide">÷ Divide</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-sm btn-light-danger mt-8 delete-input">
                                <i class="ki-duotone ki-trash fs-5"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            inputsContainer.append(inputHtml);
            
            // Initialize decimal number input
            const priceInput = inputsContainer.find('.input-item:last .input-price');
            priceInput.on('input', function() {
                // Ensure the value is a valid decimal
                if (this.value && !isNaN(this.value)) {
                    this.value = parseFloat(this.value).toFixed(3);
                }
            });
            
            // Attach event handlers
            const newInput = inputsContainer.find('.input-item:last');
            
            // Delete input button handler
            newInput.find('.delete-input').on('click', function() {
                deleteInput($(this));
            });
            
            // Add input title change handler to update formula display
            newInput.find('.input-title').on('keyup change blur', function() {
                recalculateComponentTotal(componentItem);
                recalculateCategoryTotal(categoryItem);
            });
            
            // Input price change handler for recalculation
            newInput.find('.input-price').on('keyup change blur', function() {
                recalculateComponentTotal(componentItem);
                recalculateCategoryTotal(categoryItem);
            });
            
            // Input operator change handler for recalculation
            newInput.find('.input-operator').on('change', function() {
                recalculateComponentTotal(componentItem);
                recalculateCategoryTotal(categoryItem);
            });
            
            // If this is not the first input, focus on the input title for better UX
            if (inputIndex > 0) {
                setTimeout(() => {
                    newInput.find('.input-title').focus();
                }, 100);
            }
            
            // Trigger recalculation
            recalculateComponentTotal(componentItem);
            recalculateCategoryTotal(categoryItem);
        }
        
        // Function to delete a component
        function deleteComponent(button) {
            const componentItem = $(button).closest('.component-item');
            const categoryItem = componentItem.closest('.category-item');
            
            componentItem.remove();
            recalculateCategoryTotal(categoryItem);
            
            // If no components left, update formula display with default message
            if (categoryItem.find('.component-item').length === 0) {
                categoryItem.find('.formula-display').html('No components added yet');
            }
        }

        function deleteComponentNew(button) {
            if (confirm('Are you sure you want to delete this component?')) {
                const categoryItem = $(button).closest('.category-item');
                $(button).closest('.component-item').remove();
                recalculateCategoryTotal(categoryItem);
                updateFormulaDisplay(categoryItem);
            }
        }
        
        // Function to delete an input
        function deleteInput(button) {
            const inputItem = $(button).closest('.input-item');
            const componentItem = inputItem.closest('.component-item');
            const categoryItem = componentItem.closest('.category-item');
            
            inputItem.remove();
            recalculateComponentTotal(componentItem);
            recalculateCategoryTotal(categoryItem);
        }
        
        // Function to delete a category
        function deleteCategory(button) {
            const categoryItem = $(button).closest('.category-item');
            
            // Only delete if there's more than one category
            if ($('.category-item').length > 1) {
                categoryItem.remove();
                
                // Renumber the remaining categories
                $('#level1-categories .category-item').each(function(index) {
                    $(this).find('h5').text('Category ' + (index + 1));
                    
                    // Update all input names with new index
                    $(this).find('select.level1-select').attr('name', `kt_docs_repeater_basic[${index}][category]`);
                    $(this).find('.other-category-input').attr('name', `kt_docs_repeater_basic[${index}][other_category]`);
                    $(this).find('.category-final-cost').attr('name', `kt_docs_repeater_basic[${index}][cost]`);
                    $(this).find('.formula-display-input').attr('name', `kt_docs_repeater_basic[${index}][formula_display_html]`);
                });
            } else {
                // If it's the last category, just clear inputs
                categoryItem.find('.level2-components').empty();
                categoryItem.find('.category-final-cost').val(0);
                categoryItem.find('.category-cost-display').text('Rp. 0');
            }
        }
        
        // Calculate component total based on all inputs
        function recalculateComponentTotal(componentItem) {
            let total = 0;
            let isFirstInput = true;
            let inputCount = componentItem.find('.input-item').length;
            if (inputCount === 0) {
                componentItem.find('.component-total').text('Rp. 0.00');
                recalculateCategoryTotal(componentItem.closest('.category-item'));
                return 0;
            }
            componentItem.find('.input-item').each(function() {
                let price = parseFloat($(this).find('.input-price').val().replace(/,/g, '')) || 0;
                const operator = $(this).find('.input-operator').val();
                if (isFirstInput) {
                    total = price;
                    isFirstInput = false;
                } else {
                    if (operator === 'add') total += price;
                    else if (operator === 'subtract') total -= price;
                    else if (operator === 'multiply') total *= price;
                    else if (operator === 'divide') total = price !== 0 ? total / price : total;
                }
            });
            componentItem.find('.component-total').text('Rp. ' + formatNumber(total));
            recalculateCategoryTotal(componentItem.closest('.category-item'));
            return total;
        }
        
        // Calculate category total based on all components and update formula display
        function recalculateCategoryTotal(categoryItem) {
            let total = 0;
            let formulaHtml = '';
            
            // If there are no components, show default message
            if (categoryItem.find('.component-item').length === 0) {
                formulaHtml = 'No components added yet';
            } else {
                // Build the formula display for all components
                categoryItem.find('.component-item').each(function(index) {
                    const componentName = $(this).find('.component-name').val() || 'Component ' + (index + 1);
                    const componentTotal = parseFloat($(this).find('.component-total').text().replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
                    
                    // Add component name and amount to formula
                    let componentFormula = '<div class="mb-1"><span class="text-primary fw-bold">' + componentName + '</span></div>';
                    
                    // Add the formula breakdown for this component
                    let inputFormula = '<div class="ps-3 mb-2">';
                    let calculationFormula = '';
                    let isFirstInput = true;
                    
                    $(this).find('.input-item').each(function(inputIndex) {
                        const inputTitle = $(this).find('.input-title').val() || 'Input ' + (inputIndex + 1);
                        const inputPrice = parseFloat($(this).find('.input-price').val().replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
                        const operator = $(this).find('.input-operator').val();
                        
                        if (isFirstInput) {
                            calculationFormula += '<span class="badge badge-light-primary">' + inputTitle + '</span> <span class="badge badge-light">Rp.' + formatNumber(inputPrice) + '</span>';
                            isFirstInput = false;
                        } else {
                            let operatorSymbol = '';
                            let operatorClass = '';
                            
                            switch(operator) {
                                case 'add': 
                                    operatorSymbol = ' + '; 
                                    operatorClass = 'text-success';
                                    break;
                                case 'subtract': 
                                    operatorSymbol = ' - '; 
                                    operatorClass = 'text-danger';
                                    break;
                                case 'multiply': 
                                    operatorSymbol = ' × '; 
                                    operatorClass = 'text-warning';
                                    break;
                                case 'divide': 
                                    operatorSymbol = ' ÷ '; 
                                    operatorClass = 'text-info';
                                    break;
                            }
                            
                            calculationFormula += ' <span class="' + operatorClass + ' fw-bold">' + operatorSymbol + '</span> <span class="badge badge-light-primary">' + inputTitle + '</span> <span class="badge badge-light">Rp.' + formatNumber(inputPrice) + '</span>';
                        }
                    });
                    
                    inputFormula += calculationFormula + ' = <span class="badge badge-light-success fw-bold">Rp.' + formatNumber(componentTotal) + '</span></div>';
                    
                    // Add to the overall formula
                    if (index > 0) {
                        formulaHtml += '<hr class="my-2">';
                    }
                    
                    formulaHtml += componentFormula + inputFormula;
                    
                    // Add to the total
                    total += componentTotal;
                });
                
                // Add final total as a summary if there are multiple components
                if (categoryItem.find('.component-item').length > 1) {
                    formulaHtml += '<hr class="my-2">';
                    formulaHtml += '<div class="d-flex justify-content-end"><span class="badge badge-primary fw-bold">Total: Rp.' + formatNumber(total) + '</span></div>';
                }
            }
            
            // Update the formula display
            categoryItem.find('.formula-display').html(formulaHtml);
            
            // Update the hidden input with the raw number
            categoryItem.find('.category-final-cost').val(total);
            
            // Store the formula display HTML in a hidden input for submission
            const categoryIndex = $('#level1-categories .category-item').index(categoryItem);
            if (!categoryItem.find('.formula-display-input').length) {
                categoryItem.append(`<input type="hidden" class="formula-display-input" name="kt_docs_repeater_basic[${categoryIndex}][formula_display_html]" value="">`);
            }
            categoryItem.find('.formula-display-input').val(formulaHtml);
            
            // Update the display with the formatted number
            categoryItem.find('.category-cost-display').text('Rp. ' + formatNumber(total));
            
            return total;
        }
        
        // Recalculate all totals in the form
        function recalculateAllTotals() {
            $('.category-item').each(function() {
                recalculateCategoryTotal($(this));
                // updateFormulaDisplay($(this));
            });
        }
        
        // Helper function to format numbers
        function formatNumber(num) {
            return num.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        
        // Function to load existing calculation data from saved calculation breakdown
        function loadCalculationData() {
            @if($tangibles && $tangibles->count() > 0)
                @foreach($tangibles as $index => $tangible)
                    @if($tangible->calculation_breakdown)
                        // If there's a saved calculation breakdown, display it
                        const categoryItem = $('.category-item').eq({{ $index }});
                        categoryItem.find('.formula-display').html(`{!! $tangible->calculation_breakdown !!}`);
                        
                        // Also add a hidden input for the display HTML
                        if (!categoryItem.find('.formula-display-input').length) {
                            categoryItem.append(`<input type="hidden" class="formula-display-input" name="kt_docs_repeater_basic[{{ $index }}][formula_display_html]" value="">`);
                        }
                        categoryItem.find('.formula-display-input').val(`{!! $tangible->calculation_breakdown !!}`);
                    @endif
                @endforeach
            @endif
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
            
            // Initialize formula display for existing data
            recalculateAllTotals();
            
            // For existing data, load calculation display from saved data
            loadCalculationData();
            
            // Define base URL for AJAX requests
            const base_url = '{{ url("") }}';

            // Initialize UI based on URL parameters
            if (new URLSearchParams(window.location.search).get('area_id')) {
                $('#container-form').css('height', 'auto');
            }
            
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
            
            // Initialize the 3-level hierarchical form
            
            // Add Category Button
            $('#add-category').on('click', function() {
                // Get the current number of categories
                const categoryCount = $('.category-item').length;
                
                // Create a new category with a new index
                const newCategoryHtml = `
                    <div class="category-item mb-5 border rounded p-4">
                        <h5 class="mb-3">Category ${categoryCount + 1 }</h5>
                        <div class="row mb-3">
                            <div class="col-md-9">
                                <label class="form-label">Category:</label>
                                <select name="kt_docs_repeater_basic[${categoryCount}][category]" class="form-control category-select level1-select">
                                    <option value=""></option>
                                    <option>Penghematan Biaya Bahan</option>
                                    <option>Pengurangan Biaya Project</option>
                                    <option>Penghematan Waktu</option>
                                    <option>Penurunan Biaya Pembelian</option>
                                    <option>Lainnya</option>
                                </select>
                                <div class="mt-2 other-category-container" style="display: none;">
                                    <input type="text" name="kt_docs_repeater_basic[${categoryCount}][other_category]" placeholder="Kategori lainnya..." 
                                        class="form-control other-category-input">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:;" class="btn btn-sm btn-light-danger mt-8 delete-category">
                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    Delete
                                </a>
                            </div>
                        </div>

                        <!-- Display total cost - this is hidden input that will be submitted -->
                        <input type="hidden" name="kt_docs_repeater_basic[${categoryCount}][cost]" class="category-final-cost" value="0" />
                        
                        <!-- Final calculated cost display -->
                        <div class="d-flex justify-content-end my-3">
                            <div class="bg-light-success px-4 py-2 rounded">
                                <strong>Total Cost: <span class="category-cost-display">Rp. 0</span></strong>
                            </div>
                        </div>
                        
                        <!-- Component display summary -->
                        <div class="component-summary bg-light rounded p-3 mb-3">
                            <strong class="d-block mb-2">
                                <i class="ki-duotone ki-calculator fs-4 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Calculation Breakdown:
                            </strong>
                            <div class="formula-display text-muted">
                                No components added yet
                            </div>
                        </div>
                        
                        <!-- Level 2: Component Section -->
                        <div class="level2-components">
                            <!-- Component content will be added by JavaScript -->
                        </div>

                        <!-- Add Component Button -->
                        <div class="text-center my-3">
                            <button type="button" class="btn btn-sm btn-light-info add-component">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add Component
                            </button>
                        </div>
                    </div>
                `;
                
                // Append the new category to the container
                $('#level1-categories').append(newCategoryHtml);
                
                // Initialize the new category
                const newCategory = $('.category-item:last');
                
                // Setup category select
                setupCategorySelects();
                
                // Add component button handler
                newCategory.find('.add-component').on('click', function() {
                    addComponent($(this));
                });
                
                // Delete category button handler
                newCategory.find('.delete-category').on('click', function() {
                    deleteCategory($(this));
                });
            });
            
            // Set up existing categories
            $('.category-item').each(function() {
                // For each category, set up the delete button 
                $(this).find('.delete-category').on('click', function() {
                    deleteCategory($(this));
                });
                
                // Set up add component button
                $(this).find('.add-component').on('click', function() {
                    addComponent($(this));
                });
                
                // Check if this category has saved calculation breakdown data
                const formulaDisplay = $(this).find('.formula-display');
                const categoryIndex = $('.category-item').index($(this));
                
                // Add hidden input for formula display if not exists
                if (!$(this).find('.formula-display-input').length) {
                    $(this).append(`<input type="hidden" class="formula-display-input" name="kt_docs_repeater_basic[${categoryIndex}][formula_display_html]" value="">`);
                }
            });

            $('#form_scoring_lv4').on('submit', function(e) {
                e.preventDefault();
                
                // Prepare component data for submission
                try {
                    if (typeof prepareFormComponentData === 'function') {
                        console.log('Preparing form component data...');
                        prepareFormComponentData();
                        console.log('Form component data prepared successfully');
                    } else {
                        console.warn('prepareFormComponentData function is not available');
                    }
                } catch (error) {
                    console.error('Error preparing form component data:', error);
                }

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
                
                // Process category selects for "Other" option
                $('.category-item').each(function(index) {
                    const categorySelect = $(this).find('select.level1-select');
                    if (categorySelect.val() === 'Lainnya') {
                        const otherCategoryValue = $(this).find('.other-category-input').val() || 'Lainnya';
                        const newCategoryValue = 'Lainnya:' + otherCategoryValue;
                        
                        // Update the select to include the custom option
                        categorySelect.append(new Option(newCategoryValue, newCategoryValue, true, true));
                        categorySelect.val(newCategoryValue);
                    }
                });
                
                // Recalculate all totals before submission
                recalculateAllTotals();
                
                // Collect employee IDs from checkboxes
                var employeeIds = [];
                $('.employee-checkbox:checked').each(function() {
                    employeeIds.push($(this).val());
                });
                
                const areaId = $('select[name="area_id"]').val();
                const formData = new FormData(this);
                formData.append('area_id', areaId);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Add score_positive data
                const scorePositive = $('input[name="score_positive"]:checked').val();
                if (scorePositive) {
                    formData.append('score_positive', scorePositive);
                }
                
                // Add impacts data
                const impacts = [];
                $('input[name="impacts[]"]:checked').each(function() {
                    let impactValue = $(this).val();
                    
                    // Handle "Lainnya" checkbox with custom text
                    if (impactValue === 'lainnya' || $(this).hasClass('lainnya-checkbox')) {
                        const lainnyaText = $('.lainnya-input').val();
                        if (lainnyaText) {
                            impactValue = 'lainnya:' + lainnyaText;
                        }
                    }
                    
                    impacts.push(impactValue);
                });
                
                // Append impacts to formData
                impacts.forEach(function(impact) {
                    formData.append('impacts[]', impact);
                });
                
                // Clear any existing employee_ids fields to prevent duplicates
                if (formData.getAll('employee_ids[]').length > 0) {
                    for (let i = formData.getAll('employee_ids[]').length - 1; i >= 0; i--) {
                        formData.delete('employee_ids[]');
                    }
                }
                
                // Collect and serialize component data for each category
                $('.category-item').each(function(categoryIndex) {
                    const categoryItem = $(this);
                    
                    // Get category data
                    const categorySelect = categoryItem.find('select.level1-select');
                    const categoryValue = categorySelect.val();
                    const otherCategoryValue = categoryItem.find('.other-category-input').val();
                    const finalCategory = categoryValue === 'Lainnya' && otherCategoryValue ? 
                                        `Lainnya: ${otherCategoryValue}` : categoryValue;
                    const totalCost = parseFloat(categoryItem.find('.category-final-cost').val()) || 0;
                    
                    // Add basic category data
                    formData.append(`kt_docs_repeater_basic[${categoryIndex}][category]`, finalCategory);
                    formData.append(`kt_docs_repeater_basic[${categoryIndex}][cost]`, totalCost);
                    
                    const components = [];
                    
                    categoryItem.find('.component-item').each(function() {
                        const componentItem = $(this);
                        const componentName = componentItem.find('.component-name').val() || 'Component';
                        
                        // Collect all input items for this component
                        const inputs = [];
                        componentItem.find('.input-item').each(function() {
                            const inputItem = $(this);
                            const inputTitle = inputItem.find('.input-title').val() || '';
                            const inputPrice = inputItem.find('.input-price').val() || '0';
                            const inputOperator = inputItem.find('.input-operator').val() || 'add';
                            
                            // Convert operator values to match backend expectations
                            let operatorSymbol = '*';
                            switch(inputOperator) {
                                case 'add': operatorSymbol = '+'; break;
                                case 'subtract': operatorSymbol = '-'; break;
                                case 'multiply': operatorSymbol = '*'; break;
                                case 'divide': operatorSymbol = '/'; break;
                            }
                            
                            // Use the price value as-is (do not remove decimal point)
                            let cleanPrice = inputPrice; // No replace, no parse, just as string from input
                            inputs.push({
                                sub_name: inputTitle,
                                price: cleanPrice,
                                operator: operatorSymbol
                            });
                        });
                        
                        if (inputs.length > 0) {
                            // The first input becomes the main component, others become sub_items
                            const mainInput = inputs[0];
                            const subItems = inputs.slice(1);
                            
                            components.push({
                                name: componentName,
                                sub_name: mainInput.sub_name,
                                price: mainInput.price,
                                operator: mainInput.operator,
                                sub_items: subItems
                            });
                        }
                    });
                    
                    if (components.length > 0) {
                        formData.append(`kt_docs_repeater_basic[${categoryIndex}][components]`, JSON.stringify(components));
                    }
                });
                
                // Append employee IDs to form data
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
                            
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else if (xhr.responseJSON?.errors) {
                            // Handle validation errors
                            if (xhr.responseJSON.errors.employee_ids) {
                                Swal.fire({
                                    title: "Peserta Wajib Dipilih",
                                    text: xhr.responseJSON.errors.employee_ids[0],
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
                            } else {
                                Object.keys(xhr.responseJSON.errors).forEach(key => {
                                    toastr.error(xhr.responseJSON.errors[key][0]);
                                });
                            }
                        } else {
                            console.error('Form submission error:', xhr);
                            let errorMessage = "Terjadi kesalahan saat mengirim formulir";
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage += ": " + xhr.responseJSON.error;
                            }
                            toastr.error(errorMessage);
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