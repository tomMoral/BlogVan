<?php

include("class/resize.php");
$A = scandir("toresize");
for ($i = 0; $i < count($A); $i++) {
    if (substr($A[$i], 0, 1) != ".") {
        $image2 = new SimpleImage();
        $image2->load("toresize/" . $A[$i]);
        $image2->resizeToWidth(1060);
        $exif = exif_read_data("toresize/" . $A[$i]);
        $next_name = isset($exif['DateTimeOriginal']) ? "resized/" . $exif['DateTimeOriginal']."date".$A[$i] : "resized/" . $A[$i];
        $image2->save($next_name);
        unlink("toresize/" . $A[$i]);
    }
}
?>
