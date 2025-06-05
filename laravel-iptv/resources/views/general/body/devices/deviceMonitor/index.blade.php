@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->

        <div class="card">
            @can('devices.detect_devices')
            <div class="p-3 mb-0 card-header dt-card-header-schedule">
                <button type="button" class="btn btn-primary me-1 schedule-icon-btn schedule-list-btn" onclick="detectDevices()">
                    Detect Devices <i class="fa fa-bullhorn"></i>
                </button>
            </div>
            @endcan
            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="chart-responsive">
                            <div class="chart" id="deviceDonut" style="height: 300px; position: relative;"></div>
                        </div>
                    </div>

                    <div class="col-md-6" id="device-container">
                        Loading..
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert2/script')
    
@stop

@section('pages_specific_scripts')

<script>
    let globalDonutChart = null;
    $(document).ready(function() {
        var pusher = new Pusher('46081c3d261c0267298e', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('device-channel');
        // channel.bind('device-event', function(data) {
        //     // alert(JSON.stringify(data));
        //     // loadDevices(data);
        //     inFooterScriptAjaxBasicGet(updateDeviceContainer, "/device_monitor/get", data);
        //     loadChart();
        // });

        channel.bind('device-event', function(data) {
            // alert(JSON.stringify(data));
            // loadDevices(data);
            // inFooterScriptAjaxBasicGet(updateDeviceContainer, "/device_monitor/get", data);
			switch (data.result.type) {
				case "stb":
					inFooterScriptAjaxBasicGet(updateDeviceContainer, "/device_monitor/get", data);
                    loadChart();
					break;
				default:
					break;
			}
        });
        // Setup Laravel Echo to listen for the DevicesUpdated event
        // Echo.channel('devices')
        //     .listen('DevicesUpdated', (e) => {
        //         // Update the device container with the new devices
        //         updateDeviceContainer(e.devices);
        //     });

        // Function to fetch and update devices via AJAX
        // function loadDevices(data) {
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "/device_monitor/get", // Adjust the route to match your route name
        //         type: 'GET',
        //         contentType: "application/json; charset=utf-8",
        //         dataType: "json",
        //         success: function(data) {
        //             updateDeviceContainer(data);
        //         }
        //     });
        // }



        // Load devices every 10 seconds
        // setInterval(loadDevices, 10000);

        // Initial load
        // inFooterScriptAjaxBasicGet(updateDeviceContainer, "/device_monitor/get");
        inFooterScriptAjaxBasicGet(updateDeviceContainer, "/analytics/ping-devices");
    });

    // Function to update the device container with provided devices
    function updateDeviceContainer(devices) {
        // console.log(devices);
        let currentCategory = "";
        let htmlContent = '';
        
        if(devices.result){
            return;
        }

        devices.forEach(function(device) {
            if (currentCategory !== device.name) {
                currentCategory = device.name;
                htmlContent += '<h5 class="mt-1">' + currentCategory + '</h5>';
            }

            let btnClass = (device.current_status == 'Active') ? 'btn-success' : 'btn-danger';

            htmlContent += '<a class="btn ' + btnClass + ' btn-sm stb" data-bs-toggle="popover" data-bs-placement="top" title="Room: ' + device.room_number + '" data-bs-trigger="hover" data-bs-content="' + device.ip4_address + '; ' + device.mac_address + '">';
            htmlContent += '<i class="fa fa-tv"></i></a>';
        });

        $('#device-container').html(htmlContent);
        // Reinitialize popovers after content is added
        $('[data-bs-toggle="popover"]').popover();
    }

    function loadChart(){
        inFooterScriptAjaxBasicGet(loadChartResult, "/device_monitor/chart-data");
    }

    function loadChartResult(data){
        // console.log(data);
        let deviceDonutData = [
            parseInt(data.active),
            parseInt(data.inactive)
        ]; 

        globalDonutChart = inFooterScriptDonutChart(
            deviceDonutData,
            '#deviceDonut',
            [{ 
                colors: ["#15ca20", "#fd3550"],
                chartForeColor: '#212529',
                chartHeight: 300,
				chartToolbarShow: false,
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
				plotOptionsPieDonutLabelsValueShow: true,
				plotOptionsPieDonutLabelsValueFontSize: '23',
				plotOptionsPieDonutLabelsValueFontWeight: 650,
				plotOptionsPieDonutLabelsValueColor: "#212529",
				plotOptionsPieDonutLabelsTotalShow: true,
				plotOptionsPieDonutLabelsTotalFontWeight: 650,
				plotOptionsPieDonutLabelsTotalColor: "#212529",
                tooltipEnabled: true
            }],
            ['Active', 'Inactive'],
            globalDonutChart
        );
    }

    function detectDevices(){
        // let data = {
        //     first : 1,
        //     second : 2
        // }
        // inFooterScriptAjaxBasicGet(detectDevicesResult, "/devices/detect-devices", data);
        inFooterScriptAjaxBasicGet(updateDeviceContainer, "/analytics/ping-devices");
    }

    function detectDevicesResult(data) {
        // console.log(data);  // Handle the data
    }
</script>

@stop