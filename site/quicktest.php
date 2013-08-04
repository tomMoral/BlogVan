<?php
//load the first 6 pictures and the other assyncronously
include_once("headerPHP.php");
htmlHeader("photo");
photo::updatePosition();
$photos = array();
$db = database::connect();
$perm = $user->type;
$query = $db->prepare("SELECT `icon`, `latitude`, `longitude`, `medium`, `original`, `id` FROM `photos` WHERE `permission`<=$perm AND `latitude`IS NOT NULL");
$query->execute();
while ($photo = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $photo['id'];
    $temp['original'] = $photo['original'];
    $temp['medium'] = $photo['medium'];
    $temp['icon'] = $photo['icon'];
    $temp['lat'] = $photo['latitude'];
    $temp['lon'] = $photo['longitude'];
    $photos[$photo['id']] = $temp;
}
$query->closeCursor();

$positions = array();
$query = $db->prepare("SELECT `id`, `latitude`, `longitude`, `time` FROM `position`;");
$query->execute();
while ($position = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $position['id'];
    $temp['lat'] = $position['latitude'];
    $temp['lon'] = $position['longitude'];
    $temp['time'] = $position['time'];
    $positions[$position['id']] = $temp;
}

$query = $db->prepare("SELECT `id`, `latitude`, `longitude`, `time` FROM `position` WHERE `time` in
    (SELECT MAX(`time`) FROM `position`);");
$query->execute();
$last_position = $query->fetch(PDO::FETCH_ASSOC);
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
    var positions = <?php echo json_encode($positions); ?>;
    var lastPosition = <?php echo json_encode($last_position); ?>;
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


//map dimentions
    var height = 500;
    var width = 900;

    function initialize() {
        var myLatLng = new google.maps.LatLng(38, -100);
        //define the default map
        var mapOptions = {
            zoom: 3,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        var canvasProjectionOverlay = new CanvasProjectionOverlay();
        canvasProjectionOverlay.setMap(map);

        //define coordinates of the points of the path
        var flightPlanCoordinates = [
        ];

        for (var position in positions) {
            flightPlanCoordinates.push(new google.maps.LatLng(positions[position]['lat'], positions[position]['lon']));
        }

        //define the points of the path
        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });
        //plot strait lines
        flightPath.setMap(map);

        //add a van at the end
        var imageVan = 'images/van_for_map.png';
        var myLatLngVan = new google.maps.LatLng(lastPosition['latitude'], lastPosition['longitude']);
        var markerVan = new google.maps.Marker({
            position: myLatLngVan,
            map: map,
            icon: imageVan
        });
        markerVan.setMap(map);

        //if the map change or is loaded, we change the photos position
        google.maps.event.addListener(map, 'bounds_changed', function() { // screenCoords is a GPoint object7
            for (var photo in photos) {
                var point = canvasProjectionOverlay.getProjection().fromLatLngToContainerPixel(new google.maps.LatLng(photos[photo]['lat'], photos[photo]['lon']));
                var pic = document.getElementById(photos[photo]['id']);
                if (point.x > 0 && point.x < width && point.y > 0 && point.y < height) {
                    pic.style.display = 'block';
                    var str = pic.height;
                    pic.style.top = point.y - parseFloat(str) + "px";
                    pic.style.left = point.x + "px";
                } else {
                    pic.style.display = 'none';
                }
            }
        });


        //the following limits the map zoom and position (there is problem when the map shows several time the world to display the photos
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


<div style="position:relative; width: 900px; height: 500px" id="container"><div id="map-canvas"></div>
    <?php
    foreach ($photos as $photo) {
        echo'<img src="' . $photo['icon'] . '" id="' . $photo['id'] . '" style="position:absolute; top:-10000px; left:-10000px; width:20px"/>';
    }
    ?>
</div>
<script>


    document.getElementById("map-canvas").style.height = height + "px";
    document.getElementById("map-canvas").style.width = width + "px";
    $(document).ready(function() {
        //enlarge photos on mouse over
        var enlarged = -1;
        $("img").mouseover(function(event) {
            var id = event.target.id;
            if (id !== enlarged) {
                makeSmaller(enlarged);
                enlarge(id);
            }
        });
        $("img").mouseout(function() {
            if (enlarged !== -1) {
                makeSmaller(enlarged);
                enlarged = -1;
            }
        });

        //show them in full sreen when click
        $("img").click(function(event) {
            var id = event.target.id;
            $("#bloc_page").append("<img src = 'images/black.jpg' style='position:fixed; top: 0px; left: 0px; width: 4000px; height: 5000px; z-index:999; opacity:0.5' id='black'/>");
            $("#bloc_page").append("<img src = '" + photos[id]['medium'] + "' style='position: absolute; top: 200px; left: 185px; z-index:1000' id='big_photo'/>");
            $("#big_photo").click(function() {
                $("#big_photo").remove();
                $("#black").remove();
            });
            $("#black").click(function() {
                $("#big_photo").remove();
                $("#black").remove();
            });
        });

        function enlarge(id) {
            enlarged = id;
            var pic = document.getElementById(id);

            var previousHeight = parseFloat(pic.height);
            var newHeight = 50;
            pic.style.height = newHeight + "px";
            pic.style.width = parseFloat(pic.width)*newHeight/previousHeight;

         /*   var str = pic.style.top;
            var previousTop = parseFloat(str.substr(0, str.length - 2));
            
            pic.style.top = (previousTop - 10 + previousHeight - newHeight) + "px";*/
        }

        function makeSmaller(id) {
            var pic = document.getElementById(id);
            if (pic !== null) {
                var str = pic.style.top;
                var previousTop = parseFloat(str.substr(0, str.length - 2));
                str = pic.height;
                var previousHeight = parseFloat(str);
                pic.style.height = 20 + "px";
                str = pic.height;
                var newHeight = parseFloat(str);
                 pic.style.width = parseFloat(pic.width)*newHeight/previousHeight;
             //   pic.style.top = (previousTop + 20 + previousHeight - newHeight) + "px";
            }
        }
    });
</script>
<?php
include_once("footer.php");
?>
