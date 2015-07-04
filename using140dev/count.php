<?php
	$mysqli = mysqli_connect("127.0.0.1", "", "", "harvest");
		if (mysqli_connect_errno($mysqli)) {
    	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$res = mysqli_query($mysqli, "SELECT COUNT(*) AS freq FROM json_cache;");
	$row = mysqli_fetch_assoc($res);
	echo number_format($row["freq"], 0, "", ",");
?>
