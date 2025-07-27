@extends('layouts.main')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="dashboard-header">
            <h1 class="display-6 fw-bold text-dark mb-2">Dashboard</h1>
            <p class="text-muted fs-6">Selamat datang di dashboard manajemen diklat</p>
        </div>
        <div class="d-flex align-items-center gap-4">
            <!-- Year Range Filter with improved styling -->
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-semibold text-gray-600 mb-0">Tahun</label>
                <select name="year_start" class="form-select form-select-sm border-0 bg-light px-3 py-2"
                    style="min-width: 120px">
                    <option value="all">Semua</option>
                    @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $year==$yearStart ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <span class="text-gray-400 fw-bold">-</span>
                <select name="year_end" class="form-select form-select-sm border-0 bg-light px-3 py-2"
                    style="min-width: 120px">
                    <option value="all">Semua</option>
                    @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $year==$yearEnd ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Diklat Type Filter with improved styling -->
            <div class="d-flex align-items-center gap-3">
                <label class="form-label fw-semibold text-gray-600 mb-0">Jenis Diklat</label>
                <select name="diklat_type" class="form-select form-select-sm border-0 bg-light px-3 py-2"
                    style="min-width: 220px">
                    <option value="all">Semua Jenis</option>
                    <option value="Pelatihan" {{ $diklatType=='Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                    <option value="Pelatihan & Sertifikasi" {{ $diklatType=='Pelatihan & Sertifikasi' ? 'selected' : ''
                        }}>
                        Pelatihan & Sertifikasi
                    </option>
                </select>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards with enhanced styling -->
    <div class="row g-5 g-xl-8">
        <!-- Realization Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm hover-elevate-up">
                <div class="card-body p-5 rounded-4"
                    style="background: linear-gradient(135deg, #009ef7 0%, #0077cd 100%)">
                    <div class="d-flex flex-column">
                        <div class="text-white fw-bold fs-5 mb-3">Realisasi vs Perencanaan</div>
                        <div class="d-flex align-items-center mt-2">
                            <span class="text-white fs-1 fw-bolder me-2 lh-1">{{ $diklatPercentage }}%</span>
                            <div class="ms-2">
                                <span class="text-white-50 fw-semibold fs-7 d-block">Realisasi</span>
                                <div class="progress h-6px w-100 mt-2 bg-white bg-opacity-10">
                                    <div class="progress-bar bg-white" role="progressbar"
                                        style="width: {{ $diklatPercentage }}%" aria-valuenow="{{ $diklatPercentage }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participant Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm hover-elevate-up cursor-pointer participant-card">
                <div class="card-body p-5 rounded-4"
                    style="background: linear-gradient(135deg, #50cd89 0%, #3aa163 100%)">
                    <div class="d-flex flex-column">
                        <div class="text-white fw-bold fs-5 mb-3">Jumlah Peserta (Kumulatif)</div>
                        <div class="d-flex align-items-center mt-2">
                            <span class="text-white fs-1 fw-bolder me-2 lh-1">{{ number_format($employeeCount) }}</span>
                            <span class="text-white-50 fw-semibold fs-7 ms-2">Peserta</span>
                        </div>
                        <div class="text-white-50 fs-7 mt-3">
                            <i class="fas fa-info-circle me-1"></i> Klik untuk detail
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm hover-elevate-up cursor-pointer budget-card">
                <div class="card-body p-5 rounded-4"
                    style="background: linear-gradient(135deg, #f1416c 0%, #c81f46 100%)">
                    <div class="d-flex flex-column">
                        <div class="text-white fw-bold fs-5 mb-3">Total Anggaran Disetujui</div>
                        <div class="d-flex align-items-center mt-2">
                            <span class="text-white fs-1 fw-bolder me-2 lh-1">Rp {{ number_format($totalApprovedBudget,
                                0, ',', '.') }}</span>
                        </div>
                        <div class="text-white-50 fs-7 mt-3">
                            <i class="fas fa-chart-line me-1"></i> Klik untuk grafik
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm hover-elevate-up">
                <div class="card-body p-5 rounded-4"
                    style="background: linear-gradient(135deg, #ffc700 0%, #e6b400 100%)">
                    <div class="d-flex flex-column">
                        <div class="text-white fw-bold fs-5 mb-3">Total Sertifikat</div>
                        <div class="d-flex align-items-center mt-2">
                            <span class="text-white fs-1 fw-bolder me-2 lh-1">{{ number_format($certificateCount)
                                }}</span>
                            <span class="text-white-50 fw-semibold fs-7 ms-2">Sertifikat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5 g-xl-8 mt-0">
        <!-- Total Diklat Terlaksana -->
        <div class="col-xl-6 col-md-6">
            <div class="card h-100 shadow-sm hover-elevate-up">
                <div class="card-body p-5 rounded-4"
                    style="background: linear-gradient(135deg, #7239ea 0%, #5a2ac5 100%)">
                    <div class="d-flex flex-column">
                        <div class="text-white fw-bold fs-5 mb-3">Total Diklat Terlaksana</div>
                        <div class="d-flex align-items-center mt-2">
                            <span class="text-white fs-1 fw-bolder me-2 lh-1">{{ number_format($diklatCount) }}</span>
                            <span class="text-white-50 fw-semibold fs-7 ms-2">Diklat</span>
                        </div>
                        <div class="text-white-50 fs-7 mt-3">
                            <i class="fas fa-calendar-check me-1"></i> Periode {{ $yearStart }} - {{ $yearEnd }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Diklat Direncanakan -->
        <div class="col-xl-6 col-md-6">
            <div class="card h-100 shadow-sm hover-elevate-up">
                <div class="card-body p-5 rounded-4"
                    style="background: linear-gradient(135deg, #1bc5bd 0%, #138b85 100%)">
                    <div class="d-flex flex-column">
                        <div class="text-white fw-bold fs-5 mb-3">Total Diklat Direncanakan</div>
                        <div class="d-flex align-items-center mt-2">
                            <span class="text-white fs-1 fw-bolder me-2 lh-1">{{ number_format($diklatPlanningCount)
                                }}</span>
                            <span class="text-white-50 fw-semibold fs-7 ms-2">Diklat</span>
                        </div>
                        <div class="text-white-50 fs-7 mt-3">
                            <i class="fas fa-calendar me-1"></i> Dalam Perencanaan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Charts Section with improved styling -->
    <div class="row mt-5 g-5">
        <!-- Budget Distribution Chart -->
        <div class="col-xl-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Distribusi Anggaran</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Statistik pengeluaran anggaran</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="line-chart" style="height: 350px"></div>
                </div>
            </div>
        </div>

        <!-- Payment Status Chart -->
        <div class="col-xl-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Status Pembayaran</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Persentase status pembayaran diklat</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="pie-chart" style="height: 350px"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Chart Modal -->
    <div class="modal fade" tabindex="-1" id="budgetModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Grafik Anggaran Diklat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="budget-chart" style="height: 500px"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Participant Details Modal -->
    <div class="modal fade" tabindex="-1" id="participantModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Peserta Diklat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th>Nama Diklat</th>
                                    <th>Jenis Diklat</th>
                                    <th class="text-end">Jumlah Peserta</th>
                                    <th class="text-end">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participantDetails as $detail)
                                <tr>
                                    <td>{{ $detail->name }}</td>
                                    <td>{{ $detail->diklat_type }}</td>
                                    <td class="text-end">{{ number_format($detail->count_of_participant) }}</td>
                                    <td class="text-end">{{
                                        \Carbon\Carbon::parse($detail->estimate_start_date)->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Handle filter changes
        $('select[name="year_start"], select[name="year_end"], select[name="diklat_type"]').on('change', function() {
            var yearStart = $('select[name="year_start"]').val();
            var yearEnd = $('select[name="year_end"]').val();
            var diklatType = $('select[name="diklat_type"]').val();

            if (yearStart !== 'all' && yearEnd !== 'all') {
                if (parseInt(yearStart) > parseInt(yearEnd)) {
                    toastr.error('Tahun awal tidak boleh lebih besar dari tahun akhir');
                    return;
                }
            }

            window.location.href = "{{ route('dashboard') }}?" + $.param({
                year_start: yearStart,
                year_end: yearEnd,
                diklat_type: diklatType
            });
        });

        // Handle modal triggers
        $('.participant-card').click(function() {
            $('#participantModal').modal('show');
        });

        $('.budget-card').click(function() {
            $('#budgetModal').modal('show');
            initBudgetChart();
        });

        // Initialize charts
        initColumnChart();
        initPieChart();
    });

    var chartData = [
        {
            "category": "Sudah dibayarkan",
            "value": {{ number_format($sudahDibayar, 0, '', '') }}
        },
        {
            "category": "Sudah dilaksanakan, sudah tertagih, belum terbayar",
            "value": {{ number_format($sudahDilaksanakanSudahTagih, 0, '', '') }}
        },
        {
            "category": "Sudah dilaksanakan belum tertagih atau pelatihan dalam proses",
            "value": {{ number_format($sudahDilaksanakanBelumTagih, 0, '', '') }}
        },
        {
            "category": "Pelatihan Belum dilaksanakan (belum tertagih)",
            "value": {{ number_format($belumDilaksanakan, 0, '', '') }}
        }
    ];

    // Update Column Chart initialization
    var initColumnChart = function() {
    if (typeof am5 === "undefined") {
        return;
    }

    var element = document.getElementById("line-chart");
    if (!element) {
        return;
    }

    am5.ready(function() {
        var root = am5.Root.new(element);
        root.setThemes([am5themes_Animated.new(root)]);

        // Create chart
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            layout: root.verticalLayout,
            paddingRight: 20,
            paddingLeft: 20,
            height: am5.percent(100)
        }));

        // Create axes
        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            categoryField: "category",
            renderer: am5xy.AxisRendererX.new(root, {
                minGridDistance: 20,
                cellStartLocation: 0.2,
                cellEndLocation: 0.8
            })
        }));

        // Modify X axis labels
        xAxis.get("renderer").labels.template.setAll({
            paddingTop: 15,
            fontWeight: "500",
            fontSize: 12,
            fill: am5.color("#3F4254"),
            rotation: -45,
            maxWidth: 150,
            wrap: true,
            oversizedBehavior: "wrap"
        });

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0,
            min: 0,
            extraMax: 0.1,
            renderer: am5xy.AxisRendererY.new(root, {
                strokeOpacity: 0.1
            })
        }));

        yAxis.get("renderer").labels.template.setAll({
            fontSize: 12,
            fontWeight: "500",
            fill: am5.color("#3F4254"),
            formatter: am5.NumberFormatter.new(root, {
                numberFormat: "Rp #,###.##",
                numericFields: ["valueY"]
            })
        });

        // Create series
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Anggaran",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            categoryXField: "category",
            tooltip: am5.Tooltip.new(root, {
                labelText: "Rp {valueY}",
                getFillFromSprite: false,
                autoTextColor: false
            })
        }));

        // Set columns appearance
        series.columns.template.setAll({
            cornerRadiusTL: 4,
            cornerRadiusTR: 4,
            strokeOpacity: 0,
            fillOpacity: 1,
            width: am5.percent(70),
            tooltipY: 0
        });

        // Custom column appearance
        series.columns.template.set("fillGradient", am5.LinearGradient.new(root, {
            stops: [{
                opacity: 1,
                color: am5.color("#009ef7")
            }, {
                opacity: 0.7,
                color: am5.color("#0095e8")
            }],
            rotation: 90
        }));

        // Add hover state
        series.columns.template.states.create("hover", {
            fillOpacity: 0.9,
            strokeOpacity: 0.3,
            stroke: am5.color("#009ef7")
        });

        // Set tooltip appearance
        series.get("tooltip").set("background", am5.Rectangle.new(root, {
            fill: am5.color("#131313"),
            fillOpacity: 0.8,
            stroke: am5.color("#ffffff"),
            strokeOpacity: 0.2
        }));
        
        series.get("tooltip").label.setAll({
            fill: am5.color("#ffffff")
        });

        // Set data
        xAxis.data.setAll(chartData);
        series.data.setAll(chartData);

        // Make stuff animate on load
        series.appear(1000, 100);
        chart.appear(1000, 100);
    });
};

    // Payment Status Pie Chart
    var initPieChart = function() {
        if (typeof am5 === "undefined") {
            return;
        }

        var element = document.getElementById("pie-chart");
        if (!element) {
            return;
        }

        am5.ready(function() {
            var root = am5.Root.new(element);
            root.setThemes([am5themes_Animated.new(root)]);

            var chart = root.container.children.push(
                am5percent.PieChart.new(root, {
                    layout: root.verticalLayout,
                    innerRadius: am5.percent(50)
                })
            );

            var series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    endAngle: 270
                })
            );

            series.states.create("hidden", {
                endAngle: -90
            });

            series.data.setAll(chartData);
            series.appear(1000, 100);
        });
    };

    // Budget Chart initialization
    var initBudgetChart = function() {
        if (typeof am5 === "undefined") {
            return;
        }

        var element = document.getElementById("budget-chart");
        if (!element) {
            return;
        }

        am5.ready(function() {
            var root = am5.Root.new(element);
            root.setThemes([am5themes_Animated.new(root)]);

            var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "none",
                    wheelY: "none",
                    layout: root.verticalLayout
                })
            );

            var data = @json($monthlyBudgetData);

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "month",
                renderer: am5xy.AxisRendererX.new(root, {
                    minGridDistance: 30
                })
            }));

            xAxis.get("renderer").labels.template.setAll({
                rotation: -45,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 15
            });

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Budget",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                categoryXField: "month",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "Rp {valueY}"
                })
            }));

            series.columns.template.setAll({
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                strokeOpacity: 0
            });

            xAxis.data.setAll(data);
            series.data.setAll(data);

            series.appear(1000);
            chart.appear(1000, 100);
        });
    };
