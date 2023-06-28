<?php
include_once 'UserStats.php';

$userStats = new UserStats;
$result = $userStats->getStats("2022-10-01","2022-10-15",9000);

var_dump($result);
die();