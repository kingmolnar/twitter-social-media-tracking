<?php
 $mysqli = mysqli_connect("127.0.0.1", "", "", "harvest");
        if (mysqli_connect_errno($mysqli)) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>
<select id="tagSel">
<option value="">-- select tag --</option>
<?php
    /* create top twenty list of hash tags */
$res1 = mysqli_query($mysqli,
       "SELECT tag, count(*) AS cnt FROM tweet_tags GROUP BY tag ORDER BY cnt DESC LIMIT 20;"
            );
while ($row = mysqli_fetch_assoc($res1) ) {
        echo "<option value=\"".$row["tag"]."\">".$row["tag"]." (".$row["cnt"].")</option>\n";
}
?>
</select>&nbsp;&nbsp;&nbsp;
<button onclick="example('browse.php?hash='+$('#tagSel').val(), 'browseResults');">Search</button>

