<div class="table-responsive">
    <table class="table table-striped mt-5" style="width:70vw;float:right" ng-show="viewType=='table'">
        <tr>
            <th class="nowrap">Date</th>
            <th>Advice</th>
            <th>Location</th>
            <th>Address</th>
            <th>Suburb</th>
            <th>State</th>
            <th>Time Start</th>
            <th>Time End</th>

        </tr>
        <tr ng-repeat="c in tableData.features">
            <td class="nowrap">@{{c.properties.date}}</td>
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
