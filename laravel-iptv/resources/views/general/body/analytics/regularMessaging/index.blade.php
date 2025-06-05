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
            .card-single-message-fill {
                background-color: #198754 !important;
                border-color: #198754 !important;
                color: white;
            }
            .card-group-message-fill {
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
                color: white;
            }
            .card-all-message-fill {
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
                <div class="card radius-0 overflow-hidden card-single-message-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 font-20">Single Message</p>
                                <h5 class="mb-0 text-white" id="cs-total-calls"></h5>
                            </div>
                            <div class="ms-auto font-30">	
                                <i class="fa-solid fa-user fa-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div id="singleMessageAreaChart" style="min-height: 65px;"></div>
                </div>
            </div>
            <div class="col-lg-4 col-4">
                <div class="card radius-0 overflow-hidden card-group-message-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 font-20">Group Message</p>
                                <h5 class="mb-0 text-white" id="cs-csat"></h5>
                            </div>
                            <div class="ms-auto font-30">	
                                <i class="fa-solid fa-users-between-lines fa-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div id="groupMessageAreaChart" style="min-height: 65px;"></div>
                </div>
            </div>
            <div class="col-lg-4 col-4">
                <div class="card radius-0 overflow-hidden card-all-message-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 font-20">Message to All</p>
                                <h5 class="mb-0 text-white" id="cs-avg-handling-time"></h5>
                            </div>
                            <div class="ms-auto font-30">	
                                <i class="fa-solid fa-earth-asia fa-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div id="messageToAllAreaChart" style="min-height: 65px;"></div>
                </div>
            </div>
        </div>
        <!-- end of first row -->
        
        <!-- second row -->
        <div class="row">
            <div class="col-lg-4 col-12 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Messages Status
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive">        
                            <div class="col-lg-12 col-12 col-md-12" id="messagesStatusChart" style="margin-top: 10px;"></div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
           
            <div class="col-lg-8 col-4 col-md-12 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Message Type
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive">        
                            <div class="col-lg-4 col-4 col-md-4" id="messagesTypeChart" style="margin-top: 10px;"></div>
                        
                            <div class="col-lg-8 col-8 col-md-8"id="appLineChart" style="margin-top: 15px; margin-bottom: 10px;"></div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
        <!-- end of second row -->

        <!-- third row -->
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
                            <div class="col-lg-4 col-4 col-md-4" id="singleMessageRadialChart"></div>
                            <div class="col-lg-4 col-4 col-md-4" id="groupMessageRadialChart"></div>
                            <div class="col-lg-4 col-4 col-md-4" id="messageToAllRadialChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of third row -->

        <!-- fourth row -->
        <div class="row">
            <div class="col-lg-6 col-6 col-md-6 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Today's Single Messages
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="chart-responsive">
                            <div style="margin-left: -3%;" id="singleMessageBarChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-6 col-md-6 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Today's Group Messages
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="chart-responsive">
                            <div style="margin-left: -3%;" id="groupMessageBarChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of forth row -->

    </div>
</div>
    @include('sweetalert2/script')
    <!-- include('general/body/devices/devices/modals/edit/form')
    include('general/body/devices/devices/modals/view/form')
    include('general/body/devices/devices/modals/delete/form') -->
    
@stop

@section('pages_specific_scripts')
<script>
    let globalMessagesStatusChart = null;
    let globalMessagesTypeChart = null;
    let globalDonutChart = null;
    let globalSingleMessageRadialChart = null;
    let globalGroupMessageRadialChart = null;
    let globalMessageToAllRadialChart = null;

    $(document).ready(function() {
		inFooterScriptAjaxBasicGet(loadAnalyticData, "/analytics/get-regular-messaging-data");
        // areaChart(
        //     [440, 505, 414, 671, 427, 613, 901],
        //     // data.cs_daily_aht,
        //     'AHT in seconds',
        //     '#groupMessageAreaChart',
        //     ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        // );


        // areaChart(
        //     [10, 30, 20, 70, 20, 0, 400],
        //     // data.cs_daily_total_call,
        //     'Total Calls',
        //     '#singleMessageAreaChart',
        //     ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        // );

        // areaChart(
        //     [414, 671, 427, 613, 901, 257, 160],
        //     // data.cs_daily_csat,
        //     'CSAT',
        //     '#messageToAllAreaChart',
        //     ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        // );


        // let todaysGroupMessagesData = [{
		// 	name: '',
		// 	data: [20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399,20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 20, 42, 414, 399, 121, 100, 20, 42, 414, 399]
		// }];

		// let todaysGroupMessagesRoom = ['101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '101', '102', '103', '104', '105', '106', '107', '108', '109', '110'];
		
		// const dataCount = todaysGroupMessagesRoom.length;

		// const baseHeight = 480; // Minimum height
		// const heightPerBar = 30; // Adjust this for spacing
		// const dynamicHeight = Math.max(baseHeight, dataCount * heightPerBar);
    });

	function loadAnalyticData(data){
		// console.log(data);
		const baseHeight = 120; // Minimum height
		const heightPerBar = 30; // Adjust this for spacing

		let singleMessageCounts = data.thirtyDaysMessages.single.data;

		inFooterScriptAreaChart(
			singleMessageCounts,
			"#singleMessageAreaChart",
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
				tooltipFixedOffsetX: -180,
			}],
			data.thirtyDaysMessages.days
		);

		let groupMessageCounts = data.thirtyDaysMessages.group.data;

		inFooterScriptAreaChart(
			groupMessageCounts,
			"#groupMessageAreaChart",
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
				tooltipFixedOffsetX: -180,
			}],
			data.thirtyDaysMessages.days
		);

		let messageToAllCounts = data.thirtyDaysMessages.all.data;

		inFooterScriptAreaChart(
			messageToAllCounts,
			"#messageToAllAreaChart",
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
				tooltipFixedOffsetX: -180,
			}],
			data.thirtyDaysMessages.days
		);

		let todaysSingleMessagesData = data.todays_single_messages.data;
		let singleMessagesRoomCount = data.todays_single_messages.rooms.length;
		let singleMessagesDynamicHeight = Math.max(baseHeight, singleMessagesRoomCount * heightPerBar);

		inFooterScriptBarChart(
			todaysSingleMessagesData,
			"#singleMessageBarChart",
			[{ 
				colors: ['#15a0a3', '#e52c50', '#edba21'],
				chartForeColor: '#212529',
				chartHeight: singleMessagesDynamicHeight,
				chartToolbarShow: true,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: false,
				chartDropShadowEnabled: true,
				chartDropShadowTop: 11,
				chartDropShadowLeft: 11,
				chartDropShadowBlur: 4,
				chartDropShadowOpacity: 0.10,
				chartSparkline: false,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 7,
				plotOptionsBarHorizontal: true,
				plotOptionsBarEndingShape: 'flat',//rounded
				plotOptionsDataLabelsPosition: 'bottom',
				markersSize: 4,
				markersColors: ["#007bff"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 7,
				dataLabelsEnabled: true,
				dataLabelsTextAnchor: 'start',
				dataLabelsStyleColors: ["#000000"],
				dataLabelsStyleFontSize: '12px',
				dataLabelsStyleFontWeight: 'bold',
				dataLabelsFormatterReturn: 'labelOnly',
				dataLabelsOffsetX: 0,
				strokeWidth: 0,
				strokeColors: ["#fff"],
				fillOpacity: 1,
				fillType: '',//'image',
				fillImageSrc: '',//'{{ asset('source-images/images/meshtv_logo.png') }}',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom'
			}],
			data.todays_single_messages.rooms
		);

		let todaysGroupMessagesData = data.todays_group_messages.data;
		let groupMessagesRoomCount = data.todays_group_messages.group.length;
		let groupMessagesDynamicHeight = Math.max(baseHeight, groupMessagesRoomCount * heightPerBar);

		inFooterScriptBarChart(
			todaysGroupMessagesData,
			"#groupMessageBarChart",
			[{ 
				colors: ['#15a0a3', '#e52c50', '#edba21'],
				chartForeColor: '#212529',
				chartHeight: groupMessagesDynamicHeight,
				chartToolbarShow: true,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: false,
				chartDropShadowEnabled: true,
				chartDropShadowTop: 11,
				chartDropShadowLeft: 11,
				chartDropShadowBlur: 4,
				chartDropShadowOpacity: 0.10,
				chartSparkline: false,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 7,
				plotOptionsBarHorizontal: true,
				plotOptionsBarEndingShape: 'flat',//rounded
				plotOptionsDataLabelsPosition: 'bottom',
				markersSize: 4,
				markersColors: ["#007bff"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 7,
				dataLabelsEnabled: true,
				dataLabelsTextAnchor: 'start',
				dataLabelsStyleColors: ["#000000"],
				dataLabelsStyleFontSize: '12px',
				dataLabelsStyleFontWeight: 'bold',
				dataLabelsFormatterReturn: 'labelOnly', // Use "labelOnly" to exclude the value
				dataLabelsOffsetX: 0,
				strokeWidth: 0,
				strokeColors: ["#fff"],
				fillOpacity: 1,
				fillType: '',//'image',
				fillImageSrc: '',//'{{ asset('source-images/images/meshtv_logo.png') }}',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom'
			}],
			data.todays_group_messages.group
		);

		let messagesStatusesCount = [
			data.total_new_messages,
			data.total_delivered_messages,
			data.total_new_messages,
			data.total_deleted_messages
		];

		globalMessagesStatusChart = inFooterScriptDonutChart(
            messagesStatusesCount,
            '#messagesStatusChart',
            [{ 
                colors: ["#8833ff", "#ffc107", "#0dcaf0", "#fd3550"],
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
			['New', 'Delivered', 'Seen', 'Deleted'],
            globalMessagesStatusChart
        );

		globalMessagesTypeChart = inFooterScriptDonutChart(
            data.message_type_total_counter.data,
            '#messagesTypeChart',
            [{ 
                colors: data.message_type_total_counter.color,
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
				responsiveOptionsLegendPosition: 'bottom',
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
			data.message_type_total_counter.name,
            globalMessagesTypeChart
        );

		// let applicationCounts = [{
		// 	name: 'Single Message',
		// 	data: [12, 11, 14, 18, 17, 13, 28, 29, 33, 36, 32, 32]
		// }, {
		// 	name: 'Group Mesage',
		// 	data: [28, 29, 33, 36, 32, 32, 12, 11, 14, 18, 17, 13]
		// },{
		// 	name: 'Mesage to All',
		// 	data: [18, 39, 34, 46, 32, 32, 22, 41, 24, 14, 4, 22]
		// }];

		let twelveMonthsMessageTypeChartData = data.message_type_twelve_months_chart_data;

        inFooterScriptLineChart(
            twelveMonthsMessageTypeChartData.data,
			// result.data,
			"#appLineChart",
			[{ 
				colors: data.message_type_twelve_months_chart_data.colors,//["#198754", "#0d6efd", "#dc3545"],//result.color,
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
			data.message_type_twelve_months_chart_data.months
			//['Dec', 'Nov', 'Oct', 'Sep', 'Aug', 'Jul', 'Jun', 'May', 'Apr', 'Mar', 'Feb', 'Jan']
		);

		globalSingleMessageRadialChart = inFooterScriptRadialBarChart(
			[data.message_type_monthly_percentage.single.percentage], 
			'#singleMessageRadialChart', 
			[{ 
				colors: ['#198754'],
				chartHeight: 150,
				labels: 'Single Message',
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
			[data.message_type_monthly_percentage.single.total_count],
			globalSingleMessageRadialChart
		);
		
		globalGroupMessageRadialChart = inFooterScriptRadialBarChart(
			[data.message_type_monthly_percentage.group.percentage], 
			'#groupMessageRadialChart', 
			[{ 
				colors: ['#0d6efd'],
				chartHeight: 150,
				labels: 'Group Message',
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
			[data.message_type_monthly_percentage.group.total_count],
			globalGroupMessageRadialChart
		);

		globalMessageToAllRadialChart = inFooterScriptRadialBarChart(
			[data.message_type_monthly_percentage.all.percentage], 
			'#messageToAllRadialChart', 
			[{ 
				colors: ['#dc3545'],
				chartHeight: 150,
				labels: 'Message To All',
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
			[data.message_type_monthly_percentage.all.total_count],
			globalMessageToAllRadialChart
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