@php
    $moduleName = 'Vendor';
    $moduleRoute = 'vendor';
@endphp
@extends('layouts.main')
@section('title', $moduleName)
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-10">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan Penilaian</h3>
                </div>
                <div class="card-body">
                    <form id="reportFilterForm" method="GET" action="{{ route('.report') }}">
                        <div class="row">
                        <div class="col-md-6 mb-3">
                                <label for="diklat_ids" class="required form-label">Diklat</label>
                                <select name="diklat_ids[]" id="diklat_ids" class="form-select" multiple="multiple" data-placeholder="Pilih diklat (bisa lebih dari satu)">
                                    @foreach ($diklats as $item)
                                        <option value="{{ $item->id }}"
                                            {{ is_array(request('diklat_ids')) && in_array($item->id, request('diklat_ids')) ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="required form-label">Level Penilaian</label>
                                <div class="row px-4">
                                    <div class="form-check form-check-custom form-check-primary col-md-6 mb-2">
                                        <input class="form-check-input" type="checkbox" id="level_all" name="levels[]" value="all" 
                                            {{ in_array('all', is_array(request('levels')) ? request('levels') : []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="level_all">
                                            Semua Level
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-primary col-md-6 mb-2">
                                        <input class="form-check-input level-check" type="checkbox" id="level_1" name="levels[]" value="1" 
                                            {{ in_array('1', is_array(request('levels')) ? request('levels') : []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="level_1">
                                            Level 1 - Reaction
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-primary col-md-6 mb-2">
                                        <input class="form-check-input level-check" type="checkbox" id="level_2" name="levels[]" value="2" 
                                            {{ in_array('2', is_array(request('levels')) ? request('levels') : []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="level_2">
                                            Level 2 - Learning
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-primary col-md-6 mb-2">
                                        <input class="form-check-input level-check" type="checkbox" id="level_3" name="levels[]" value="3" 
                                            {{ in_array('3', is_array(request('levels')) ? request('levels') : []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="level_3">
                                            Level 3 - Behavior
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-primary col-md-6 mb-2">
                                        <input class="form-check-input level-check" type="checkbox" id="level_4" name="levels[]" value="4" 
                                            {{ in_array('4', is_array(request('levels')) ? request('levels') : []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="level_4">
                                            Level 4 - Result
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-primary col-md-6 mb-2">
                                        <input class="form-check-input level-check" type="checkbox" id="level_5" name="levels[]" value="5" 
                                            {{ in_array('5', is_array(request('levels')) ? request('levels') : []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="level_5">
                                            Level 5 - ROI
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (!empty($diklatIds))
                <div class="card mb-6">
                    <div class="card-header">
                        <h1 class="card-title fw-bold fs-1">
                            Laporan Perbandingan Pelatihan
                        </h1>
                        <div class="card-toolbar">
                            @if(count($diklatIds) == 1)
                            <a href="{{ url('/report/download-pdf/' . $diklatIds[0]) . '?' . http_build_query(['levels' => $levels]) }}" class="btn btn-success me-2">
                                Export PDF
                            </a>
                            @endif
                            <button onclick="printDiv('printableArea')" class="btn btn-primary">
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="printableArea">
                        @php
                            $levelLabels = [
                                'all' => 'Semua Level Penilaian',
                                '1' => 'Level 1 - Reaction',
                                '2' => 'Level 2 - Learning',
                                '3' => 'Level 3 - Behavior',
                                '4' => 'Level 4 - Result',
                                '5' => 'Level 5 - Return On Training Investment'
                            ];
                            
                            $selectedLevelLabels = [];
                            foreach ($selectedLevels as $lvl) {
                                if (isset($levelLabels[$lvl])) {
                                    $selectedLevelLabels[] = $levelLabels[$lvl];
                                }
                            }
                            
                            $currentLevelLabel = $showAllLevels 
                                ? 'Semua Level Penilaian' 
                                : implode(', ', $selectedLevelLabels);
                        @endphp
                        
                        <div class="alert alert-light-primary mb-5">
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-2hx svg-icon-primary me-3">
                                    <i class="bi bi-filter-square fs-1"></i>
                                </span>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-dark">{{ $currentLevelLabel }}</h4>
                                    <span>Menampilkan hasil penilaian untuk {{ $currentLevelLabel }}</span>
                                </div>
                            </div>
                        </div>
                        @if($showAllLevels || in_array('1', $selectedLevels))
                        <div class="mb-6">
                            <div class="mb-6">
                                <h3 class="bg-light p-3 rounded">
                                    <span class="badge {{ in_array('1', $selectedLevels) && !$showAllLevels ? 'badge-primary' : 'badge-light' }} me-2">1</span>
                                    Level 1 - Reaction
                                </h3>
                            </div>

                            @foreach($diklatsForComparison as $diklat)
                            <div class="mb-5">
                                <h4 class="mb-3">{{ $diklat->name }}</h4>
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="7" style="background-color: #FF9B17">Pelaksanaan Pelatihan</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #FCB454">Parameter</td>
                                        <td style="background-color: #FCB454">Skor 1</td>
                                        <td style="background-color: #FCB454">Skor 2</td>
                                        <td style="background-color: #FCB454">Skor 3</td>
                                        <td style="background-color: #FCB454">Skor 4</td>
                                        <td style="background-color: #FCB454">Skor 5</td>
                                        <td style="background-color: #FCB454">Jumlah Peserta</td>
                                    </tr>
                                    @foreach ($diklatData[$diklat->id]['tableList1'] as $item)
                                        <tr>
                                            <td>{{ $item['question'] }}</td>
                                            <td>{{ $item['score1'] }}</td>
                                            <td>{{ $item['score2'] }}</td>
                                            <td>{{ $item['score3'] }}</td>
                                            <td>{{ $item['score4'] }}</td>
                                            <td>{{ $item['score5'] }}</td>
                                            <td>{{ $item['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>

                                <table class="table table-bordered mt-4">
                                    <tr>
                                        <td colspan="7" style="background-color: #FF9B17">Narasumber Pelatihan</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #FCB454">Parameter</td>
                                        <td style="background-color: #FCB454">Skor 1</td>
                                        <td style="background-color: #FCB454">Skor 2</td>
                                        <td style="background-color: #FCB454">Skor 3</td>
                                        <td style="background-color: #FCB454">Skor 4</td>
                                        <td style="background-color: #FCB454">Skor 5</td>
                                        <td style="background-color: #FCB454">Jumlah Peserta</td>
                                    </tr>
                                    @foreach ($diklatData[$diklat->id]['tableList2'] as $item)
                                        <tr>
                                            <td>{{ $item['question'] }}</td>
                                            <td>{{ $item['score1'] }}</td>
                                            <td>{{ $item['score2'] }}</td>
                                            <td>{{ $item['score3'] }}</td>
                                            <td>{{ $item['score4'] }}</td>
                                            <td>{{ $item['score5'] }}</td>
                                            <td>{{ $item['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                
                                <div class="border rounded p-6 text-center mb-3 mt-4">
                                    <h4>Distribusi Skala Penilaian Pelaksanaan Pelatihan - {{ $diklat->name }}</h4>
                                    <div id="scoreList1_{{ $diklat->id }}" style="height: 350px;"></div>
                                </div>
                                <div class="border rounded p-6 text-center">
                                    <h4>Distribusi Skala Penilaian Narasumber Pelatihan - {{ $diklat->name }}</h4>
                                    <div id="scoreList2_{{ $diklat->id }}" style="height: 350px;"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($showAllLevels || in_array('2', $selectedLevels))
                        <div class="mb-6">
                            <div class="mb-6">
                                <h3 class="bg-light p-3 rounded">
                                    <span class="badge {{ in_array('2', $selectedLevels) && !$showAllLevels ? 'badge-primary' : 'badge-light' }} me-2">2</span>
                                    Level 2 - Learning
                                </h3>
                            </div>
                            
                            @foreach($diklatsForComparison as $diklat)
                            <div class="mb-5">
                                <h4 class="mb-3">{{ $diklat->name }}</h4>
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="background-color: #FCB454">No</td>
                                        <td style="background-color: #FCB454">Nama</td>
                                        <td style="background-color: #FCB454">Nilai Pre-Test</td>
                                        <td style="background-color: #FCB454">Nilai Post-Test</td>
                                        <td style="background-color: #FCB454">Peningkatan Nilai</td>
                                    </tr>
                                    @foreach ($diklatData[$diklat->id]['score2'] as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->diklatParticipant->employee->name }}</td>
                                            <td>{{ $item->pretest_score }}</td>
                                            <td>{{ $item->posttest_score }}</td>
                                            <td>{{ $item->diff_score }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="background-color: #90C67C" colspan="2">Rata-rata</td>
                                        <td style="background-color: #90C67C">{{ $diklatData[$diklat->id]['score2']->avg('pretest_score') }}</td>
                                        <td style="background-color: #90C67C">{{ $diklatData[$diklat->id]['score2']->avg('posttest_score') }}</td>
                                        <td style="background-color: #90C67C">{{ $diklatData[$diklat->id]['score2']->avg('diff_score') }}</td>
                                    </tr>
                                </table>

                                <table>
                                    <tr class="p-3">
                                        <td class="p-3" style="width: 200px; background-color: #3699FF">Rata-rata Nilai Pre-Test</td>
                                        <td style="width: 80px; text-align: center">{{ $diklatData[$diklat->id]['avgPretest'] }}</td>
                                    </tr>
                                    <tr class="p-3">
                                        <td class="p-3" style="width: 200px; background-color: #0BB7AF">Rata-rata Nilai Post-Test</td>
                                        <td style="width: 80px; text-align: center">{{ $diklatData[$diklat->id]['avgPosttest'] }}</td>
                                    </tr>
                                    <tr class="p-3">
                                        <td class="p-3" style="width: 200px; background-color: #3699FF">Rata-rata Peningkatan</td>
                                        <td style="width: 80px; text-align: center">{{ $diklatData[$diklat->id]['avgLevel2'] }}</td>
                                    </tr>
                                </table>
                            </div>
                            @endforeach

                            @if(count($diklatsForComparison) > 1)
                            <div class="mt-5">
                                <h4 class="mb-3">Perbandingan Antar Pelatihan</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="background-color: #FCB454">Pelatihan</td>
                                        <td style="background-color: #FCB454">Rata-rata Nilai Pre-Test</td>
                                        <td style="background-color: #FCB454">Rata-rata Nilai Post-Test</td>
                                        <td style="background-color: #FCB454">Rata-rata Peningkatan</td>
                                    </tr>
                                    @foreach($diklatsForComparison as $diklat)
                                    <tr>
                                        <td>{{ $diklat->name }}</td>
                                        <td>{{ $diklatData[$diklat->id]['avgPretest'] }}</td>
                                        <td>{{ $diklatData[$diklat->id]['avgPosttest'] }}</td>
                                        <td>{{ $diklatData[$diklat->id]['avgLevel2'] }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($showAllLevels || in_array('3', $selectedLevels))
                        <div class="mb-6">
                            <div class="mb-6">
                                <h3 class="bg-light p-3 rounded">
                                    <span class="badge {{ in_array('3', $selectedLevels) && !$showAllLevels ? 'badge-primary' : 'badge-light' }} me-2">3</span>
                                    Level 3 - Behavior
                                </h3>
                            </div>

                            @foreach($diklatsForComparison as $diklat)
                            <div class="mb-5">
                                <h4 class="mb-3">{{ $diklat->name }}</h4>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td style="background-color: #FCB454">Nama</td>
                                            @foreach ($score3Question as $question)
                                            <td style="background-color: #FCB454">{{ $question->name }}</td>
                                            @endforeach
                                            <td style="background-color: #FCB454">Total</td>
                                            <td style="background-color: #FCB454">Rata-rata</td>
                                            <td style="background-color: #FCB454">Kategori</td>
                                        </tr>
                                        @foreach ($diklatData[$diklat->id]['scoreList3'] as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            @foreach ($score3Question as $question)
                                            <td>{{ $item['score']->where('scoring_lv3_question_id', $question->id)->first()->score ?? '-' }}</td>
                                            @endforeach
                                            <td>{{ $item['sum'] }}</td>
                                            <td>{{ $item['avg'] }}</td>
                                            <td>{{ $item['result'] }}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <table class="table table-bordered mt-4">
                                    <tr>
                                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align:middle">Kategori</td>
                                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle">Rentang Nilai</td>
                                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle">Jumlah Peserta</td>
                                    </tr>
                                    @foreach ($diklatData[$diklat->id]['scoreList3Result'] as $item)
                                    <tr>
                                        <td class="p-3 bg-secondary" style="vertical-align: middle">{{ $item['name'] }}</td>
                                        <td class="p-3 bg-secondary" style="vertical-align: middle">{{ $item['range'] }}</td>
                                        <td class="p-3 bg-secondary" style="vertical-align: middle">{{ $item['count'] }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle">Total</td>
                                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle"></td>
                                        <td class="p-3 d-flex justify-content-between align-items-center" style="width: 100%; background-color: #3699FF; vertical-align: middle">
                                            {{ $diklatData[$diklat->id]['scoreList3Result']->sum('count') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endforeach

                            @if(count($diklatsForComparison) > 1)
                            <div class="mt-5">
                                <h4 class="mb-3">Perbandingan Efektivitas Pelatihan</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="background-color: #FCB454">Pelatihan</td>
                                        <td style="background-color: #FCB454">Tidak Efektif</td>
                                        <td style="background-color: #FCB454">Kurang Efektif</td>
                                        <td style="background-color: #FCB454">Cukup Efektif</td>
                                        <td style="background-color: #FCB454">Efektif</td>
                                        <td style="background-color: #FCB454">Sangat Efektif</td>
                                        <td style="background-color: #FCB454">Total Peserta</td>
                                    </tr>
                                    @foreach($diklatsForComparison as $diklat)
                                    <tr>
                                        <td>{{ $diklat->name }}</td>
                                        <td>{{ $diklatData[$diklat->id]['scoreList3Result']->where('name', 'Tidak Efektif')->first()['count'] ?? 0 }}</td>
                                        <td>{{ $diklatData[$diklat->id]['scoreList3Result']->where('name', 'Kurang Efektif')->first()['count'] ?? 0 }}</td>
                                        <td>{{ $diklatData[$diklat->id]['scoreList3Result']->where('name', 'Cukup Efektif')->first()['count'] ?? 0 }}</td>
                                        <td>{{ $diklatData[$diklat->id]['scoreList3Result']->where('name', 'Efektif')->first()['count'] ?? 0 }}</td>
                                        <td>{{ $diklatData[$diklat->id]['scoreList3Result']->where('name', 'Sangat Efektif')->first()['count'] ?? 0 }}</td>
                                        <td>{{ $diklatData[$diklat->id]['scoreList3Result']->sum('count') }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($showAllLevels || in_array('4', $selectedLevels))
                        <div class="mb-6">
                            <div class="mb-6">
                                <h3 class="bg-light p-3 rounded">
                                    <span class="badge {{ in_array('4', $selectedLevels) && !$showAllLevels ? 'badge-primary' : 'badge-light' }} me-2">4</span>
                                    Level 4 - Result
                                </h3>
                            </div>

                            @foreach($diklatsForComparison as $diklat)
                            <div class="mb-5">
                                <h4 class="mb-3">{{ $diklat->name }}</h4>

                                <p>1. Program pelatihan ini <span class="text-primary fw-bold fs-4">{{ $diklatData[$diklat->id]['score4Label'] }}</span> pada unit.</p>
                                <p>2. Program pelatihan ini berdampak bagi peserta pada aspek <span class="text-primary">{{ implode(', ', $diklatData[$diklat->id]['score4Impacts']) }}</span></p>

                                <table class="table table-bordered">
                                    <tr>
                                        <td style="text-align: center; background-color: #3699FF" colspan="2">Tangible Benefits</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; background-color: #0BB7AF">Tangible Returns Categories</td>
                                        <td style="text-align: center; background-color: #0BB7AF">Total</td>
                                    </tr>
                                    @foreach ($diklatData[$diklat->id]['score4Tangibles'] as $item)
                                    <tr>
                                        <td class="bg-secondary" style="width: 300px">{{ $item->category }}</td>
                                        <td class="bg-secondary d-flex justify-content-between" style="width: 100%">
                                            <p>Rp.</p>
                                            <p>{{ number_format($item->cost, 2) }}</p>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td style="width: 300px; background-color: #3699FF">Total Benefits</td>
                                        <td class="d-flex justify-content-between align-items-center" style="width: 100%; background-color: #3699FF">
                                            <p>Rp.</p>
                                            <p>{{ number_format($diklatData[$diklat->id]['score4Tangibles']->sum('cost'), 2) }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endforeach

                            @if(count($diklatsForComparison) > 1)
                            <div class="mt-5">
                                <h4 class="mb-3">Perbandingan Dampak Pelatihan</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="background-color: #FCB454">Pelatihan</td>
                                        <td style="background-color: #FCB454">Level Dampak</td>
                                        <td style="background-color: #FCB454">Total Tangible Benefits</td>
                                    </tr>
                                    @foreach($diklatsForComparison as $diklat)
                                    <tr>
                                        <td>{{ $diklat->name }}</td>
                                        <td>{{ $diklatData[$diklat->id]['score4Label'] }}</td>
                                        <td>Rp. {{ number_format($diklatData[$diklat->id]['score4Tangibles']->sum('cost'), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($showAllLevels || in_array('5', $selectedLevels))
                        <div class="mb-6">
                            <div class="mb-6">
                                <h3 class="bg-light p-3 rounded">
                                    <span class="badge {{ in_array('5', $selectedLevels) && !$showAllLevels ? 'badge-primary' : 'badge-light' }} me-2">5</span>
                                    Level 5 - Return On Training Investment
                                </h3>
                            </div>

                            @foreach($diklatsForComparison as $diklat)
                            <div class="mb-5">
                                <h4 class="mb-3">{{ $diklat->name }}</h4>

                                @if($diklatData[$diklat->id]['score5'])
                                    <table class="mb-3">
                                        <tr class="p-3">
                                            <td class="p-3" style="width: 400px; background-color: #3699FF">Total Benefits</td>
                                            <td style="width: 150px; text-align: center">Rp. {{ number_format($diklatData[$diklat->id]['score5']->total_tangible, 2) }}</td>
                                        </tr>
                                        <tr class="p-3">
                                            <td class="p-3" style="width: 400px; background-color: #0BB7AF">Total Cost</td>
                                            <td style="width: 150px; text-align: center">Rp. {{ number_format($diklatData[$diklat->id]['score5']->cost_of_training, 2) }}</td>
                                        </tr>
                                        <tr class="p-3">
                                            <td class="p-3" style="width: 400px; background-color: #3699FF">Return On Training Investment</td>
                                            <td style="width: 150px; text-align: center">{{ $diklatData[$diklat->id]['score5']->roti }}%</td>
                                        </tr>
                                    </table>

                                    <p>Untuk setiap rupiah yang dikeluarkan dalam pelatihan, pemberi kerja mendapatkan kembali sebesar <span class="text-primary">Rp. {{ $diklatData[$diklat->id]['score5']->hasilroti, 2 }}</span> dalam bentuk manfaat dari <span class="text-primary">{{ implode(', ', $diklatData[$diklat->id]['score4Tangibles']->pluck('category')->toArray()) }}</span></p>
                                @else
                                    <p class="alert alert-info">Data ROI tidak tersedia untuk pelatihan ini.</p>
                                @endif
                            </div>
                            @endforeach

                            @if(count($diklatsForComparison) > 1)
                            <div class="mt-5">
                                <h4 class="mb-3">Perbandingan ROI Pelatihan</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="background-color: #FCB454">Pelatihan</td>
                                        <td style="background-color: #FCB454">Total Benefits</td>
                                        <td style="background-color: #FCB454">Total Cost</td>
                                        <td style="background-color: #FCB454">ROI</td>
                                    </tr>
                                    @foreach($diklatsForComparison as $diklat)
                                    <tr>
                                        <td>{{ $diklat->name }}</td>
                                        <td>
                                            @if($diklatData[$diklat->id]['score5'])
                                                Rp. {{ number_format($diklatData[$diklat->id]['score5']->total_tangible, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($diklatData[$diklat->id]['score5'])
                                                Rp. {{ number_format($diklatData[$diklat->id]['score5']->cost_of_training, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($diklatData[$diklat->id]['score5'])
                                                {{ $diklatData[$diklat->id]['score5']->roti }}%
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            @endif
            
        </div>
    </div>
@endsection
@section('script')
    <script>
        "use strict";

        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        // Initialize on document ready
        KTUtil.onDOMContentLoaded(function() {
            // Initialize select2 for better dropdown experience
            $('#diklat_ids').select2({
                placeholder: "Pilih pelatihan (bisa lebih dari satu)",
                allowClear: true
            });
            
            // Handle checkbox interactions
            $('#level_all').on('change', function() {
                if($(this).is(':checked')) {
                    // If "All Levels" is checked, disable individual level checkboxes
                    $('.level-check').prop('disabled', true);
                } else {
                    // If "All Levels" is unchecked, enable individual level checkboxes
                    $('.level-check').prop('disabled', false);
                }
            });
            
            // Initially set state based on current selection
            if($('#level_all').is(':checked')) {
                $('.level-check').prop('disabled', true);
            }
            
            // Handle form submission
            $('#reportFilterForm').on('submit', function(e) {
                // Make sure at least one checkbox is selected
                if ($('input[name="levels[]"]:checked').length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu level penilaian');
                    return false;
                }
                
                // Continue with form submission
                return true;
            });
        });
    </script>

    <script>
        "use strict";

        var report = function () {
            const initCharts = () => {
                @foreach($diklatsForComparison ?? [] as $diklat)
                var element1_{{ $diklat->id }} = document.getElementById('scoreList1_{{ $diklat->id }}');
                var element2_{{ $diklat->id }} = document.getElementById('scoreList2_{{ $diklat->id }}');

                if (element1_{{ $diklat->id }}) {
                    var height = parseInt(KTUtil.css(element1_{{ $diklat->id }}, 'height'));
                    
                    var options = {
                        series: @json($diklatData[$diklat->id]['scoreList1'] ?? []),
                        chart: {
                            fontFamily: 'inherit',
                            type: 'bar',
                            height: height,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: ['30%'],
                                endingShape: 'rounded'
                            },
                        },
                        legend: {
                            show: true // Changed to true to show series names
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: @json($scoreList1Category), // Static categories for x-axis
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false
                            },
                            labels: {
                                style: {
                                    colors: '#787878', // Static color for x-axis labels
                                    fontSize: '12px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#787878', // Static color for y-axis labels
                                    fontSize: '12px'
                                }
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        states: {
                            normal: {
                                filter: {
                                    type: 'none',
                                    value: 0
                                }
                            },
                            hover: {
                                filter: {
                                    type: 'none',
                                    value: 0
                                }
                            },
                            active: {
                                allowMultipleDataPointsSelection: false,
                                filter: {
                                    type: 'none',
                                    value: 0
                                }
                            }
                        },
                        tooltip: {
                            style: {
                                fontSize: '12px'
                            },
                            y: {
                                formatter: function (val) {
                                    return val + ' peserta'
                                }
                            }
                        },
                        colors: [
                            '#3699FF',  // Blue
                            '#F64E60',  // Red
                            '#1BC5BD',  // Green
                            '#8950FC',  // Purple
                            '#0BB7AF'   // Young Blue (Teal)
                        ], // Static colors for bars
                        grid: {
                            borderColor: '#E4E6EF', // Static border color
                            strokeDashArray: 4,
                            yaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(element1_{{ $diklat->id }}, options);
                    chart.render();
                }

                if (element2_{{ $diklat->id }}) {
                    var height = parseInt(KTUtil.css(element2_{{ $diklat->id }}, 'height'));
                    
                    var options = {
                        series: @json($diklatData[$diklat->id]['scoreList2'] ?? []),
                        chart: {
                            fontFamily: 'inherit',
                            type: 'bar',
                            height: height,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: ['30%'],
                                endingShape: 'rounded'
                            },
                        },
                        legend: {
                            show: true // Changed to true to show series names
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: @json($scoreList2Category), // Static categories for x-axis
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false
                            },
                            labels: {
                                style: {
                                    colors: '#787878', // Static color for x-axis labels
                                    fontSize: '12px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#787878', // Static color for y-axis labels
                                    fontSize: '12px'
                                }
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        states: {
                            normal: {
                                filter: {
                                    type: 'none',
                                    value: 0
                                }
                            },
                            hover: {
                                filter: {
                                    type: 'none',
                                    value: 0
                                }
                            },
                            active: {
                                allowMultipleDataPointsSelection: false,
                                filter: {
                                    type: 'none',
                                    value: 0
                                }
                            }
                        },
                        tooltip: {
                            style: {
                                fontSize: '12px'
                            },
                            y: {
                                formatter: function (val) {
                                    return val + ' peserta'
                                }
                            }
                        },
                        colors: [
                            '#FF5733',  // Red
                            '#33FF57',  // Green
                            '#3357FF',  // Blue
                            '#F1C40F',  // Yellow
                            '#8E44AD'   // Purple
                        ], // Static colors for bars
                        grid: {
                            borderColor: '#E4E6EF', // Static border color
                            strokeDashArray: 4,
                            yaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(element2_{{ $diklat->id }}, options);
                    chart.render();
                }
                @endforeach
            };
            
            return {
                init: function () {
                    initCharts();
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function () {
            report.init();
        });
    </script>
@endsection
