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

    if (isset($_POST['timing']) && isset($_POST['title'])) {
        //echo json_encode($_POST);
        
        $notallowed = $_POST['notalloweddate'];  //not Applicable Date for date no need Insert ex[06/11/2024,08/23/2024]
        
        $timing = $_POST['timing'];          //get time

        $title = $_POST['title'];            //schedule message or title 

        $startdate = $_POST['start'];       //get start date

        $enddate = $_POST['end'];            //get end date

        $colour = $_POST['colour'];         //get colur 

        $audioFile = $_FILES['audioFile'];     //single audio from add message 

        $day_not_allowed = $_POST['days'];      // get not applicable day like [sun,sat,mon]

        $saturday_check = json_decode($_POST['sat_day'], true);    //five saturday 1 mean 1 saturday and 2 mean 2 saturday and 3 mean 4 saturday 

        $start_date = new DateTime($startdate); //-- set start date datetime module for find inbetween date 

        $end_date = new DateTime($enddate); //-- set end date date datetime module for find inbetween date 
        
        $success_count = 0; // check if sucessfull insert data or Not 
        
        $date_not_check = (empty($notallowed)) ? "" : array_map('trim',explode(",", $notallowed)); //value change into array 

        if(!empty($notallowed)){     //if not date is check is not emtpy 

            
            $formatted_check_dates = array_map(function($date) {
                return DateTime::createFromFormat('m/d/Y', $date)->format('Y-m-d');
            }, $date_not_check); //change day-not-check array into vaild date format 
        
        }
        else{
            $formatted_check_dates=array(); //else change into emtpy array
        }
        if(!empty($day_not_allowed)){

            $day_check_allowed =  array_map('trim',explode(",", $day_not_allowed));
        }
        else{
            $day_check_allowed = array();
        }
        $dates = [];

        $count_insert = 0;

        $message = "";
        
        $owed = 0;

        $sat_count = 0;

        //------------------------------------------------------------------------------------------

        $targetDirectory = "upload/"; // Specify the directory where you want to store the uploaded audio files

        $targetFile = $targetDirectory . uniqid() . '.' . strtolower(pathinfo($_FILES["audioFile"]["name"], PATHINFO_EXTENSION));

        $filename = $_FILES["audioFile"]["name"];

        $uploadOk = 1;

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        //------------------------------------------------------------------------------------------

        $sql = "SELECT MAX(event_id) AS id FROM taskmanager"; // Combines timestamp and 16-character random string

        $result = mysqli_query($conn, $sql);

        if ($result) {

            $row = mysqli_fetch_assoc($result);

            $event_id = $row['id'];                                   //-- fetch event id from database and create new id for new event.

            $event_id++;
        
        } else {

            $event_id = 1;
        }
        //---------------------------------------------------------------------------

        if ($fileType != "mp3" && $fileType != "wav" && $fileType != "ogg") {

            $message = "Sorry, only MP3, WAV, and OGG audio files are allowed. ";

            $uploadOk = 0;
        } else {

            if (move_uploaded_file($_FILES["audioFile"]["tmp_name"], $targetFile)) {

                $message = "The audio file " . basename($_FILES["audioFile"]["name"]) . " has been uploaded ";

                //echo "The audio file " . basename($_FILES["audioFile"]['tmp_name']);
                $uploadOk = 1;

            } else {

                $uploadOk = 0;

                $message = "Sorry, there was an error uploading your audio file. ";
            }
        }

        while ($start_date < $end_date) {
            $day_of_month = (int)$start_date->format('d');  //get date from like 11,01,12,14
            $day_of_week = (int)$start_date->format('w');    //get week like saturday or not saturday number is 6
            $day_abbr = substr($start_date->format('l'),0,3); //get first three char from start date like Mon and Sun Tue
        
            if ($day_of_month == 1) {                      //if new month reset the into saturday count is 0
                $sat_count = 0; 
            }
        
            if ($day_of_week == 6) {                       //if saturday match with day range and check 1 sat or 2 sat like in $saturday_check array
                $sat_count++;
                if (in_array($sat_count, $saturday_check)) {     //if match 1 sat or 2 sat or 3 sat - move to next date
                    $start_date->add(new DateInterval('P1D'));          //move to next date
                    continue;  
                }
            }
        
            if (!in_array($day_abbr, $day_check_allowed)) { // check for day like Sun or Sat and Mon . if not in day check allowed
                $dates[] = $start_date->format('Y-m-d');        //add into array
            }
        
            $start_date->add(new DateInterval('P1D'));    //move into next date
        }
        
        $dates_count = count($dates); //get count from $dates count for check data is insert are not

        if ($dates_count >= 2) {


            foreach ($dates as $date) {

                $dateinsert = $date . PHP_EOL;         //date create only date like 11/06/2000

                if (!in_array($date,$formatted_check_dates) && $uploadOk == 1) {

                    $sql = "INSERT INTO `taskmanager`( `event_id`,`message`, `startdate`, `enddate`, `notallowed`, `timing`, `colour`, `days`,`audio`,`audioname`) VALUES ('$event_id','$title','$dateinsert','$enddate','$notallowed','$timing','$colour','$day_not_allowed','$targetFile','$filename')";

                    if (mysqli_query($conn, $sql)) {

                        ++$count_insert;
                    } else {

                        echo "Something Went Wrong. Please Try again on Date";
                        return;
                    }
                }
            }
        } else {

            $notallowed = "";

            $sql = "INSERT INTO `taskmanager`( `event_id`,`message`, `startdate`, `enddate`, `notallowed`, `timing`, `colour`, `days`,`audio`,`audioname`) VALUES ('$event_id','$title','$startdate','$enddate','$notallowed','$timing','$colour','$day_not_allowed','$targetFile','$filename')";

            if (mysqli_query($conn, $sql)) {

                $count_insert = 1;

                $uploadOk = 1;

                echo $message . " and Successfull";
                unset($dates,$formatted_check_dates);

                mysqli_close($conn);

                return;
            } else {

                echo "Something Went Wrong. Please Try again";
                unset($dates,$formatted_check_dates);
                return;
            }
        }
        mysqli_close($conn);
        if($uploadOk){
            echo "New Schedule created successfully ";
            unset($dates,$formatted_check_dates);
        }
       
    } else {

        echo "Please try again later.";
        unset($dates,$formatted_check_dates);
    }
}

?>