<h1 class="sidebar-header">
    Insert Record <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
</h1>
<h4 style="width: 300px"> Share Locations Visited </h4>

<div class="form" style="width:100%;padding-right:5%; color:rgb(220,31,37)">
    <form method="post" ng-submit="submitForm()">
        <label>Date</label>
        <div class="input-group" moment-picker="form.date" format="YYYY-MM-DD">
            <span class="input-group-addon">
                <i class="octicon octicon-calendar"></i>
            </span>
            <input class="form-control" placeholder="Select a date" ng-model="form.date"
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
            <input type="text" gm-places-autocomplete ng-model="form.location" class="form-control" />
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
            <div uib-timepicker ng-model="form.start_time" hour-step="1" minute-step="1" show-meridian="false"
                show-spinners="true"></div>
        </div>
        <div class="form-group">
            <label>Time To</label>
            <div uib-timepicker ng-model="form.end_time" hour-step="1" minute-step="1" show-meridian="false"
                show-spinners="true"></div>
        </div>
        <div class="form-group">
            <label>Comments</label>
            <textarea ng-model="form.comments" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Enter URL if you have sourced this information</label>
            <input type="text" ng-model="form.source" class="form-control">
        </div>
        <div class="form-group">
            <label>Positive Case Date</label>
            <div class="input-group" moment-picker="form.positive_case_date" format="YYYY-MM-DD">
                <span class="input-group-addon">
                    <i class="octicon octicon-calendar"></i>
                </span>
                <input class="form-control" placeholder="Select a date" ng-model="form.positive_case_date"
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
            <label>Submitted By (your name)</label>
            <input type="text" ng-model="form.submitted_by" class="form-control">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" ng-model="form.email" class="form-control">
            <label>Email will not be shown in results</label>
            <label>Email is used to track multiple venues</label>
            <label>Please use the same one for multiple entries</label>
        </div>

        <div class="alert alert-danger" ng-repeat="e in errors">@{{e}}</div>


        <div class="d-grid gap-2 col-8 mx-auto mt-3">
            <button ng-show="processing==false" class="btn btn-primary"><i class="fa fa-save"></i> SAVE</button>
            <div ng-show="processing==true" class="alert alert-info mt-5">Please wait...</div>
        </div>


    </form>
</div>

</div>