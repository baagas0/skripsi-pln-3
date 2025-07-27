@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome to the dashboard!</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <label for="year" class="form-label text-muted">Tahun</label>
                <select name="year" id="" class="form-control" style="min-width: 100px; width: 100px">
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal">
                    <!--end::Svg Icon-->
                    Realisasi & Rencana
                </a>
            </div>
        </div>

        <div class="row g-5 g-xl-8">
            <div class="col-xl-4">
                <div style="background-color: #28a8e0" class="card card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body my-3">
                        <a href="#" class="card-title fw-bold text-white fs-5 mb-3 d-block">Jumlah Rencana</a>
                        <div class="py-1">
                            <span class="text-white fs-1 fw-bold me-2">{{ $diklatPlanningCount }}</span>
                            <span class="fw-semibold text-white fs-7">Rencana Diklat</span>
                        </div>
                    </div>
                    <!--end:: Body-->
                </div>
            </div>
            <div class="col-xl-4">
                <div style="background-color: #28a8e0" class="card card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body my-3">
                        <a href="#" class="card-title fw-bold text-white fs-5 mb-3 d-block">Jumlah Realisasi</a>
                        <div class="py-1">
                            <span class="text-white fs-1 fw-bold me-2">{{ $diklatCount }}</span>
                            <span class="fw-semibold text-white fs-7">Realisasi Diklat</span>
                        </div>
                    </div>
                    <!--end:: Body-->
                </div>
            </div>
            <div class="col-xl-4">
                <div style="background-color: #28a8e0" class="card card-xl-stretch mb-xl-8">
                    <div class="card-body my-3">
                        <a href="#" class="card-title fw-bold text-white fs-5 mb-3 d-block">Presentase Jumlah Realisasi Terhadap Rencana</a>
                        <div class="py-1">
                            <span class="text-white fs-1 fw-bold me-2">{{ $diklatPercentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div style="background-color: #28a8e0" class="card card-xl-stretch mb-xl-8">
                    <div class="card-body my-3">
                        <a href="#" class="card-title fw-bold text-white fs-5 mb-3 d-block">Jumlah Peserta</a>
                        <div class="py-1">
                            <span class="text-white fs-1 fw-bold me-2">{{ $employeeCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div style="background-color: #28a8e0" class="card card-xl-stretch mb-xl-8">
                    <div class="card-body my-3">
                        <a href="#" class="card-title fw-bold text-white fs-5 mb-3 d-block">Jumlah Sertifikat</a>
                        <div class="py-1">
                            <span class="text-white fs-1 fw-bold me-2">{{ $certificateCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="kt_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Data Realisasi & Rencana</h5>
    
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span class="svg-icon svg-icon-2x"></span>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="modal-body">
                        <div id="line-chart" style="height: 500px;"></div>
                        <div id="pie-chart" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('select[name="year"]').on('change', function() {
                var year = $(this).val();
                window.location.href = "{{ route('dashboard') }}?year=" + year;
            });
        });

        // Chart data that will be shared between both charts
        var chartData = [
            {
                category: "Sudah Dibayarkan",
                value: {{ $sudahDibayar }},
                color: am5.color(KTUtil.getCssVariableValue('--bs-danger'))
            },
            {
                category: "Vendor Sudah Melakukan Penagihan",
                value: {{ $sudahTagih }},
                color: am5.color(KTUtil.getCssVariableValue('--bs-primary'))
            },
            {
                category: "Pelatihan Belum Dilaksanakan (Dalam Perencanaan)",
                value: {{ $totalDiklatPlanning }},
                color: am5.color(KTUtil.getCssVariableValue('--bs-warning'))
            },
            {
                category: "Pelatihan Belum Dilaksanakan (Sudah Berkontrak Surat)",
                value: {{ $totalDiklat }},
                color: am5.color(KTUtil.getCssVariableValue('--bs-success'))
            },
            {
                category: "Vendor Belum Mengirim Tagihan",
                value: {{ $belumTagih }},
                color: am5.color(KTUtil.getCssVariableValue('--bs-info'))
            },
        ];

        var initColumnChart = function () {
            if (typeof am5 === "undefined") {
                console.warn("Warning - amCharts library is not loaded.");
                return;
            }

            var element = document.getElementById("line-chart");
            if (!element) {
                return;
            }

            var init = function() {
                // Create root element
                var root = am5.Root.new(element);
                
                // Set themes
                root.setThemes([am5themes_Animated.new(root)]);

                // Create chart
                var chart = root.container.children.push(
                    am5xy.XYChart.new(root, {
                        panX: false,
                        panY: false,
                        layout: root.verticalLayout,
                    })
                );

                // Create axes
                var xAxis = chart.xAxes.push(
                    am5xy.CategoryAxis.new(root, {
                        categoryField: "category",
                        renderer: am5xy.AxisRendererX.new(root, {
                            minGridDistance: 30,
                        }),
                    })
                );

                xAxis.get("renderer").labels.template.setAll({
                    paddingTop: 20,                
                    fontWeight: "400",
                    fontSize: 10,
                    fill: am5.color(KTUtil.getCssVariableValue('--bs-gray-500')),
                    rotation: -45,
                });
                
                xAxis.get("renderer").grid.template.setAll({
                    disabled: true,
                    strokeOpacity: 0
                });

                var yAxis = chart.yAxes.push(
                    am5xy.ValueAxis.new(root, {
                        renderer: am5xy.AxisRendererY.new(root, {}),
                    })
                );

                yAxis.get("renderer").grid.template.setAll({
                    stroke: am5.color(KTUtil.getCssVariableValue('--bs-gray-300')),
                    strokeWidth: 1,
                    strokeOpacity: 1,
                    strokeDasharray: [3]
                });

                yAxis.get("renderer").labels.template.setAll({
                    fontWeight: "400",
                    fontSize: 10,
                    fill: am5.color(KTUtil.getCssVariableValue('--bs-gray-500'))
                });

                // Add series
                var series = chart.series.push(
                    am5xy.ColumnSeries.new(root, {
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: "value",
                        categoryXField: "category"
                    })
                );

                series.columns.template.setAll({
                    tooltipText: "{categoryX}: {valueY}",
                    tooltipY: 0,
                    strokeOpacity: 0,
                    width: am5.percent(90),
                    templateField: "columnSettings",
                });

                series.columns.template.adapters.add("fill", function(fill, target) {
                    return target.dataItem.dataContext.color;
                });

                series.columns.template.setAll({
                    strokeOpacity: 0,
                    cornerRadiusBR: 0,
                    cornerRadiusTR: 6,
                    cornerRadiusBL: 0,
                    cornerRadiusTL: 6,
                });

                // Set data
                xAxis.data.setAll(chartData);
                series.data.setAll(chartData);

                // Make stuff animate on load
                series.appear();
                chart.appear(1000, 100);
            }

            am5.ready(function () {
                init();
            });
        };

        var initPieChart = function() {
            if (typeof am5 === "undefined") {
                console.warn("Warning - amCharts library is not loaded.");
                return;
            }
        
            var element = document.getElementById("pie-chart");
            if (!element) {
                return;
            }
        
            var init = function() {
                // Create root element
                var root = am5.Root.new(element);
                
                // Set themes
                root.setThemes([am5themes_Animated.new(root)]);
        
                // Create chart
                var chart = root.container.children.push(
                    am5percent.PieChart.new(root, {
                        layout: root.horizontalLayout,
                        innerRadius: am5.percent(40)
                    })
                );
        
                // Create series
                var series = chart.series.push(
                    am5percent.PieSeries.new(root, {
                        valueField: "value",
                        categoryField: "category",
                        alignLabels: false,
                        legendLabelText: "{category}",
                        legendValueText: "{value}"
                    })
                );
        
                // Hide the regular labels on slices
                series.labels.template.set("forceHidden", true);
        
                // Set custom colors for each slice
                series.slices.template.adapters.add("fill", function(fill, target) {
                    return target.dataItem.dataContext.color;
                });
        
                // Add legend at the bottom
                var legend = chart.children.push(am5.Legend.new(root, {
                    centerX: am5.percent(50),
                    x: am5.percent(50),
                    y: am5.percent(100),
                    layout: root.horizontalLayout,
                    height: 100,
                    verticalCenter: "bottom",
                    marginTop: 20
                }));
                legend.data.setAll(series.dataItems);
        
                // Set data
                series.data.setAll(chartData);
        
                // Make stuff animate on load
                series.appear(1000, 100);
            };
        
            am5.ready(function () {
                init();
            });
        };

        KTUtil.onDOMContentLoaded(function () {
            initColumnChart();
            initPieChart();
        });
    </script>
@endsection