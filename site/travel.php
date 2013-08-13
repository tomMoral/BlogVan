<?php
include("headerPHP.php");
htmlHeader("travel");

//get the time from the begining of the adventure ;)
$date = strtotime("August 1, 2013 8:00 AM");
$remaining = time() - $date;
$days_remaining = floor(($remaining % 2678400) / 86400);
photo::updatePosition();

//get the photos with GPS position
$photos = array();
$db = database::connect();
$perm = $user->type;
$query = $db->prepare("SELECT `icon`, `latitude`, `longitude`, `medium`, `original`, `id` FROM `photos` 
                       WHERE `permission`<=$perm AND `latitude`IS NOT NULL
                        ORDER BY `time` ASC");
$query->execute();
$i = 0;
while ($photo = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $photo['id'];
    $temp['original'] = $photo['original'];
    $temp['medium'] = $photo['medium'];
    $temp['icon'] = $photo['icon'];
    $temp['lat'] = $photo['latitude'];
    $temp['lon'] = $photo['longitude'];
    $photos[$i] = $temp;
    $i++;
}
$query->closeCursor();

//get the GPS positions
$positions = array();
$query = $db->prepare('CREATE TABLE IF NOT EXISTS `position` (
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `time` datetime NOT NULL,
  `precision` float NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
$query->execute();
$query = $db->prepare("SELECT `latitude`, `longitude` FROM `position` 
                       WHERE `precision`< 200
                       ORDER BY `time` ASC;");
$query->execute();
$i = 0;
while ($position = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['lat'] = $position['latitude'];
    $temp['lon'] = $position['longitude'];
    $positions[$i] = $temp;
    $i += 1;
    $last_position = $position;
}
?>
<style>
    html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
    }
</style>
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
        var myLatLng = new google.maps.LatLng(37.081416, -116.599812);
        //define the default map
        var mapOptions = {
            zoom: 6,
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
        var objectives = [];
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(39.10662, -120.027809),
            map: map,
            title: 'Lac Tahoe',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(36.463263, -117.062531),
            map: map,
            title: 'Death Valley',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(36.134216, -115.095657),
            map: map,
            title: 'Las Vegas',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(36.067001, -112.101088),
            map: map,
            title: 'Gran Canyon',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(38.729782, -109.605153),
            map: map,
            title: 'Arches national Parc',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(37.222759, -112.95585),
            map: map,
            title: 'Zion National Parc',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(34.061257, -118.246397),
            map: map,
            title: 'Los Angeles',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(34.01441, -118.801465),
            map: map,
            title: 'Malibu',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(34.421934, -119.695833),
            map: map,
            title: 'Santa Barbara',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(36.272338, -121.807058),
            map: map,
            title: 'Big Sur',
            icon: "images/cible.png"
        }));
        objectives.push(new google.maps.Marker({
            position: new google.maps.LatLng(37.776378, -122.428621),
            map: map,
            title: 'San Francisco',
            icon: "images/cible.png"
        }));

        for (var obj in objectives) {
            objectives[obj].setMap(map);
        }


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


