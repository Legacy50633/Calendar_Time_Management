<?php

include("./gpio_database.php");

// Correct the typo in the table name
$sql = "SELECT * FROM `gpio_setting` WHERE `ID` = 1";  // Select all columns where ID is 1
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  $events = array();  // Initialize an empty array to store data
  while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;  // Append each row to the array
  }
  // Output the data in JSON format
  header('Content-Type: application/json');
  echo json_encode($events);
} else {
  // If no records are found, return an empty JSON array
  echo json_encode([]);
}

// Close the database connection
mysqli_close($conn);

?>
