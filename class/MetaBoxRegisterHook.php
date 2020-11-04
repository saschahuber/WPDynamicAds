<?php

class WPDA_MetaBoxRegisterHook{
    /**
     * Start up
     */
    public function __construct(){
		add_action( 'save_post', array($this, 'wpda_save_ad_meta'), 1, 2 );
    }
	
	function wpda_add_ad_metaboxes() {
		add_meta_box('wpda_ad_keywords', __('Ad keywords (separated by ";")', 'wp-dynamic-ads'), array($this, 'wpda_ad_keywords'), 'dynamic-ad', 'advanced', 'default');
	}
	
	function wpda_ad_keywords() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'wpda_ad_keywords_field' );
		$ad_keywords = get_post_meta( $post->ID, 'wpda_ad_keywords', true );
		?>
			<p><?php echo __('Give some keywords that the post must contain in order for the ad to be displayed.', 'wp-dynamic-ads'); ?></p>
			<input type="text" name="wpda_ad_keywords" value="<?php echo esc_textarea( $ad_keywords ); ?>" class="widefat">
		<?php
	}
	
	function wpda_save_ad_meta( $post_id, $post ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

		$meta_fields = array(
			array('field' => 'wpda_ad_keywords', 'type' => 'text')
		);

		$ad_meta = array();
		
		foreach($meta_fields as $field){
			//Clean checkboxes
			if($field['type'] == 'bool'){
				delete_post_meta($post_id, $field['field']);
			}
			
			if ( ! isset( $_POST[$field['field']] ) || ! wp_verify_nonce( $_POST[$field['field'].'_field'], basename(__FILE__) ) ) {
				continue;
			}
			
			switch($field['type']){
				case 'text':
					$ad_meta[$field['name']] = sanitize_textarea_field( $_POST[$field['name']] );
				case 'bool':
					$ad_meta[$field['name']] = sanitize_text_field($_POST[$field['name']]);
					break;
				case 'html':
					$ad_meta[$field['name']] = sanitize_text_field($_POST[$field['name']]);
					break;
				default:
					$ad_meta[$field['name']] = sanitize_text_field($_POST[$field['name']]);
					break;
			}
		}

		foreach ( $ad_meta as $key => $value ){
			if ( get_post_meta( $post_id, $key, false ) ) {
				update_post_meta( $post_id, $key, $value );
			} else {
				add_post_meta( $post_id, $key, $value);
			}

			if ( ! $value ) {
				delete_post_meta( $post_id, $key );
			}
		}
	}
}

?>