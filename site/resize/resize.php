<?php
include_once("../class/resize.php");
ini_set('max_execution_time', 1000);
$A = scandir("toresize");
for ($i = 0; $i < count($A); $i++) {
    if (substr($A[$i], 0, 1) != ".") {
        $image = new SimpleImage();
        $image->load("toresize/" . $A[$i]);
        $image->resizeToWidth(1060);
        $exif = exif_read_data("toresize/" . $A[$i]);
        $next_name = isset($exif['DateTimeOriginal']) ? "resized/" . $exif['DateTimeOriginal']."date".$A[$i] : "resized/" . $A[$i];
        $image->save($next_name);
        unlink("toresize/" . $A[$i]);
    }
}
?>
