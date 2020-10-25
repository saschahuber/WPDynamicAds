<?php

class WPDA_MetaBoxPostListRegisterHook{
    /**
     * Start up
     */
    public function __construct(){
		add_filter('manage_dynamic-ad_posts_columns', array($this, 'create_table_head'));
		add_action('manage_dynamic-ad_posts_custom_column', array($this, 'create_table_content'), 10, 2);
    }
	
	function create_table_head( $columns ) {
		$columns['wpda_ad_keywords']  = __('Ad keywords', 'wp-dynamic-ads');
		return $columns;

	}

	function create_table_content( $column_name, $post_id ) {
		if( $column_name == 'wpda_ad_keywords' ) {
			$ad_keywords = get_post_meta( $post_id, 'wpda_ad_keywords', true );
			echo ad_keywords;
		}
	}
}

if( is_admin() ){
    $wp_dynamic_ads_metabox_post_list_register_hook = new WPDA_MetaBoxPostListRegisterHook();
}

?>