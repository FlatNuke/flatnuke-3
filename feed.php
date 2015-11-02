<?php 
	header("Content-Type: application/xml; charset=ISO-8859-1"); 
	include("include/rss/rss.php"); 
	include("config.php");
	include("shared.php");
	$myfeed = new RSSFeed(); 

	$myfeed->SetChannel('http://www.mysite.com/xml.rss',	// Get website url
				$sitename."RSS feed",
				$sitedescription,
				'en-us',			// Select encoding from $lang
				'My copyright text',		// Get Copyright from?
				'me',				// Get
				'my subject');
	$myfeed->SetImage('http://www.mysite.com/mylogo.jpg');
	
	/* use feed.php from whatever you want */
	$from = getparam("from", PAR_GET, SAN_FLAT);
	
	if($from == "news") {

		/* here we can Re-Set channel with specific informations 
		   $myfeed->SetChannel(...) */

		for($i = 0; $i < $newspp; $i++) {

			/* remove this and use flatnuke API */
			/************************************/
			$name = "news ".$i;
			$description = "Description ".$i;
			/************************************/
		
			$myfeed->AddItem('http://www.mysite.com/article.php?id='.$i,
					$name,
					$description);
		}
	}

	echo $myfeed->output();
?> 
