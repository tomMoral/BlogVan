<?php
include("headerPHP.php");
$user = user::getSessionUser();
if (isset($user) && $user->type == 2) {
    if (isset($_FILES["file"]) && isset($_FILES["file"]['tmp_name'])) {
        $file = file($_FILES["file"]['tmp_name']);
        foreach ($file as $line_number => $line) {
            $tab = explode(",", $line);
            print_r($tab);
            $time = 0;//date("Y-m-d H:i:s",$tab[0]);
            $lat = $tab[1];
            $lon = $tab[2];
            $accuracy = $tab[3];
            position::create($lat, $lon, $time, $accuracy);
            echo $line . "<br/>";
        }
    }
    echo date();
    ?>
    <form action="uploadCSVFile.php" method="post" enctype="multipart/form-data" id="np">    
        file :<input type="file" name="file"/>
        <input type="submit"/>
    </form>

<?php
} else {
    header('Location: index.php');
    die;
}?>