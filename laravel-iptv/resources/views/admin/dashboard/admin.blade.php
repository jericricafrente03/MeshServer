@extends('layouts.master')
@section('content')
<style>
	/* dashboard cards */
    .bg-db-online-stb {
        background-color: #15a0a3 !important;
    }
	
	.bg-db-online-stb > .inner > h3,
	.bg-db-online-stb > .inner > p {
		color: #ffffff !important;
	}

	.bg-db-offline-stb {
        background-color: #db3d5c !important;
    }
	
	.bg-db-offline-stb > .inner > h3,
	.bg-db-offline-stb > .inner > p {
		color: #ffffff !important;
	}

	.bg-db-guest-checkin {
        background-color: #edba21 !important;
    }
	
	.bg-db-guest-checkin > .inner > h3,
	.bg-db-guest-checkin > .inner > p {
		color: #ffffff !important;
	}

	.bg-db-guest-checkout {
        background-color: #507fb5 !important;
    }
	
	.bg-db-guest-checkout > .inner > h3,
	.bg-db-guest-checkout > .inner > p {
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

	/* Active tab styles */
	.nav-pills .nav-link.active {
		background-color: #15a0a3 !important; /* Active tab background color */
		color: #fff !important; /* Active tab text color */
	}

	/* Non-active tab styles */
	.nav-pills .nav-link {
		color: #6c757d; /* Non-active tab text color */
	}

	/* Hover effect for non-active tabs */
	.nav-pills .nav-link:hover {
		background-color: #e2e6ea; /* Background color on hover */
		color: #495057; /* Text color on hover */
	}
</style>
<!--start page wrapper -->
	<div class="page-wrapper">
		<div class="page-content">

			<!-- first row -->
			@include('admin/dashboard/partials/first_row')

			<!-- second row -->
			@include('admin/dashboard/partials/second_row')

			<!-- third row -->
			@include('admin/dashboard/partials/third_row')

			<!-- fourth row -->
			@include('admin/dashboard/partials/fourth_row')

			<!-- fifth row -->
			@include('admin/dashboard/partials/fifth_row')

		</div>
	</div>
<!--end page wrapper -->

@stop
@section('pages_specific_scripts')   

<script>
	let globalDonutChart = null;
	let globalRoomPieChart = null;
	let globalRoomVaccantRadialChart = null;
	let globalRoomOccupiedRadialChart = null;
	let globalRoomMaintenanceRadialChart = null;
	let globalRoomRepairRadialChart = null;
	let globalCpuRadialChart = null;
	let globalRealMemoryRadialChart = null;
	let globalVirtualMemoryRadialChart = null;
	let globalLocalDiskSpaceRadialChart = null;


	$(document).ready(function() {
		//collapse System Information
		$('[data-card-widget="collapse"]').on('click', function () {
            let button = $(this);
			let card = button.closest('.card');
			let icon = $('.minimize-holder');
			
			// Toggle the collapse functionality
			card.find('.card-body').slideToggle();

			// Toggle the icon based on the state
			if (icon.hasClass('fa-minus')) {
				icon.removeClass('fa-minus').addClass('fa-plus');
			} else {
				icon.removeClass('fa-plus').addClass('fa-minus');
			}
        });

		var pusher = new Pusher('46081c3d261c0267298e', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('device-channel');
        channel.bind('device-event', function(data) {
            // alert(JSON.stringify(data));
            // loadDevices(data);
            // inFooterScriptAjaxBasicGet(updateDeviceContainer, "/device_monitor/get", data);
			switch (data.result.type) {
				case "stb":
					loadStbCardData(data);
					break;
				case "guest":
					loadGuestCardData(data);
					break;
				case "room":
					loadRoomData(data.result);
					break;
				case "system_info":
					loadSystemInfo(data.result);
					break;
				default:
					break;
			}
        });

		// Initially load data
		inFooterScriptAjaxBasicGet(loadPingData, "/analytics/ping-devices");
		// Auto-refresh data every 1 minute
        setInterval(function() {
            inFooterScriptAjaxBasicGet(loadPingData, "/analytics/ping-devices");
        }, 60000);

		inFooterScriptAjaxBasicGet(loadAppAnalyticData, "/analytics/get-apps");

		inFooterScriptAjaxBasicGet(loadThisYearGuestCheckinData, "/analytics/get-year-checkin");

		inFooterScriptAjaxBasicGet(loadTop10TvChannelsData, "/analytics/get-top-10-tv-channels");

		inFooterScriptAjaxBasicGet(loadRoomData, "/analytics/get-rooms");

		inFooterScriptAjaxBasicGet(loadGuestCardData, "/analytics/get-guests");

		inFooterScriptAjaxBasicGet(loadTop10FnbsData, "/analytics/get-top-10-fnbs");

		inFooterScriptAjaxBasicGet(loadTop10ItemRequestData, "/analytics/get-top-10-item-requests");

		inFooterScriptAjaxBasicGet(loadTop10ServiceRequestData, "/analytics/get-top-10-service-requests");
	
	});	

	function loadSystemInfo(data){
		globalCpuRadialChart = inFooterScriptRadialBarChart(
			[data.stats.cpu], 
			'#cpuRadialChart', 
			[{ 
				colors: ['#15a0a3'],
				chartHeight: 250,
				labels: 'CPU',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 250,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: -90,
				plotOptionsRadialBarEndAngle: 90,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: -100,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "flat"
			}],
			[data.stats.cpu+'%'],
			globalCpuRadialChart
		);

		globalRealMemoryRadialChart = inFooterScriptRadialBarChart(
			[data.stats.memory_used_percent], 
			'#realMemoryRadialChart', 
			[{ 
				colors: ['#e52c50'],
				chartHeight: 250,
				labels: 'Real Memory',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 250,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: -90,
				plotOptionsRadialBarEndAngle: 90,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: -100,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "flat"
			}],
			[data.stats.memory_used_percent+'%'],
			globalRealMemoryRadialChart
		);

		globalVirtualMemoryRadialChart = inFooterScriptRadialBarChart(
			[data.stats.virtual_memory_used_percent], 
			'#virtualMemoryRadialChart', 
			[{ 
				colors: ['#edba21'],
				chartHeight: 250,
				labels: 'Virtual Memory',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 250,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: -90,
				plotOptionsRadialBarEndAngle: 90,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: -100,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "flat"
			}],
			[data.stats.virtual_memory_used_percent+'%'],
			globalVirtualMemoryRadialChart
		);

		globalLocalDiskSpaceRadialChart = inFooterScriptRadialBarChart(
			[data.stats.disk_used_percent], 
			'#localDiskSpaceRadialChart', 
			[{ 
				colors: ['#507fb5'],
				chartHeight: 250,
				labels: 'Local Disk Space',
				responsiveBreakpoint: 1195,
				responsiveOptionsChartHeight: 250,
				plotOptionsRadialBarHollowMargin: 15,
				plotOptionsRadialBarHollowSize: "50%",
				plotOptionsRadialBarStartAngle: -90,
				plotOptionsRadialBarEndAngle: 90,
				plotOptionsRadialBarDataLabelsShow: true,
				plotOptionsRadialBarDataLabelsNameOffsetY: -100,
				plotOptionsRadialBarDataLabelsNameShow: true,
				plotOptionsRadialBarDataLabelsNameColor: "#111",
				plotOptionsRadialBarDataLabelsNameFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueOffsetY: -10,
				plotOptionsRadialBarDataLabelsValueColor: "#111",
				plotOptionsRadialBarDataLabelsValueFontWeight: 650,
				plotOptionsRadialBarDataLabelsValueFontSize: "25px",
				plotOptionsRadialBarDataLabelsValueShow: true,
				strokeLineCap: "flat"
			}],
			[data.stats.disk_used_percent+'%'],
			globalLocalDiskSpaceRadialChart
		);
	}

	function loadStbCardData(data){
		$('#onlineStbCount').text(data.result.active);
		$('#offlineStbCount').text(data.result.inactive);
	}

	function loadGuestCardData(data){
		$('#guestCheckinCount').text(data.result.checkin);
		$('#guestCheckoutCount').text(data.result.checkout);
	}

	function loadAppAnalyticData(result, data){
		// let applicationCounts = [{
		// 	name: 'TV',
		// 	data: [12, 11, 14, 18, 17, 13, 28, 29, 33, 36, 32, 32]
		// }, {
		// 	name: 'Netflix',
		// 	data: [28, 29, 33, 36, 32, 32, 12, 11, 14, 18, 17, 13]
		// }];
		
		inFooterScriptLineChart(
			result.data,
			"#appLineChart",
			[{ 
				colors: result.color,
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
			result.month
			// ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
		);

		let names = result.data.map(item => item.name); // Extract names
    	let thisMonth = result.data.map(item => item.data[0]); // Get first data value (this month)

		globalDonutChart = inFooterScriptDonutChart(
            thisMonth,
            '#appDonutChart',
            [{ 
                colors: result.color,
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
			names,
            // ['TV', 'Netflix'],
            globalDonutChart
        );
    };

	function loadThisYearGuestCheckinData(result, data){
		let guestCheckInData = [{
			name: 'Guest Check-in',
			data: result.data //[20, 42, 414, 399, 121, 100, 20, 42, 414, 399, 121, 100]
		}];
                
		inFooterScriptBarChart(
			guestCheckInData,
			"#guestCheckInBarChart",
			[{ 
				colors: ['#15a0a3'],
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
				fillOpacity: 1,
				fillType: '',
				fillImageSrc: '',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom'
			}],
			result.month
			// ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
		);
	}

	function loadTop10TvChannelsData(result, data){
		let top10TvChannelsData = [{
			name: '',
			data: result.data,//[20, 42, 414, 399, 121, 100, 20, 42, 414, 399]
		}];

		inFooterScriptBarChart(
			top10TvChannelsData,
			"#top10TvChannelsBarChart",
			[{ 
				colors: ['#15a0a3', '#e52c50', '#edba21'],
				chartForeColor: '#212529',
				chartHeight: 480,
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
				fillOpacity: 1,
				fillType: '',//'image',
				fillImageSrc: '',//'{{ asset('source-images/images/meshtv_logo.png') }}',
				fillImageWidth: 600,
				fillImageHeight: 600,
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				legendFloating: true,
				xaxisPosition: 'bottom',
				dataLabelsEnabled: true,
				dataLabelsTextAnchor: 'start',
				dataLabelsStyleColors: ["#000000"],
				dataLabelsStyleFontSize: '15px',
				dataLabelsStyleFontWeight: 'bold',
				dataLabelsFormatterReturn: 'labelOnly',
				dataLabelsOffsetX: 0,
				strokeWidth: 0,
				strokeColors: ["#fff"],

			}],
			result.name
			// ['Euro News', 'NHK World', 'GMA News TV', 'Cartoon Network', 'Animal Planet', 'National Geographic Channel', 'HBO', 'ABS-CBN', 'Animax', 'Asian Drama']
		);
	}

	function loadRoomData(result, data){
		// let roomPieData = [
        //     3,2,2,1
        // ]; 

		let roomData = result.data.map(item => item.count);
        let nameData = result.data.map(item => item.name);

		globalRoomPieChart = inFooterScriptPieChart(
            roomData,
            '#roomsPieChart',
            [{ 
                colors: result.color,//["#15a0a3", "#db3d5c", "#edba21", "#507fb5"],
                chartHeight: 300,
				chartForeColor: '#212529',
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
				plotOptionsPieDonutLabelsShow: false,
				plotOptionsPieDonutLabelsNameShow: true,
				plotOptionsPieDonutLabelsValueShow: true,
				plotOptionsPieDonutLabelsValueFontSize: '23',
				plotOptionsPieDonutLabelsValueFontWeight: 650,
				plotOptionsPieDonutLabelsValueColor: "#404143",
				plotOptionsPieDonutLabelsTotalShow: true,
				plotOptionsPieDonutLabelsTotalFontWeight: 650,
				plotOptionsPieDonutLabelsTotalColor: "#404143",
				tooltipEnabled: true
            }],
            nameData,// ['Vaccant', 'Occupied', 'Maintenance', 'Repair'],
            globalRoomPieChart
        );

		let vaccantData = result.data.find(item => item.name === "Vaccant");

		let vaccantRoomData = vaccantData ? vaccantData.count : 0;
		let vaccantPercentageData = vaccantData ? vaccantData.percentage : 0;
		let vaccantNameData = vaccantData ? vaccantData.name : "";
		let vaccantColorData = vaccantData ? vaccantData.color : "";

		globalRoomVaccantRadialChart = inFooterScriptRadialBarChart(
			[vaccantPercentageData], 
			'#vaccantRadialChart', 
			[{ 
				colors: [vaccantColorData],
				chartHeight: 150,
				labels: vaccantNameData,//'Vaccant',
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
			[vaccantRoomData],
			globalRoomVaccantRadialChart
		);

		let occupiedData = result.data.find(item => item.name === "Occupied");

		let occupiedRoomData = occupiedData ? occupiedData.count : 0;
		let occupiedPercentageData = occupiedData ? occupiedData.percentage : 0;
		let occupiedNameData = occupiedData ? occupiedData.name : "";
		let occupiedColorData = occupiedData ? occupiedData.color : "";
		
		globalRoomOccupiedRadialChart = inFooterScriptRadialBarChart(
			[occupiedPercentageData], 
			'#occupiedRadialChart', 
			[{ 
				colors: [occupiedColorData],
				chartHeight: 150,
				labels: occupiedNameData,
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
			[occupiedRoomData],
			globalRoomOccupiedRadialChart
		);

		let maintenanceData = result.data.find(item => item.name === "Maintenance");

		let maintenanceRoomData = maintenanceData ? maintenanceData.count : 0;
		let maintenancePercentageData = maintenanceData ? maintenanceData.percentage : 0;
		let maintenanceNameData = maintenanceData ? maintenanceData.name : "";
		let maintenanceColorData = maintenanceData ? maintenanceData.color : "";

		globalRoomMaintenanceRadialChart = inFooterScriptRadialBarChart(
			[maintenancePercentageData], 
			'#maintenanceRadialChart', 
			[{ 
				colors: [maintenanceColorData],
				chartHeight: 150,
				labels: maintenanceNameData,
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
			[maintenanceRoomData],
			globalRoomMaintenanceRadialChart
		);

		let repairData = result.data.find(item => item.name === "Repair");

		let repairRoomData = repairData ? repairData.count : 0;
		let repairPercentageData = repairData ? repairData.percentage : 0;
		let repairNameData = repairData ? repairData.name : "";
		let repairColorData = repairData ? repairData.color : "";

		globalRoomRepairRadialChart = inFooterScriptRadialBarChart(
			[repairPercentageData], 
			'#repairRadialChart', 
			[{ 
				colors: [repairColorData],
				chartHeight: 150,
				labels: repairNameData,
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
			[repairRoomData],
			globalRoomRepairRadialChart
		);
	}

	function loadTop10FnbsData(result, data){
		let fnbCounts = [{
			name: 'Last month order',
			type: 'line',
			data: result.data.last_month//[12, 11, 14, 18, 17, 13, 28, 29, 33, 36]
		}, {
			name: 'This month order',
			type: 'area',
			data: result.data.this_month//[28, 29, 33, 36, 32, 32, 12, 11, 14, 18]
		}];
		
		inFooterScriptLineChart(
			fnbCounts,
			"#top10FnbLineChart",
			[{ 
				colors: ['#db3d5c', '#15a0a3'],
				titleText: '',
				titleAlign: 'left',
				titleStyleFontSize: '16px',
				titleStyleColor: '#413c3c',
				chartForeColor: '#212529',
				chartHeight: 287,
				chartToolbarShow: true,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 0,
				strokeWidth: 5,
				strokeCurve: 'straight',
				markersSize: 0,
				markersSizeHoverSize: 5,
				yaxisTitle: '',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				optionsBarColumnWidth: '80%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: true,
				fillOpacity: 1,
				fillType: 'solid'
			}],
			result.name
			// ['Fried Chicken', 'Beer', 'Adobo', 'Mechado', 'Afritada', 'Sinampalukan', 'Sisig', 'Fried Rice', 'Salad', 'Sea Foods']
		);
	}

	function loadTop10ItemRequestData(result, data){
		let itemRequestCounts = [{
			name: 'Last month order',
			type: 'line',
			data: result.data.last_month//[17, 13, 28, 29]
		}, {
			name: 'This month order',
			type: 'area',
			data: result.data.this_month//[36, 32, 32, 12]
		}];

		inFooterScriptLineChart(
			itemRequestCounts,
			"#top10ItemRequestLineChart",
			[{ 
				colors: ['#db3d5c', '#15a0a3'],
				titleText: '',
				titleAlign: 'left',
				titleStyleFontSize: '16px',
				titleStyleColor: '#413c3c',
				chartForeColor: '#212529',
				chartHeight: 287,
				chartToolbarShow: true,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 0,
				strokeWidth: 5,
				strokeCurve: 'straight',
				markersSize: 0,
				markersSizeHoverSize: 5,
				yaxisTitle: '',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				optionsBarColumnWidth: '80%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: true,
				fillOpacity: 1,
				fillType: 'solid'
			}],
			result.name
			// ['Tooth Paste (Colgate)', 'Towel', 'Shampoo & Conditioner', 'Tooth Paste (Close-up)']
		);
	}

	function loadTop10ServiceRequestData(result, data){
		let sericeRequestCounts = [{
			name: 'Last month order',
			type: 'line',
			data: result.data.last_month//[29, 33, 36]
		}, {
			name: 'This month order',
			type: 'area',
			data: result.data.this_month//[11, 14, 18]
		}];

		inFooterScriptLineChart(
			sericeRequestCounts,
			"#top10ServiceRequestLineChart",
			[{ 
				colors: ['#db3d5c', '#15a0a3'],
				titleText: '',
				titleAlign: 'left',
				titleStyleFontSize: '16px',
				titleStyleColor: '#413c3c',
				chartForeColor: '#212529',
				chartHeight: 287,
				chartToolbarShow: true,
				gridShow: true,
				gridBorderColor: '#dfdfdfc7',
				gridStrokeDashArray: 0,
				strokeWidth: 5,
				strokeCurve: 'straight',
				markersSize: 0,
				markersSizeHoverSize: 5,
				yaxisTitle: '',
				legendPosition: 'top',
				legendHorizontalAlign: 'center',
				optionsBarColumnWidth: '80%',
				xaxisAxisBorderShow: false,
				xaxisAxisTicks: false,
				xaxisToolTipEnabled: false,
				xaxisLabelsShow: false,
				yaxisAxisBorderShow: false,
				yaxisAxisTicks: false,
				yaxisLabelsShow: true,
				fillOpacity: 1,
				fillType: 'solid'
			}],
			result.name
			// ['Garbage Collection', 'Shuttle Service', 'Plumbing Service']
		);
	}

	function loadPingData(result, data){
		//holder
	}
</script>
@stop
