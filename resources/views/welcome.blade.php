@extends('layouts.public')
@section('content')
<div ng-controller="AppCtrl">
    <div id="sidebar" class="sidebar collapsed">
        <div class="sidebar-tabs">
            <ul role="tablist">
                <li><a href="#filtersTab" role="tab"><i class="fa fa-bars"></i></a></li>
                <li><a href="#formTab" role="tab"><i class="fa fa-plus"></i></a></li>
                <li><a href="#infoTab" role="tab"><i class="fas fa-info"></i></a></li>
            </ul>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-pane" id="filtersTab">
                <h1 class="sidebar-header" id="tlac">
                    EXPOSURE COUNT: @{{totalCases}}
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <!--Map Selection filters-->
                <div class="row">
                    <div class="col-md-12">

                        <div class="btn-group mt-3">
                            <button ng-class="{'btn':true ,'btn-primary':viewType=='pod','btn-light':viewType!='pod'}"
                                type="button" ng-click="switchView('pod')">
                                <i class="fa fa-map"> </i> MAP
                            </button>
                            <button
                                ng-class="{'btn':true ,'btn-primary':viewType=='table','btn-light':viewType!='table'}"
                                type="button" ng-click="switchView('table')">
                                <i class="fa fa-table"></i>
                                TABLE
                            </button>

                        </div>

                    </div>
                </div>

                <!--Contact Types filters -->
                <div class="row mt-5">
                    <label class="label mb-2"> FILTER BY TYPE</label>
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
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" ng-model="filters.customer" value="customer"
                                id="customer" ng-change="filterData()">
                            <label class="label" for="customer">
                                Customer
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img src="/img/blue_pointer.png" height="30">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" ng-model="filters.staff" value="staff"
                                id="staff" ng-change="filterData()">
                            <label class="label" for="staff">
                                Staff
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img src="/img/green_pointer.png" height="30">
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
                <!-- FILTER BY LGA-->
                <div class="row mt-3">
                    <div class="col-md-8">
                        <label class="label mb-2"> FILTER BY LGA</label>
                        <select ng-model="filters.lgs" class="form-control" ng-change="filterData()"
                            placeholder="Filter">
                            <option value="">All LGAs</option>
                            <option ng-repeat="l in lgs" ng-value="l.name">@{{l.name}} (@{{l.total}})</option>
                        </select>
                    </div>
                </div>
                <!-- FILTER BY DATE-->
                <div class="row mt-3">
                    <div class="col-md-8">
                        <label class="label mb-2"> FILTER BY DATE</label>
                        <select ng-model="filters.date" class="form-control" ng-change="filterData()"
                            placeholder="Filter">
                            <option value="">All Dates</option>
                            <option ng-repeat="l in dates" ng-value="l.name">@{{l.name}} (@{{l.total}})</option>
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
                    Website Information
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>

                <div class="lorem">
                    <b>Using Crowd Sourcing to track community cases</b>
                </div>
            </div>

            <div class="sidebar-pane" id="formTab">
                @include('new_record_form')
            </div>
        </div>

        @include('table_view')
        @include('popup')

    </div>
    <div id="map" class="sidebar-map"></div>


    @endsection
