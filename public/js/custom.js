// Create the map
var map=false;
var app =angular.module('covid', ['ui.bootstrap','gm','moment-picker']);
var mapycz = L.tileLayer('https://m{s}.mapserver.mapy.cz/base-m/{z}-{x}-{y}', {
    ident: 'mapycz',
    attribution: '&copy;Seznam.cz a.s., | &copy;OpenStreetMap <a href="https://mapy.cz"><img class="print" target="_blank" src="//api.mapy.cz/img/api/logo.png" style="cursor: pointer; position:relative;top: 5px;"></a>',
    maxZoom: 20,
    subdomains: "1234"
});
var pod = L.tileLayer('https://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
    ident: 'pod',
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
});
app.controller('AppCtrl',function($scope,$http){
    $scope.filters={};
    $scope.totalCases=0;
    $scope.suburbs=[];
    $scope.lgs=[];
    $scope.dates=[];
    $scope.form={};
    $scope.viewType='pod';
    $scope.tableData=[];
    $scope.setSuburbs=function(){
        $http.get("/api/suburbs",{params:$scope.filters}).then(function(res){
            $scope.suburbs = res.data;
        });
    }
    $scope.setLgs=function(){
        $http.get("/api/lgs",{params:$scope.filters}).then(function(res){
            $scope.lgs = res.data;
        });
    }
    $scope.setDates=function(){
        $http.get("/api/days",{params:$scope.filters}).then(function(res){
            $scope.dates = res.data;
        });
    }
    $scope.filterData=function(){
        $http.get("/api/warnings",{params:$scope.filters}).then(function(res){
            $scope.totalCases=res.data.features.length;
            $scope.tableData = res.data;
            if(map == false){
                $scope.drawMap(res.data);
            }else{
                $scope.reDrawMarkers(res.data);
            }
            
            $scope.setSuburbs();
            $scope.setLgs();
            $scope.setDates();
           
        });
    }

    $scope.reDrawMarkers=function(mapData){
        map.eachLayer(function(layer){
            if(layer.hasOwnProperty('feature')){
                layer.remove();
            }
        });
       
        let markers= L.geoJson(mapData, {
            pointToLayer: function (feature, latlng, ) {
                return L.marker(latlng, {
                    icon: L.icon({
                        iconUrl: feature.properties.icon,
                        iconSize: [30, 40],
                        iconAnchor: [10, 15],
                    })
                });
            },
            onEachFeature: function (feature, layer) {
                let body="<strong><i class='fa fa-home'></i> Address</strong>:"+feature.properties.full_address
                body=body+"<br><strong><i class='fa fa-calendar'></i> Date</strong>:"+feature.properties.date
                body=body+"<br><strong><i class='fa fa-clock'></i> Time</strong>:"+feature.properties.time;
                layer.bindPopup(body, {
                    className: 'myCSSClass'
                });
            }
        });
    
        map.addLayer(markers);

        map.setView(new L.LatLng(mapData.crs.properties.lat, mapData.crs.properties.lng, 95));
    }
   
    $scope.drawMap=function(mapData){
        var casualContactsLayer = L.geoJson(mapData, {
            pointToLayer: function (feature, latlng, ) {
                return L.marker(latlng, {
                    icon: feature.properties.icon
                });
            },
            onEachFeature: function (feature, layer) {

                layer.bindPopup("<h3>"+feature.properties.address+"</h3>", {
                    className: 'myCSSClass'
                });
            }
        });
        map = new L.map('map', {
           center: new L.LatLng(mapData.crs.properties.lat, mapData.crs.properties.lng, 95),
           zoom: 8,
           maxZoom: 18,
           drawControl:true,
           zoomControl: false,
           layers: pod,
               casualContactsLayer
           });
           L.control.zoom({
               position: 'topright'
           }).addTo(map);              
    
       pod.addTo(map);
       
       var theMarker = {};
       
       map.on('click', function (e) {
           lat = e.latlng.lat;
           lon = e.latlng.lng;
           if (theMarker != undefined) {
               map.removeLayer(theMarker);
           };
           theMarker = L.marker([lat, lon]).addTo(map);
       });
       
      
       
       var LeafIcon = L.Icon.extend({
           options: {
               iconSize: [30, 35],
               iconAnchor: [1, 30],
               shadowAnchor: [4, 62],
               popupAnchor: [-3, -76]
           }
       });
       

       
       var  groupedOverlays = { };
       var  sidebar = L.control.sidebar('sidebar').addTo(map);
            sidebar.open('filtersTab');
       
     
       

     

       
       map.on('click', function (e) {
           $('#latInput').val(e.latlng.lat);
           $('#lngInput').val(e.latlng.lng);
            $scope.form.lat=e.latlng.lat;
            $scope.form.lng=e.latlng.lng;
       });
       
       L.control.scale({
           position: 'bottomright',
           maxWidth: 150,
           metric: true

       }).addTo(map);       
       L.Control.geocoder().addTo(map);
       var tisk = L.control.browserPrint({
        position: 'topright'
    }).addTo(map);

    
    L.Control.geocoder().addTo(map);
       $scope.setUpTimeRangeSlider(map);
       $scope.reDrawMarkers(mapData);
   }

   $scope.setUpTimeRangeSlider=function(){
       var nonLinearSlider = document.getElementById('timeRange');
       noUiSlider.create(nonLinearSlider, {
           connect: true,
           behaviour: 'tap',
           start: [0,24],
           step:1,
           tooltips:true,
           range: {
               'min': [0],
               'max': [24]
           },
           pips: {
               mode: 'values',
               values: [1,4,8,12,16,20,24],
               density: 1,
               format:wNumb({
                   decimals: 2,
                   mark:":"
               })
           },
          
       });
      
       nonLinearSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {
           $scope.filters['time_start_hour']=parseInt(values[0]);
           $scope.filters['time_finish_hour']=parseInt(values[1]);
           $scope.filterData();
       
          map.eachLayer(function(layer){
               if(layer && layer.hasOwnProperty('feature') && parseInt(layer.feature.properties.time_start_hour) >= parseInt(values[0]) && parseInt(layer.feature.properties.time_finish_hour) <= parseInt(values[0])){
                   layer.feature.properties.isVisible=false;
               }
          });
       });
   }

   $scope.init=function(){
    $scope.setSuburbs();
    $scope.setLgs();
    $scope.setDates();
    $scope.filterData();
   }
   
   $scope.init();
   /*-----------------------FORM -----------------------*/

    $scope.errors=[];
    $scope.processing=false;
    $scope.submitForm=function(){
        $scope.processing=true;
        $scope.errors=[];
        var formData = Object.assign({},$scope.form);
        if(formData.hasOwnProperty('start_time') && typeof formData.start_time =='object'){
            formData.start_time = formData.start_time.getHours().toString()+":"+formData.start_time.getMinutes().toString();
            formData.end_time = formData.end_time.getHours().toString()+":"+formData.end_time.getMinutes().toString();
        }
        if( typeof formData.location == "object"){
            formData.location="";
        }
        $http.post("/api/warnings",formData).then((res)=>{

            $scope.processing=false;
            $scope.form={};
            $scope.filterData();

            alert("Thank you. The form is submitted successfully!");
            
        },(res)=>{
            console.log('ERROR',res);
            $scope.processing=false;
            if(res.data.hasOwnProperty('errors')){
                $scope.errors = $scope.formatErrors(res.data.errors);
            }

           
        });
    }

    $scope.formatErrors=function(errors){
        let formattedErrors=[];
        for( let e in errors){
            for( let error of errors[e]){
              formattedErrors.push(error);
            }
        }
  
        return formattedErrors;
    }

    $scope.getAddressByType=function(location,type,shortName=false){
        for(let l of location.address_components){
            for(let t of l.types){
                if(t == type){
                    if(!shortName){
                        return l.long_name;
                    }else{
                        return l.short_name;
                    }
                  
                }
            }
        }
        return '';
    }
   
    $scope.$on('gmPlacesAutocomplete::placeChanged', function(){
        var location = $scope.form.location.getPlace();
       
        $scope.form.address     =   $scope.getAddressByType(location,'street_number') + " " + $scope.getAddressByType(location,'route');
        $scope.form.suburb      =   $scope.getAddressByType(location,'locality')
        $scope.form.state       =   $scope.getAddressByType(location,'administrative_area_level_1',true)
        var geo=location.geometry.location;
        $scope.lat = geo.lat();
        $scope.lng = geo.lng();
        if(location.hasOwnProperty('business_status')){
            $scope.form.location = location.name;
        }else{
            $scope.form.location = "";
        }
        $scope.$apply();
    });
    $scope.dateSelector={opened:false};
    $scope.dateOptions = {
        dateDisabled: false,
        formatYear: 'yy',
        maxDate: new Date(2020, 5, 22),
        minDate: new Date(),
        startingDay: 1
      };
    $scope.altInputFormats = ['yyyy/mm/dd'];
    $scope.open = function() {
        $scope.dateSelector.opened = true;
        console.log('OPEN',$scope.dateSelector.opened);
      };
    $scope.switchView=function(type){
        $scope.viewType = type;
        map.eachLayer(function(layer){
            console.log('TYPE',type);
            console.log('layer',layer.options);
            if(layer && layer.hasOwnProperty('options') && layer.options.hasOwnProperty('ident') && layer.options.ident == 'pod' ){
                if(type == 'mapycz'){
              
                    map.removeLayer(layer);
                    map.addLayer(mapycz);
                }
            }
            if(layer && layer.hasOwnProperty('options') && layer.options.hasOwnProperty('ident') && layer.options.ident == 'mapycz'  ){
                if(type == 'pod'){
                    map.removeLayer(layer);
                    map.addLayer(pod);
                }
            }
       });
        if(type == 'table'){
            $("#map").fadeOut();
        }else{
            $("#map").fadeIn();
        }
    }
    
});
