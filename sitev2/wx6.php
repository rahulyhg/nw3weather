<?php require('unit-select.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php include("phptags.php");
	$file = 6; ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>NW3 Weather - Old(v2) - Sun and Moon Info</title>

	<meta name="description" content="Old v2 - Sun and Moon Info, and some other astronomy, for Hampstead, North London; provided by NW3 weather." />

	<? require('chead.php'); ?>
	<?php include_once("ggltrack.php") ?>
</head>

<body>
	<!-- For non-visual user agents: -->
	<div id="top"><a href="#main-copy" class="doNotDisplay doNotPrint">Skip to main content.</a></div>

	<!-- ##### Header ##### -->
	<? require('header.php'); ?>

	<!-- ##### Left Sidebar ##### -->
	<? require('leftsidebar.php'); ?>
	
	<!-- ##### Main Copy ##### -->
	<div id="main-copy">
	<? require('site_status.php'); ?>
</div>
	<div id="main-copy-dark">
<h1>Astronomy Information</h1>

<div align="center">
<table width="99%" cellpadding="0" border="0" cellspacing="0">
<tr>
	<td width="50%" align="center"><h3>Sun</h3></td>
	<td width="50%" align="center"><h3>Moon</h3></td>
</tr>
<tr>
	<td width="50%" align="center">
	<img border="0" src="/static-images/sunicon.jpg" width="104" height="72" alt="Sun" /></td>
	<td width="50%" align="center">
	<img border="0" src="/static-images/<?php $splitmoon = explode(" ", $moonage); if($splitmoon[2] < 2): echo 'moon1.jpg';
	elseif($splitmoon[2] >= 2 && $splitmoon[2] < 4): echo 'moon2.jpg';
	elseif($splitmoon[2] >= 4 && $splitmoon[2] < 6): echo 'moon3.jpg';
	elseif($splitmoon[2] >= 6 && $splitmoon[2] < 9): echo 'moon4.jpg';
	elseif($splitmoon[2] >= 9 && $splitmoon[2] < 11): echo 'moon5.jpg';
	elseif($splitmoon[2] >= 11 && $splitmoon[2] < 13): echo 'moon6.jpg';
	elseif($splitmoon[2] >= 13 && $splitmoon[2] < 16): echo 'moon7.jpg';
	elseif($splitmoon[2] >= 16 && $splitmoon[2] < 18): echo 'moon8.jpg';
	elseif($splitmoon[2] >= 18 && $splitmoon[2] < 21): echo 'moon9.jpg';
	elseif($splitmoon[2] >= 21 && $splitmoon[2] < 23): echo 'moon10.jpg';
	elseif($splitmoon[2] >= 23 && $splitmoon[2] < 26): echo 'moon11.jpg';
	elseif($splitmoon[2] >= 26 && $splitmoon[2] < 28): echo 'moon12.jpg';
	else: echo 'moon13.jpg';
	endif;	echo '?', mt_rand(1,1000), '" width="90" height="90" title="', $moonphasename, '" alt="', $moonage; ?>" /></td>
</tr>
<tr>
	<td width="50%" align="center">Sunrise: <?php echo $sunrise; ?><br />Sunset: <?php echo $sunset; ?><br />Daylight: <?php echo $hoursofpossibledaylight; ?></td>
	<td width="50%" align="center">Moonrise: <?php echo $moonrise; ?><br />Moonset: <?php echo $moonset; ?><br /><?php echo $moonphase; ?> illuminated</td>
</tr>
</table>
</div>

<br /><br />

<div align="center">
<table width="99%" cellpadding="0" border="0" cellspacing="0">
	<tr>
	<td width="25%" align="center">First Quarter Moon</td>
	<td width="25%" align="center">Full Moon</td>
	<td width="25%" align="center">Last Quarter Moon</td>
	<td width="25%" align="center">New Moon</td>
	</tr>
	<tr>
	<td width="25%" align="center">
	<img border="0" src="/static-images/firstquartermoon.jpg" width="87" height="75" alt="First Quarter Moon" /></td>
	<td width="25%" align="center">
	<img border="0" src="/static-images/fullmoon.jpg" width="87" height="75" alt="Full Moon" /></td>
	<td width="25%" align="center">
	<img border="0" src="/static-images/lastquartermoon.jpg" width="87" height="75" alt="Last Quarter Moon" /></td>
	<td width="25%" align="center">
	<img border="0" src="/static-images/newmoon.jpg" width="87" height="75" alt="New Moon" /></td>
	</tr>
	<tr>
	<td width="25%" align="center"><?php echo $firstquarter; ?></td>
	<td width="25%" align="center"><?php echo $fullmoon; ?></td>
	<td width="25%" align="center"><?php echo $lastquarter; ?></td>
	<td width="25%" align="center"><?php echo $nextnewmoon; ?></td>
	</tr>
</table>
</div>
	</div>

<div id="main-copy">

<br />

<div align="center">
<h3>Earth View</h3>
	<a href="http://www.fourmilab.ch/earthview/" target="_blank">
	<img src="http://www.fourmilab.ch/cgi-bin/Earth?img=learth.evif&amp;imgsize=320&amp;dynimg=y&amp;opt=-p&amp;lat=&amp;lon=&amp;alt=&amp;tle=&amp;date=0&amp;utc=&amp;jd="
			width="640" height="320" border="0"	alt="Earth Daylight &amp; Darkness" /> </a><br />Current location of sunlight/darkness across the globe
	<a href="http://www.fourmilab.ch/earthview/credits.php" title="Credit">(Credit)</a>
	
<br />
<br />

<table class="table1" width="850" align="center" cellpadding="5" cellspacing="0">
<tr><td class="td6" colspan="2" ><h3>More Astronomy</h3></td></tr>
<tr class="table-top">
<td class="td6" colspan="1">Measure</td><td class="td6" colspan="1">Value</td>
</tr>

<tr class="column-light">
<td class="td6" width="50%">Moon Age</td>
<td class="td6" width="50%"><?php $splitmoon2 = explode(",", $moonage);echo $splitmoon[2], ' days, ', $splitmoon2[1], ' (', str_ireplace(' moon', '', $moonphasename); ?>)</td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Next Moon <acronym title="Nearest approach of Moon to Earth">Perigee</acronym></td>
<td class="td6" width="50%"><?php echo $moonperigee; ?> </td></tr>
<tr class="column-light">
<td class="td6" width="50%">Next Moon <acronym title="Furthest approach of Moon to Earth">Apogee</acronym></td>
<td class="td6" width="50%"><?php echo $moonapogee; ?> </td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Moon Transit</td>
<td class="td6" width="50%"><?php echo str_ireplace('local', $dst, $moontransit); ?> </td></tr>
<tr class="column-light">
<td class="td6" width="50%">Sun Transit</td>
<td class="td6" width="50%"><?php echo str_ireplace('local', $dst, $suntransit); ?> </td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Next Lunar Eclipse</td>
<td class="td6" width="50%"><?php echo $mooneclipse; ?></td></tr>
<tr class="column-light">
<td class="td6" width="50%">Next Solar Eclipse</td>
<td class="td6" width="50%"><?php echo $suneclipse; ?> </td></tr>
<tr class="column-dark">
<td class="td6" width="50%">---</td>
<td class="td6" width="50%">---</td></tr>
<tr class="column-light">
<td class="td6" width="50%">Vernal Equinox</td>
<td class="td6" width="50%"><?php echo $marchequinox; ?></td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Summer Solstice</td>
<td class="td6" width="50%"><?php echo $junesolstice; ?> </td></tr>
<tr class="column-light">
<td class="td6" width="50%">Autumnal Equinox</td>
<td class="td6" width="50%"><?php echo $sepequinox; ?> </td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Winter Solstice</td>
<td class="td6" width="50%"><?php echo $decsolstice; ?> </td></tr>
<tr class="column-light">
<td class="td6" width="50%">---</td>
<td class="td6" width="50%">---</td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Day Length</td>
<td class="td6" width="50%"><?php echo $hoursofpossibledaylight; ?></td></tr>
<tr class="column-light">
<td class="td6" width="50%">Change since Yesterday</td>
<td class="td6" width="50%"><?php echo $changeinday; ?> </td></tr>
<tr class="column-dark">
<td class="td6" width="50%">Change since Summer Solstice</td>
<td class="td6" width="50%"><?php echo $changeindayjun; ?></td></tr>
<tr class="column-light">
<td class="td6" width="50%">Chance since Winter Solstice </td>
<td class="td6" width="50%"><?php echo $changeindaydec; ?> </td></tr>
</table>

</div>
</div>

<!-- ##### Footer ##### -->
	<? require("footer.php"); ?>

</body>
</html>