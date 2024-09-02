<?php

include("./config.php");

date_default_timezone_set("Asia/Kolkata");

$currentTime = date("H:i");

$currentDate = date('Y-m-d');
// echo$currentTime."-------".$currentDate;

$sql = "SELECT audio FROM `taskmanager` WHERE  timing = '$currentTime' AND startdate = '$currentDate'"; //timing = '$currentTime' AND

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $events[] = $row;

    }

    echo json_encode($events);

}

mysqli_close($conn);

?>