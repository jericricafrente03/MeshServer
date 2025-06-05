@php
    $hasAppsPermission = auth()->user()->can('analytics.apps');
    $hasGuestCheckinPermission = auth()->user()->can('analytics.room_assignments');
    $columnClass = ($hasAppsPermission && $hasGuestCheckinPermission) ? 'col-lg-6' : 'col-lg-12';
@endphp

<div class="row">
    @if($hasAppsPermission)
        <div class="{{ $columnClass }} col-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Apps
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-appdonutchart" type="button" role="tab">Month</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-applinechart" type="button" role="tab">Year</button>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body cb-mb">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade chart-responsive chart-container" id="pills-appdonutchart" role="tabpanel">
                            <div id="appDonutChart" style="height: 300px; margin-top: -20px; margin-bottom: 10px;"></div>
                        </div>
                        <div class="tab-pane fade show active chart-responsive chart-container" id="pills-applinechart" role="tabpanel">
                            <div id="appLineChart" style="margin-top: -25px; margin-bottom: 15px;"></div>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </div>
    @endif

    @if($hasGuestCheckinPermission)
        <div class="{{ $columnClass }} col-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-solid fa-chart-column mr-1"></i>
                        Guest Checkin
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body cb-mb">
                    <div class="chart-responsive">
                        <div id="guestCheckInBarChart"></div>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </div>
    @endif
</div>