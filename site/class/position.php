<?php

class position {

    public $id;
    public $latitude;
    public $longitude;
    public $time;
    public $precision;

    public static function create($lat, $lon, $time, $precision = 0) {

        $id = position::nextId();

        $db = database::connect();
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
        $query = $db->prepare("INSERT INTO `position` (`id`, `latitude`, `longitude`,`time`,`precision`) VALUES(?,?,?,?,?) ");
        $query->execute(array($id, $lat, $lon, $time, $precision));
    }

    private static function nextId() {
        $query = "SELECT Max(`id`) as M FROM `position`;";
        $dbh = Database::connect();
        $sth = $dbh->prepare($query);
        $sth->execute();
        $courant = $sth->fetch(PDO::FETCH_ASSOC);
        $id = $courant['M'] + 1;
        return $id;
    }

    public static function lastPositionBefore($time) {
        $db = database::connect();
        $query = $db->prepare("SELECT * FROM `position` WHERE `time` <=\"$time\" ORDER BY `time` DESC;");
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'position');
        $query->execute();
        $position = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        $dbh = null;
        return $position;
    }

    public static function firstPositionAfter($time) {
        $db = database::connect();
        $query = $db->prepare("SELECT * FROM `position` WHERE `time` >\"$time\" ORDER BY `time` ASC;");
        $query->execute();
        $position = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        $dbh = null;
        return $position;
    }

}

?>
