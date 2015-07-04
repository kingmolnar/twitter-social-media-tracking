<?php


?>
<html>
<head>
<title>The Twitter Project</title>
<link rel="stylesheet" href="jquery-powered-html5-navigation-menu/Blue/css/navbar.css" />
<style>
body { background: #0055AD url('harvest-background.gif') repeat-x bottom left; padding: 0; margin: 0;}
#outerframe { width: 100px; left: 50%; position: absolute; }
#frame { 
	width:800px;
	position: relative;
	left : -400px;
	background-color: #FFF;
	padding: 30px;
	margin: 30px 0;
	-moz-box-shadow: 6px 6px 16px #666;
	-webkit-box-shadow: 6px 6px 16px #666;
	box-shadow: 6px 6px 16px #666;
}
table.structure td {border: 1px blue solid; }
div.source { background-color: #DDD; padding: 10px; }
div.result { background-color: #ACF; padding: 10px; }
table.diskfull td { text-align: right; color: blue; }
.shadow {
	-moz-box-shadow: 3px 3px 4px #666;
	-webkit-box-shadow: 3px 3px 4px #666;
	box-shadow: 3px 3px 4px #666;
}
</style>

</head>
<body onload="initBody()">
<div id="outerframe">
<div id="frame">
<header>
 <h1>The Twitter Project (<span id="numberOfTweets">A lot of</span> tweets harvested)</h1>
</header>
<nav class="blue">
	<ul class="clear">
	<li><a href="##about" onclick="showPage('about');" >About</a></li>
	<li><a href="##database" onclick="showPage('database');" >Database</a>
		<ul>
		<li><a href="##db-structure" onclick="showPage('db-structure');" >Structure</a></li>
		<li><a href="##db-browse" onclick="showPage('db-browse');" >Browse</a></li>
		</ul>
	</li>
	<li><a href="##examples" onclick="showPage('examples');" >Examples</a></li>
	<li><a href="##documentation" onclick="showPage('documentation');" >Documentation</a></li>
	<li><a href="##projectideas" onclick="showPage('projectideas');" >Project Ideas</a></li>
	</ul>
</nav>
<div id="about" class="page">
<ul>
<li><a href="scripts">Scripts</a></li>
<li>Copy data files from <code>&tilde;pmolnar/public_html/TWITTER/files</code> </li>
</ul>
<h2>About</h2>
<p>The project uses a <a href="http://twitter.com">Twitter</a> public stream to harvest tweets about current political issues. The stream is active since mid September 2012. 
</p>
<p>Once the capacity of the allocated disk space is reached older tweets will be purged from the data-base. The current disk allocation is shown in the table below.
</p>
<table class="diskfull">
<tr><th>Used</th><th>Available</th><th>Use%</th></tr>
<?php
	passthru("df -h /home2/ | tail -1 | awk '{print \"<tr><td>\" $2 \"</td><td>\" $3 \"</td><td>\" $4 \"</td></tr>\"}'");
?>
</table>

<h3>Terms of Use</h3>
<ul>
	<li>Access to these data is granted to faculty and students of Clark Atlanta University, and researchers who are affiliated with the institution.</li>
	<li>Sharing any content of the data base with third parties is PROHIBITED.</li>
	<li>Users are not allowed to create (partial) copies of the database. </li>
	<li>This data base is provided for academic use only. Commercial use of this data base, any source code provided here, and any analytical results requires prior permission by the data base creator. </li>

</ul>

<h3>Keywords</h3>
<?php
$keywords = array(
'Abortion', 'Afghanistan', 'AfricanAmerican', 'Ambassador', 'AnneRomney', 'AUC', 'Bengali', 'BirthCertificate', 'Birther', 'Black',
'BlackVoter', 'Bluestates', 'CAU', 'ChristopherStephens', 'Congress', 'Conservative', 'CivilRights', 'Debt', 'Democrat', 'DINO', 'economy', 'Embassy', 'energy', 'gas', 'HBCU', 'Heathcare', 'HHS', 'Hurricane', 'Iran', 'Israel', 'Jerusalem', 'Liberal', 'Libya', 'Media', 'MichelleObama', 'MiddleClass', 'MidWest', 'Mitt', 'Morehouse', 'Moveon', 'MarriageEquality', 'NAACP', 'Nationaldebt', 'Obama',
 'Obamacare', 'Pakistan', 'PaulRyan', 'Poll', 'Poor', 'POTUS', 'Purplestates', 'Prolive', 'Parenthood', 'Redstates', 'Republican', 'Rich', 'RINO', 'Romney', 'Romneycare', 'Senate', 'South', 'Spelman', 'SwingState', 'Taxes', 'TeaParty', 'Undecided', 'VoterID', 'VotingRights', 'West', 'Yemen', 'Youth', 'Youthvote'
);
echo implode($keywords, ", ");
?>
<h3>Harvesting Program</h3>
The programs to harvest the public stream are based on <em>140dev Twitter Framework</em> <a href="http://140dev.com/free-twitter-api-source-code-library/">http://140dev.com/free-twitter-api-source-code-library/</a>.

<center>
<img src="tweets-per-day.png" alt="Graph of tweets per day" />
</center>
</div>

<div id="database" class="page">
<h2>Database</h2>
<p>The MySQL database is called "harvest", and can be accessed by any user process on this system. The diagram below depicts the relationships between the tables.</p>
<img src="twitter_database.png" />
<p>Follow the links <a href="##db-structure" onclick="showPage('db-structure');">data base structure</a>, or 
<a href="##db-browse" onclick="showPage('db-browse');">to browse the data base</a>.
</div><!-- #database -->

<div id="db-structure" class="page">
<h2>Structure</h2>
<p>The SQL commands <code>SHOW TABLES</code> and <code>DESCRIBE</code> can be used to extract information about the structure of the data base. Unlike other SQL data bases MySQL does not support relationships between tables for performance reasons. The integrity of the data in the tables must be maintained by the programmer.</p>
<?php

	$mysqli = mysqli_connect("127.0.0.1", "", "", "harvest");
	if (mysqli_connect_errno($mysqli)) {
    		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$tables = array();
	$res = mysqli_query($mysqli, "SHOW TABLES;");
	while ($row = mysqli_fetch_row($res) ) {
		$tables[] = $row[0];
	}
	foreach ($tables as $ind => $table) {

		echo "<div>\n";
		echo "<h5>Table <code>$table</code></h5>\n";
		echo "<table class=\"structure\" >";
		$res2 = mysqli_query($mysqli, "DESCRIBE $table ;");
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
		echo "</div>\n\n";
 	}
	
?>
</div><!-- #db_structure -->

<div id="db-browse" class="page">
<h2>Browse</h2>
<p>Select a hash tag from the list below, and view the most recent tweets that include the tag.</p>
<!-- <div id="tagSelector">Please, wait while the system compiles a list of the most frequently used tags ...</div> -->
<div id="tagSelector">This feature is no longer available.</div>

<div id="browseResults" class="result shadow">
</div>


</div><!-- #db-browse -->

<div id="examples" class="page">
<h2>Examples</h2>
<p>This is a basic example in PHP. The script connects anonymously (i.e. no user-name, no password) to the "harvest" database.</p>
<div class="source shadow">
<?php
	passthru("php -s stats.php")
?>
</div>
<div class="result shadow">
	<div id="exampleStats">Click the "Update" button to see the results of this example</div>
	<button onclick="example('stats.php', 'exampleStats');">Update</button>
</div>


<p>The next example shows the most frequently used hash-tags. Note that the example code prints the entire result table. Use the "LIMIT" statement in your SQL query to reduce the size of the output.</p>
<div class="source shadow">
<?php
	passthru("php -s toptags.php")
?>
</div>
<div class="result shadow">
	<div id="exampleTags">Click the "Update" button to see the results of this example</div>
	<button onclick="example('toptags.php', 'exampleTags');">Update</button>
</div>


</div><!-- #examples -->

<div id="documentation" class="page">
<h2>Documentation</h2>
<p>There are a number of tools available to retreive, process and analyse the data of this project. The example code is shown for using PHP. In addition, SQL queries can also be called from the command line. For example 
<pre>
$ mysql harvest -e "SELECT COUNT(*) FROM tweets;"
</pre>
produces the total number of tweets in the database. The command 
<pre>
$ mysql harvest -e "SELECT tag, count(*) AS cnt
                    FROM tweet_tags GROUP BY tag ORDER BY cnt DESC LIMIT 20;"
</pre>
lists the 20 most frequently used hash tags.
</p>
<p>The command line use of <code>mysql</code> is particularity useful to test as SQL statement. PHP program be executed from the command line as well. For example
<pre>
$ php myprog.php
</pre>
would execute in a similar way as it would if it were part of a web page. Due to the size of the data-base and the extensive time that is needed to process, web-page initiated queries should be kept to a minimum. The examples on this site demonstrate this.
</p>
<p>Developers are advised to use as many of the existing tools as possible, and write scripts to create workflows for the analysis and visualization of the data.</p>

<h3>Text Analysis</h3>
Many tweets seem to be entirely composed of hash tags and URLs. However, some tweets actually contain basic sentences. Natural Language Understanding (NRL) can be used to extract the meaning of a tweet, and potentially determine what position to a particular issue has been expressed. Among the resources to analyse text are:
<ol>
<li><A href="http://wordnet.princeton.edu">WordNet</a> is a large lexical database of English. Nouns, verbs, adjectives and adverbs are grouped into sets of cognitive synonyms (synsets), each expressing a distinct concept. The database and tools are developed at Princeton University.</li>
<li><a href="http://www.swi-prolog.org">SWI-Prolog</a> is a popular PROLOG implementation that supports Definite Clause Grammar (DCG) to create syntax rules. PROLOG is used to implement systems for logical reasoning and problem solving. The WordNet database is also available as a PROLOG knowledge base.</li>
<li><a href="www.perl.org">The Perl Programming Language</a> offers efficient pattern matching function to process text-based data. The programming language was originally developed to create tools for extracting data from large text files.
</li>
</ol>

<h3>Visualization</h3>
<ol>
<li><a href="http://gnuplot.sourceforge.net">GNUPLOT</a> is an effective tool to create 2 and 3 dimensial plots. The Programs supports different types, such as scatter plots, histograms, error bars. and many more.</li>
<li><a href="http://www.graphviz.org">GraphiViz</a> comprises a set of tools produce graphs that visualize networks and relationships. The software has it's own syntax for input files. It offeres several different algorithms to produce the layout of the graph.</li>
<li>Word Clouds allow to visualize the importance of different topics. There are a number of web-based implementations, including
	<ul>
	<li><a href="http://www.jasondavies.com/wordcloud/">http://www.jasondavies.com/wordcloud/</a></li>
	</ul>
There are also a number of web-sites that produce word clouds from uploaded files. However, those applications cannot be integrated into the workflow.
</li>
<li>Geographical data include the geo-location of a tweet, the location of the referred web-site, or the location of region mentioned in the tweet. The <a href="https://developers.google.com/maps/">Google Maps API</a> offers a JavaScript based programming library to display geographical data.</li>
<li>Another type of visualizations are time lines, which often allow intactive panning and zooming. <a href="http://timeglider.com">Time Glider</a>, e.g., provides a PHP/JavaScript library to create web-based interactive time lines.</li>

</ol>

<h3>Additional Data Extraction</h3>
<ol>
<li>Most tweets contain short URLs to save characters. Therefore, the URLs in the database might not be very useful. For further analysis the original URL needs to be recovered. Besides the content of the referenced web-site, information about the site can be extracted from the domain owner and the IP address. This might lead to an address of the organization, or at least a geographic location or region. To learn more about how to work with Short URLs visit <a href="http://www.robotics.cau.edu/mediawiki/index.php/Short_URLs">http://www.robotics.cau.edu/mediawiki/index.php/Short_URLs<a/>.</li>

<li>A number of social applications integrate with Twitter, including <a href="http://instagram.com">Instagram</a>, <aa href="facebook.com">Facebook</a>, and many more. Tweets may include (shortened) URLs to entries to these sites, which in turn have their own APIs to retrieve data.
</ol>
</div><!-- #documentation -->


<div id="projectideas" class="page">
<h2>Project Ideas</h2>
<p>The following are suggestions for projects based on the twitter harvest database. These documents should server as inspiration for further ideas.
</p>
<ol>
<?php
$path='projects/';
$handle=opendir($path);

while (($file = readdir($handle))!==false) {
	if(strlen($file)>3){
		echo "<li><a href=\"$path/$file\">$file</a></li>";
	}
}
closedir($handle);
?>
</ol>

</div><!-- #projectideas -->

<footer style="padding-top: 20px">
	<div style="text-align: center">Project created by P&eacute;ter Moln&aacute;r - 2012</div>
</footer>


</div><!-- #frame -->
</div><!-- #outerframe -->
<script type="text/javascript" language="javascript" src="/lib/jquery.js"></script>
<script>

function showPage(page) {
	$(".page").hide();
	$("#"+page).show();
}

function updateCount() {
  $.get('count.php', function(data) {
     $('#numberOfTweets').html(data);
  });
}

function example(url, element) {
	$('#'+element).html("Loading (this may take a while) ... <img src='Spinning_wheel_throbber.gif' />");
	$.get(url, function(data) { $('#'+element).html(data);});
}

function initBody() {

	var p = document.location.hash.match("[a-z\-_]+$");
	if(document.getElementById(p)) {
		showPage(p);
    } else {
        showPage("about");
	}

   updateCount();
   setInterval(updateCount, 30000);	
 
   // find top hash tags and create a select tag for #db-browse
//	example('browseform.php', 'tagSelector');
	
	// Function for navigating different coloured bars, only needed in the demo
	$(".navWrap a").click(function() {
		var activeTab = $(this).attr("href");
		$(activeTab).show().addClass('activeMenu');
		$(activeTab).siblings('.wrapper').hide().removeClass('activeMenu');
		return false;
	});

	// Requried: Navigation bar drop-down
	$("nav ul li").hover(function() {
		$(this).addClass("active");
		$(this).find("ul").show().animate({opacity: 1}, 400);
		},function() {
		$(this).find("ul").hide().animate({opacity: 0}, 200);
		$(this).removeClass("active");
	});
	
	// Requried: Addtional styling elements
	$('nav ul li ul li:first-child').prepend('<li class="arrow"></li>');
	$('nav ul li:first-child').addClass('first');
	$('nav ul li:last-child').addClass('last');
	$('nav ul li ul').parent().append('<span class="dropdown"></span>').addClass('drop');

}
</script>
</body>
</html>
