

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Simple Polylines</title>
        <style>
            html, body, #map-canvas {
                margin: 0;
                padding: 0;
                height: 100%;
            }
        </style>
        <link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script>

            var height = 500;
            var width = 900;
            var lat1 = 0;
            var lat2 = 0;
            var lon1 = 0;
            var lon2 = 0;
            function initialize() {
                var myLatLng = new google.maps.LatLng(38, -100);
                var mapOptions = {
                    zoom: 3,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.TERRAIN
                };

                var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                var flightPlanCoordinates = [
                    new google.maps.LatLng(45.772323, -122.214897),
                    new google.maps.LatLng(42.291982, -100),
                    new google.maps.LatLng(34.142599, -80),
                    new google.maps.LatLng(29.46758, -56)
                ];

                var flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });
                flightPath.setMap(map);

                var imageVan = 'images/van_for_map.png';
                var myLatLngVan = new google.maps.LatLng(29.46758, -56);
                var markerVan = new google.maps.Marker({
                    position: myLatLngVan,
                    map: map,
                    icon: imageVan
                });
                markerVan.setMap(map);

                var image = 'images/dealIcon.JPG';
                var myLatLng = new google.maps.LatLng(34.142599, -80);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    icon: image,
                    size: new google.maps.Size(5, 5)
                });
                marker.setMap(map);


                google.maps.event.addListener(marker, 'click', function() {
                    alert("test");
                });


                //the following limits the map zoom and position
                // This is the minimum zoom level that we'll allow
                var minZoomLevel = 2;
                // Bounds for North America
                var strictBounds = new google.maps.LatLngBounds(
                        new google.maps.LatLng(28.70, -127.50),
                        new google.maps.LatLng(48.85, -55.90)
                        );
                // Listen for the dragend event
                google.maps.event.addListener(map, 'dragend', function() {
                    if (strictBounds.contains(map.getCenter()))
                        return;
                    // We're out of bounds - Move the map back within the bounds
                    var c = map.getCenter(),
                            x = c.lng(),
                            y = c.lat(),
                            maxX = strictBounds.getNorthEast().lng(),
                            maxY = strictBounds.getNorthEast().lat(),
                            minX = strictBounds.getSouthWest().lng(),
                            minY = strictBounds.getSouthWest().lat();

                    if (x < minX)
                        x = minX;
                    if (x > maxX)
                        x = maxX;
                    if (y < minY)
                        y = minY;
                    if (y > maxY)
                        y = maxY;

                    map.setCenter(new google.maps.LatLng(y, x));
                });
                // Limit the zoom level
                google.maps.event.addListener(map, 'zoom_changed', function() {
                    if (map.getZoom() < minZoomLevel)
                        map.setZoom(minZoomLevel);
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script> 


    </head>
    <body>
        <div id="map-canvas"></div>
    </body>
    <script>
        document.getElementById("map-canvas").style.height = height + "px";
        document.getElementById("map-canvas").style.width = width + "px";
    </script>
</html>

