@php
    $hasTop10Permission = auth()->user()->can('analytics.tv');
    $hasRoomPermission = auth()->user()->can('analytics.rooms');
    $columnClass = ($hasTop10Permission && $hasRoomPermission) ? 'col-lg-6' : 'col-lg-12';
@endphp

<div class="row">
    @if($hasTop10Permission)
        <div class="{{ $columnClass }} col-6 col-md-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Top 10 TV Channels
                </h3>
            </div><!-- /.card-header -->
            <div class="card-body cb-mb">
                <div class="chart-responsive">
                    <div id="top10TvChannelsBarChart"></div>
                </div>
            </div><!-- /.card-body -->
            </div>
        </div>
    @endif

    @if($hasRoomPermission)
        <div class="{{ $columnClass }} col-6 col-md-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                    <i class="fas fa-solid fa-chart-pie mr-1"></i>
                    Rooms
                </h3>
            </div><!-- /.card-header -->
            <div class="card-body cb-mb">
                <div class="row chart-responsive">
                    <div class="col-lg-12 col-12 col-md-12" id="roomsPieChart" class="mb-3"></div>
                    <div class="col-lg-3 col-3 col-md-3" id="vaccantRadialChart" class="mb-3"></div>
                    <div class="col-lg-3 col-3 col-md-3" id="occupiedRadialChart" class="mb-3"></div>
                    <div class="col-lg-3 col-3 col-md-3" id="maintenanceRadialChart" class="mb-3"></div>
                    <div class="col-lg-3 col-3 col-md-3" id="repairRadialChart" class="mb-3"></div>
                </div>
            </div><!-- /.card-body -->
            </div>
        </div>
    @endif
</div>