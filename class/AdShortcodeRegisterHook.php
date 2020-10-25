<?php

class WPDA_AdShortcodeRegisterHook{

    /**
     * Start up
     */
    public function __construct(){
		add_shortcode( 'dynamic_ads', array($this, 'dynamic_ads_shortcode') );
    }
	
	function dynamic_ads_shortcode( $atts ) {
		try{
			$MAX_ITEMS = 3;
			
			$queried_object = get_queried_object();

			$displayed_ads = array();
			
			if ( $queried_object ) {
				$post_id = $queried_object->ID;
				
				$ads = get_posts(array('post_type' => 'dynamic-ad'));
				
				foreach($ads as $ad){
					$content = $ad->post_content;
					$keywords_data = get_post_meta($ad->ID, 'wpda_ad_keywords', true);
					
					$score = $this->post_contains($post_id, $keywords_data);
					if($score > 0){
						array_push($displayed_ads, array('ad' => $ad, 'score' => $score));
					}
				}
			}
			
			usort($displayed_ads, array($this, 'ad_order_function'));
			
			$output = '';
			$counter = 0;
			$asins = array();
			foreach($displayed_ads as $displayed_ad){
				if($counter >= $MAX_ITEMS){
					break;
				}
				
				$output .= $displayed_ad['ad']->post_content;
				$counter++;
			}
			
			return $output;
		}
		catch(Exception $e){
			return $e;
		}
	}

	function ad_order_function($a, $b){
		return $b["score"] - $a["score"];
	}

	function post_contains($id, $keywords_data){
		$content_post = get_post($id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		
		$keywords = array();
		
		foreach(explode(';', $keywords_data) as $data_item){
			if(!empty(trim($data_item))){
				array_push($keywords, trim($data_item));
			}
		}
		
		$TITLE_SCORE = 10;
		$CONTENT_SCORE = 1;
		
		$first_position = PHP_INT_MAX;
		$score = 0;
		
		$post_title = get_the_title($id);
		foreach($keywords as $keyword){
			$lastPos = 0;
			while (($lastPos = stripos($post_title, $keyword, $lastPos))!== false) {
				if($lastPos < $first_position){
					$first_position = $lastPos;
				}
				
				$score += $TITLE_SCORE;
				
				$lastPos = $lastPos + strlen($keyword);
			}
		}
		
		//scan post content
		foreach($keywords as $keyword){		
			$lastPos = 0;
			while (($lastPos = stripos($content, $keyword, $lastPos))!== false) {
				if($lastPos+(strlen($post_title)) < $first_position){
					$first_position = $lastPos;
				}
				
				$score += $CONTENT_SCORE;
				
				$lastPos = $lastPos + strlen($keyword);
			}
		}
		
		return intval((floatval($score)/($first_position+1))*10000);
	}
}

$wp_dynamic_ads_register_shortcode = new WPDA_AdShortcodeRegisterHook();

?>