<?php

if($mainDataCritical) {
	$crsize1 = filesize(LIVE_DATA_PATH);
	$crmoddiff1 = time() - filemtime(LIVE_DATA_PATH);
	if($crmoddiff1 <= 1 && $crsize1 == 0) { // stalled/mid upload
		sleep(1); //should fix things; not too critical anyway
		$scriptbeg -= 1.0;
		clearstatcache(); //has resolved issue?
		$slept = true;
	}
}
$crsizeFinal = filesize(LIVE_DATA_PATH);

//Select appropriate file to use
if($crsizeFinal === 0) {
	$usePath = ROOT.'clientrawBackup.txt';
	$badCRdata = true;
} else {
	$usePath = LIVE_DATA_PATH;
}

$client = file($usePath);
$mainData = explode(" ", $client[0]);

if($badCRdata || $slept) {
	log_events('clientrawBad.txt', $crsizeFinal ."B ". makeBool($slept));
}

$kntsToMph = 1.152;
// Main current weather variables
$temp = $mainData[4];
$humi = $mainData[5];

$pres = $mainData[6];
$rain = $mainData[7];
$wind = $mainData[1] * $kntsToMph;
$gust = $mainData[140] * $kntsToMph; //actually the max 1-min gust
$gustRaw = $mainData[2] * $kntsToMph; //true 14s gust
$w10m = $mainData[158] * $kntsToMph;
$wdir = $mainData[3];

// Time variables
$unix = filemtime(LIVE_DATA_PATH);

// Derived current weather variables
$dewp = dewPoint($temp, $humi);
$feel = feelsLike($temp, $gust, $dewp);

// Other multi-use weather vars
$maxgsthr = $HR24['misc']['maxhrgst'];
$maxgstToday = $NOW['max']['gust'];
$maxavgToday = $maxavgspd;

// No wind data - use Harpenden wind data from their clientraw (cached by cron_main)
$NO_WIND_DATA = false;
if($NO_WIND_DATA) {
	$extClient = file(ROOT.'EXTclientraw.txt');
	$extOffset = 0.91; // 0.91; //1.3 - tott;
	$extData = explode(" ", $extClient[0]);
	$wind = $extData[1] * $kntsToMph * $extOffset;
	$gust = $extData[140] * $kntsToMph * $extOffset; //actually the max 1-min gust
	$gustRaw = $extData[2] * $kntsToMph * $extOffset; //true 14s gust
	$w10m = $extData[158] * $kntsToMph * $extOffset;
	$wdir = $extData[3];

	$feel = feelsLike($temp, $gust, $dewp);
	$maxavgToday = $NOW['max']['wind'];
}
if(false && $extData[3] === "101") { // CASA rules whilst Harpenden is down ;(
	$extClient = file(ROOT.'EXTclientraw2.txt');
	$extOffset = 0.95; // 0.91; //1.3 - tott;
	$extData = explode(" ", $extClient[0]);
	$wind = $extData[5] * $extOffset;
	$gust = $extData[6] * $extOffset;
	$gustRaw = $extData[6] * $extOffset;
	$w10m = $extData[5] * $extOffset;
	$wdir = $extData[7];

	$feel = feelsLike($temp, $gust, $dewp);
	$maxavgToday = $NOW['max']['wind'];
}
if(false && $temp == 16.9) {
	// Casa
	$extClient2 = file(ROOT.'EXTclientraw2.txt');
	$extData2 = explode(" ", $extClient2[0]);
	$temp = $extData2[2] - 0.7;
	$humi = $extData2[3] + 1;
	$dewp = dewPoint($temp, $humi);
	$feel = feelsLike($temp, $gust, $dewp);
}
if(false && $temp == 16.9) {
	// StAlbans
	$extClient2 = file(ROOT.'EXTclientraw2.txt');
	$extData2 = explode(" ", $extClient2[0]);
	$temp = $extData2[4] + 0.3;
	$humi = $extData2[5] - 1;
	$dewp = dewPoint($temp, $humi);
	$feel = feelsLike($temp, $gust, $dewp);
}

if(false && $rain == 0 && date("Hi") > "0009") {
	// Casa rain
	$extClient2 = file(ROOT.'EXTclientraw2.txt');
	$extData2 = explode(" ", $extClient2[0]);
	$rain = $extData2[9];
}

if(false && $rain == 0 && date("Hi") > "0009") {
	// Bencook/brixton rain
	$extClient2 = file(ROOT.'EXTclientraw2.txt');
	$extData2 = explode(" ", $extClient2[0]);
	$rain = $extData2[7];
}


?>
