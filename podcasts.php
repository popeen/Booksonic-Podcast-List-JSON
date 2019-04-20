<?php
	
	$booksonicDBScript = "C:\booksonic\db\booksonic.script";
	
	function get_between_all($content, $start, $end, $array = array()){
		$temp = explode($start, $content, 2);
		if(isset($temp[1])){
			$temp = explode($end, $temp[1], 2);
			$array[] = $temp[0];
			return get_between_all($temp[1], $start, $end, $array);
		} else{
			return $array;
		}
	}
	
	function string_contain($string, $substring){
		if(strpos($string, $substring) !== FALSE){
			return true;
		}
		else{
			return false;
		}
	}

	$data = file_get_contents($booksonicDBScript);
	
	$podcastLines = get_between_all($data, "INSERT INTO PODCAST_CHANNEL VALUES(", "')");
	$output = array();
	foreach($podcastLines as $podcastLine){
		if(
			!string_contain($podcastLine, "Error on line ") && //In case there is a link added to the database that isn't a valid podcast we ignore it
			!string_contain($podcastLine, "192.168") //Internal "podcast" I use for testing during development
		){
			$explodedLine = explode(",'", $podcastLine);
			$url = str_replace("'", "", $explodedLine[1]);
			$name = json_decode('{"name":"'.str_replace("'", '"', $explodedLine[2]).'}', true)['name'];
			if(string_contain($url, "patreon.com/rss/")){
				$url = "Patreon url censored";
			}
			$desc = json_decode('{"desc":"'.str_replace("'", '"', str_replace(array("''", '"', ":"), "", $explodedLine[3])).'}', true)['desc'];
			$img = $explodedLine[5];
			array_push($output, array('name'=>$name, 'url' =>$url, 'desc' =>$desc, 'img' =>$img));
		}
	}
	echo json_encode($output);
?>