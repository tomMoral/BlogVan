<?php
include("headerPHP.php");
$user = user::getSessionUser();
if (isset($user) && $user->type == 2) {
    if (isset($_FILES["file"]) && isset($_FILES["file"]['tmp_name'])) {
        $file = file($_FILES["file"]['tmp_name']);
        $offset = 0;
        if(isset($_POST['device']))
            $offset = intval($_POST['device']);
        foreach ($file as $line_number => $line) {
            $tab = explode(",", $line);
            print_r($tab);
            $time = $tab[0] - $offset;
            $lat = $tab[1];
            $lon = $tab[2];
            $accuracy = $tab[3];
            position::create($lat, $lon, $time, $accuracy);
            echo $line . "<br/>";
        }
    }
    ?>
    <form action="uploadCSVFile.php" method="post" enctype="multipart/form-data" id="np">    
        File :<input type="file" name="file"/><br/>
        Device : <input type="checkbox", name="device" value="3060000"><br/>
        <input type="submit"/>
    </form>

<?php
} else {
    header('Location: index.php');
    die;
}?>
