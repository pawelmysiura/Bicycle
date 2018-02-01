var GoogleMapApi = function() {
    marker1 = null,
        marker2 = null,
        directionsDisplay = null,
        directionsService = null,
        data = {};
    //var mapElement = document.getElementById('map');
    var buttons =  {
        send: $('#send-button')
    };

    this.initMap = function() {
        directionsDisplay = new google.maps.DirectionsRenderer;
        directionsService = new google.maps.DirectionsService;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: {lat: 51.51054, lng: 19.201806},
            gestureHandling: 'greedy'
        });
        directionsDisplay = new google.maps.DirectionsRenderer({
            'draggable': true
        });
        directionsDisplay.setMap(map);

        if (!marker2) {
            map.addListener('click', function (e) {
                var position = e.latLng;
                this.addPoint(map, position, directionsService, directionsDisplay)
            }.bind(this))
        }
    };

    this.addPoint = function(map, position, directionsService, directionsDisplay) {
        if (!marker1) {
            marker1 = new google.maps.Marker({
                position: position,
                map: map,
                title: 'marker1',
                draggable: true
            });
        } else if (!marker2) {
            marker2 = new google.maps.Marker({
                position: position,
                map: map,
                title: 'marker2',
                draggable: null
            });
            this.calculateRoute(directionsService, directionsDisplay);
        }
    };

    this.calculateRoute = function(directionsService, directionsDisplay) {
        directionsService.route({
            origin: marker1.getPosition(),
            destination: marker2.getPosition(),
            optimizeWaypoints: true,
            travelMode: 'BICYCLING'
        }, function (response, status) {
            if (status == 'OK') {
                directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    };

    this.save = function() {
        var waypoint = {},
            wp;
        var rleg = directionsDisplay.directions.routes[0].legs[0];
        data.start = {'lat': rleg.start_location.lat(), 'lng': rleg.start_location.lng()};
        data.end = {'lat': rleg.end_location.lat(), 'lng': rleg.end_location.lng()}
        wp = rleg.via_waypoints;
        for (i = 0; i<wp.length;i++){
            // waypoint[i] = [{'lat': wp[i].lat(), 'lng': wp[i].lng()}]
            waypoint[i] = {'lat': wp[i].lat(), 'lng': wp[i].lng()}
        }
        data.waypoints = waypoint;

        data.end = JSON.stringify(data.end);
        data.start = JSON.stringify(data.start);
        data.waypoints = JSON.stringify(data.waypoints);

        $.ajax({
            type: 'POST',
            url: "{{ path('panel_create_map') }}",
            data: {data: JSON.stringify(data)},
            dataType: 'json'
        });
    };

    this.bindButtons = function() {
        buttons.send.on('click', this.save());
    };

    this.initialize = function() {
        this.initMap();
        this.bindButtons();
    };

    this.initialize();
};

$( document ).ready(function() {
    new GoogleMapApi();
});
