<?php
	
	$booksonicUrl = "http://demo.booksonic.org/booksonic";
	$booksonicUsername = "demo";
	$booksonicPassword = "demo";
	
	function string_contain($string, $substring){
		if(strpos($string, $substring) !== FALSE){
			return true;
		}
		else{
			return false;
		}
	}

	$data = json_decode(file_get_contents("{$booksonicUrl}/rest/getPodcasts.view?u={$booksonicUsername}&p={$booksonicPassword}&v=1.12.0&c=BooksonicPodcastList&includeEpisodes=false&f=json"), true);
	
	$output = array();
	if(isset($data['subsonic-response']['podcasts']['channel'])){
		foreach($data['subsonic-response']['podcasts']['channel'] as $podcast){
			if(
				!string_contain($podcast['url'], "192.168") //Internal "podcast" I use for testing during development
			){
				array_push($output, array('name'=>$podcast['title'], 'url' =>$podcast['url'], 'desc' =>$podcast['description'], 'img' =>$podcast['originalImageUrl']));
			}
		}
	}else{
		$output['error'] = "No podcasts on the server";	
	}
	echo json_encode($output);
?>