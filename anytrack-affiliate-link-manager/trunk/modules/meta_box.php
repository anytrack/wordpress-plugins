<?php 
 
	class aalmMetaBox1{
		
		private $metabox_parameters = null;
		private $fields_parameters = null;
		private $data_html = null;
		
		function __construct( $metabox_parameters , $fields_parameters){
			$this->metabox_parameters = $metabox_parameters;
			$this->fields_parameters = $fields_parameters;
 
			add_action( 'add_meta_boxes', array( $this, 'add_custom_box' ) );
			add_action( 'save_post', array( $this, 'save_postdata' ) );
		}
		
		function add_custom_box(){
			add_meta_box( 
				'custom_meta_editor_'.rand( 100, 999 ),
				$this->metabox_parameters['title'],
				array( $this, 'custom_meta_editor' ),
				$this->metabox_parameters['post_type'] , 
				$this->metabox_parameters['position'], 
				$this->metabox_parameters['place']
			);
		}
		function custom_meta_editor(){
			global $post;
			
			$out = '

			<div class="tw-bs4">
				<div class="form-horizontal ">';
			
			foreach( $this->fields_parameters as $single_field){
			 
				$interface_element = new aalmFormElementsClass( $single_field['type'], $single_field, get_post_meta( $post->ID, $single_field['name'], true ) );
				$out .= $interface_element->get_code();
			  
			}		
			
					
					
			$out .= '
					</div>	
				</div>
				';	
			$this->data_html = $out;
			 
			$this->echo_data();
		}
		
		function echo_data(){
			echo $this->data_html;
		}
		
		function save_postdata( $post_id ) {
			global $current_user; 
			 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				  return;

			  if ( 'page' == $_POST['post_type'] ) 
			  {
				if ( !current_user_can( 'edit_page', $post_id ) )
					return;
			  }
			  else
			  {
				if ( !current_user_can( 'edit_post', $post_id ) )
					return;
			  }
			  /// User editotions

				if( get_post_type($post_id) == $this->metabox_parameters['post_type'] ){
					foreach( $this->fields_parameters as $single_parameter ){
						update_post_meta( $post_id, $single_parameter['name'], sanitize_text_field( $_POST[$single_parameter['name']] ) );
					}
					
				}
				
			}
	}
 

 
 
add_Action('admin_init',  function (){
	 
	 
	 
	$locale = 'aalm';
	 
	 $meta_box = array(
		'title' => __('Redirect Parameters', $locale ),
		'post_type' => 'custom_redirect',
		'position' => 'advanced',
		'place' => 'high'
	);
	$fields_parameters = array(

		array(
			'type' => 'text',
			'title' => __('Source URL', $locale),
			'name' => 'source_url',
		),
		/*
		array(
			'type' => 'select',
			'title' => __('Query Parameters', $locale),
			'name' => 'query_params',
			'value' => [ 'exact' => __('Exact match all parameters in any order', $locale), 'ignore' => __('Ignore all parameters', $locale), 'pass' => __('Ignore & pass parameters to the target', $locale) ]
		),
		*/
		array(
			'type' => 'text',
			'title' => __('Target URL', $locale),
			'name' => 'target_url',
		),

		 
	 
	);		
	$new_metabox = new aalmMetaBox1( $meta_box, $fields_parameters); 
	 
 } );
 

?>