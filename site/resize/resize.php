<?php
include_once("../class/resize.php");
$test=ini_get_all();
print_r($test['max_file_uploads'] );
print_r($test['max_execution_time'] );
ini_set('max_execution_time', 10000);
$test=ini_get_all();
print_r($test['max_execution_time'] );
$A = scandir("toresize");
for ($i = 0; $i < count($A); $i++) {
    if (substr($A[$i], 0, 1) != ".") {
        $image = new SimpleImage();
        $image->load("toresize/" . $A[$i]);
        $image->resizeToWidth(530);
        $exif = exif_read_data("toresize/" . $A[$i]);
        $next_name = isset($exif['DateTimeOriginal']) ? "resized/" . $exif['DateTimeOriginal']."date".$A[$i] : "resized/" . $A[$i];
        $image->save($next_name);
        unlink("toresize/" . $A[$i]);
    }
}
?>
