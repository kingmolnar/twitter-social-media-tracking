<?php
	$mysqli = mysqli_connect("127.0.0.1", "", "", "harvest");
	if (mysqli_connect_errno($mysqli)) {
    		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	echo "<table>";	
	$res = mysqli_query($mysqli, "SELECT COUNT(*) AS freq FROM tweets;");
	$row = mysqli_fetch_assoc($res);	
	$freq = number_format($row["freq"], 0, "", ",");
	echo "<tr><td>Tweets</td><td style=\"text-align:right\">$freq</td></tr>\n";
	
	$res = mysqli_query($mysqli,
	 "SELECT  COUNT(tag) AS freq FROM (SELECT DISTINCT tag FROM tweet_tags) AS dtags;");
	$row = mysqli_fetch_assoc($res);	
	$freq = number_format($row["freq"], 0, "", ",");
	echo "<tr><td>Hash tags</td><td style=\"text-align:right\">$freq</td></tr>\n";

	$res = mysqli_query($mysqli, "SELECT COUNT(*) AS freq FROM users;");
	$row = mysqli_fetch_assoc($res);	
	$freq = number_format($row["freq"], 0, "", ",");
	echo "<tr><td>Users</td><td style=\"text-align:right\">$freq</td></tr>\n";
	
	echo "</table>\n\n";
?>
