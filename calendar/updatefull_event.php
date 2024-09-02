<?php
include("./config.php");
if (isset($_POST['message']) && isset($_POST['times']) && isset($_POST["event_id"])) {
    //echo json_encode($_POST);
    //echo $_FILES["audioFile"]["name"];
    //     //--------------------------------------------------------------------------

    $event_id = $_POST["event_id"];

    $filename = $_POST['exitpath'];

    $notallowed = $_POST['notallowed'];

    $timing = $_POST['times'];

    $title = $_POST['message'];

    $startdate = $_POST['startdate'];

    $enddate = $_POST['enddate'];

    $colour = $_POST['colour'];

    $exitpath = $_POST['exitpath'];

    $audioname = $_POST['audioname'];

    $audioFile = "";

    $start_date_Def = new DateTime($startdate); //-- set start date 

    $end_date_Def = new DateTime($enddate); //-- set end date 

    $start_date = new DateTime($startdate);

    $end_date = new DateTime($enddate);

    $skip_date = new DateTime((empty($notallowed)) ? "0000-00-00" : $notallowed);

    $count_insert = 0;

    $message = "";

    $data_deleted = 0;

    $check_start_date = 0;

    $check_end_date = 0;

    $isModified = 0;

    $dates_count_ins = 0;

    //----------------------------------------------------------- check the audio is exits or not
    if (!empty($_FILES['audioFile'])) {


        $targetDirectory = "upload/"; // Specify the directory where you want to store the uploaded audio files

        $targetFile = $targetDirectory . uniqid() . '.' . strtolower(pathinfo($_FILES["audioFile"]["name"], PATHINFO_EXTENSION));

        $audioname = $_FILES["audioFile"]["name"];

        $uploadOk = 1;

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $exitpath = $targetFile;                              //--new file is exits

        if ($fileType != "mp3" && $fileType != "wav" && $fileType != "ogg") {

            echo " Sorry, only MP3, WAV, and OGG audio files are allowed. ";

            $uploadOk = 0;

            return;

        } else {

            if (move_uploaded_file($_FILES["audioFile"]["tmp_name"], $targetFile)) {

                $message .= " The audio file " . basename($_FILES["audioFile"]["name"]) . " has been uploaded ";

                //echo "The audio file " . basename($_FILES["audioFile"]['tmp_name']);
            } else {

                $uploadOk = 0;

                $message .= " Sorry, there was an error uploading your audio file. ";
            }
        }
    }

    //-------------------------------------------------------------------

    $sql = "SELECT MIN(startdate) AS min_startdate,MAX(startdate) AS max_startdate FROM taskmanager WHERE event_id = $event_id";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        $row = mysqli_fetch_assoc($result);                         //!!fetch max date and min date from database

        //echo json_encode($row);
        $minDate = $row['min_startdate'];

        $maxDate = $row['max_startdate'];
    }

    //-----------------------------------------------------------

    if ($minDate != $startdate) {                      //*check start date is modified or not

        $message .= " Start Date Modified ";

        if ($startdate > $minDate) {                              //--DELETE old data

            $originalDate = new DateTime($startdate);

            $newDate = $originalDate->modify('-1 day')->format('Y-m-d');

            $sql = "DELETE FROM taskmanager WHERE event_id = $event_id AND startdate BETWEEN '$minDate' AND '$newDate'";

            if (mysqli_query($conn, $sql)) {

                $check_start_date = 0;                  //--check any data is deleted 

                $isModified = 1;
            }
        } else if ($startdate < $minDate) {                          //--INSERT data 

            $tempDate = new DateTime($minDate);

            $dates = [];

            while ($start_date <= $tempDate) {

                $dates[] = $start_date->format('Y-m-d');

                $start_date->add(new DateInterval('P1D'));
            }

            $dates_count = count($dates) - 1;                //--get the date count and delete one date because two repeat 

            for ($i = 0; $i < $dates_count; $i++) {                  //--insert new start date for startdate 
                //echo $dates[$i]  .PHP_EOL."/n";               //$dates as $date
                $dateinsert = $dates[$i] . PHP_EOL;

                if ($dateinsert) {
                    //echo "---".$dateinsert."------".$notallowed."<br>";

                    $sql = "INSERT INTO `taskmanager`( `event_id`,`message`, `startdate`, `enddate`, `notallowed`, `timing`, `colour`,`audio`,`audioname`) VALUES ('$event_id','$title','$dateinsert','$enddate','$notallowed','$timing','$colour','$exitpath',' $audioname')";

                    if (mysqli_query($conn, $sql)) {

                        $count_insert = 1;

                        $isModified = 1;
                    } else {

                        echo "Unable to Insert";
                    }
                }
            }
            unset($dates);
        }
    }
    if ($enddate != $maxDate) {                      //* check the end date is modified or not 

        $message .= " End Date Modified  ";

        if ($enddate > $maxDate) {                              //--insert new data

            $tempDate = new DateTime($maxDate);

            $tempDate = $tempDate->modify('+1 day');

            $dates_end = [];

            while ($tempDate <= $end_date) {

                $dates_end[] = $tempDate->format('Y-m-d');

                $tempDate->add(new DateInterval('P1D'));
            }

            $dates_count = count($dates_end);                //--get the date count and delete one date because two repeat 

            //echo $dates_count;

            for ($i = 0; $i < $dates_count; $i++) {                  //--insert new start date for startdate 
                //echo $dates[$i]  .PHP_EOL."/n";               //$dates as $date
                $dateinsert = $dates_end[$i] . PHP_EOL;

                if ($dateinsert) {
                    //echo "---".$dateinsert."------".$notallowed."<br>";

                    $sql = "INSERT INTO `taskmanager`( `event_id`,`message`, `startdate`, `enddate`, `notallowed`, `timing`, `colour`,`audio`,`audioname`) VALUES ('$event_id','$title','$dateinsert','$enddate','$notallowed','$timing','$colour','$exitpath',' $audioname')";

                    if (mysqli_query($conn, $sql)) {

                        $count_insert = 1;

                        $isModified = 1;
                    
                    } else {

                        echo "Unable to Insert";
                    }
                }
            }

            unset($dates);

        } else if ($enddate < $maxDate) {                          //--delete data from database


            $originalDate = new DateTime($enddate);

            $newDate = $originalDate->modify('+1 day')->format('Y-m-d');

            $sql = "DELETE FROM taskmanager WHERE event_id = $event_id AND startdate BETWEEN '$newDate' AND '$maxDate'";

            if (mysqli_query($conn, $sql)) {

                $check_start_date = 0;

                $data_deleted = 1;                     //--check any data is deleted 

                $isModified = 1;
            }
        }
    }
    //----------------------------------------------------- full update after change or not

    $sql = "UPDATE taskmanager SET message='$title', timing='$timing' , enddate='$enddate', colour='$colour' , audio='$exitpath', audioname='$audioname' WHERE event_id = $event_id";

    if (mysqli_query($conn, $sql)) {

        $isModified = 1;
    
    } else {

        $isModified = 0;
    }

    //-------------------------------------------------
    if ($isModified) {
    
        echo $message . " Sucessfull Data Modified";
    
    } else {
    
        echo " No Data Modified ";
    
    }
}

?>