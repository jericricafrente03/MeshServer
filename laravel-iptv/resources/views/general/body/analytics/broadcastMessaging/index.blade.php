@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->

        <style>
            .card-custom-fill {
                background-color: #EF145B !important;
                border-color: #EF145B !important;
                color: white;
            }
            .card-ticker-message-fill {
                background-color: #198754 !important;
                border-color: #198754 !important;
                color: white;
            }
            .card-advertisement-message-fill {
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
                color: white;
            }
            .card-emergency-message-fill {
                background-color: #dc3545 !important;
                border-color: #dc3545 !important;
                color: white;
            }

            /* Dashboard Apps */
            .card-header>.card-tools {
                float: right;
                margin-right: -4px;
                margin-top: -17px;
                margin-bottom: -110px;
            }

            .card-title {
                float: left;
                padding-left: 15px;
                font-weight: 400;
                margin: 0;
                font-size: 20px;
                margin-top: -12px;
            }

            .chart-container {
                display: flex; /* Use flexbox for horizontal alignment */
                justify-content: space-around; /* Space charts evenly */
                align-items: center; /* Vertically align the charts */
                gap: 20px; /* Optional: Adds spacing between the charts */
                margin-top: 25px;
            }

            .cb-mb {
                margin-bottom: -30px;
            }
        </style>
        <!-- first row -->
        <div class="row">
            <div class="col-lg-4 col-4">
                <div class="card radius-0 overflow-hidden card-ticker-message-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 font-20">Ticker</p>
                                <h5 class="mb-0 text-white" id="cs-total-calls"></h5>
                            </div>
                            <div class="ms-auto font-30">	
                                <i class="fa-solid fa-closed-captioning fa-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div id="tickerAreaChart" style="min-height: 65px;"></div>
                </div>
            </div>
            <div class="col-lg-4 col-4">
                <div class="card radius-0 overflow-hidden card-advertisement-message-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 font-20">Advertisement</p>
                                <h5 class="mb-0 text-white" id="cs-csat"></h5>
                            </div>
                            <div class="ms-auto font-30">	
                                <i class="fa-solid fa-rectangle-ad fa-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div id="advertisementAreaChart" style="min-height: 65px;"></div>
                </div>
            </div>
            <div class="col-lg-4 col-4">
                <div class="card radius-0 overflow-hidden card-emergency-message-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 font-20">Emergency</p>
                                <h5 class="mb-0 text-white" id="cs-avg-handling-time"></h5>
                            </div>
                            <div class="ms-auto font-30">	
                                <i class="fa-solid fa-triangle-exclamation fa-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div id="emergencyAreaChart" style="min-height: 65px;"></div>
                </div>
            </div>
        </div>
        <!-- end of first row -->
        
        <!-- second row -->
        <div class="row">
            <div class="col-lg-4 col-4 col-md-12 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Ticker Type
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive">        
                            <div id="tickerTypeDonutChart" style="margin-top: 10px;"></div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>

			<div class="col-lg-4 col-4 col-md-12 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Advertisement Type
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive">        
                            <div id="advertisementTypeDonutChart" style="margin-top: 10px;"></div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>

			<div class="col-lg-4 col-4 col-md-12 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Emergency Type
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive">        
                            <div id="emergencyTypeDonutChart" style="margin-top: 10px;"></div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
        <!-- end of second row -->

		

        <!-- fourth row -->
        <div class="row">
            <div class="col-lg-12 col-6 col-md-12">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Monthly Message Type Percentage
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive mb-4">
                            <div class="col-lg-2 col-4 col-md-4" id="tickerRadialChart" style="margin-top: 55px;"></div>
                            <div class="col-lg-2 col-4 col-md-4" id="advertisementRadialChart" style="margin-top: 55px;"></div>
                            <div class="col-lg-2 col-4 col-md-4" id="messageToAllRadialChart" style="margin-top: 55px;"></div>
							<div class="col-lg-6 col-4 col-md-12" id="broadcastMessagesTypeLineChart" style="margin-top: 15px; margin-bottom: -5px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of fourth row -->

    </div>
</div>
    @include('sweetalert2/script')
    <!-- include('general/body/devices/devices/modals/edit/form')
    include('general/body/devices/devices/modals/view/form')
    include('general/body/devices/devices/modals/delete/form') -->
    
@stop

