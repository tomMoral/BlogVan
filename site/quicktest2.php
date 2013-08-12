<?php
include("headerPHP.php");
htmlHeader("travel");
if(isset($_GET['delete'])){
    position::remove($_GET['delete']);
}
//position::create(rand(0, 100), rand(0, 100), date("Y-m-d H:i:s", rand(0, 400000000000)));
$db = database::connect();
$id_limit = 494;
//get the GPS positions
$positions1 = array();
$positions2 = array();
$query = $db->prepare('CREATE TABLE IF NOT EXISTS `position` (
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `time` datetime NOT NULL,
  `precision` float NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;'
);
$query->execute();
$query = $db->prepare("SELECT `id`, `latitude`, `longitude`, `time` FROM `position` WHERE `precision`< 200 AND `id`<$id_limit
    ORDER BY `time` ASC;");
$query->execute();
while ($position = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $position['id'];
    $temp['latitude'] = $position['latitude'];
    $temp['longitude'] = $position['longitude'];
    $temp['time'] = $position['time'];
    $positions1[$position['id']] = $temp;
}
$query = $db->prepare("SELECT `id`, `latitude`, `longitude`, `time` FROM `position` WHERE `precision`< 200 AND `id`>=$id_limit
    ORDER BY `time` ASC;");
$query->execute();
while ($position = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $position['id'];
    $temp['latitude'] = $position['latitude'];
    $temp['longitude'] = $position['longitude'];
    $temp['time'] = $position['time'];
    $positions2[$position['id']] = $temp;
}


$query = $db->prepare("SELECT MAX(`latitude`) AS malat, MAX(`longitude`) AS malon, MIN(`latitude`) AS milat, MIN(`longitude`) AS milon FROM `position` WHERE `precision`< 200;");
$query->execute();
$extrem = $query->fetch(PDO::FETCH_ASSOC);
print_r($extrem);
print_r($_POST);
?><form action="quicktest2.php" method="post" enctype="multipart/form-data" id="np">
    <input type="text" name="minLat"  placeholder="minLat" value="<?php echo isset($_POST['minLat']) && $_POST['minLat']!=""  ? $_POST['minLat']: $extrem['milat'];?>"></br></br>
    <input type="text" name="minLon"  placeholder="minLon" value="<?php echo isset($_POST['minLon']) && $_POST['minLon']!="" ? $_POST['minLon']:  $extrem['milon'];?>"></br></br>
    <input type="text" name="maxLat"  placeholder="maxLat" value="<?php echo isset($_POST['maxLat']) && $_POST['maxLat']!="" ? $_POST['maxLat']:  $extrem['malat'];?>"></br></br>
    <input type="text" name="maxLon"  placeholder="maxLon" value="<?php echo isset($_POST['maxLon']) && $_POST['maxLon']!="" ? $_POST['maxLon']:  $extrem['malon'];?>"></br></br>
    <input type="submit"></br></br>
</form>
<div id="time"></div>
<div id="container" style="width: 700px; height:400px; position: relative"></div>

<script>

    var positions1 = <?php echo json_encode($positions1); ?>;
    var positions2 = <?php echo json_encode($positions2); ?>;
    var minLat = <?php echo isset($_POST['minLat']) && $_POST['minLat']!=""  ? $_POST['minLat']: $extrem['milat']; ?>;
    var minLon = <?php echo isset($_POST['minLon']) && $_POST['minLon']!="" ? $_POST['minLon']:  $extrem['milon']; ?>;
    var maxLat = <?php echo isset($_POST['maxLat']) && $_POST['maxLat']!="" ? $_POST['maxLat']:  $extrem['malat']; ?>;
    var maxLon = <?php echo isset($_POST['maxLon']) && $_POST['maxLon']!="" ? $_POST['maxLon']:  $extrem['malon']; ?>;
    var width = 700;
    var height = 400;
    for (var position in positions1) {
        var a = positions1[position];
        if (a['latitude'] <= maxLat && a['latitude'] >= minLat && a['longitude'] <= maxLon && a['longitude'] >= minLon) {
            $("#container").append("<img src='images/face_1_small.png' style='position: absolute; top:" + (height * (positions1[position]['latitude'] - minLat) / (maxLat - minLat)) + "px; left:" + (width * (positions1[position]['longitude'] - minLon) / (maxLon - minLon)) + "px;' id='" + positions1[position]['id'] + "'/>");
        }
    }
    for (var position in positions2) {
        var a = positions2[position];
        if (a['latitude'] <= maxLat && a['latitude'] >= minLat && a['longitude'] <= maxLon && a['longitude'] >= minLon) {
            $("#container").append("<img src='images/face_2_small.png' style='position: absolute; top:" + (height * (positions2[position]['latitude'] - minLat) / (maxLat - minLat)) + "px; left:" + (width * (positions2[position]['longitude'] - minLon) / (maxLon - minLon)) + "px;' id='" + positions2[position]['id'] + "'/>");
        }
    }
    $(document).ready(function() {
        $("img").mouseover(function(event) {
            var id = event.target.id;
            var src = event.target.src;
            var cluster = src.substr(src.length - 11, 1);
            $("#time").html(cluster === "1" ? positions1[id]['time'] +"   cluster1<br/>Lat : " + positions1[id]['latitude'] +"<br/>Lon: " + positions1[id]['longitude'] :positions2[id]['time']+"   cluster2<br/>Lat : " + positions2[id]['latitude'] +"<br/>Lon: " + positions2[id]['longitude'] );
        });
        $("img").click(function(event) {
            var id = event.target.id;
            window.location = "quicktest2.php?delete="+id;
        });
    });
</script>
<?php
include_once("footer.php");
?>
