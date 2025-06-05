@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->

        <style>
			/* dashboard cards */
			.bg-enabled-channels {
				background-color: #15a0a3 !important;
			}
			
			.bg-enabled-channels > .inner > h3,
			.bg-enabled-channels > .inner > p {
				color: #ffffff !important;
			}

			.bg-disabled-channels {
				background-color: #db3d5c !important;
			}
			
			.bg-disabled-channels > .inner > h3,
			.bg-disabled-channels > .inner > p {
				color: #ffffff !important;
			}

			.small-box {
				border-radius: .25rem;
				box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
				display: block;
				margin-bottom: 20px;
				position: relative;
			}
			.small-box>.inner {
				padding: 10px;
			}

			.small-box h3 {
				font-size: 2.2rem;
				font-weight: 700;
				margin: 0 0 10px;
				padding: 0;
				white-space: nowrap;
			}

			.small-box p {
				font-size: 1rem;
			}

			.small-box .icon {
				color: rgba(0, 0, 0, .15);
				z-index: 0;
				font-size: 70px;
				position: absolute;
				top: 0px;
				right: 15px;
			}

			.small-box>.small-box-footer {
				background-color: rgba(0, 0, 0, .1);
				color: rgba(255, 255, 255, .8);
				display: block;
				padding: 3px 0;
				position: relative;
				text-align: center;
				text-decoration: none;
				z-index: 1;
			}

			.fa, .fas {
				font-weight: 900;
			}
			.fa, .far, .fas {
				font-family: "Font Awesome 5 Free";
			}
			.fa, .fab, .fad, .fal, .far, .fas {
				-moz-osx-font-smoothing: grayscale;
				-webkit-font-smoothing: antialiased;
				display: inline-block;
				font-style: normal;
				font-variant: normal;
				text-rendering: auto;
				line-height: 1;
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
			<div class="col-lg-6 col-6">
				<!-- small box -->
				<div class="small-box bg-enabled-channels">
				<div class="inner">
					<h3 id="enabledChannelCount">...</h3>

					<p>Enabled Channels</p>
				</div>
				<div class="icon">
					<i class="fa fa-solid fa-square-check"></i>
				</div>
				<!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-6 col-6">
				<!-- small box -->
				<div class="small-box bg-disabled-channels">
				<div class="inner">
					<h3 id="disabledChannelCount">...</h3>

					<p>Disabled Channels</p>
				</div>
				<div class="icon">
				<i class="fa fa-solid fa-square-xmark"></i>
				</div>
				<!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
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
                            Tv Channel Status
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive">        
                            <div id="tvChannelStatusDonutChart" style="margin-top: 10px;"></div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>

			<div class="col-lg-8 col-4 col-md-12 d-flex">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Tv Channel Activity
                        </h3>
                    </div><!-- /.card-header -->
					<div class="card-body cb-mb">
                        <div class="row chart-responsive mb-4">
							<div class="col-lg-4 col-4 col-md-12" id="tvChannelActivityDonutChart" style="margin-top: 10px;"></div>
							<div class="col-lg-8 col-4 col-md-12" id="tvChannelActivityLineChart" style="margin-top: 15px; margin-bottom: -5px;"></div>
                        </div>
                    </div>
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
                            Tv Channel Category Counter
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive mb-4">
							<div class="col-lg-4 col-4 col-md-12" id="tvChannelCategoryDonutChart" style="margin-top: 10px;"></div>
							<div class="col-lg-8 col-4 col-md-12" id="tvChannelCategoryLineChart" style="margin-top: 15px; margin-bottom: -5px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of fourth row -->
		
		<!-- fifth row -->
        <div class="row">
            <div class="col-lg-12 col-6 col-md-12">
                <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                    <div class="card-header d-flex align-items-center gap-2 ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            Tv Channel Counter
						</h3>
						<div style="margin-top: -10px;"><select class="form-control w-auto" name="tv_channel_id" id="tv_channel_id" data-placeholder="Select Tv Channel.."></select></div>
                    </div><!-- /.card-header -->
                    <div class="card-body cb-mb">
                        <div class="row chart-responsive mb-4">
							<div class="col-lg-4 col-4 col-md-12" id="tvChannelCounterAreaChart" style="margin-top: 10px;"></div>
							<div class="col-lg-8 col-4 col-md-12" id="tvChannelCounterLineChart" style="margin-top: 15px; margin-bottom: -5px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of fifth row -->

		<!-- sixth row -->
		<div class="row">
			<div class="col-lg-12 col-6 col-md-12 d-flex">
				<div class="card w-100" style="position: relative; left: 0px; top: 0px;">
				<div class="card-header ui-sortable-handle" style="cursor: move;">
					<h3 class="card-title">
						Top TV Channels
					</h3>
				</div><!-- /.card-header -->
				<div class="card-body cb-mb">
					<div class="chart-responsive">
						<div id="topTvChannelsBarChart"></div>
					</div>
				</div><!-- /.card-body -->
				</div>
			</div>
		</div>
		<!-- end of sixth row -->

    </div>
</div>
    @include('sweetalert2/script')
    <!-- include('general/body/devices/devices/modals/edit/form')
    include('general/body/devices/devices/modals/view/form')
    include('general/body/devices/devices/modals/delete/form') -->
    
@stop

@section('pages_specific_scripts')
<script>
    let globalTvChannelStatusDonutChart = null;
    let globalTvChannelActivityDonutChart = null;
	let globalTvChannelCategoryDonutChart = null;
	let globalTvChannelMonthBarChart = null;
	let globalTvChannelDayAreaChart = null;

    $(document).ready(function() {
		inFooterScriptAjaxBasicGet(loadAnalyticData, "/analytics/get-tv-channel-data");

		inFooterScriptNoModalDropDownBelowSelect2('#tv_channel_id');

        let data = {
			id: ''
		};
		inFooterScriptAjaxBasicGet(loadChannelDropdownResult, "/search/tv-channels", data);

		// globalTvChannelCounterDonutChart = inFooterScriptDonutChart(
        //     [574,899,678],
        //     '#tvChannelCounterDonutChart',
        //     [{ 
        //         colors: ["#198754", "#0d6efd", "#dc3545"],
		// 		chartForeColor: '#212529',
        //         chartHeight: 300,
		// 		chartToolbarShow: true,
		// 		legendPosition: 'top',
        //         legendShow: true,
		// 		legendOffsetY: 0,
		// 		legendOffsetX: 0,
		// 		legendHeight: '',
		// 		responsiveBreakpoint: 1300,
		// 		responsiveOptionsChartHeight: 300,
		// 		responsiveOptionsLegendPosition: 'bottom',
		// 		responsiveOptionsPlotOptionsPie: 1,
		// 		plotOptionsPieDonutSize: '50%',
		// 		plotOptionsPieDonutLabelsShow: true,
		// 		plotOptionsPieDonutLabelsNameShow: true,
		// 		plotOptionsPieDonutLabelsValueShow: true,
		// 		plotOptionsPieDonutLabelsValueFontSize: '23',
		// 		plotOptionsPieDonutLabelsValueFontWeight: 650,
		// 		plotOptionsPieDonutLabelsValueColor: "#212529",
		// 		plotOptionsPieDonutLabelsTotalShow: true,
		// 		plotOptionsPieDonutLabelsTotalFontWeight: 650,
		// 		plotOptionsPieDonutLabelsTotalColor: "#212529",
        //         tooltipEnabled: true
        //     }],
		// 	['Sports', 'News', 'Drama'],
        //     globalTvChannelCounterDonutChart
        // );

    });

	$(document).on('change', '#tv_channel_id', function() {
        let data = {
            tv_channel_id: $('#tv_channel_id').val()
        };
        inFooterScriptAjaxBasicGet(loadTvChannelCounterData, "/analytics/get-tv-channel-counter", data);
    });

	function loadTvChannelCounterData(result, data){
		console.log(result);
		
		let tvChannelCounterCounts = result.tv_channel_month.data;
                
		globalTvChannelMonthBarChart = inFooterScriptBarChart(
			tvChannelCounterCounts,
			"#tvChannelCounterLineChart",
			[{ 
				colors: ['#15a0a3'],
				titleText: 'Monthly Tv Channel',
				titleAlign: 'left',
				subtitleText: 'Counter',
				subtitleAlign: 'left',
				chartForeColor: '#212529',
				chartHeight: 287,
				chartToolbarShow: true,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: true,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: true,
				chartDropShadowEnabled: true,
				chartDropShadowTop: 11,
				chartDropShadowLeft: 11,
				chartDropShadowBlur: 4,
				chartDropShadowOpacity: 0.10,
				chartSparkline: false,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 3,
				plotOptionsBarHorizontal: false,
				plotOptionsBarEndingShape: 'flat',//rounded
				plotOptionsDataLabelsPosition: 'bottom',
				markersSize: 4,
				markersColors: ["#007bff"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 7,
				dataLabelsEnabled: false,
				dataLabelsTextAnchor: 'start',
				dataLabelsStyleColors: ["#000000"],
				dataLabelsOffsetX: 0,
				strokeWidth: 0,
				strokeColors: ["#fff"],
				fillOpacity: 0.85,
				fillType: 'image',
				fillImageSrc: 'source-images/images/tv_channels.jpg',
				fillImageWidth: 600,
				fillImageHeight: 600,
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom',
			}],
			result.tv_channel_month.months,
			globalTvChannelMonthBarChart
		);

		globalTvChannelDayAreaChart = inFooterScriptAreaChart(
			result.tv_channel_day.data,
			"#tvChannelCounterAreaChart",
			[{ 
				colors: ['#15a0a3'],
				titleText: '30 Days Tv Channel',
				titleAlign: 'left',
				subtitleText: 'Counter',
				subtitleAlign: 'left',
				chartForeColor: '#212529',
				chartHeight: 287,
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
				chartSparkLineEnabled: false,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 3,
				plotOptionsBarHorizontal: false,
				plotOptionsBarEndingShape: 'flat',//rounded
				markersSize: 0,
				markersColors: ["#007bff"],
				markersStrokeColors: "#fff",
				markersStrokeWidth: 2,
				markersHoverSize: 0,
				dataLabelsEnabled: false,
				strokeShow: true,
				strokeWidth: 1,
				strokeCurve: 'smooth',
				fillOpacity: 1,
				fillType: '',
				fillImageSrc: '',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom',
				tooltipTheme: 'light',
				tooltipXShow: true,
			}],
			result.tv_channel_day.date,
			globalTvChannelDayAreaChart
		);

	}

	function loadChannelDropdownResult(result, data){
        let len = result.length;
        
        $("#tv_channel_id").empty();
        $("#tv_channel_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
        
          	$("#tv_channel_id").append("<option value='"+id+"'>"+name+"</option>");
        }
    };

	function loadAnalyticData(data){
		// console.log(data);
		globalTvChannelStatusDonutChart = inFooterScriptDonutChart(
            [data.enable_tv_channel, data.disable_tv_channel],
            '#tvChannelStatusDonutChart',
            [{ 
                colors: ["#15a0a3", "#db3d5c"],
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
			['Enabled', 'Disabled'],
            globalTvChannelStatusDonutChart
        );

		globalTvChannelActivityDonutChart = inFooterScriptDonutChart(
            [data.created_count, data.deleted_count],
            '#tvChannelActivityDonutChart',
            [{ 
                colors: ["#198754", "#dc3545"],
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
			['Created', 'Deleted'],
            globalTvChannelActivityDonutChart
        );

		// let tvChannelActivityCounts = [{
		// 	name: 'Created',
		// 	data: [12, 11, 14, 18, 17, 13, 28, 29, 33, 36, 32, 32]
		// }, {
		// 	name: 'Deleted',
		// 	data: [18, 39, 34, 46, 32, 32, 22, 41, 24, 14, 4, 22]
		// }];

		let tvChannelActivityCounts = data.tv_channel_activity.data;

        inFooterScriptLineChart(
            tvChannelActivityCounts,
			// result.data,
			"#tvChannelActivityLineChart",
			[{ 
				colors: ["#198754", "#dc3545"],//result.color,
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
			data.tv_channel_activity.months
			// ['Dec', 'Nov', 'Oct', 'Sep', 'Aug', 'Jul', 'Jun', 'May', 'Apr', 'Mar', 'Feb', 'Jan']
		);
		
		globalTvChannelCategoryDonutChart = inFooterScriptDonutChart(
            data.category_counter.total_counter,//[574,899,678],
            '#tvChannelCategoryDonutChart',
            [{ 
                colors: data.category_counter.color,//["#198754", "#0d6efd", "#dc3545"],
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
			data.category_counter.category,//['Sports', 'News', 'Drama'],
            globalTvChannelCategoryDonutChart
        );

		let tvChannelCategoryCounts = data.category_counter.data;

        inFooterScriptLineChart(
            tvChannelCategoryCounts,
			// result.data,
			"#tvChannelCategoryLineChart",
			[{ 
				colors: data.category_counter.color,//"#198754", "#dc3545", "#0d6efd"],
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
			data.category_counter.months
			// ['Dec', 'Nov', 'Oct', 'Sep', 'Aug', 'Jul', 'Jun', 'May', 'Apr', 'Mar', 'Feb', 'Jan']
		);

		$('#enabledChannelCount').text(data.enable_tv_channel);
		$('#disabledChannelCount').text(data.disable_tv_channel);


		let topTvChannelCounts = [{
			name: 'This month ',
			// type: 'area',
			data: data.top_tv_channel.this_month//[28, 29, 33, 36, 32, 32, 12, 11, 14, 18]
		},{
			name: 'Last month ',
			// type: 'line',
			data: data.top_tv_channel.last_month//[12, 11, 14, 18, 17, 13, 28, 29, 33, 36]
		}];

		let topTvChannelName = data.top_tv_channel.name;

		const dataCount = topTvChannelName.length;

		const baseHeight = 480; // Minimum height
		const heightPerBar = 75; // Adjust this for spacing
		const dynamicHeight = Math.max(baseHeight, dataCount * heightPerBar);
		
		inFooterScriptBarChart(
			topTvChannelCounts,
			"#topTvChannelsBarChart",
			[{ 
				colors: ['#15a0a3', '#e52c50', '#edba21'],
				chartForeColor: '#212529',
				chartHeight: dynamicHeight,
				chartToolbarShow: true,
				plotOptionsBarColumnWidth: '75%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: true,
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
				gridStrokeDashArray: 3,
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
				dataLabelsStyleFontSize: '15px',
				dataLabelsStyleFontWeight: 'bold',
				dataLabelsFormatterReturn: 'withValue',
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
			topTvChannelName
			// ['Fried Chicken', 'Beer', 'Adobo', 'Mechado', 'Afritada', 'Sinampalukan', 'Sisig', 'Fried Rice', 'Salad', 'Sea Foods']
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
				height: 277,
				toolbar: {
					show: true
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
			colors: ["#15a0a3"],
			xaxis: {
                categories: __categories
				// categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			},
			tooltip: {
				theme: 'dark',
				x: {
				    show: true
				},
                // fixed: {
                //     enabled: true,
                //     // position: 'center',
                //     offsetY: -47,
                //     offsetX: -110   

                // }
			},
			fill: {
                opacity: 1,
                type: 'solid',
                colors: '#15a0a3'
            },
		};
		let chart = new ApexCharts(document.querySelector(__selector), options);
		chart.render();
    }
</script>

@stop