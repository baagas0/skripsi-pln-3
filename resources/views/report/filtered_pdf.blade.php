<html>
    @include('layouts.head')

    <body>        @php
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
            
            // Get diklat name
            $diklat = \App\Models\Diklat::find($diklatId);
            $diklatName = $diklat ? $diklat->name : 'Pelatihan';
        @endphp
        
        <div class="text-center mb-5">
            <h2>Laporan Penilaian Pelatihan</h2>
            <h3>{{ $diklatName }}</h3>
            <h4>{{ $currentLevelLabel }}</h4>
        </div>
        
        <div class="">            @if($showAllLevels || in_array('1', $selectedLevels))
            <div class="mb-6">
                <div class="mb-6">
                    <h3>Level 1 - Reaction</h3>
                </div>
                <div class="border rounded p-6 text-center mb-3">
                    <h4>Distribusi Skala Penilaian Pelaksanaan Pelatihan</h4>
                    <div id="scoreList1" style="height: 350px;"></div>
                </div>
                <div class="border rounded p-6 text-center">
                    <h4>Distribusi Skala Penilaian Narasumber Pelatihan</h4>
                    <div id="scoreList2" style="height: 350px;"></div>
                </div>
            </div>
            @endif            @if($showAllLevels || in_array('2', $selectedLevels))
            <div class="mb-6">
                <div class="mb-6">
                    <h3>Level 2 - Learning</h3>
                </div>
                <table>
                    <tr class="p-3">
                        <td class="p-3" style="width: 200px; background-color: #3699FF">Rata-rata Nilai Pre-Test</td>
                        <td style="width: 80px; text-align: center">{{ $avgPretest }}</td>
                    </tr>
                    <tr class="p-3">
                        <td class="p-3" style="width: 200px; background-color: #0BB7AF">Rata-rata Nilai Post-Test</td>
                        <td style="width: 80px; text-align: center">{{ $avgPosttest }}</td>
                    </tr>
                    <tr class="p-3">
                        <td class="p-3" style="width: 200px; background-color: #3699FF">Peningkatan Nilai</td>
                        <td style="width: 80px; text-align: center">{{ $avgLevel2 }}</td>
                    </tr>
                </table>
            </div>
            @endif            @if($showAllLevels || in_array('3', $selectedLevels))
            <div class="mb-6">
                <div class="mb-6">
                    <h3>Level 3 - Behavior</h3>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align:middle">Kategori</td>
                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle">Rentang Nilai</td>
                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle">Jumlah Peserta</td>
                    </tr>
                    @foreach ($scoreList3Result as $item)
                    <tr>
                        <td class="p-3" style="vertical-align: middle">{{ $item['name'] }}</td>
                        <td class="p-3" style="vertical-align: middle">{{ $item['range'] }}</td>
                        <td class="p-3" style="vertical-align: middle">{{ $item['count'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle">Total</td>
                        <td class="p-3" style="width: 300px; background-color: #3699FF; vertical-align: middle"></td>
                        <td class="p-3 d-flex justify-content-between align-items-center" style="width: 100%; background-color: #3699FF; vertical-align: middle">
                            {{ $scoreList3Result->sum('count') }}
                        </td>
                    </tr>
                </table>
            </div>
            @endif            @if($showAllLevels || in_array('4', $selectedLevels))
            <div class="mb-6">
                <div class="mb-6">
                    <h3>Level 4 - Result</h3>
                </div>
                <p>1. Program pelatihan ini <span class="text-primary fw-bold fs-4">{{ $score4Label }}</span> pada unit.</p>
                <p>2. Program pelatihan ini berdampak bagi peserta pada aspek <span class="text-primary">{{ implode(',', $score4Impacts) }}</span></p>

                <table class="table table-bordered">
                    <tr>
                        <td style="text-align: center; background-color: #3699FF" colspan="2">Tangible Benefits</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; background-color: #0BB7AF">Tangible Returns Categories</td>
                        <td style="text-align: center; background-color: #0BB7AF">Total</td>
                    </tr>
                    @foreach ($score4Tangibles as $item)
                    <tr>
                        <td style="width: 300px">{{ $item->category }}</td>
                        <td class="d-flex justify-content-between" style="width: 100%">
                            <p>Rp.</p>
                            <p>{{ number_format($item->cost, 2) }}</p>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="width: 300px; background-color: #3699FF">Total Benefits</td>
                        <td class="d-flex justify-content-between align-items-center" style="width: 100%; background-color: #3699FF">
                            <p>Rp.</p>
                            <p>{{ number_format($score4Tangibles->sum('cost'), 2) }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            @endif            @if($showAllLevels || in_array('5', $selectedLevels))
            <div class="mb-6">
                <div class="mb-6">
                    <h3>Level 5 - Return On Training Investment</h3>
                </div>

                @if($score5)
                    <table class="mb-3">
                        <tr class="p-3">
                            <td class="p-3" style="width: 400px; background-color: #3699FF">Total Benefits</td>
                            <td style="width: 150px; text-align: center">Rp. {{ number_format($score5->total_tangible, 2) }}</td>
                        </tr>
                        <tr class="p-3">
                            <td class="p-3" style="width: 400px; background-color: #0BB7AF">Total Cost</td>
                            <td style="width: 150px; text-align: center">Rp. {{ number_format($score5->cost_of_training, 2) }}</td>
                        </tr>
                        <tr class="p-3">
                            <td class="p-3" style="width: 400px; background-color: #3699FF">Return On Training Investment</td>
                            <td style="width: 150px; text-align: center">{{ $score5->roti }}%</td>
                        </tr>
                    </table>

                    <p>Untuk setiap rupiah yang dikeluarkan dalam pelatihan, pemberi kerja mendapatkan kembali sebesar <span class="text-primary">Rp. {{ number_format($score5->roti, 2) }}</span> dalam bentuk manfaat dari <span class="text-primary">{{ implode(',', $score4Impacts) }}</span></p>
                @endif
            </div>
            @endif
        </div>

    </body>
    @include('layouts.script')
    <script>
        "use strict";

        var report = function () {
            const level_1_reaction = () => {
                var element = document.getElementById('scoreList1');
                var element2 = document.getElementById('scoreList2');

                if (!element || !element2) {
                    return;
                }

                var height = parseInt(KTUtil.css(element, 'height'));
                var height2 = parseInt(KTUtil.css(element2, 'height'));

                var options = {
                    series: @json($scoreList1),
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

                var chart = new ApexCharts(element, options);
                chart.render();

                var option2 = { ...options };
                option2.chart.height = height2;
                option2.series = @json($scoreList2);
                option2.xaxis.categories = @json($scoreList2Category); // Static categories for x-axis
                option2.colors = [
                    '#FF5733',  // Red
                    '#33FF57',  // Green
                    '#3357FF',  // Blue
                    '#F1C40F',  // Yellow
                    '#8E44AD'   // Purple
                ]; // Static colors for bars
                var chart2 = new ApexCharts(element2, option2);
                chart2.render();
            };
            return {
                init: function () {
                    level_1_reaction();
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function () {
            report.init();
        });
    </script>
</html>
