<?php
include("./config.php");
if (isset($_FILES['csv_file'])) {

  $colour = '#2aa2c0';
  $day_not_allowed = '';
  $filename = '';
  $targetFile = '';
  $notallowed = '';
  // Check for successful upload
  $count_insert = 0;
  if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    echo "Failed to upload file.";
    exit;
  }


  // Validate file type
  $allowed_mime_types = ['text/csv', 'application/csv', 'text/plain'];
  if (!in_array($_FILES['csv_file']['type'], $allowed_mime_types)) {
    echo "Invalid file type. Please upload a CSV file.";
    exit;
  }

  // Validate file extension
  $file_extension = pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION);
  if (strtolower($file_extension) !== 'csv') {
    echo "Invalid file extension. Please upload a CSV file.";
    exit;
  }

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

  $filename = $_FILES['csv_file']['tmp_name'];
  if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $num = count($data);
      for ($c = 1; $c < $num; $c++) {
        if (!empty($data[$c]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3])) {
          try {
            $title = mysqli_real_escape_string($conn, $data[2]);
            $start_date = DateTime::createFromFormat('d/m/y', $data[1]);
            $dateinsert = $start_date->format('Y-m-d');
            $enddate = $dateinsert;
            $timing = mysqli_real_escape_string($conn, $data[3]);
            $sql = "INSERT INTO `taskmanager`( `event_id`,`message`, `startdate`, `enddate`, `notallowed`, `timing`, `colour`, `days`,`audio`,`audioname`) VALUES ('$event_id','$title','$dateinsert','$enddate','$notallowed','$timing','$colour','$day_not_allowed','$targetFile','$filename')";

            if (mysqli_query($conn, $sql)) {

              ++$count_insert;
              //echo $count_insert."-";
            } else {

              echo "Unable to Insert";
              break;
            }
          } catch (Exception $k) {
            echo $k;
          }
        }
      }
      fclose($handle);
    }
  } else {
    echo "Failed to open the file.";
  }
} else {
  echo "No file uploaded.";
}
