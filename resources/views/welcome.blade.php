@extends('layouts.public')
@section('content')
<div ng-controller="AppCtrl">
    <div id="sidebar" class="sidebar collapsed">
        <div class="sidebar-tabs">
            <ul role="tablist">
                <li><a href="#filtersTab" role="tab"><i class="fa fa-bars"></i></a></li>
                <li><a href="#infoTab" role="tab"><i class="fas fa-info"></i></a></li>
                <li><a href="#formTab" role="tab"><i class="fab fa-wpforms"></i></a></li>
            </ul>
        </div>
        <div class="sidebar-content"  >
            <div class="sidebar-pane" id="filtersTab">
                <h1 class="sidebar-header" id="tlac">
                    CASES @{{totalCases}}
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                </h1>
                <!--Map Selection filters-->
                <div class="row">
                    <div class="col-md-12">
                      
                        <div class="btn-group mt-3">
                            <button ng-class="{'btn':true ,'btn-primary':viewType=='pod','btn-light':viewType!='pod'}" type="button" ng-click="switchView('pod')"> 
                                <i class="fa fa-map"> </i> OSM
                            </button>
                            <button ng-class="{'btn':true ,'btn-primary':viewType=='mapycz','btn-light':viewType!='mapycz'}" type="button" ng-click="switchView('mapycz')">
                                <i class="fa fa-road"></i> MAPY.CZ
                            </button>
                            <button ng-class="{'btn':true ,'btn-primary':viewType=='table','btn-light':viewType!='table'}" type="button" ng-click="switchView('table')">
                                <i class="fa fa-table"></i>
                                TABLE
                            
                            </button>
                         
                        </div>

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
                    <div class="input-group"
                        moment-picker="form.date"
                        format="YYYY-MM-DD">
                        <span class="input-group-addon">
                            <i class="octicon octicon-calendar"></i>
                        </span>
                        <input class="form-control"
                            placeholder="Select a date"
                            ng-model="form.date"
                            ng-model-options="{ updateOn: 'blur' }">
                    </div>
       
                        <div class="form-group">
                            <label>Type Of Contact</label>
                            <select class="form-control" ng-model="form.advice">
                                <option value=""></option>
                                <option value="Close">Close Contact</option>
                                <option value="Casual">Casual Contact</option>
                                <option value="Staff">Staff</option>
                                <option value="Customer">Customer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label> Location </label>
                            <input type="text" gm-places-autocomplete ng-model="form.location" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label> Address </label>
                            <input type="text" ng-model="form.address" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label> Suburb </label>
                            <input type="text" ng-model="form.suburb" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label> State </label>
                            <select class="form-control" ng-model="form.state">
                                <option value=""></option>
                                <option value="NSW">NSW</option>
                                <option value="VIC">VIC</option>
                                <option value="QLD">QLD</option>
                                <option value="ACT">ACT</option>
                                <option value="SA">SA</option>
                                <option value="WA">WA</option>
                                <option value="TAS">TAS</option>
                                <option value="NT">NT</option>
                         
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Time From</label>
                            <div uib-timepicker ng-model="form.start_time"  hour-step="1" minute-step="1" show-meridian="false" show-spinners="true"></div>
                        </div>
                        <div class="form-group">
                            <label>Time To</label>
                            <div uib-timepicker ng-model="form.end_time"  hour-step="1" minute-step="1" show-meridian="false" show-spinners="true"></div>
                        </div>
                        <div class="form-group">
                            <label>Comments</label>
                            <textarea ng-model="form.comments" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Please enter your email or Website Reference</label>
                            <input type="text" ng-model="form.source" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Positive Case Date</label>
                            <div class="input-group"
                                moment-picker="form.positive_case_date"
                                format="YYYY-MM-DD">
                                <span class="input-group-addon">
                                    <i class="octicon octicon-calendar"></i>
                                </span>
                                <input class="form-control"
                                    placeholder="Select a date"
                                    ng-model="form.positive_case_date"
                                    ng-model-options="{ updateOn: 'blur' }">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Positive Case Type</label>
                            <select class="form-control" ng-model="form.positive_case_type">
                                <option value=""></option>
                                <option value="PCR">PCR</option>
                                <option value="RAT">RAT</option>  
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Submitted By</label>
                            <input type="text" ng-model="form.submitted_by" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" ng-model="form.email" class="form-control">
                        </div>
                     
                     

                       
                        <div class="alert alert-danger" ng-repeat="e in errors">@{{e}}</div>
                       
                       
                            <div class="d-grid gap-2 col-8 mx-auto mt-3">
                                <button   ng-show="processing==false" class="btn btn-primary" ><i class="fa fa-save"></i> SAVE</button>
                                <div   ng-show="processing==true" class="alert alert-info mt-5">Please wait...</div>
                            </div>
                        
                        
                    </form>
                </div>

            </div>


        </div>
    </div>

    <table class="table table-striped mt-5" style="width:70vw;float:right"  ng-show="viewType=='table'">
                <tr>
                    <th>Date</th>
                    <th>Advice</th>
                    <th>Location</th>
                    <th>Address</th>      
                    <th>Suburb</th>
                    <th>State</th>
                    <th>Time Start</th>
                    <th>Time End</th>
              
                </tr>
                <tr ng-repeat="c in tableData.features">
                    <td>@{{c.properties.date}}</td>
                    <td>@{{c.properties.advise}}</td>
                    <td>@{{c.properties.location}}</td>
                    <td>@{{c.properties.address}}</td>
                    <td>@{{c.properties.suburb}}</td>
                    <td>@{{c.properties.state}}</td>
                    <td>@{{c.properties.start_time}}</td>
                    <td>@{{c.properties.end_time}}</td>
                    
                </tr>
                </table>
      
    </div>

    <div id="map" class="sidebar-map"></div>
  

  

@endsection
