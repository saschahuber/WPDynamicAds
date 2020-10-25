<?php

class WPDA_AdPostTypeRegisterHook{

    /**
     * Start up
     */
    public function __construct(){
		$this->metabox_register_hook = new WPDA_MetaBoxRegisterHook();
        add_action('init', array($this, 'register_ad_post_type'), 0);
    }
	
	function register_ad_post_type() {
		$labels = array(
			'name'                => _x( 'Ad', 'Post Type General Name', 'wp-dynamic-ads' ),
			'singular_name'       => _x( 'Ad', 'Post Type Singular Name', 'wp-dynamic-ads' ),
			'menu_name'           => __( 'Ads', 'wp-dynamic-ads' ),
			'parent_item_colon'   => __( 'Ad', 'wp-dynamic-ads' ),
			'all_items'           => __( 'All ads', 'wp-dynamic-ads' ),
			'view_item'           => __( 'View ad', 'wp-dynamic-ads' ),
			'add_new_item'        => __( 'Add New ad', 'wp-dynamic-ads' ),
			'add_new'             => __( 'Add New', 'wp-dynamic-ads' ),
			'edit_item'           => __( 'Edit ad', 'wp-dynamic-ads' ),
			'update_item'         => __( 'Update ad', 'wp-dynamic-ads' ),
			'search_items'        => __( 'Search ad', 'wp-dynamic-ads' ),
			'not_found'           => __( 'Not Found', 'wp-dynamic-ads' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wp-dynamic-ads' ),
		);
		 
		$args = array(
			'label'               => __( 'dynamic-ad', 'wp-dynamic-ads' ),
			'description'         => __( 'Dynamic ads', 'wp-dynamic-ads' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor'),
			#'taxonomies'          => array( '' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 6,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest' => false,
			'register_meta_box_cb' => array($this->metabox_register_hook, 'wpda_add_ad_metaboxes'),
	 
		);
		 
		register_post_type( 'dynamic-ad', $args );
	}
}

$wp_dynamic_ads_register_post_type = new WPDA_AdPostTypeRegisterHook();

?>