<?php 

 
class anytrack_for_woocommerce_SettingsClassV2{
	/* V1.0.1 */
	var $setttings_parameters;
	var $settings_prefix;
	var $message;
	
	function __construct( $prefix ){
		$this->setttings_prefix = $prefix;	
		
		if( isset($_POST[$this->setttings_prefix.'save_settings_field']) ){
			if(  wp_verify_nonce($_POST[$this->setttings_prefix.'save_settings_field'], $this->setttings_prefix.'save_settings_action') ){
				$options = array();
				foreach( $_POST as $key=>$value ){
					$options[$key] = trim( sanitize_text_field( $value ) );
				}
				update_option( $this->setttings_prefix.'_options', $options );
				
				$this->message = '<div class="alert alert-success">'.__('Settings saved', $this->setttings_prefix ).'</div>';
				
			}
		}
		
		
	}
	
	function get_setting( $setting_name ){
		$inner_option = get_option( $this->setttings_prefix.'_options');
		return $inner_option[$setting_name];
	}
	
	function create_menu( $parameters ){
		$this->setttings_parameters = $parameters;		
			
		add_action('admin_menu', array( $this, 'add_menu_item'), 100000 );
		
	}
	
	 
	
	
	function add_menu_item(){

		$default_array = [
			'type' => '',
			'parent_slug' => '',
			'form_title' => '',
			'is_form' => '',
			'page_title' => '',
			'menu_title' => '',
			'capability' => '',
			'menu_slug' => '',
			'icon' => ''
		];	
		$this->setttings_parameters = array_merge( $default_array, $this->setttings_parameters );
		
		foreach( $this->setttings_parameters as $single_option ){
			if( !isset( $single_option['type'] ) ){ continue; }
			if( $single_option['type'] == 'menu' ){
				add_menu_page(  			 
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$this->setttings_prefix.$single_option['menu_slug'], 
				array( $this, 'show_settings' ),
				$single_option['icon']
				);
			}
			if( $single_option['type'] == 'submenu' ){
				add_submenu_page(  
				$single_option['parent_slug'],  
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$this->setttings_prefix.$single_option['menu_slug'], 
				array( $this, 'show_settings' ),
				1000
				);
			}
			if( $single_option['type'] == 'option' ){
				add_options_page(  				  
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$this->setttings_prefix.$single_option['menu_slug'], 
				array( $this, 'show_settings' ) 
				);
			}
		}
		 
	}
	
	function show_settings(){
		// hide output if its parent menu
		if( count( $this->setttings_parameters[0]['parameters'] ) == 0 ){ return false; }
		
		?>
		<div class="wrap tw-bs4">
		
		
		
	 
		
		
		<?php if( $this->setttings_parameters[0]['is_form'] ): ?>
			<form class="form-horizontal" method="post" action="">
		<?php endif; ?>

		<?php 
		wp_nonce_field( $this->setttings_prefix.'save_settings_action', $this->setttings_prefix.'save_settings_field'  );  
		$config = get_option( $this->setttings_prefix.'_options'); 
		 
		?>  
		<fieldset>

			<?php 
			$cnt = 0;
			foreach( $this->setttings_parameters as $single_page ){	
			if( !isset($single_page['parameters']) ){ continue; }
				foreach( $single_page['parameters'] as $key=>$value ){	
					
					$interface_element_value =  '';
					if( isset($value['name']) ){
						if( isset( $config[$value['name']] ) ){
							$interface_element_value =  $config[$value['name']];
						}
					}
					
					if( $cnt == 1 ){
						echo $this->message;
					}
					

					$interface_element = new anytrack_for_woocommerce_formElementsClass( $value['type'], $value, $interface_element_value );
					echo $interface_element->get_code();	 

		
					
					$cnt++;
				}
			}
			?>
		</fieldset>  
		
		<?php if( $this->setttings_parameters[0]['is_form'] ): ?>
		</form>
		<?php endif; ?>

		</div>
		<?php
	}
}	

 
	
	
add_Action('init',  function (){

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return;
	}

	$locale = 'waap';
	$config_big = 
	array(
		array(
			'type' => 'submenu',
			'parent_slug' => 'woocommerce',
			'form_title' => __('AnyTrack for WooCommerce', $locale),
			'is_form' => true,
			'page_title' => __('AnyTrack for WooCommerce', $locale),
			'menu_title' => __('AnyTrack', $locale),
			'capability' => 'edit_published_posts',
			'menu_slug' => 'main_settings',
			'parameters' => array(
				array(
					'type' => 'top_placeholder',
					'title' => __('Car Listing Page', $locale),
					'name' => 'car_listing_page',
					'value' => '',
					'id' => '',
					'style' => '',
					'class' => ''
				),
				array(
					'type' => 'separator',
					'title' => __('Tracking Script',$locale),
					'name' => 'locations_list',
					'sub_text' => __('Which tracking TAG we should place on your website? ', $locale),
					'style' => ' height:300px; ',
					'id' => '',
					'class' => ''
				),

				array(
					'type' => 'text',
					'title' => __('Property ID',$locale),
					'name' => 'property_id',
					'sub_text' => __('Which tracking TAG we should place on your website?', $locale),
					'style' => ' height:300px; ',
					'placeholder' => 'XBa0y94uKFxS',
					'sub_text' =>  __('You can find your property id under <a href="https://dashboard.anytrack.io/asset/tracking-script?aid=GNa7yq4sKAxS" target="_blank">Property Settings</a>.', $locale),
					'id' => '',
					'class' => ''
				),

				array(
					'type' => 'spacing',
					'title' => __('',$locale),
					'name' => '',
					'sub_text' =>'',
					'style' => '',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'separator',
					'title' => __('Conversion Events',$locale),
					'name' => 'locations_list',
					'sub_text' => __('Each of the following events is supported. You can disable some of the events or remap their names depending on your property settings.', $locale),
					'style' => ' height:300px; ',
					'id' => '',
					'class' => ''
				),

				
				
				array(
					'type' => 'text',
					'title' => __('View Content',$locale),
					'name' => 'ViewContent',
					'sub_text' => __('', $locale),
					'style' => ' ',
					'placeholder' => 'ViewContent',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'text',
					'title' => __('Add to Cart',$locale),
					'name' => 'add_to_cart',
					'sub_text' => __('', $locale),
					'style' => ' ',
					'placeholder' => 'AddToCart',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'text',
					'title' => __('Initiate Checkout',$locale),
					'name' => 'initiate_checkout',
					'sub_text' => __('', $locale),
					'style' => ' ',
					'placeholder' => 'InitiateCheckout',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'text',
					'title' => __('Add Payment Info',$locale),
					'name' => 'add_payment_info',
					'sub_text' => __('', $locale),
					'style' => ' ',
					'placeholder' => 'AddPaymentInfo',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'text',
					'title' => __('Purchase',$locale),
					'name' => 'purchase',
					'sub_text' => __('', $locale),
					'style' => ' ',
					'placeholder' => 'Purchase',
					'id' => '',
					'class' => ''
				),
				


				array(
					'type' => 'save',
					'title' => __('Save changes', $locale),
				),
				
				 
			)
		)
	); 
	global $settings;

	$settings = new anytrack_for_woocommerce_SettingsClassV2( $locale ); 
	$settings->create_menu(  $config_big   );
	
} );
	
 

?>