@section('pages_specific_scripts')
<script>
    let globaltickerStatusDonutChart = null;
    let globalAdvertisementTypeDonutChart = null;
	let globalEmergencyTypeDonutChart = null;
    let globalDonutChart = null;
    let globalTickerRadialChart = null;
    let globalAdvertisementRadialChart = null;
    let globalMessageToAllRadialChart = null;

    $(document).ready(function() {
		inFooterScriptAjaxBasicGet(loadAnalyticData, "/analytics/get-broadcast-messaging-data");
        // areaChart(
        //     [440, 505, 414, 671, 427, 613, 901],
        //     // data.cs_daily_aht,
        //     'AHT in seconds',
        //     '#emergencyAreaChart',
        //     ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        // );

        // areaChart(
        //     [30, 20, 30, 60, 20, 10, 10],
        //     // data.cs_daily_total_call,
        //     'Total Calls',
        //     '#tickerAreaChart',
        //     ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        // );

        // areaChart(
        //     [414, 671, 427, 613, 901, 257, 160],
        //     // data.cs_daily_csat,
        //     'CSAT',
        //     '#advertisementAreaChart',
        //     ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        // );

		
    });

	function loadAnalyticData(data){
		console.log(data);

		globalTickerRadialChart = inFooterScriptRadialBarChart(
			[data.broadcastMessagesTypeTotalPercentageCount.Ticker.percentage], 
			'#tickerRadialChart', 
			[{ 
				colors: [data.broadcastMessagesTypeTotalPercentageCount.Ticker.color],
				chartHeight: 150,
				labels: 'Ticker',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 120,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: 0,
				plotOptionsRadialBarEndAngle: 360,
				plotOptionsRadialBarStartAngle: 0,
				plotOptionsRadialBarEndAngle: 360,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: 75,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "17px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "round"
			}],
			[data.broadcastMessagesTypeTotalPercentageCount.Ticker.count],
			globalTickerRadialChart
		);
		
		globalAdvertisementRadialChart = inFooterScriptRadialBarChart(
			[data.broadcastMessagesTypeTotalPercentageCount.Advertisement.percentage], 
			'#advertisementRadialChart', 
			[{ 
				colors: [data.broadcastMessagesTypeTotalPercentageCount.Advertisement.color],
				chartHeight: 150,
				labels: 'Advertisement',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 120,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: 0,
				plotOptionsRadialBarEndAngle: 360,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: 75,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "17px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "round"
			}],
			[data.broadcastMessagesTypeTotalPercentageCount.Advertisement.count],
			globalAdvertisementRadialChart
		);

		globalMessageToAllRadialChart = inFooterScriptRadialBarChart(
			[data.broadcastMessagesTypeTotalPercentageCount.Emergency.percentage], 
			'#messageToAllRadialChart', 
			[{ 
				colors: [data.broadcastMessagesTypeTotalPercentageCount.Emergency.color],
				chartHeight: 150,
				labels: 'Emergency',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 120,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: 0,
				plotOptionsRadialBarEndAngle: 360,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: 75,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "17px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "round"
			}],
			[data.broadcastMessagesTypeTotalPercentageCount.Emergency.count],
			globalMessageToAllRadialChart
		);


        inFooterScriptLineChart(
            data.twelveMonthsBroadcastMessagesTypeData.data,
			// result.data,
			"#broadcastMessagesTypeLineChart",
			[{ 
				colors: data.twelveMonthsBroadcastMessagesTypeData.colors,//result.color,
				titleText: '',
				titleAlign: 'left',
				titleStyleFontSize: '16px',
				titleStyleColor: '#413c3c',
				chartHeight: 287,
				chartToolbarShow: true,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 0,
				strokeWidth: 5,
				strokeCurve: 'smooth',
				markersSize: 4,
				markersSizeHoverSize: 5,
				yaxisTitle: '',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				optionsBarColumnWidth: '80%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: true,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: true,
				fillOpacity: 1,
				fillType: 'solid'
			}],
			// result.month
			data.twelveMonthsBroadcastMessagesTypeData.months
		);

		globaltickerStatusDonutChart = inFooterScriptDonutChart(
            data.broadcastType.Ticker.data,
            '#tickerTypeDonutChart',
            [{ 
                colors: data.broadcastType.Ticker.color,
				chartForeColor: '#212529',
                chartHeight: 300,
				chartToolbarShow: true,
				legendPosition: 'top',
                legendShow: true,
				legendOffsetY: 0,
				legendOffsetX: 0,
				legendHeight: '',
				responsiveBreakpoint: 1300,
				responsiveOptionsChartHeight: 300,
				responsiveOptionsLegendPosition: 'top',
				responsiveOptionsPlotOptionsPie: 1,
				plotOptionsPieDonutSize: '50%',
				plotOptionsPieDonutLabelsShow: true,
				plotOptionsPieDonutLabelsNameShow: true,
				plotOptionsPieDonutLabelsNameColor: '#212529',
				plotOptionsPieDonutLabelsValueShow: true,
				plotOptionsPieDonutLabelsValueFontSize: '23',
				plotOptionsPieDonutLabelsValueFontWeight: 650,
				plotOptionsPieDonutLabelsValueColor: "#212529",
				plotOptionsPieDonutLabelsTotalShow: true,
				plotOptionsPieDonutLabelsTotalFontWeight: 650,
				plotOptionsPieDonutLabelsTotalColor: "#212529",
                tooltipEnabled: true
            }],
			data.broadcastType.Ticker.name,
            globaltickerStatusDonutChart
        );

        globalAdvertisementTypeDonutChart = inFooterScriptDonutChart(
            data.broadcastType.Advertisement.data,
            '#advertisementTypeDonutChart',
            [{ 
                colors: data.broadcastType.Advertisement.color,
				chartForeColor: '#212529',
                chartHeight: 300,
				chartToolbarShow: true,
				legendPosition: 'top',
                legendShow: true,
				legendOffsetY: 0,
				legendOffsetX: 0,
				legendHeight: '',
				responsiveBreakpoint: 1300,
				responsiveOptionsChartHeight: 300,
				responsiveOptionsLegendPosition: 'top',
				responsiveOptionsPlotOptionsPie: 1,
				plotOptionsPieDonutSize: '50%',
				plotOptionsPieDonutLabelsShow: true,
				plotOptionsPieDonutLabelsNameShow: true,
				plotOptionsPieDonutLabelsNameColor: '#212529',
				plotOptionsPieDonutLabelsValueShow: true,
				plotOptionsPieDonutLabelsValueFontSize: '23',
				plotOptionsPieDonutLabelsValueFontWeight: 650,
				plotOptionsPieDonutLabelsValueColor: "#212529",
				plotOptionsPieDonutLabelsTotalShow: true,
				plotOptionsPieDonutLabelsTotalFontWeight: 650,
				plotOptionsPieDonutLabelsTotalColor: "#212529",
                tooltipEnabled: true
            }],
			data.broadcastType.Advertisement.name,
            globalAdvertisementTypeDonutChart
        );

		globalEmergencyTypeDonutChart = inFooterScriptDonutChart(
            data.broadcastType.Emergency.data,
            '#emergencyTypeDonutChart',
            [{ 
                colors: data.broadcastType.Emergency.color,
				chartForeColor: '#212529',
                chartHeight: 300,
				chartToolbarShow: true,
				legendPosition: 'top',
                legendShow: true,
				legendOffsetY: 0,
				legendOffsetX: 0,
				legendHeight: '',
				responsiveBreakpoint: 1300,
				responsiveOptionsChartHeight: 300,
				responsiveOptionsLegendPosition: 'top',
				responsiveOptionsPlotOptionsPie: 1,
				plotOptionsPieDonutSize: '50%',
				plotOptionsPieDonutLabelsShow: true,
				plotOptionsPieDonutLabelsNameShow: true,
				plotOptionsPieDonutLabelsNameColor: '#212529',
				plotOptionsPieDonutLabelsValueShow: true,
				plotOptionsPieDonutLabelsValueFontSize: '23',
				plotOptionsPieDonutLabelsValueFontWeight: 650,
				plotOptionsPieDonutLabelsValueColor: "#212529",
				plotOptionsPieDonutLabelsTotalShow: true,
				plotOptionsPieDonutLabelsTotalFontWeight: 650,
				plotOptionsPieDonutLabelsTotalColor: "#212529",
                tooltipEnabled: true
            }],
			data.broadcastType.Emergency.name,
            globalEmergencyTypeDonutChart
        );
		
		let tickerAreaChartCounts = data.thirtyDaysMessages.ticker;

		inFooterScriptAreaChart(
			tickerAreaChartCounts,
			"#tickerAreaChart",
			[{ 
				colors: ['#fff'],
				chartForeColor: '#212529',
				chartHeight: 50,
				chartToolbarShow: false,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: false,
				chartDropShadowEnabled: false,
				chartDropShadowTop: 3,
				chartDropShadowLeft: 14,
				chartDropShadowBlur: 4,
				chartDropShadowOpacity: 0.10,
				chartSparkLineEnabled: true,
				gridShow: false,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 3,
				plotOptionsBarHorizontal: false,
				plotOptionsBarEndingShape: 'flat',//rounded
				markersSize: 0,
				markersColors: ["#198754"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 7,
				dataLabelsEnabled: false,
				strokeShow: true,
				strokeWidth: 1,
				strokeCurve: 'smooth',
				fillOpacity: 1,
				fillType: 'solid',
				fillImageSrc: '#f0f0f0',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom',
				tooltipTheme: 'dark',
				tooltipXShow: true,
				tooltipFixedEnabled: true,
				tooltipFixedOffsetY: -47,
				tooltipFixedOffsetX: -110,
			}],
			data.thirtyDaysMessages.days
		);

		let advertisementAreaChartCounts = data.thirtyDaysMessages.advertisement;

		inFooterScriptAreaChart(
			advertisementAreaChartCounts,
			"#advertisementAreaChart",
			[{ 
				colors: ['#fff'],
				chartForeColor: '#212529',
				chartHeight: 50,
				chartToolbarShow: false,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: false,
				chartDropShadowEnabled: false,
				chartDropShadowTop: 3,
				chartDropShadowLeft: 14,
				chartDropShadowBlur: 4,
				chartDropShadowOpacity: 0.10,
				chartSparkLineEnabled: true,
				gridShow: false,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 3,
				plotOptionsBarHorizontal: false,
				plotOptionsBarEndingShape: 'flat',//rounded
				markersSize: 0,
				markersColors: ["#007bff"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 7,
				dataLabelsEnabled: false,
				strokeShow: true,
				strokeWidth: 1,
				strokeCurve: 'smooth',
				fillOpacity: 1,
				fillType: 'solid',
				fillImageSrc: '#f0f0f0',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom',
				tooltipTheme: 'dark',
				tooltipXShow: true,
				tooltipFixedEnabled: true,
				tooltipFixedOffsetY: -47,
				tooltipFixedOffsetX: -110,
			}],
			data.thirtyDaysMessages.days
		);

		let emergencyAreaChartCounts = data.thirtyDaysMessages.emergency;

		inFooterScriptAreaChart(
			emergencyAreaChartCounts,
			"#emergencyAreaChart",
			[{ 
				colors: ['#fff'],
				chartForeColor: '#212529',
				chartHeight: 50,
				chartToolbarShow: false,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: false,
				chartDropShadowEnabled: false,
				chartDropShadowTop: 3,
				chartDropShadowLeft: 14,
				chartDropShadowBlur: 4,
				chartDropShadowOpacity: 0.10,
				chartSparkLineEnabled: true,
				gridShow: false,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 3,
				plotOptionsBarHorizontal: false,
				plotOptionsBarEndingShape: 'flat',//rounded
				markersSize: 0,
				markersColors: ["#dc3545"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 7,
				dataLabelsEnabled: false,
				strokeShow: true,
				strokeWidth: 1,
				strokeCurve: 'smooth',
				fillOpacity: 1,
				fillType: 'solid',
				fillImageSrc: '#f0f0f0',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom',
				tooltipTheme: 'dark',
				tooltipXShow: true,
				tooltipFixedEnabled: true,
				tooltipFixedOffsetY: -47,
				tooltipFixedOffsetX: -110,
			}],
			data.thirtyDaysMessages.days
		);
	}

    function areaChart(__data_array = [], __title = '', __selector = '', __categories = []){   
        let options = {
			series: [{
				name: __title,
				data: __data_array
			}],
			chart: {
				type: 'area',
				height: 50,
				toolbar: {
					show: false
				},
				zoom: {
					enabled: false
				},
				dropShadow: {
					enabled: false,
					top: 3,
					left: 14,
					blur: 4,
					opacity: 0.10,
				},
				sparkline: {
					enabled: true
				}
			},
			markers: {
				size: 0,
				colors: ["#007bff"],
				strokeColors: "#fff",
				strokeWidth: 2,
				hover: {
					size: 7,
				}
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				show: true,
				width: 1,
				curve: 'smooth'
			},
			colors: ["#fff"],
			xaxis: {
                categories: __categories
				// categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			},
			tooltip: {
				theme: 'dark',
				x: {
				    show: true
				},
                fixed: {
                    enabled: true,
                    // position: 'center',
                    offsetY: -47,
                    offsetX: -110   

                }
			},
			fill: {
                opacity: 1,
                type: 'solid',
                colors: '#f0f0f0'
            },
		};
		let chart = new ApexCharts(document.querySelector(__selector), options);
		chart.render();
    }
</script>

@stop