<div class="center">
    <h1><?php
        echo_trad("The adventure began");
        echo " " . $days_remaining . " ";
        echo_trad("days ago");
        ?>.<br/><?php echo_trad("It's awesome!"); ?>
    </h1>
    <div style="position:relative; width: 900px; height: 500px" id="container"><div id="map-canvas"></div>
        <?php
        foreach ($photos as $photo) {
            echo'<img src="' . $photo['icon'] . '" id="' . $photo['id'] . '" class="map_photo"/>';
        }
        ?>
    </div>
    <script>
        var idDiapo = 0,
                diapoLenght = photos.length,
                diaporamaRunning = false,
                firstDiapo = true;
        function diapoNext() {
            idDiapo = (idDiapo + 1 + diapoLenght) % diapoLenght;
            displayFullScreen(photos[idDiapo]['original']);
            // if (diaporamaRunning)
            //   setTimeout(diapoNext, 2000);
        }
        function diapoPrev() {
            idDiapo = (idDiapo - 1 + diapoLenght) % diapoLenght;
            displayFullScreen(photos[idDiapo]['original']);
            //if (diaporamaRunning)
            //  setTimeout(diapoNext, 2000);
        }

        var hovered = "";
        var loaded = "";
        $(document).ready(function() {
            $(".map_photo").hover(function() {
                var pic = this;
                var id = pic.id;
                if (id !== hovered) {
                    makeSmall();
                    hovered = id;
                    var h = pic.height;
                    var src = pic.src;
                    if (src.indexOf("Icon" != -1)) {
                        // pic.src = src.replace("Icon"
                        //       , "Medium");
                    }
                    var previousH = pic.height;
                    var previousW = pic.width;
                    pic.height = 50;
                    pic.style.width = "auto";
                    $("#" + id).css("top", parseInt($("#" + id).css("top").replace("px", "")) - (pic.height - previousH) / 2 + "px");
                    $("#" + id).css("left", parseInt($("#" + id).css("left").replace("px", "")) - (pic.width - previousW) / 2 + "px");
                    $("#" + id).css("z-index", "20");
                }
            });
            function makeSmall() {
                if (hovered != "") {
                    var pic = $("#" + hovered);
                    var previousH = parseInt(pic.css("height").replace("px", ""));
                    var previousW = parseInt(pic.css("width").replace("px", ""));
                    $("#" + hovered).css("width", "20px");
                    $("#" + hovered).css("height", "auto");
                    $("#" + hovered).css("top", parseInt($("#" + hovered).css("top").replace("px", "")) - (parseInt(pic.css("height").replace("px", "")) - previousH) / 2 + "px");
                    $("#" + hovered).css("left", parseInt($("#" + hovered).css("left").replace("px", "")) - (parseInt(pic.css("width").replace("px", "")) - previousW) / 2 + "px");
                    $("#" + hovered).css("z-index", "1");
                    hovered = "";
                }
            }
            $(".map_photo").mouseout(function() {
                makeSmall();
            });
            $(".map_photo").click(function() {
                var pic = this;
                idDiapo = parseInt(pic.id) - 1;
                displayFullScreen(pic.src);
            });
        });

        function getOffset(el) {
            var _x = 0;
            var _y = 0;
            while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
                _x += el.offsetLeft - el.scrollLeft;
                _y += el.offsetTop - el.scrollTop;
                el = el.offsetParent;
            }
            return {top: _y, left: _x};
        }

        function keyboardHandler(event) {
            switch (event.which) {
                case 37:
                case 39:
                    diapoPrev();
                    break;
                case 38:
                case 40:
                    diapoNext();
                    break;
                case 27:
                    close();
            }

        }

        function displayFullScreen(src) {
            if (firstDiapo) {
                firstDiapo = false;
                $("body").append("<img src='images/right_arrow.png' class='arrow' id='right_arrow'/>");
                $("body").append("<img src='images/left_arrow.png' class='arrow' id='left_arrow'/>");
                $("body").append("<img src='images/cross.png' class='arrow' id='cross'/>");
                $(document).bind('keydown', keyboardHandler);
                $('#cross').click(close);
                $('#left_arrow').click(diapoPrev);
                $('#right_arrow').click(diapoNext);
            }

            src = src.indexOf("Icon") !== -1 ? src.replace("Icon", "") : src;
            $("#full_screen_photo").remove();
            $("#full_screen_background").remove();
            $("body").append("<img src='" + src + "' id='full_screen_photo' onload='resize();'/>");
            $("body").append("<img src='images/black.jpg' id='full_screen_background'/>");
            if (loaded !== "") {
                $("#for_load1").remove();
                $("#for_load2").remove();
            }
            var previous = (idDiapo - 1 + diapoLenght) % diapoLenght;
            $("body").append("<div id='for_load1'><img src='" + photos[previous]['original'] + "'/></div>");
            var next = (idDiapo + 1 + diapoLenght) % diapoLenght;
            $("body").append("<div id='for_load2'><img src='" + photos[next]['original'] + "'/></div>");
            loaded = "1";
        }

        function close() {
            $("#full_screen_photo").remove();
            $("#full_screen_background").remove();
            $("#right_arrow").remove();
            $("#left_arrow").remove();
            $("#cross").remove();
            $("#for_load1").remove();
            $("#for_load2").remove();
            load = "";
            $(document).unbind('keydown', keyboardHandler);
            firstDiapo = true;
        }



        function resize() {
            var windowW =  window.innerWidth;
            var windowH =  window.innerHeight;
            var imgW = windowW - 150;
            var imgH = windowH * 0.9;
            var bigPic = $("#full_screen_photo");
            //set the image size
            var width = parseInt(bigPic.css("width").replace("px", ""));
            var height = parseInt(bigPic.css("height").replace("px", ""));
            if (width > imgW) {
                var ratio = width / height;
                bigPic.css("width", imgW + "px");
                bigPic.css("height", imgW / ratio + "px");
            }
            width = parseInt(bigPic.css("width").replace("px", ""));
            height = parseInt(bigPic.css("height").replace("px", ""));
            if (height > imgH) {
                var ratio = width / height;
                bigPic.css("height", imgH + "px");
                bigPic.css("width", imgH * ratio + "px");
            }
            height = parseInt(bigPic.css("height").replace("px", ""));
            width = parseInt(bigPic.css("width").replace("px", ""));
            //set the image position
            bigPic.css("left", ((windowW - width) / 2) + "px");
            bigPic.css("top", ((windowH - height) / 2) + "px");
            $("#right_arrow").css("right", ((windowW - width) / 2) - 50 + "px");
            $("#left_arrow").css("left", ((windowW - width) / 2) - 50 + "px");
            $("#right_arrow").css("top", (windowH / 2) - 20 + "px");
            $("#left_arrow").css("top", (windowH / 2) - 20 + "px");
        }
    </script>
    <h2><?php echo_trad("On the agenda"); ?>:</h2>
    <ul>
        <li><?php echo_trad("2500 miles of aventure, sweat and laugther"); ?>
        </li>
        <li><?php echo_trad("lot of beers to fight Death Valley heat"); ?>
        </li>
        <li>
            <?php echo_trad("wedding at Vegas"); ?>
        </li>
        <li>
            <?php echo_trad("earplugs to let Thomas sing with the radio"); ?>
        </li>
        <li>
            <?php echo_trad("divorce at Vegas"); ?>
        </li>
        <li>
            <?php echo_trad("a cooler to put Micheaux in when she is too hot and the beers are gone"); ?>
        </li>
        <li>
            <?php echo_trad("a tent to prevent Marine from taking all the space in the van"); ?>
        </li>
        <li>
            <?php echo_trad("bankruptcy at Vegas"); ?>
        </li>
        <li>
            <?php echo_trad("no grimace on Greg's photos"); ?>
        </li>
        <li>
            <?php echo_trad("culturation in museums or lying on the beach"); ?>
        </li>
        <li>
            <?php echo_trad("car breakdown"); ?>
        </li>
        <li>
            <?php echo_trad("waking up Guigui while driving"); ?>
        </li>
    </ul>
    <h2>
        <?php echo_trad("a lot of other surprises and above all"); ?></h2>

    <h1><?php echo_trad("a lot of posts and photos !"); ?></h1>
</div>
<script>
    var $start_id = 500;
    $(document).ready(function() {
        var n = $("ul").children().length;
        for (var i = 0; i < n; i++) {
            $("ul li:nth-child(" + i + ")").css("background", "url(images/face_" + Math.floor(7 * Math.random()) + "_small.png) no-repeat top left");
        }
    });
    
</script>
<?php
include_once("footer.php");
?>
