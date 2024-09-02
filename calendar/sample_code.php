<?php
$start_date = new DateTime('2024-06-01'); // Replace with your start date
$end_date = new DateTime('2024-06-10'); // Replace with your end date

$dates = [];

while ($start_date <= $end_date) {
    $dates[] = [
        'date' => $start_date->format('Y-m-d'),
        'day' => substr($start_date->format('l'),0,3)
    ];
    $start_date->add(new DateInterval('P1D'));
}

foreach ($dates as $date) {
    echo "Date: " . $date['date'] . " - Day: " . $date['day'] . "<br>";
}
?>