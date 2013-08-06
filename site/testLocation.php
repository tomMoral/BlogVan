<?php
include("headerPHP.php");
htmlHeader("travel");

//get the GPS positions
$positions = array(); $query = $db->prepare('CREATE TABLE IF NOT EXISTS `position` (
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `time` bigint NOT NULL,
  `precision` float NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;'
        );
        $query->execute();
$query = $db->prepare("SELECT `id`, `latitude`, `longitude`, `time` FROM `position`
                       ORDER BY `time` ASC;");
$query->execute();
while ($position = $query->fetch(PDO::FETCH_ASSOC)) {
    $temp = array();
    $temp['id'] = $position['id'];
    $temp['lat'] = $position['latitude'];
    $temp['lon'] = $position['longitude'];
    $temp['time'] = date("Y-m-d H:i:s",$position['time']/1000);
    $positions[$position['id']] = $temp;
}


?>
