<?php
include("./gpio_database.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['selftest'])){
        $selftest = $_POST['selftest'];

        if ($selftest == 0){
            $gpio_message = 'Gpio Staus is On';
        }
        else{
            $gpio_message = 'Gpio Staus is On';
        }

        $sql = "UPDATE `gpio_setting` SET `selftest` = '$selftest' WHERE `id` = 1";

            if (mysqli_query($conn, $sql)){

                echo $gpio_message;
                return ;
            }
            else{
                echo 'Something went Wrong. Please Try again';
                return ;
            }
    }


    if (isset($_POST['isToggled']) && isset($_POST['count'])) {

        $button_status = htmlspecialchars($_POST['isToggled']);

        $button_time_Interval = $_POST['count'];


        $targetDirectory = "audioBell/"; // Specify the directory where you want to store the uploaded audio files

        $start_audio = $_FILES["startaudio"]["name"];

        $end_audio = $_FILES["endaudio"]["name"];

        $middle_audio = isset($_FILES['middleaudio']['name']) ?  $_FILES['middleaudio']['name'] : "";

        $start_temp = $_FILES["startaudio"]["tmp_name"];

        $end_temp = $_FILES["endaudio"]["tmp_name"];

        $First_File = $targetDirectory . uniqid() . '.' . strtolower(pathinfo($start_audio, PATHINFO_EXTENSION));

        $Second_File = $targetDirectory . uniqid() . '.' . strtolower(pathinfo($end_audio, PATHINFO_EXTENSION));

        $Third_File = "";

        if(!empty($middle_audio)){

            $Third_File = $targetDirectory.  uniqid(). '.' . strtolower(pathinfo($middle_audio, PATHINFO_EXTENSION));

        }
        else{

            $Third_File = "";            

        }

        $uploadOk = 1;

        $fileType1 = strtolower(pathinfo($_FILES["startaudio"]["name"], PATHINFO_EXTENSION));

        $fileType2 = strtolower(pathinfo($Second_File, PATHINFO_EXTENSION));

        if ($fileType1 != "mp3" && $fileType1 != "wav" && $fileType2 != "mp3" && $fileType2 != "wav") {

            $message = "Sorry, only MP3, WAV, and OGG audio files are allowed. ";

            $uploadOk = 0;
        } else {

            if (move_uploaded_file($_FILES["startaudio"]["tmp_name"], $First_File)) {

                $message = "The audio file " . $First_File . " has been uploaded ";

                $uploadOk = 1;

            }

            if (move_uploaded_file($_FILES["endaudio"]["tmp_name"], $Second_File)) {

                $uploadOk = 1;

                if(!empty($middle_audio)){

                    move_uploaded_file($_FILES["middleaudio"]["tmp_name"], $Third_File);
                
                }

            }

             else {

                $uploadOk = 0;

                $message = "Sorry, there was an error uploading your audio file. ";
            }
        }

        if ($uploadOk) {

            $sql = "UPDATE `gpio_setting` 
        SET `start_audio` = '$First_File', 
            `end_audio` = '$Second_File',
            `middle_audio` = '$Third_File', 
            `button_status` = '$button_status', 
            `time_interval` = '$button_time_Interval' 
        WHERE `id` = 1";

            if (mysqli_query($conn, $sql)) {

                $count_insert = 1;


                echo $message . " and Successful";

                mysqli_close($conn);

                return;
            } else {
                echo "Unsucessfull. Please Try again";
            }
        } else {

            echo "Something Went Wrong. Please Try again";
        }
    }
    else{
        echo "Something Went Wrong. Please Try again";
    }

}
