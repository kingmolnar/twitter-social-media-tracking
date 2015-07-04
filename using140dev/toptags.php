<?php
	$mysqli = mysqli_connect("127.0.0.1", "", "", "harvest");
		if (mysqli_connect_errno($mysqli)) {
    	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	echo "<table>";
	$res2 = mysqli_query($mysqli,
 "SELECT tag AS 'Tag', count(*) AS Cnt FROM tweet_tags GROUP BY tag ORDER BY Cnt DESC LIMIT 20;"
			);
	$row2 = mysqli_fetch_assoc($res2);
	echo "<tr>";
	foreach ($row2 as $k => $v) { echo "<th>$k</th>"; }
	echo "</tr>\n";
	echo "<tr>";
	foreach ($row2 as $k => $v) { echo "<td>$v</td>"; }
	echo "</tr>\n";
	while ($row2 = mysqli_fetch_assoc($res2) ) {
		echo "<tr>";
		foreach ($row2 as $k => $v) { echo "<td>$v</td>"; }
		echo "</tr>\n";
	}
	echo "</table>\n";

?>
