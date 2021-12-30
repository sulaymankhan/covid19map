// Create the map
var map=false;
var app =angular.module('covid', [],function ($interpolateProvider) {
    $interpolateProvider.startSymbol('|||');
    $interpolateProvider.endSymbol('|||');
 
});
app.controller('MarkersCtrl',function($scope,$http){
    $scope.filters={};
    $scope.totalCases=0;
    $scope.suburbs=[];
    $scope.setSuburbs=function(){
        $http.get("/api/suburbs").then(function(res){
            $scope.suburbs = res.data;
        });
    }
    $scope.filterData=function(){
        $http.get("/api/warnings",{params:$scope.filters}).then(function(res){
            $scope.totalCases=res.data.features.length;
            if(map == false){
                $scope.drawMap(res.data);
            }else{
                $scope.reDrawMarkers(res.data);
            }
           
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
                        iconSize: [30, 30],
                        iconAnchor: [10, 15],
                    })
                });
            },
            onEachFeature: function (feature, layer) {
                let body="<strong><i class='fa fa-home'></i> Address</strong>:"+feature.properties.address+" "+feature.properties.suburb;
                body=body+"<br><strong><i class='fa fa-calendar'></i> Date</strong>:"+feature.properties.date
                body=body+"<br><strong><i class='fa fa-clock'></i> Time</strong>:"+feature.properties.time;
                layer.bindPopup(body, {
                    className: 'myCSSClass'
                });
            }
        });
    
        map.addLayer(markers);
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
       var pod = L.tileLayer('http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
           maxZoom: 18,
           attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
       });
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
       
       var mapycz = L.tileLayer('http://m{s}.mapserver.mapy.cz/base-m/{z}-{x}-{y}', {
           ident: 'mapycz',
           attribution: '&copy;Seznam.cz a.s., | &copy;OpenStreetMap <a href="http://mapy.cz"><img class="print" target="_blank" src="//api.mapy.cz/img/api/logo.png" style="cursor: pointer; position:relative;top: 5px;"></a>',
           maxZoom: 20,
           subdomains: "1234"
       });
       
       var LeafIcon = L.Icon.extend({
           options: {
               iconSize: [30, 35],
               iconAnchor: [1, 30],
               shadowAnchor: [4, 62],
               popupAnchor: [-3, -76]
           }
       });
       
       var baseMaps = {
           "OSM": pod,
           "<b style=color:red;>M</b><b style=color:black;>APY.CZ": mapycz
       };
       
       var  groupedOverlays = { };
       var  sidebar = L.control.sidebar('sidebar').addTo(map);
            sidebar.open('filtersTab');
       
       var panel = L.control.groupedLayers(baseMaps, groupedOverlays, {
           collapsed: false
       }).addTo(map);
       
       var htmlObject = panel.getContainer();
       var a = document.getElementById('mapTypes')

       function setParent(el, newParent) {
           newParent.appendChild(el);

       }
       setParent(htmlObject, a);
     

       
       map.on('click', function (e) {
           $('#latInput').val(e.latlng.lat);
           $('#lngInput').val(e.latlng.lng);
           updateMarker(e.latlng.lat, e.latlng.lng);
       });
       
       var updateMarkerByInputs = function () {
           return updateMarker($('#latInput').val(), $('#lngInput').val());
       }
       $('#latInput').on('input', updateMarkerByInputs);
       $('#lngInput').on('input', updateMarkerByInputs);
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
           start: [1,24],
           step:1,
           tooltips:true,
           range: {
               'min': [1],
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
    $scope.filterData();
   }
   
   $scope.init();

});



