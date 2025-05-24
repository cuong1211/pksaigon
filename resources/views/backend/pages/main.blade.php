@extends('backend.layout.index')
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div class="container-xxl" id="kt_content_container">
                <div class="row g-5 g-xl-8">
                    <div class="col-xl-8">
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Doanh thu 7 ngày gần nhất</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div id="kt_apexcharts_3" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Top sản phẩm bán chạy nhất</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="kt_chartjs_3" class="mh-400px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row g-5 g-xl-8">
                    <div class="col-xl-4">
                        <div class="row">
                            <div class="col-xl-12">
                                <!--begin::Statistics Widget 5-->
                                <a href="#" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body">
                                        <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm002.svg-->
                                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M21 10H13V11C13 11.6 12.6 12 12 12C11.4 12 11 11.6 11 11V10H3C2.4 10 2 10.4 2 11V13H22V11C22 10.4 21.6 10 21 10Z"
                                                    fill="black" />
                                                <path opacity="0.3"
                                                    d="M12 12C11.4 12 11 11.6 11 11V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V11C13 11.6 12.6 12 12 12Z"
                                                    fill="black" />
                                                <path opacity="0.3"
                                                    d="M18.1 21H5.9C5.4 21 4.9 20.6 4.8 20.1L3 13H21L19.2 20.1C19.1 20.6 18.6 21 18.1 21ZM13 18V15C13 14.4 12.6 14 12 14C11.4 14 11 14.4 11 15V18C11 18.6 11.4 19 12 19C12.6 19 13 18.6 13 18ZM17 18V15C17 14.4 16.6 14 16 14C15.4 14 15 14.4 15 15V18C15 18.6 15.4 19 16 19C16.6 19 17 18.6 17 18ZM9 18V15C9 14.4 8.6 14 8 14C7.4 14 7 14.4 7 15V18C7 18.6 7.4 19 8 19C8.6 19 9 18.6 9 18Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <div class="text-white fw-bolder fs-2 mb-2 mt-5">{{ $orderCount }}</div>
                                        <div class="fw-bold text-white">Tổng số đơn hàng tháng {{ date('m-Y') }}</div>
                                    </div>
                                    <!--end::Body-->
                                </a>
                                <!--end::Statistics Widget 5-->
                            </div>
                            <div class="col-xl-12">
                                <!--begin::Statistics Widget 5-->
                                <a href="#" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body">
                                        <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M18 21.6C16.3 21.6 15 20.3 15 18.6V2.50001C15 2.20001 14.6 1.99996 14.3 2.19996L13 3.59999L11.7 2.3C11.3 1.9 10.7 1.9 10.3 2.3L9 3.59999L7.70001 2.3C7.30001 1.9 6.69999 1.9 6.29999 2.3L5 3.59999L3.70001 2.3C3.50001 2.1 3 2.20001 3 3.50001V18.6C3 20.3 4.3 21.6 6 21.6H18Z"
                                                    fill="black" />
                                                <path
                                                    d="M12 12.6H11C10.4 12.6 10 12.2 10 11.6C10 11 10.4 10.6 11 10.6H12C12.6 10.6 13 11 13 11.6C13 12.2 12.6 12.6 12 12.6ZM9 11.6C9 11 8.6 10.6 8 10.6H6C5.4 10.6 5 11 5 11.6C5 12.2 5.4 12.6 6 12.6H8C8.6 12.6 9 12.2 9 11.6ZM9 7.59998C9 6.99998 8.6 6.59998 8 6.59998H6C5.4 6.59998 5 6.99998 5 7.59998C5 8.19998 5.4 8.59998 6 8.59998H8C8.6 8.59998 9 8.19998 9 7.59998ZM13 7.59998C13 6.99998 12.6 6.59998 12 6.59998H11C10.4 6.59998 10 6.99998 10 7.59998C10 8.19998 10.4 8.59998 11 8.59998H12C12.6 8.59998 13 8.19998 13 7.59998ZM13 15.6C13 15 12.6 14.6 12 14.6H10C9.4 14.6 9 15 9 15.6C9 16.2 9.4 16.6 10 16.6H12C12.6 16.6 13 16.2 13 15.6Z"
                                                    fill="black" />
                                                <path
                                                    d="M15 18.6C15 20.3 16.3 21.6 18 21.6C19.7 21.6 21 20.3 21 18.6V12.5C21 12.2 20.6 12 20.3 12.2L19 13.6L17.7 12.3C17.3 11.9 16.7 11.9 16.3 12.3L15 13.6V18.6Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <div class="text-white fw-bolder fs-2 mb-2 mt-5">{{ number_format($totalOrder, 0, '.', '.') }} VNĐ</div>
                                        <div class="fw-bold text-white">Doanh thu tháng {{ date('m-Y') }}</div>
                                    </div>
                                    <!--end::Body-->
                                </a>
                                <!--end::Statistics Widget 5-->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Doanh thu theo mặt hàng</span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="kt_chartjs_category" class="mh-400px"></canvas>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Tỉ lệ hoàn thành đơn hàng</span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="kt_chartjs_order" class="mh-400px"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--end::Container-->
                <!--end::Post-->
            </div>

        </div>
    </div>
