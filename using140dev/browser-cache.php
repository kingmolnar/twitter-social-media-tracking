#!/usr/bin/env php
<?php
//require_once 'NeoRest.php';

/**
*	Create a graphDb connection 
*	Note:	this does not actually perform any network access, 
*			the server is only accessed when you use the database
*/

// connect to neo4j ...
//$graphDb = new GraphDatabaseService('http://10.43.4.8:7474/');

// connect mysql
$mysqli = mysqli_connect("127.0.0.1", "foo", "", "harvest");
       if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }

   $res2 = mysqli_query($mysqli,
"SELECT * FROM json_cache LIMIT 100;"
           );
   while ($row2 = mysqli_fetch_assoc($res2) ) {
	$tweet = unserialize(base64_decode($row2["raw_tweet"]));
	print_r($tweet);
/*
	if(isset($tweet->retweet_count)) {
		echo "retweet count: $tweet->retweet_count\n";
	}
	if(isset($tweet->retweeted_status, $tweet->retweeted_status->in_reply_to_user_id)) {
		echo "in reply to: ";
		print_r($tweet->retweeted_status);
		echo "\n";
	}
*/
	if(isset($tweet->source)) {
		echo "source: ".$tweet->source."\n";
	}
	
	// work with $tweet: insert nodes and links into neo4j ... print out what would be done

   }

	// clean up!!

?>
