@extends('layouts.public')
@section('content')

    <div id="sidebar" class="sidebar collapsed">
        <div class="sidebar-tabs">
            <ul role="tablist">
                <li><a href="#filtersTab" role="tab"><i class="fa fa-bars"></i></a></li>
                <li><a href="#infoTab" role="tab"><i class="fas fa-info"></i></a></li>
                <li><a href="#formTab" role="tab"><i class="fab fa-wpforms"></i></a></li>
            </ul>
        </div>
        <div class="sidebar-content"  ng-controller="AppCtrl">
            <div class="sidebar-pane" id="filtersTab">
                <h1 class="sidebar-header" id="tlac">
                    CASES @{{totalCases}}
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <!--Map Selection filters-->
                <div class="row">
                    <div class="col-md-12">
                        <div id="mapTypes"></div>
                    </div>
                </div>

                <!--Contact Types filters -->
                <div class="row mt-5">
                    <div class="col-md-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" ng-model="filters.casual" value="casual"
                                id="casual" ng-change="filterData()">
                            <label class="label" for="casual">
                                Casual Contact
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img src="/img/yellow_pointer.png" height="30">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" ng-model="filters.close" value="close"
                                id="close" ng-change="filterData()">
                            <label class="label" for="close">
                                Close Contact
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img src="/img/red_pointer.png" height="30">
                    </div>
                </div>
                <!-- FILTER BY SUBURB-->
                <div class="row mt-3">
                    <div class="col-md-8">
                        <label class="label mb-2"> FILTER BY SUBURB</label>
                        <select ng-model="filters.suburb" class="form-control" ng-change="filterData()"
                            placeholder="Filter">
                            <option value="">All Suburbs</option>
                            <option ng-repeat="s in suburbs" ng-value="s.suburb">@{{s.suburb}} (@{{s.total}})</option>
                        </select>
                    </div>
                </div>
                <!-- FILTER BY TIME -->
                <div class="mt-3">
                    <label class="label">TIME RANGE</label>
                </div>

                <div id="timeRange" style="margin-top:10%;max-width:250px"></div>
            </div>
            <!-- PROJECT INFO TAB -->
            <div class="sidebar-pane" id="infoTab">
                <h1 class="sidebar-header">
                    Project result
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>

                <div class="lorem">
                    <b>INFO</b>
                </div>
            </div>

        <div class="sidebar-pane" id="formTab"  >
                <h1 class="sidebar-header">
                    Create data <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <h4 style="width: 300px"> REPORT COVID CASE </h4>
                <div class="form" style="width: 15vw; color:rgb(220,31,37)">
                    <form method="post" ng-submit="submitForm()">
                        <div class="form-group">
                            <label> Address </label>
                            <input type="text" ng-model="form.address" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label> Suburb </label>
                            <input type="text" ng-model="form.suburb" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" ng-model="form.lat" id="latInput" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" ng-model="form.lng" id="lngInput" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label>Advise Type</label>
                            <select class="form-control" ng-model="form.advice">
                                <option value=""></option>
                                <option value="Close">Close Contact</option>
                                <option value="Casual">Casual Contact</option>
                            </select>
                        </div>
                        <div class="alert alert-danger" ng-repeat="e in errors">@{{e}}</div>
                       
                            <button   ng-show="processing==false" class="btn btn-primary pull-right" >SAVE</button>
                            <div   ng-show="processing==true" class="alert alert-info">Please wait...</button>
                        
                        
                    </form>
                </div>

            </div>


        </div>
    </div>
</div>

    <div id="map" class="sidebar-map"></div>

@endsection
