<?php

date_default_timezone_set('UTC');

	// Start date
$date = '2020-04-01';
// End date
$end_date = '2020-03-30';
$date = date ("Y-m-d", strtotime("-1 day", strtotime($date)));
echo "$date<br>";

// while (strtotime($date) >= strtotime($end_date)) {
//             echo "$date<br>";
//             $date = date ("Y-m-d", strtotime("-1 day", strtotime($date)));
// }
?>