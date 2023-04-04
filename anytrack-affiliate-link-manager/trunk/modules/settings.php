<?php 

 
class aalmSettingsClassCar{
	
	var $setttings_parameters;
	var $settings_prefix;
	var $message;
	
	function __construct( $prefix ){
		$this->setttings_prefix = $prefix;	
		
		if( isset($_POST[$this->setttings_prefix.'save_settings_field']) ){
			if(  wp_verify_nonce($_POST[$this->setttings_prefix.'save_settings_field'], $this->setttings_prefix.'save_settings_action') ){
				$options = array();

				$options['property_id'] = sanitize_text_field( $_POST['property_id'] );
				/*
				foreach( $_POST as $key=>$value ){
					$options[$key] = sanitize_text_field( $value );
				}
				*/
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
			
		add_action('admin_menu', array( $this, 'add_menu_item') );
		
	}
	
	 
	
	
	function add_menu_item(){
		

		

		foreach( $this->setttings_parameters as $single_option ){
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
				array( $this, 'show_settings' ) 
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
		
		
		
		<h2><?php _e('Settings', 'sc'); ?></h2>
		<hr/>
		<?php 
			echo $this->message;
		?>
		
		<form class="form-horizontal" method="post" action="">
		<?php 
		wp_nonce_field( $this->setttings_prefix.'save_settings_action', $this->setttings_prefix.'save_settings_field'  );  
		$config = get_option( $this->setttings_prefix.'_options'); 
		 
		?>  
		<fieldset>

			<?php 
		foreach( $this->setttings_parameters as $single_page ){	
			foreach( $single_page['parameters'] as $key=>$value ){	
		 
					$interface_element_value =  '';
					if( isset($value['name']) ){
						if( isset( $config[$value['name']] ) ){
							$interface_element_value =  $config[$value['name']];
						}
					}
		
					$interface_element = new aalmFormElementsClass( $value['type'], $value, $interface_element_value );
					echo $interface_element->get_code();	 
				
				
			 
			}
		}
			
			?>

				
				   
				</fieldset>  

		</form>

		</div>
		<?php
	}
}	

 
	
	
add_Action('init',  function (){

	$locale = 'aalm';
	$config_big = 
	array(

		array(
			'type' => 'submenu',
			'parent_slug' => 'edit.php?post_type=custom_redirect',
			'page_title' => __('Property Settings', $locale),
			'menu_title' => __('Property Settings', $locale),
			'capability' => 'edit_published_posts',
			'menu_slug' => 'redirects_seetings',

			'parameters' => array(
	 
				array(
					'type' => 'text',
					'title' => __('AnyTrack property ID',$locale),
					'name' => 'property_id',
					'sub_text' => __('Please, enter your AnyTrack property ID', $locale),
					'style' => ' ',
					'id' => '',
		 
					'class' => ''
				 
				),
				array(
					'type' => 'save',
					'title' => __('Save', $locale),
					
				),
				
				 
			)
		)
	); 
	global $settings;

	$settings = new aalmSettingsClassCar( $locale ); 
	$settings->create_menu(  $config_big   );
	
} );
	
 

?>