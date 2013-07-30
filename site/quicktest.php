<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("photo");
$photos = array();
$db = database::connect();
$perm = $user->type;
$query = $db->prepare("SELECT `icon`, `latitude`, `longitude`, `name`, `id` FROM `photos` WHERE `permission`<=$perm AND `latitude`IS NOT NULL");
$query->execute();
while ($photo = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $photo['id'];
    $temp['name'] = $photo['name'];
    $temp['icon'] = $photo['icon'];
    $temp['lat'] = $photo['latitude'];
    $temp['lon'] = $photo['longitude'];
    $photos[$photo['id']] = $temp;
}
?>
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

    var photos = <?php echo json_encode($photos); ?>;
    function CanvasProjectionOverlay() {
    }
    CanvasProjectionOverlay.prototype = new google.maps.OverlayView();
    CanvasProjectionOverlay.prototype.constructor = CanvasProjectionOverlay;
    CanvasProjectionOverlay.prototype.onAdd = function() {
    };
    CanvasProjectionOverlay.prototype.draw = function() {
    };
    CanvasProjectionOverlay.prototype.onRemove = function() {
    };



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
        var canvasProjectionOverlay = new CanvasProjectionOverlay();
        canvasProjectionOverlay.setMap(map);

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


        google.maps.event.addListener(map, 'bounds_changed', function() { // screenCoords is a GPoint object7
            for (var photo in photos) {
                var point = canvasProjectionOverlay.getProjection().fromLatLngToContainerPixel(new google.maps.LatLng(photos[photo]['lat'], photos[photo]['lon']));
                var pic = document.getElementById(photos[photo]['id']);
                if (point.x > 0 && point.x < width && point.y > 0 && point.y < height) {
                    pic.style.display = 'block';
                    pic.style.top = point.y + "px";
                    pic.style.left = point.x + "px";
                } else {
                    pic.style.display = 'none';
                }
            }
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


<div style="position:relative; width: 900px; height: 500px"><div id="map-canvas"></div>
    <?php
    foreach ($photos as $photo) {
        echo'<img src="' . $photo['icon'] . '" id="' . $photo['id'] . '" style="position:absolute; top:-10000px; left:-10000px"/>';
    }
    ?>
</div>
<script>
    document.getElementById("map-canvas").style.height = height + "px";
    document.getElementById("map-canvas").style.width = width + "px";
    $(document).ready(function() {
        $("img").click(function(event) {
            var id = event.target.id;
            $("#bloc_page").append("<img src = 'images/black.jpg' style='position:fixed; top: 0px; left: 0px; width: 4000px; height: 5000px; z-index:999; opacity:0.5' id='black'/>");
            $("#bloc_page").append("<img src = '" + photos[id]['name'] + "' style='position: absolute; top: 200px; left: 185px; z-index:1000' id='big_photo'/>");
            $("#big_photo").click(function() {
                $("#big_photo").remove();
                $("#black").remove();
            });
            $("#black").click(function() {
                $("#big_photo").remove();
                $("#black").remove();
            });
        });
    });
</script>
<?php
include_once("footer.php");
?>
