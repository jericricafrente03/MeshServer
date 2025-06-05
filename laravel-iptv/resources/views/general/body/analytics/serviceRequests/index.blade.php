@extends('layouts.master')
<style>
	/* dashboard cards */
	.bg-enabled {
		background-color: #15a0a3 !important;
	}
	
	.bg-enabled > .inner > h3,
	.bg-enabled > .inner > p {
		color: #ffffff !important;
	}

	.bg-disabled {
		background-color: #db3d5c !important;
	}
	
	.bg-disabled > .inner > h3,
	.bg-disabled > .inner > p {
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
		top: 10px;
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
@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->

        <!-- first row -->
        @include('general/body/analytics/serviceRequests/partials/firstRow')
        <!-- end of first row -->
        
        <!-- second row -->
        @include('general/body/analytics/serviceRequests/partials/secondRow')
        <!-- end of second row -->

        <!-- third row -->
        @include('general/body/analytics/serviceRequests/partials/thirdRow')
        <!-- end of third row -->
		
		<!-- fourth row -->
		@include('general/body/analytics/serviceRequests/partials/fourthRow')
        <!-- end of fourth row -->

    </div>
</div>
    @include('sweetalert2/script')
@stop

@section('pages_specific_scripts')
<script>
    let globalStatusDonutChart = null;
    let globalActivityDonutChart = null;
	let globalCategoryDonutChart = null;
	let globalMonthBarChart = null;
	let globalDayAreaChart = null;

    $(document).ready(function() {
		inFooterScriptAjaxBasicGet(loadAnalyticData, "/analytics/get-service-request-data");

		inFooterScriptNoModalDropDownBelowSelect2('#item_id');

        let data = {
			id: ''
		};
		inFooterScriptAjaxBasicGet(loadItemDropdownResult, "/search/service-requests", data);
    });

	$(document).on('change', '#item_id', function() {
        let data = {
            item_id: $('#item_id').val()
        };
        inFooterScriptAjaxBasicGet(loadItemCounterData, "/analytics/get-service-request-counter", data);
    });

	function loadItemCounterData(result, data){
		let itemCounterCounts = result.item_month.data;
                
		globalMonthBarChart = inFooterScriptBarChart(
			itemCounterCounts,
			"#counterLineChart",
			[{ 
				colors: ['#15a0a3'],
				titleText: 'Monthly Service Request',
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
			result.item_month.months,
			globalMonthBarChart
		);

		globalDayAreaChart = inFooterScriptAreaChart(
			result.item_day.data,
			"#counterAreaChart",
			[{ 
				colors: ['#15a0a3'],
				titleText: '30 Days Service Request',
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
			result.item_day.date,
			globalDayAreaChart
		);

	}

	function loadItemDropdownResult(result, data){
        let len = result.length;
        
        $("#item_id").empty();
        $("#item_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
        
          	$("#item_id").append("<option value='"+id+"'>"+name+"</option>");
        }
    };

	function loadAnalyticData(data){
		console.log(data);
		globalStatusDonutChart = inFooterScriptDonutChart(
            [data.enable_item, data.disable_item],
            '#statusDonutChart',
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
            globalStatusDonutChart
        );

		globalActivityDonutChart = inFooterScriptDonutChart(
            [data.created_count, data.deleted_count],
            '#activityDonutChart',
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
            globalActivityDonutChart
        );

		let itemActivityCounts = data.item_activity.data;

        inFooterScriptLineChart(
            itemActivityCounts,
			"#activityLineChart",
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
			data.item_activity.months
			// ['Dec', 'Nov', 'Oct', 'Sep', 'Aug', 'Jul', 'Jun', 'May', 'Apr', 'Mar', 'Feb', 'Jan']
		);

		$('#enabledCount').text(data.enable_item);
		$('#disabledCount').text(data.disable_item);


		let topItemCounts = [{
			name: 'This month ',
			data: data.top_item.this_month
		},{
			name: 'Last month ',
			data: data.top_item.last_month
		}];

		let topItemName = data.top_item.name;

		const dataCount = topItemName.length;

		const baseHeight = 480; // Minimum height
		const heightPerBar = 75; // Adjust this for spacing
		const dynamicHeight = Math.max(baseHeight, dataCount * heightPerBar);
		
		inFooterScriptBarChart(
			topItemCounts,
			"#topBarChart",
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
			topItemName
			// ['Fried Chicken', 'Beer', 'Adobo', 'Mechado', 'Afritada', 'Sinampalukan', 'Sisig', 'Fried Rice', 'Salad', 'Sea Foods']
		);

	}
</script>

@stop