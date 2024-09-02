<?php
/* Database schema  
id 	
event_id	
message	
startdate	
enddate	
notallowed	
timing	
colour	
audio	
audioname	
*/


include("./config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['message']) && isset($_POST['times']) && isset($_POST['id'])) {

        $timing = $_POST['times'];

        $mes = $_POST['message'];

        $id = $_POST['id'];

        $colour = $_POST["colour"];

        if (!isset($_FILES['audioFile'])) {

            $sql = "UPDATE taskmanager SET message='$mes', timing='$timing' , colour='$colour' WHERE id = $id";

            if (mysqli_query($conn, $sql)) {

                echo " Record updated successfully.";
            } else {

                echo " Error updating record: " . mysqli_error($conn);
            }
        } else {

            $targetDirectory = "upload/"; // Specify the directory where you want to store the uploaded audio files

            $targetFile = $targetDirectory . uniqid() . '.' . strtolower(pathinfo($_FILES["audioFile"]["name"], PATHINFO_EXTENSION)); //uniquid name for upload file

            $uploadOk = 1;

            $filename = $_FILES["audioFile"]["name"];  // get default file name

            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if ($fileType != "mp3" && $fileType != "wav" && $fileType != "ogg") {

                $message = "Sorry, only MP3, WAV, and OGG audio files are allowed ";

                $uploadOk = 0;
            } else {

                if (move_uploaded_file($_FILES["audioFile"]["tmp_name"], $targetFile)) {

                    $message = "The audio file " . basename($_FILES["audioFile"]["name"]) . " has been uploaded ";
                
                } else {

                    $uploadOk = 0;

                    $message = "Sorry, there was an error uploading your audio file ";
                }
            }

            if ($uploadOk == 1) {                    //`audio`,`audioname`

                $sql = "UPDATE taskmanager SET message='$mes', timing='$timing' , colour='$colour' , audio='$targetFile', audioname='$filename' WHERE id = $id";

                if (mysqli_query($conn, $sql)) {

                    echo $message . " and Record updated successfully.";
                } else {

                    echo $message . " Error updating record: " . mysqli_error($conn);
                }
            } else {

                echo "Unable to Set";
            }
        }

        mysqli_close($conn);
    }
} else {

    echo "Invaild data";
}
