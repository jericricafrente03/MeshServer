<div class="row">
    @can('analytics.fnbs')
        <div class="col-lg-4 col-12 col-md-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area mr-1"></i>
                        Top 10 FnB
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body cb-mb">
                    <div class="chart-responsive">
                        <div id="top10FnbLineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('analytics.item_requests')
        <div class="col-lg-4 col-12 col-md-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area mr-1"></i>
                        Top 10 Item Request
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body cb-mb">
                    <div class="chart-responsive">
                        <div id="top10ItemRequestLineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('analytics.service_requests')
        <div class="col-lg-4 col-12 col-md-12 d-flex">
            <div class="card w-100" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area mr-1"></i>
                        Top 10 Service Request
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body cb-mb">
                    <div class="chart-responsive">
                        <div id="top10ServiceRequestLineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
</div>