@endsection
@push('jscustom')
    <script>
        function number_format(number, decimals, dec_point, thousands_sep) {
            // This ensures we have a number and it respects the desired number of decimal places
            number = Number(number).toFixed(decimals);

            // Split the number into the integer part and decimal part (if any)
            let parts = number.split('.');
            let integerPart = parts[0];
            let decimalPart = parts.length > 1 ? dec_point + parts[1] : '';

            // Add thousands separator
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);

            return integerPart + decimalPart;
        }

        function sale_chart() {
            var element = document.getElementById('kt_apexcharts_3');

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
            var baseColor = KTUtil.getCssVariableValue('--bs-info');
            var lightColor = KTUtil.getCssVariableValue('--bs-light-info');
            // if (!element) {
            //     return 0;
            // }

            var options = {
                series: [{
                    name: 'Doanh thu',
                    data: {!! json_encode($total) !!}
                }],
                chart: {
                    fontFamily: 'inherit',
                    type: 'area',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: 'solid',
                    opacity: 1
                },
                stroke: {
                    curve: 'smooth',
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: {!! json_encode($date) !!},
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    },
                    crosshairs: {
                        position: 'front',
                        stroke: {
                            color: baseColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    }
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
                        formatter: function(val) {
                            return '' + number_format(val, 0, '.', '.') + 'VNĐ'
                        }
                    }
                },
                colors: [lightColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    strokeColor: baseColor,
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        }

        function product_chart() {
            var ctx = document.getElementById('kt_chartjs_3');

            // Define colors
            var primaryColor = KTUtil.getCssVariableValue('--bs-primary');
            var dangerColor = KTUtil.getCssVariableValue('--bs-danger');
            var successColor = KTUtil.getCssVariableValue('--bs-success');
            var warningColor = KTUtil.getCssVariableValue('--bs-warning');
            var infoColor = KTUtil.getCssVariableValue('--bs-info');

            // Define fonts
            var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

            // Chart labels
            const labels = {!! json_encode($top_product_label) !!};

            // Chart data
            const data = {
                labels: labels,
                datasets: [{
                    data: {!! json_encode($top_product_count) !!},
                    backgroundColor: [
                        primaryColor,
                        dangerColor,
                        successColor,
                        warningColor,
                        infoColor,
                    ],
                    hoverOffset: 4
                }, ]
            };

            // Chart config
            const config = {
                type: 'pie',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: false,
                        }
                    },
                    responsive: true,
                },
                defaults: {
                    global: {
                        defaultFont: fontFamily
                    }
                }
            };

            // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            var myChart = new Chart(ctx, config);
        }

        function category_chart() {
            var ctx = document.getElementById('kt_chartjs_category');

            // Define colors
            var primaryColor = KTUtil.getCssVariableValue('--bs-primary');
            var dangerColor = KTUtil.getCssVariableValue('--bs-danger');
            var successColor = KTUtil.getCssVariableValue('--bs-success');
            var warningColor = KTUtil.getCssVariableValue('--bs-warning');
            var infoColor = KTUtil.getCssVariableValue('--bs-info');

            // Define fonts
            var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

            // Chart labels
            const labels = {!! json_encode($category_label) !!};
            console.log(labels);

            // Chart data
            const data = {
                labels: labels,
                datasets: [{
                    data: {!! json_encode($category_earn) !!},
                    backgroundColor: [
                        primaryColor,
                        dangerColor,
                        successColor,
                        warningColor,
                        infoColor,
                    ],
                    hoverOffset: 4
                }, ]
            };

            // Chart config
            const config = {
                type: 'pie',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: false,
                        }
                    },
                    responsive: true,
                },
                defaults: {
                    global: {
                        defaultFont: fontFamily
                    }
                }
            };

            // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            var myChart = new Chart(ctx, config);
        }

        function order_chart() {
            var ctx = document.getElementById('kt_chartjs_order');

            // Define colors
            var primaryColor = KTUtil.getCssVariableValue('--bs-primary');
            var dangerColor = KTUtil.getCssVariableValue('--bs-danger');
            var successColor = KTUtil.getCssVariableValue('--bs-success');
            var warningColor = KTUtil.getCssVariableValue('--bs-warning');
            var infoColor = KTUtil.getCssVariableValue('--bs-info');

            // Define fonts
            var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

            // Chart labels
            const labels = ['Đã hoàn thành', 'Đã hủy'];
            console.log(labels);

            // Chart data
            const data = {
                labels: labels,
                datasets: [{
                    data: [{!! json_encode($order_complete) !!}, {!! json_encode($order_fail) !!}],
                    backgroundColor: [
                        primaryColor,
                        dangerColor, ,
                    ],
                    hoverOffset: 4
                }, ]
            };

            // Chart config
            const config = {
                type: 'pie',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: false,
                        }
                    },
                    responsive: true,
                },
                defaults: {
                    global: {
                        defaultFont: fontFamily
                    }
                }
            };

            // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            var myChart = new Chart(ctx, config);
        }
        sale_chart();
        product_chart();
        category_chart();
        order_chart();
    </script>
@endpush
