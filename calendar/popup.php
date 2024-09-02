<?php
/* Database schema  
id	
message	
startdate	
enddate	
notallowed	
timing	
colour	
*/
include("./config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if(isset($_POST['id'])){
    
        $eventid=$_POST['id'];
    
        $sql = "SELECT `id`,`event_id`,`message`, `startdate`, `enddate`, `notallowed`, `timing`, `colour`,`audioname`,`audio` FROM `taskmanager` WHERE id = $eventid";
        
        $result = mysqli_query($conn, $sql);


        $events = array();


        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                $events[] = $row;

            }


            foreach ($events as $event) {

                $event_id = $event['event_id'];

            }


            $sql = "SELECT MIN(startdate) AS min_startdate FROM taskmanager WHERE event_id = $event_id";

            $result_min_startdate = mysqli_query($conn, $sql);


            if ($result_min_startdate) {

                $min_startdate_row = mysqli_fetch_assoc($result_min_startdate);

                $min_startdate = $min_startdate_row['min_startdate'];

                $newEvent = array('min_startdate' => $min_startdate);

                $events[] = $newEvent;

            }

        }


        echo json_encode($events);

        mysqli_close($conn);

    }

}

else{

    echo "Invaild data";

}

?>

