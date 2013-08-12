<?php

class photo {

    public $id;
    public $original;
    public $medium;
    public $icon;
    public $permission;
    public $time;
    public $latitude;
    public $longitude;

    public static function add($temp_file, $name, $permission) {
        $dossier = $permission == 1 ? 'pics_up/A/' : 'pics_up/B/';
        $pic = photo::getByOriginalName($dossier . $name);
        if ($pic == null) {
            $temp = explode(".", $name);
            $extension = $temp[count($temp) - 1];
            $time = 0;
            $lon = NULL;
            $lat = NULL;
            if ($extension != "gif") {
                $main_name = substr($name, 0, strlen($name) - 1 - strlen($extension));

                $image0 = new SimpleImage();
                $image0->load($temp_file);
                $image0->save($dossier . $name);

                $image1 = new SimpleImage();
                $image1->load($temp_file);
                $image1->resizeToWidth(530);
                $image1->save($dossier . $main_name . "Medium." . $extension);

                $image2 = new SimpleImage();
                $image2->load($temp_file);
                $image2->resizeToWidth(50);
                $image2->save($dossier . $main_name . "Icon." . $extension);

                $exif = exif_read_data($temp_file);
                if (isset($exif['DateTimeOriginal'])) {
                    $time = $exif['DateTimeOriginal'];
                } else if (strpos($name, "date")) {
                    $ex = explode("date", $name);
                    $time = $ex[0];
                }
                $lon = isset($exif["GPSLongitude"]) && isset($exif['GPSLongitudeRef']) ? photo::getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']) : NULL;
                $lat = isset($exif["GPSLatitude"]) && isset($exif['GPSLatitudeRef']) ? photo::getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']) : NULL;
            } else {
                move_uploaded_file($temp_file, $dossier . $name);
            }
            $id = photo::nextId();
            $db = database::connect();
            $query = $db->prepare('CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `medium` text NOT NULL,
  `icon` text NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `permission` int(8) NOT NULL,
  `original` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
'
            );
            $query->execute();

            $query = $db->prepare("INSERT INTO `photos` (`id`, `original`, `medium`,`icon`,`permission`, `time`, `latitude`, `longitude`) VALUES(?,?,?,?,?,?,?,?) ");
            if ($extension != "gif") {
                $query->execute(array($id, $dossier . $name, $dossier . $main_name . "Medium." . $extension, $dossier . $main_name . "Icon." . $extension, $permission == 1 ? 0 : 1, $time, $lat, $lon));
            } else {
                $query->execute(array($id, $dossier . $name, $dossier . $name, $dossier . $name, $permission == 1 ? 0 : 1, $time, $lat, $lon));
            }
            if (file_exists($temp_file)) {
                unlink($temp_file);
            }
        }
    }

    private static function nextId() {
        $query = "SELECT Max(`id`) as M FROM `photos`;";
        $dbh = Database::connect();
        $sth = $dbh->prepare($query);
        $sth->execute();
        $courant = $sth->fetch(PDO::FETCH_ASSOC);
        $id = $courant['M'] + 1;
        return $id;
    }

    private static function getGps($exifCoord, $hemi) {

        $degrees = count($exifCoord) > 0 ? photo::gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? photo::gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? photo::gps2Num($exifCoord[2]) : 0;

        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    private static function gps2Num($coordPart) {

        $parts = explode('/', $coordPart);

        if (count($parts) <= 0)
            return 0;

        if (count($parts) == 1)
            return $parts[0];

        return floatval($parts[0]) / floatval($parts[1]);
    }

    public static function remove($id) {
        $db = database::connect();
        $query = $db->prepare("SELECT * FROM `photos` WHERE `id` = $id;");
        $query->execute();
        $photo = $query->fetch(PDO::FETCH_ASSOC);
        if ($photo) {
            $query = $db->prepare("DELETE FROM `photos` WHERE `id` = $id;");
            $query->execute();
            if (file_exists($photo['original'])) {
                unlink($photo['original']);
            }
            if (file_exists($photo['medium'])) {
                unlink($photo['medium']);
            }
            if (file_exists($photo['icon'])) {
                unlink($photo['icon']);
            }
        }
    }

    public static function updatePosition() {
        $db = database::connect();
        $query = $db->prepare('CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `medium` text NOT NULL,
  `icon` text NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `permission` int(8) NOT NULL,
  `original` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
'
        );
        $query->execute();
        $query = $db->prepare("SELECT * FROM `photos` WHERE `time` IS NOT NULL AND (`latitude` IS NULL OR `longitude` IS NULL);");
        $query->execute();
        while ($photo = $query->fetch(PDO::FETCH_ASSOC)) {
            $pos1 = position::lastPositionBefore($photo['time']);
            $pos2 = position::firstPositionAfter($photo['time']);
            $id = $photo['id'];
            print_r($pos1);
            print_r($pos2);
            if ($pos1 != null && $pos2 != null) {
                $lat = ((strtotime($photo['time']) - strtotime($pos1['time'])) * $pos1['latitude'] + (strtotime($pos2['time']) - strtotime($photo['time'])) * $pos2['latitude']) / (strtotime($pos2['time']) - strtotime($pos1['time']));
                $lon = ((strtotime($photo['time']) - strtotime($pos1['time'])) * $pos1['longitude'] + (strtotime($pos2['time']) - strtotime($photo['time'])) * $pos2['longitude']) / (strtotime($pos2['time']) - strtotime($pos1['time']));
                $query2 = $db->prepare("UPDATE `photos` SET `latitude` =$lat , `longitude`=$lon WHERE `id`=$id;");
                $query2->execute();
            }
        }
    }

    public static function getByOriginalName($name) {
        $dbh = Database::connect();
        $query = "SELECT * FROM `photos` WHERE `original` = \"$name\"";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'photos');
        $sth->execute();
        $photo = $sth->fetch();
        $sth->closeCursor();
        $dbh = null;
        return $photo;
    }

}

?>
