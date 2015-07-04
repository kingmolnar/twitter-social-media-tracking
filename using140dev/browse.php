<?php
$mysqli = mysqli_connect("127.0.0.1", "", "", "harvest");
if (mysqli_connect_errno($mysqli)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$limit = 100;

if (isset($_REQUEST["hash"]) and strlen($_REQUEST["hash"])) {
	$hash = addslashes($_REQUEST["hash"]);
	$q = "
SELECT tweet_text AS 'Text', created_at AS 'Date', screen_name 'Screen Name' FROM
	(SELECT tweet_id FROM tweet_tags
		WHERE tag='$hash'
		ORDER BY tweet_id DESC LIMIT $limit) AS tid
    JOIN tweets ON (tid.tweet_id=tweets.tweet_id);
";

/* 
SELECT tweet_text, created_at, screen_name FROM (SELECT tweet_id WHERE tag='$hash' AS t) JOIN tweets ON (t.tweet_id=tweets.tweet_id)
	ORDER BY created_at DESC LIMIT 100;
	"; 
*/
	$m = "Tweets taged #$hash";
} else {
	$q = "
SELECT tweet_text AS 'Text', created_at AS 'Date', screen_name AS 'Screen Name' FROM tweets
	ORDER BY created_at DESC LIMIT $limit;
	"; 
	$m = "All tweets";
}
echo "<h3>$m</h3>\n";
echo "<table>";
$res2 = mysqli_query($mysqli, $q);
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