</script>
<style>
    #line-chart {
        height: 450px !important;
        width: 100%;
        margin: 20px 0;
    }

    .container {
        padding: 0 !important;
        max-width: 100% !important;
    }

    .card {
        border: none;
        margin: 0;
        border-radius: 15px;
        overflow: hidden;
    }

    .card-body {
        padding: 2rem;
        border-radius: 15px;
    }

    .row {
        margin: 0;
    }

    .col-xl-3,
    .col-md-6 {
        padding: 10px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px !important;
        }
    }

    .hover-elevate-up {
        transition: transform 0.3s ease;
    }

    .hover-elevate-up:hover {
        transform: translateY(-5px);
    }

    .rounded-4 {
        border-radius: 15px !important;
    }

    .h-6px {
        height: 6px !important;
    }

    .form-select {
        box-shadow: 0 0.5rem 1.5rem 0.5rem rgb(0 0 0 / 0.075);
    }

    .form-select:focus {
        box-shadow: 0 0.5rem 1.5rem 0.5rem rgb(0 0 0 / 0.075);
    }

    .table-row-bordered td {
        padding: 1rem;
    }

    .table-row-bordered tr:not(:last-child) td {
        border-bottom: 1px dashed #e4e6ef;
    }

    .table-row-bordered th {
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        background-color: #f5f8fa;
    }

    .modal-content {
        border: none;
        box-shadow: 0 0.5rem 1.5rem 0.5rem rgb(0 0 0 / 0.075);
    }

    .modal-header {
        border-bottom: 1px dashed #e4e6ef;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }
</style>
@endsection