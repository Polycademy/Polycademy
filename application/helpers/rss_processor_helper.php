<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); 

function rss_process($feed_url){
	
	$xml = simplexml_load_file($feed_url, null, LIBXML_NOCDATA);
	
	if(isset($xml->channel->item)){
		
		foreach($xml->channel->item as $item){
		
			$feeds[(string) $item->guid] = array(
				'title'			=> (string) word_limiter($item->title, 10),
				'link'			=> (string) $item->link,
				'description'	=> (string) $item->description,
				'date'			=> (string) mdate('%Y/%m/%d', strtotime($item->pubDate)),
			);
			
		}
		
		return $feeds;
		
	}
	
	return false;
	
}

?>