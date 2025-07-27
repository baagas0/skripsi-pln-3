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

        .calculated-total {
            background-color: #f0f8ff;
            padding: 5px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 5px;
        }

        .formula-display {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 14px;
            line-height: 1.5;
            overflow-x: auto;
            max-height: 200px;
            overflow-y: auto;
        }

        .formula-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #e0e0e0;
        }

        .formula-row:last-child {
            border-bottom: none;
        }

        .formula-result {
            font-weight: bold;
            color: #0d6efd;
        }

        .component-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
                        <h1 class="anchor fw-bold my-5" id="theme-colors" data-kt-scroll-offset="50">
                            {{ $diklat ? $diklat->name : '' }} - Result</h1>
                        <div>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#guide">
                                Guide
                            </button>
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
                    </div>


                    <div class="col-md-12 mb-3">
                        <label for="exampleFormControlInput1" class="required form-label">Bidang</label>
                        <select name="area_id" required class="form-select" data-placeholder="Pilih Bidang">
                            <option></option>
                            @foreach ($areas as $item)
                                <option value="{{ $item->id }}" {{ request('area_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (!$scoreLv4 && request('area_id'))
                        <div class="alert alert-danger">
                            Bidang belum dinilai formulir penilaian ini.
                        </div>
                    @endif

                    <!-- Employee selection section -->
                    @if (request('area_id') && $participants->count() > 0)
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
                                    @if (!$scoreLv4)
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
                                        @foreach ($participants as $participant)
                                            <div class="col-md-6 mb-3">
                                                <div
                                                    class="form-check form-check-custom form-check-solid {{ in_array($participant->employee_id, $selectedEmployees ?? []) ? 'bg-light-success rounded' : '' }}">
                                                    <input class="form-check-input employee-checkbox" type="checkbox"
                                                        value="{{ $participant->employee_id }}"
                                                        {{ in_array($participant->employee_id, $selectedEmployees ?? []) ? 'checked' : '' }}
                                                        {{ $scoreLv4 ? 'disabled' : '' }} name="employee_ids[]"
                                                        id="employee_{{ $participant->employee_id }}" />
                                                    <label class="form-check-label d-flex align-items-center"
                                                        for="employee_{{ $participant->employee_id }}">
                                                        <div class="symbol symbol-30px me-3">
                                                            <div
                                                                class="symbol-label {{ in_array($participant->employee_id, $selectedEmployees ?? []) ? 'bg-success text-white' : 'bg-light-primary text-primary' }} fw-bold">
                                                                {{ substr($participant->employee->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="fw-bold d-block">{{ $participant->employee->name }}</span>
                                                            <small
                                                                class="text-muted">{{ $participant->employee->nip }}</small>
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

                    @if (request('area_id'))
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
                                            <input class="form-check-input" type="radio" required
                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="1"
                                                {{ $scoreLv4 && $scoreLv4->score_positive == '1' ? 'checked' : '' }}
                                                name="score_positive" id="score_positive_1" />
                                            <label class="form-check-label" for="score_positive_1">1</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required
                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="2"
                                                {{ $scoreLv4 && $scoreLv4->score_positive == '2' ? 'checked' : '' }}
                                                name="score_positive" id="score_positive_2" />
                                            <label class="form-check-label" for="score_positive_2">2</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required
                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="3"
                                                {{ $scoreLv4 && $scoreLv4->score_positive == '3' ? 'checked' : '' }}
                                                name="score_positive" id="score_positive_3" />
                                            <label class="form-check-label" for="score_positive_3">3</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required
                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="4"
                                                {{ $scoreLv4 && $scoreLv4->score_positive == '4' ? 'checked' : '' }}
                                                name="score_positive" id="score_positive_4" />
                                            <label class="form-check-label" for="score_positive_4">4</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" required
                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} value="5"
                                                {{ $scoreLv4 && $scoreLv4->score_positive == '5' ? 'checked' : '' }}
                                                name="score_positive" id="score_positive_5" />
                                            <label class="form-check-label" for="score_positive_5">5</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td colspan="2">
                                        Saya telah melihat/merasakan dampak pada aspek berikut ini sebagai hasil penerapan
                                        apa yang telah peserta (bawahan) pelajari.

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
                                                        <input class="form-check-input" type="checkbox"
                                                            {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}
                                                            name="impacts[]" {{ $isChecked ? 'checked' : '' }}
                                                            value="{{ $impact }}"
                                                            id="impact_{{ $key + 1 }}" />
                                                        <label class="form-check-label" for="impact_{{ $key + 1 }}">
                                                            {{ $impact }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-4 mt-3">
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}
                                                        name="impacts[]" value="lainnya" id="impact_8" />
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

                            @if (auth()->user()->role_id == 6)
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            @endif
                        </form>


                        <form action="" id="form_tangible" class="border rounded">
                            <div class="bg-primary p-3 rounded">
                                <h3 class="fw-bold mb-0 text-white">Tangible Benefit</h3>
                            </div>
                            <!--begin::Hierarchical 3-level input-->
                            <div class="p-3">
                                <div id="tangible-benefit-container" class="mb-6">
                                    <!-- Level 1: Category Selection -->
                                    <div id="level1-categories" class="form-group">
                                        @if (count($tangibles) > 0)
                                            @foreach ($tangibles as $key => $tangible)
                                                <div class="category-item mb-5 border rounded p-4">
                                                    <h5 class="mb-3">Category {{ $key + 1 }}</h5>
                                                    <div class="row mb-3">
                                                        <div class="col-md-9">
                                                            <label class="form-label">Category:</label>
                                                            <select
                                                                name="kt_docs_repeater_basic[{{ $key }}][category]"
                                                                class="form-control category-select level1-select"
                                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}>
                                                                <option value=""></option>
                                                                <option
                                                                    {{ $tangible->category === 'Penghematan Biaya Bahan' ? 'selected' : '' }}>
                                                                    Penghematan Biaya Bahan</option>
                                                                <option
                                                                    {{ $tangible->category === 'Pengurangan Biaya Project' ? 'selected' : '' }}>
                                                                    Pengurangan Biaya Project</option>
                                                                <option
                                                                    {{ $tangible->category === 'Penghematan Waktu' ? 'selected' : '' }}>
                                                                    Penghematan Waktu</option>
                                                                <option
                                                                    {{ $tangible->category === 'Penurunan Biaya Pembelian' ? 'selected' : '' }}>
                                                                    Penurunan Biaya Pembelian</option>
                                                                <option
                                                                    {{ strpos($tangible->category, 'Lainnya:') === 0 ? 'selected' : '' }}>
                                                                    Lainnya</option>
                                                            </select>
                                                            <div class="mt-2 other-category-container"
                                                                style="{{ strpos($tangible->category, 'Lainnya:') === 0 ? '' : 'display: none;' }}">
                                                                <input type="text"
                                                                    name="kt_docs_repeater_basic[{{ $key }}][other_category]"
                                                                    placeholder="Kategori lainnya..."
                                                                    class="form-control other-category-input"
                                                                    {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}
                                                                    value="{{ strpos($tangible->category, 'Lainnya:') === 0 ? substr($tangible->category, 8) : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            @if (auth()->user()->role_id == 6)
                                                                <a href="javascript:;"
                                                                    class="btn btn-sm btn-light-danger mt-8 delete-category">
                                                                    <i class="ki-duotone ki-trash fs-5"><span
                                                                            class="path1"></span><span
                                                                            class="path2"></span><span
                                                                            class="path3"></span><span
                                                                            class="path4"></span><span
                                                                            class="path5"></span></i>
                                                                    Delete
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Display total cost - this is hidden input that will be submitted -->
                                                    <input type="hidden"
                                                        name="kt_docs_repeater_basic[{{ $key }}][cost]"
                                                        class="category-final-cost" value="{{ $tangible->cost }}" />

                                                    <!-- Final calculated cost display -->
                                                    <div class="d-flex justify-content-end my-3">
                                                        <div class="bg-light-success px-4 py-2 rounded">
                                                            <strong>Total Cost: <span class="category-cost-display">Rp. {{ number_format($tangible->cost, 2, ',', '.') }}</span></strong>
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
                                                        @if (isset($tangible->componentGroups) && count($tangible->componentGroups) > 0)
                                                            @foreach ($tangible->componentGroups as $componentName => $details)
                                                                <div class="component-item border rounded p-3 mb-3">
                                                                    <div class="component-header mb-2">
                                                                        <h6 class="mb-2">Component: {{ $componentName }}
                                                                        </h6>
                                                                        @if (auth()->user()->role_id == 6)
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-light-danger delete-component"
                                                                                style="float: right;">
                                                                                <i class="ki-duotone ki-trash fs-5"></i>Delete
                                                                            </button>
                                                                        @endif
                                                                    </div>

                                                                    <div class="component-name-input mb-3">
                                                                        <input type="text"
                                                                            class="form-control component-name"
                                                                            value="{{ $componentName }}"
                                                                            placeholder="Component name"
                                                                            {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} />
                                                                    </div>

                                                                    <!-- Level 3: Input Items -->
                                                                    <div class="level3-inputs">
                                                                        @foreach ($details as $index => $detail)
                                                                            <div class="input-item mb-2">
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <label class="form-label">Title:</label>
                                                                                        <input type="text"
                                                                                            class="form-control input-title"
                                                                                            value="{{ $detail->sub_component_name ?? 'Input ' . ($index + 1) }}"
                                                                                            placeholder="Input name"
                                                                                            {{ auth()->user()->role_id == 6 ? '' : 'disabled' }} />
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label class="form-label">Price:</label>
                                                                                        <input type="number" step="0.01" class="form-control input-price" placeholder="0" 
                                                                                            value="{{ $detail->price }}" {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}/>                      
                                                                                        </div>
                                                                                    <div class="col-md-3">
                                                                                        <label class="form-label">Operator:</label>
                                                                                        <select
                                                                                            class="form-select input-operator"
                                                                                            {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}>
                                                                                            <option value="add"
                                                                                                {{ $detail->operator === '+' ? 'selected' : '' }}>
                                                                                                + Add</option>
                                                                                            <option value="subtract"
                                                                                                {{ $detail->operator === '-' ? 'selected' : '' }}>
                                                                                                - Subtract</option>
                                                                                            <option value="multiply"
                                                                                                {{ $detail->operator === '*' ? 'selected' : '' }}>
                                                                                                × Multiply</option>
                                                                                            <option value="divide"
                                                                                                {{ $detail->operator === '/' ? 'selected' : '' }}>
                                                                                                ÷ Divide</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-3 text-end">
                                                                                        @if (auth()->user()->role_id == 6)
                                                                                            <button type="button"
                                                                                                class="btn btn-sm btn-light-danger delete-input">
                                                                                                <i
                                                                                                    class="ki-duotone ki-trash fs-5"></i>Delete
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
                                                                            <strong>Component Total: <span
                                                                                    class="component-total">Rp.
                                                                                    {{ isset($tangible->componentGroups[$componentName]->subtotal) ? number_format($tangible->componentGroups[$componentName]->subtotal, 0, ',', '.') : '0' }}</span></strong>
                                                                        </div>
                                                                    </div>

                                                                    @if (auth()->user()->role_id == 6)
                                                                        <!-- Add Input Button -->
                                                                        <div class="text-center mt-3">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-light-success add-input">
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
                                                    @if (auth()->user()->role_id == 6)
                                                        <div class="text-center my-3">
                                                            <button type="button"
                                                                class="btn btn-sm btn-light-info add-component">
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
                                                        <select name="kt_docs_repeater_basic[0][category]"
                                                            class="form-control category-select level1-select"
                                                            {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}>
                                                            <option value=""></option>
                                                            <option>Penghematan Biaya Bahan</option>
                                                            <option>Pengurangan Biaya Project</option>
                                                            <option>Penghematan Waktu</option>
                                                            <option>Penurunan Biaya Pembelian</option>
                                                            <option>Lainnya</option>
                                                        </select>
                                                        <div class="mt-2 other-category-container" style="display: none;">
                                                            <input type="text"
                                                                name="kt_docs_repeater_basic[0][other_category]"
                                                                placeholder="Kategori lainnya..."
                                                                class="form-control other-category-input"
                                                                {{ auth()->user()->role_id == 6 ? '' : 'disabled' }}
                                                                value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        @if (auth()->user()->role_id == 6)
                                                            <a href="javascript:;"
                                                                class="btn btn-sm btn-light-danger mt-8 delete-category">
                                                                <i class="ki-duotone ki-trash fs-5"><span
                                                                        class="path1"></span><span
                                                                        class="path2"></span><span
                                                                        class="path3"></span><span
                                                                        class="path4"></span><span
                                                                        class="path5"></span></i>
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Display total cost - this is hidden input that will be submitted -->
                                                <input type="hidden" name="kt_docs_repeater_basic[0][cost]"
                                                    class="category-final-cost" value="0" />

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
                                                @if (auth()->user()->role_id == 6)
                                                    <div class="text-center my-3">
                                                        <button type="button"
                                                            class="btn btn-sm btn-light-info add-component">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            Add Component
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Add Category Button -->
                                    @if (auth()->user()->role_id == 6)
                                        <div class="text-center mt-5">
                                            <button type="button" class="btn btn-light-primary" id="add-category-btn">
                                                <i class="ki-duotone ki-plus fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>Add New Category
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!--end::Hierarchical 3-level input-->

                            @if (auth()->user()->role_id == 6)
                                <div class="d-flex justify-content-center p-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ki-duotone ki-check fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>Submit
                                    </button>
                                </div>
                            @endif
                        </form>

                        <!-- Component template for JavaScript -->
                        <script type="text/template" id="component-template">
                        <div class="component-item mb-3">
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Component Name:</label>
                                    <input type="text" name="components[__INDEX__][name]" class="form-control" placeholder="Component name" />
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-light-danger form-control remove-component">
                                        <i class="ki-duotone ki-trash fs-7"></i> Remove
                                    </button>
                                </div>
                            </div>
                            
                            <div class="sub-components-container">
                                <div class="row mb-2 sub-component-item">
                                    <div class="col-md-4">
                                        <label class="form-label">Sub-component:</label>
                                        <input type="text" name="components[__INDEX__][sub_name]" class="form-control" placeholder="Sub-component name" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Price:</label>
                                        <input type="number" step="0.01" name="components[__INDEX__][inputs][__INPUT_INDEX__][price]" class="form-control input-price" placeholder="Masukkan nilai" autocomplete="off" />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Operator:</label>
                                        <select name="components[__INDEX__][sub_items][__SUB_INDEX__][operator]" class="form-control component-operator">
                                            <option value="*">*</option>
                                            <option value="+">+</option>
                                            <option value="-">-</option>
                                            <option value="/">/</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-sm btn-light-danger form-control remove-sub-component">
                                            <i class="ki-duotone ki-trash fs-7"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="formula-display mt-3">
                                <div class="formula-row">
                                    <span>Calculation:</span>
                                    <span class="formula-result"></span>
                                </div>
                            </div>
                        </div>
                    </script>

                        <script type="text/template" id="sub-component-template">
                        <div class="row mb-2 sub-component-item">
                            <div class="col-md-4">
                                <input type="text" name="components[__COMPONENT_INDEX__][sub_items][__SUB_INDEX__][sub_name]" class="form-control" placeholder="Sub-component name" />
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="components[__COMPONENT_INDEX__][sub_items][__SUB_INDEX__][price]" class="form-control input-price" placeholder="Masukkan nilai" autocomplete="off" />
                            </div>
                            <div class="col-md-2">
                                <select name="components[__COMPONENT_INDEX__][sub_items][__SUB_INDEX__][operator]" class="form-control component-operator">
                                    <option value="*">*</option>
                                    <option value="+">+</option>
                                    <option value="-">-</option>
                                    <option value="/">/</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-sm btn-light-danger form-control remove-sub-component">
                                    <i class="ki-duotone ki-trash fs-7"></i> Remove
                                </button>
                            </div>
                        </div>
                    </script>
                    @endif
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

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
                    <div
                        class="notice d-flex bg-light-primary rounded border-primary border border-dashed min-w-lg-600px flex-shrink-0 p-6">
                        <i class="ki-outline ki-devices-2 fs-2tx text-primary me-4 mt-1"></i>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-semibold">
                                <div class="fs-6 text-gray-700">Copy link dan bagikan kepada atasan bidang unit
                                    {{ $diklat ? $diklat->unit->name : '' }}</div>
                                <textarea name="" id="url-lv-4" cols="40" rows="1" class="w-100">{{ $urlLv4 }}</textarea>
                            </div>
                            <button type="button" class="btn btn-primary px-6 align-self-center text-nowrap"
                                id="copy-button" onclick="copyToClipboard('4')">
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
    <script src="{{ asset('resources/js/tangible-calculation.js') }}"></script>

    <script>
        "use strict";

        function copyToClipboard(level = '1') {
            $(`#url-lv-${level}`).select();

            try {
                document.execCommand('copy');
            } catch (error) {
                console.error('Unable to copy to clipboard', error);
            }
        }

        // Function for handling category selects
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
                //                     <button type="button" class="btn btn-sm btn-light-danger mt-8 delete-component" onclick="deleteComponent(this)">
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
                //                         <input type="number" step="0.01" class="form-control input-price" name="kt_docs_repeater_basic[__INDEX__][components][__COMPONENT_INDEX__][inputs][__INPUT_INDEX__][price]" placeholder="Masukkan nilai" autocomplete="off" />
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
                //         // Add event handler for price changes and operator changes
                //         newComponent.find('.input-price, .input-operator').on('input change', function() {
                //             recalculateComponentTotal($(this).closest('.component-item'));
                //             recalculateCategoryTotal(categoryItem);
                //             updateFormulaDisplay(categoryItem);
                //         });
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

        // Calculate and update totals for components
        function recalculateTotals() {
            $('[data-repeater-item]').each(function() {
                const item = $(this);
                const componentsContainer = item.find('.components-container');
                const totalDisplay = item.find('.calculated-amount');
                const costInput = item.find('.cost-input');

                if (componentsContainer.length) {
                    const components = [];

                    componentsContainer.find('.component-item').each(function() {
                        // Collect component data
                        const component = {
                            name: $(this).find('input[name$="[name]"]').val(),
                            price: $(this).find('.sub-component-item:first input[name$="[price]"]')
                            .val(),
                            operator: $(this).find(
                                '.sub-component-item:first select[name$="[operator]"]').val(),
                            sub_items: []
                        };

                        // Get sub-components data (excluding the first one)
                        $(this).find('.sub-component-item:not(:first)').each(function() {
                            component.sub_items.push({
                                price: $(this).find('input[name$="[price]"]').val(),
                                operator: $(this).find('select[name$="[operator]"]').val()
                            });
                        });

                        components.push(component);
                    });

                    // Calculate the total
                    const total = calculateTangibleTotal(components);

                    // Update the displayed total
                    totalDisplay.text(formatCurrency(total));

                    // Update the cost input if it's empty or auto-calculation is preferred
                    if (costInput.val() === '' || !costInput.data('manual-entry')) {
                        costInput.val(total.toFixed(3)); // Use 3 decimal places
                    }
                }
            });
        }

        // Calculate and update formulas display for a component
        function updateComponentFormula(componentItem) {
            const formulaDisplay = componentItem.find('.formula-display .formula-result');
            const result = displayComponentFormula(componentItem);
            formulaDisplay.text(result.formula);
            return result.result;
        }

        // Calculate and update formulas for all components and total
        function updateAllFormulas() {
            let totalAll = 0;

            $('[data-repeater-item]').each(function() {
                const repeaterItem = $(this);
                let categoryTotal = 0;

                repeaterItem.find('.component-item').each(function() {
                    const componentResult = updateComponentFormula($(this));
                    categoryTotal += componentResult;
                });

                // Update the category total
                repeaterItem.find('.calculated-amount').text(formatCurrency(categoryTotal));

                // Update the cost input if it's not manually set
                const costInput = repeaterItem.find('.cost-input');
                if (costInput.val() === '' || !costInput.data('manual-entry')) {
                    costInput.val(categoryTotal.toFixed(2));
                }

                totalAll += parseFloatSafe(costInput.val());
            });

            return totalAll;
        }

        // Recalculate on input change
        $(document).on('change keyup',
            '.component-price, .component-operator, input[name$="[name]"], input[name$="[sub_name]"]',
            function() {
                updateAllFormulas();
            });

        // Initialize all formulas on load
        setTimeout(function() {
            updateAllFormulas();
        }, 500);

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            console.log('Tangible benefit form initialized');
            console.log('jQuery version:', $.fn.jquery);
            console.log('Repeater plugin available:', typeof $.fn.repeater !== 'undefined');
            setupCategorySelects();

            // Setup component actions
            // Add component button
            $(document).on('click', '.add-component', function() {
                const repeaterItem = $(this).closest('[data-repeater-item]');
                const componentsContainer = repeaterItem.find('.components-container');
                const componentCount = componentsContainer.find('.component-item').length;

                // Get the template and replace index placeholders
                let template = $('#component-template').html();
                template = template.replace(/__INDEX__/g, componentCount);

                // Add the new component before the action buttons
                $(this).closest('.component-actions').before($(template));

                // Recalculate totals
                recalculateAllTotals();
            });

            // Remove component button
            $(document).on('click', '.remove-component', function() {
                const componentItem = $(this).closest('.component-item');
                const repeaterItem = componentItem.closest('[data-repeater-item]');
                componentItem.remove();

                // Re-index remaining components
                reindexComponents(repeaterItem);

                // Recalculate totals
                recalculateAllTotals();
            });

            // Add sub-component button
            $(document).on('click', '.add-sub-component', function() {
                const componentItem = $(this).closest('.component-item');
                const repeaterItem = $(this).closest('[data-repeater-item]');
                const index = componentItem.index();
                const subComponentsContainer = componentItem.find('.sub-components-container');
                const subComponentCount = subComponentsContainer.find('.sub-component-item').length;

                // Get the template and replace index placeholders
                let template = $('#sub-component-template').html();
                template = template.replace(/__COMPONENT_INDEX__/g, index);
                template = template.replace(/__SUB_INDEX__/g, subComponentCount - 1);

                // Add the new sub-component
                subComponentsContainer.append($(template));

                // Recalculate totals
                recalculateAllTotals();
            });

            // Remove sub-component button
            $(document).on('click', '.remove-sub-component', function() {
                const subComponentItem = $(this).closest('.sub-component-item');
                const componentItem = subComponentItem.closest('.component-item');
                const repeaterItem = componentItem.closest('[data-repeater-item]');

                subComponentItem.remove();

                // Re-index remaining sub-components
                reindexSubComponents(componentItem);

                // Recalculate totals
                recalculateAllTotals();
            });

            // Handle select/deselect all employees
            $('.select-all-employees').on('click', function(e) {
                $('.employee-checkbox:not(:disabled)').prop('checked', true);
            });

            $('.deselect-all-employees').on('click', function(e) {
                $('.employee-checkbox:not(:disabled)').prop('checked', false);
            });

            $('select[name="area_id"]').on('change', function() {
                const value = $(this).val();
                if (value) {
                    const url = window.location.origin + window.location.pathname + '?area_id=' + value;
                    window.location.href = url;
                }
            });

            // // Mark cost inputs as manually entered and handle decimal format
            // $(document).on('input', '.cost-input, .input-price', function() {
            //     $(this).data('manual-entry', true);

            //     // Ensure decimal values are properly formatted
            //     if (this.value && !isNaN(this.value)) {
            //         this.value = parseFloat(this.value).toFixed(3);
            //     }
            // });

            // Initialize calculations
            recalculateTotals();

            // Initialize form repeater
            $('#kt_docs_repeater_basic').repeater({
                initEmpty: {{ count($tangibles) == 0 ? 'true' : 'false' }},
                defaultValues: {
                    'category': '',
                    'cost': '',
                    'other_category': ''
                },
                show: function() {
                    $(this).slideDown();
                    setupCategorySelects();
                    recalculateTotals();
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                    recalculateTotals();
                }
            });

            // Debug: Check if repeater is working
            console.log('Form repeater initialized');

            // Add Category Button functionality (matching head_area implementation)
            $('#add-category-btn').on('click', function(e) {
                e.preventDefault();
                console.log('Add category button clicked');

                // Get the current number of categories
                const categoryCount = $('.category-item').length;

                // Create a new category with a new index
                const newCategoryHtml = `
                    <div class="category-item mb-5 border rounded p-4">
                        <h5 class="mb-3">Category ${categoryCount + 1}</h5>
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
                        <input type="hidden name="kt_docs_repeater_basic[${categoryCount}][cost]" class="category-final-cost" value="0" min="0" max="99999999999999999999.999" step="0.001" />
                        
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

                // Setup category select for the new category
                setupCategorySelects();

                // Add component button handler
                newCategory.find('.add-component').on('click', function() {
                    addComponent($(this));
                });

                // Delete category button handler
                newCategory.find('.delete-category').on('click', function() {
                    deleteCategory($(this));
                });

                console.log('New category added successfully');
            });

            // Form submission
            $('#form_tangible').on('submit', function(e) {
                e.preventDefault();

                // Process form data before submission
                $(this).find('.category-select').each(function() {
                    if ($(this).val() === 'Lainnya') {
                        const customValue = $(this).closest('.form-group').find(
                            '.other-category-input').val();
                        // Format the category as "Lainnya: [custom text]" so you can detect it on the server
                        if (customValue) {
                            $(this).val('Lainnya: ' + customValue);
                        }
                    }
                });

                // Collect form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('area_id', '{{ request('area_id') }}');

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
                    if (impactValue === 'lainnya') {
                        const lainnyaText = $(this).closest('.form-check').find(
                            'input[type="text"]').val();
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

                // Collect employee IDs from checkboxes
                const employeeIds = [];
                $('.employee-checkbox:checked').each(function() {
                    employeeIds.push($(this).val());
                });

                // Append employee IDs to form data
                employeeIds.forEach(function(id) {
                    formData.append('employee_ids[]', id);
                });

                // Collect categories and their component data
                $('.category-item').each(function(categoryIndex) {
                    const categorySelect = $(this).find('.category-select');
                    const categoryValue = categorySelect.val();
                    const otherCategoryValue = $(this).find('.other-category-input').val();
                    const finalCategory = categoryValue === 'Lainnya' && otherCategoryValue ?
                        `Lainnya: ${otherCategoryValue}` : categoryValue;

                    // Add basic category data
                    formData.append(`kt_docs_repeater_basic[${categoryIndex}][category]`,
                        finalCategory);
                    formData.append(`kt_docs_repeater_basic[${categoryIndex}][cost]`, parseFloat($(
                        this).find('.category-final-cost').val()) || 0);

                    // Collect component data for this category
                    const components = [];
                    $(this).find('.component-item').each(function(componentIndex) {
                        const componentName = $(this).find('.component-name').val() || '';

                        // Collect all inputs for this component
                        const inputs = [];
                        $(this).find('.input-item').each(function(inputIndex) {
                            const title = $(this).find('.input-title').val() || '';
                            const priceText = $(this).find('.input-price').val() || '0';
                            const operatorSelect = $(this).find('.input-operator').val();

                            // Use parseFloat directly for price, do not clean or reformat
                            let cleanPrice = priceText === '' ? 0 : parseFloat(priceText);
                            cleanPrice = isNaN(cleanPrice) ? 0 : cleanPrice;

                            // Convert operator to database format
                            let operatorSymbol = '*';
                            switch (operatorSelect) {
                                case 'add':
                                    operatorSymbol = '+';
                                    break;
                                case 'subtract':
                                    operatorSymbol = '-';
                                    break;
                                case 'multiply':
                                    operatorSymbol = '*';
                                    break;
                                case 'divide':
                                    operatorSymbol = '/';
                                    break;
                            }

                            inputs.push({
                                sub_name: title,
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
                        formData.append(`kt_docs_repeater_basic[${categoryIndex}][components]`, JSON
                            .stringify(components));
                    }
                });

                // Log form data for debugging
                console.log('Submitting form data...');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ':', pair[1]);
                }

                // Submit the form via AJAX
                $.ajax({
                    url: `${base_url}/form/lv4/{{ $diklat->id }}/submit`,
                    type: 'POST',
                    data: formData,
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
                                    toastr.error(xhr.responseJSON.errors[key][0]);
                                });
                        } else {
                            Swal.fire({
                                text: "An error occurred while submitting the form.",
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
            });
        });

        // Function to add a new component (Level 2) - from head_area template
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

        // Function to add a new calculation input (Level 3) - from head_area template
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
                            <input type="number" step="0.01" class="form-control input-price" name="kt_docs_repeater_basic[__INDEX__][components][__COMPONENT_INDEX__][inputs][__INPUT_INDEX__][price]" placeholder="Masukkan nilai" autocomplete="off" />
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
                                        <button type="button" class="btn btn-sm btn-light-danger delete-input">
                                            <i class="ki-duotone ki-trash fs-5"></i> Delete
                                        </button>
                                    </div>
                    </div>
                </div>
            `;

            inputsContainer.append(inputHtml);

            // Initialize currency mask if available
            if (typeof Inputmask !== 'undefined') {            Inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                radixPoint: ',',
                autoGroup: true,
                digits: 2,
                digitsOptional: false,
                allowMinus: false,
                placeholder: '0,00',
                rightAlign: true,
                autoUnmask: false,
                removeMaskOnSubmit: false,
                clearMaskOnLostFocus: false,
                onBeforeMask: function(value, opts) {
                    if (value === '') return '0,00';
                    return value;
                },
                onUnMask: function(maskedValue, unmaskedValue) {
                    // Convert from Indonesian format to standard decimal
                    return maskedValue.replace(/\./g, '').replace(',', '.');
                }
            }).mask(inputsContainer.find('.input-item:last .input-price'));
            }

            // Attach event handlers
            const newInput = inputsContainer.children('.input-item:last');

            // Price change handler
            newInput.find('.input-price, .input-operator').on('change input', function() {
                recalculateComponentTotal(componentItem);
                recalculateCategoryTotal(categoryItem);
                updateFormulaDisplay(categoryItem);
            });

            // Input title change handler to update formula display (matching head_area implementation)
            newInput.find('.input-title').on('keyup change blur', function() {
                recalculateComponentTotal(componentItem);
                recalculateCategoryTotal(categoryItem);
                updateFormulaDisplay(categoryItem);
            });

            // Delete input button handler
            newInput.find('.delete-input').on('click', function() {
                deleteInput($(this));
            });
        }

        // Function to delete a category
        function deleteCategory(button) {
            if (confirm('Are you sure you want to delete this category?')) {
                $(button).closest('.category-item').remove();

                // Re-index remaining categories
                $('.category-item').each(function(index) {
                    $(this).find('h5').text('Category ' + (index + 1));

                    // Update field names with new indices
                    $(this).find('select[name*="kt_docs_repeater_basic"]').attr('name',
                        `kt_docs_repeater_basic[${index}][category]`);
                    $(this).find('input[name*="other_category"]').attr('name',
                        `kt_docs_repeater_basic[${index}][other_category]`);
                    $(this).find('input[name*="[cost]"]').attr('name', `kt_docs_repeater_basic[${index}][cost]`);
                });
            }
        }

        // Function to delete a component
        function deleteComponent(button) {
            if (confirm('Are you sure you want to delete this component?')) {
                const categoryItem = $(button).closest('.category-item');
                $(button).closest('.component-item').remove();
                recalculateCategoryTotal(categoryItem);
                updateFormulaDisplay(categoryItem);
            }
        }

        // Function to delete an input
        function deleteInput(button) {
            const componentItem = $(button).closest('.component-item');
            const categoryItem = componentItem.closest('.category-item');
            $(button).closest('.input-item').remove();
            recalculateComponentTotal(componentItem);
            recalculateCategoryTotal(categoryItem);
            updateFormulaDisplay(categoryItem);
        }

        // Function to recalculate component total
        function recalculateComponentTotal(componentItem, updateFormula = true) {
            let total = 0;
            let isFirstInput = true;
            const inputCount = componentItem.find('.input-item').length;

            // Handle case with no inputs
            if (inputCount === 0) {
                componentItem.find('.component-total').text('Rp. 0,00');
                componentItem.data('component-total', 0);
                return 0;
            }

            componentItem.find('.input-item').each(function() {
                // Get the raw value from input and clean it
                let price = $(this).find('.input-price').val();
                price = price === undefined || price === null || price === '' ? 0 : parseFloat(price);
                price = isNaN(price) ? 0 : price;

                // Get operator
                const operator = $(this).find('.input-operator').val();

                if (isFirstInput) {
                    // First input always sets the initial value
                    total = price;
                    isFirstInput = false;
                } else {
                    // Apply the selected operator
                    switch (operator) {
                        case 'add':
                            total += price;
                            break;
                        case 'subtract':
                            total -= price;
                            break;
                        case 'multiply':
                            total *= price;
                            break;
                        case 'divide':
                            if (price !== 0) {
                                total /= price;
                            }
                            break;
                    }
                }
            });

            // Format with exactly 2 decimal places for display
            const formattedTotal = formatNumberWithDecimals(total);
            componentItem.find('.component-total').text('Rp. ' + formattedTotal);
            componentItem.data('component-total', total);
            if (updateFormula) {
                updateFormulaDisplay(componentItem.closest('.category-item'));
            }
            return total;
        }

        // Function to recalculate category total
        function recalculateCategoryTotal(categoryItem) {
            let total = 0;
            categoryItem.find('.component-item').each(function() {
                // Get the stored numeric value from the component
                let componentTotal = $(this).data('component-total') || 0;
                total += componentTotal;
            });
            // Update category total display and hidden input with proper decimal handling
            categoryItem.find('.category-cost-display').text('Rp. ' + formatNumberWithDecimals(total));
            categoryItem.find('.category-final-cost').val(total); // Store as plain decimal, not fixed(2) string
            return total;
        }

        // Function to recalculate all totals
        function recalculateAllTotals() {
            $('.category-item').each(function() {
                // recalculateCategoryTotal($(this));
                updateFormulaDisplay($(this));
            });
        }

        // Helper function to format numbers with thousands separators and decimals (Indonesian format)
        function formatNumberWithDecimals(num) {
            // Ensure we're working with a number
            if (typeof num !== 'number') {
                num = parseFloat(num) || 0;
            }
            
            // Round to exactly 2 decimal places to avoid floating point precision issues
            num = Number(parseFloat(num).toFixed(2));
            
            // Split into whole and decimal parts
            let [wholePart, decimalPart = '00'] = num.toString().split('.');
            
            // Add thousand separators (dots) to whole part
            wholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            
            // Ensure decimal part is exactly 2 digits
            decimalPart = decimalPart.padEnd(2, '0').slice(0, 2);
            
            // Join with decimal separator (comma)
            return wholePart + ',' + decimalPart;
        }

        // Helper function to parse number (reverse of formatNumberWithDecimals)
        function parseFormattedNumber(str) {
            if (!str) return 0;
            if (typeof str === 'number') return str;
            
            // Remove everything except digits, dots, and commas
            str = str.replace(/[^\d,\.]/g, '');
            
            // Split by comma (decimal separator)
            let parts = str.split(',');
            
            if (parts.length === 2) {
                // We have a decimal number
                let wholePart = parts[0].replace(/\./g, ''); // Remove thousand separators
                let decimalPart = parts[1];
                return parseFloat(wholePart + '.' + decimalPart);
            }
            
            // No decimal part
            return parseFloat(parts[0].replace(/\./g, ''));
        }

        // Function to update formula display
        function updateFormulaDisplay(categoryItem) {
            const formulaDisplay = categoryItem.find('.formula-display');
            let formulaHtml = '';

            // If there are no components, show default message
            if (categoryItem.find('.component-item').length === 0) {
                formulaHtml = 'No components added yet';
            } else {
                // Build the formula display for all components
                categoryItem.find('.component-item').each(function(componentIndex) {
                    const componentName = $(this).find('.component-name').val() || `Component ${componentIndex + 1}`;
                    const componentTotal = recalculateComponentTotal($(this), false);

                    // Add component name and total
                    formulaHtml += `<div class="mb-1"><span class="text-primary fw-bold">${componentName}</span></div>`;

                    // Add the formula breakdown for this component
                    let inputFormula = '<div class="ps-3 mb-2">';
                    let calculationFormula = '';
                    let isFirstInput = true;

                    $(this).find('.input-item').each(function(inputIndex) {
                        const inputTitle = $(this).find('.input-title').val() || `Input ${inputIndex + 1}`;
                        let inputPrice = $(this).find('.input-price').val();
                        inputPrice = inputPrice === undefined || inputPrice === null || inputPrice === '' ? 0 : parseFloat(inputPrice);
                        inputPrice = isNaN(inputPrice) ? 0 : inputPrice;
                        const operator = $(this).find('.input-operator').val();
                        const operatorSymbol = operator === 'add' ? '+' : operator === 'subtract' ? '-' : operator === 'multiply' ? '×' : operator === 'divide' ? '÷' : '';
                        if (isFirstInput) {
                            calculationFormula += `${inputTitle}: ${inputPrice}`;
                            isFirstInput = false;
                        } else {
                            calculationFormula += ` ${operatorSymbol} ${inputTitle}: ${inputPrice}`;
                        }
                    });

                    inputFormula += calculationFormula;
                    inputFormula += `<br><strong>= ${parseFloat(componentTotal)}</strong>`;
                    inputFormula += '</div>';

                    formulaHtml += inputFormula;

                    // Add separator between components
                    if (componentIndex < categoryItem.find('.component-item').length - 1) {
                        formulaHtml += '<hr class="my-2">';
                    }
                });

                // Add total if there are multiple components
                if (categoryItem.find('.component-item').length > 1) {
                    const categoryTotal = parseFloat(categoryItem.find('.category-final-cost').val()) || 0;
                    formulaHtml += '<hr class="my-2">';
                    formulaHtml += '<div class="d-flex justify-content-end"><span class="badge badge-primary fw-bold">Total: Rp.' + formatNumberWithDecimals(categoryTotal) + '</span></div>';
                }
            }

            formulaDisplay.html(formulaHtml);
        }

        // Initialize existing categories (matching head_area implementation)
        $('.category-item').each(function() {
            const categoryItem = $(this);

            // For each category, set up the delete button 
            categoryItem.find('.delete-category').on('click', function() {
                deleteCategory($(this));
            });

            // Set up add component button
            categoryItem.find('.add-component').on('click', function() {
                addComponent($(this));
            });

            // Set up existing component event handlers
            categoryItem.find('.component-item').each(function() {
                const componentItem = $(this);

                // Component name change handler
                componentItem.find('.component-name').on('change', function() {
                    recalculateCategoryTotal(categoryItem);
                    updateFormulaDisplay(categoryItem);
                });

                // Delete component button handler
                componentItem.find('.delete-component').on('click', function() {
                    deleteComponent($(this));
                });

                // Add input button handler
                componentItem.find('.add-input').on('click', function() {
                    addInput($(this));
                });

                // Set up existing input event handlers
                componentItem.find('.input-item').each(function() {
                    const inputItem = $(this);

                    // Price and operator change handlers
                    inputItem.find('.input-price, .input-operator').on('change input', function() {
                        recalculateComponentTotal(componentItem);
                        recalculateCategoryTotal(categoryItem);
                        updateFormulaDisplay(categoryItem);
                    });

                    // Input title change handler to update formula display (matching head_area implementation)
                    inputItem.find('.input-title').on('keyup change blur', function() {
                        recalculateComponentTotal(componentItem);
                        recalculateCategoryTotal(categoryItem);
                        updateFormulaDisplay(categoryItem);
                    });

                    // Delete input button handler
                    inputItem.find('.delete-input').on('click', function() {
                        deleteInput($(this));
                    });
                });
            });

            // Initialize formula display
            const categoryIndex = $('.category-item').index(categoryItem);

            // Add hidden input for formula display if not exists
            if (!categoryItem.find('.formula-display-input').length) {
                categoryItem.append(
                    `<input type="hidden" class="formula-display-input" name="kt_docs_repeater_basic[${categoryIndex}][formula_display_html]" value="">`
                    );
            }
        });

        // Initialize category selects
        setupCategorySelects();

        // Initialize formula display and calculations for existing data (matching head_area implementation)
        console.log('Initializing calculations for existing data...');
        recalculateAllTotals();
    </script>
@endsection
