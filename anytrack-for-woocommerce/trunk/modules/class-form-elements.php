<?php
 
	class anytrack_for_woocommerce_formElementsClass{
		
		var  $type;
		var  $settings;
		var  $content;
	 
		function __construct( $type, $parameters, $value ){
	 
			$this->type = $type;
			$this->parameters = $parameters;

			// array empty patch
			$default_array = [
				'class' => '',
				'id' => '',
				'value' => '',
				'default' => '',
				'width' => '',
				'title' => '',
				'sub_title' => '',
				'sub_text' => '',
				'rows' => '',
				'name' => '',
				'href' => '',
				'style' => '',
				'upload_text' => '',
				'placeholder' => '',
			];	
			$this->parameters = array_merge( $default_array, $this->parameters );
			$this->value = $value;
			$this->generate_result_block();
 
		}
		function generate_result_block(){
			global $post;

			$out = '';
			switch( $this->type ){
				
				case "top_placeholder":
					$out .= '<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="hero_section row bg-white  mb-4">
							<div class="col-3 text-center">
								<img src="'.plugins_url('/images/logopng.png', __FILE__ ).'" alt="" class="item_logo" />
							</div>
							<div class="col-9">
								<div class="h2 mb-4">Setup AnyTrack for WooCommerce</div>
								<div class="h5 mb-3">Track all purchase activity with AnyTrack, forward conversions to all your pixels and build custom audiences. Sign-up and add your property.</div>
								<div class="mb-5">&nbsp;</div>
						 
						 
								<div class="control_block mb-3">
									<a target="_blank" href="https://dashboard.anytrack.io" class=" btn btn-primary text-white text-uppercase font-weight-bold">Your Account</a>
									<a target="_blank" href="https://dashboard.anytrack.io/sign-up" class=" btn btn-white  text-uppercase font-weight-bold">Sign Up Free</a>
								</div>
								<div class="sub_text h6">5 minutes setup & no credit card required</div>
							</div>
						</div> 
					</div>';	
				break;
				case "shortcode":
					$out .= '<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
							
							<input type="text" readonly class="form-control input-xlarge"   
							value="['.$this->parameters['name'].' id=\''.$post->ID.'\']"
							
							>  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p>  
							
						  </div> 
					</div>';	
				break;
				
				case "sheet_picker":
					$out .= '
					<div class="row">
						<div class="col-6">
							<div class="form-group">  
					 
							  <input type="text"  class="form-control '.$this->parameters['class'].'"  name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" placeholder="'.$this->parameters['placeholder'].'" value="'.( $this->value && $this->value != '' ? esc_html( stripslashes( $this->value ) ) : $this->parameters['default'] ).'">  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p>  
							
							</div> 
							
							<div class="form-group">  
					 
							  <input type="text"  class="form-control '.$this->parameters['class'].'"  name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" placeholder="Recipients Email">  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p>  
							
							</div>
						</div> 
					</div>
						';
				break;
				case "separator":
					$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class=" h5 font-weight-bold mb-2">'.$this->parameters['title'].'</div> 
						<div class=" h6 mb-3  font-weight-normal">'.$this->parameters['sub_text'].'</div> 
					</div>
						';
				break;

				case "spacing":
					$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class=" h5 font-weight-bold mb-2">&nbsp;</div> 
			 
					</div>
						';
				break;
				
				case "text":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="row mb-3">
							<div class="col-12 col-sm-2"><label class="control-label fs-14" for="'.$this->parameters['id'].'"><b>'.$this->parameters['title'].'</b></label></div>
							<div class="col-11 col-sm-10">
								
							 '.( $this->parameters['sub_text'] && $this->parameters['sub_text'] != '' ? '<i qtip-content="'.htmlentities( $this->parameters['sub_text'] ).'" class="fa ml-15 qtip_picker fs-16 fa-question-circle " aria-hidden="true"></i>' : '' ).'
								
								<input type="text"  class="form-control is_small '.$this->parameters['class'].'"  name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" placeholder="'.$this->parameters['placeholder'].'" value="'.( $this->value && $this->value != '' ? esc_html( stripslashes( $this->value ) ) : $this->parameters['default'] ).'"> 
							 
							</div>
						</div>

					</div>
						';
				break;
				case "textcombo":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<div class="row">
								<div class="col-8">
									<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
							
									<input type="text"  class="form-control '.$this->parameters['class'].'"  name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" placeholder="'.$this->parameters['placeholder'].'" value="'.( $this->value && $this->value != '' ? esc_html( stripslashes( $this->value ) ) : $this->parameters['default'] ).'">  
								</div>
								<div class="col-4">
									<button class="btn btn-success mt-4" id="'.$this->parameters['button_id'].'" type="button">'.$this->parameters['button_text'].'</button>
								</div>
								<div class="col-12">
									<div class="api_test_result mt-2 mb-2"></div>
									<p class="help-block">'.$this->parameters['sub_text'].'</p>  
								</div>
							</div>
							
							
							
							
						  </div> 
					</div>
						';
				break;
				case "button":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="">&nbsp;</label>  
							
							  <a class="'.( $this->parameters['class'] ? $this->parameters['class'] : 'btn btn-success' ).'" href="'.$this->parameters['href'].'"   >'.$this->parameters['title'].'</a>  
							  
							
						</div> 
					</div>
						';
				break;
				case "select":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
							 
							  <select  style="'.$this->parameters['style'].'" class="form-control '.$this->parameters['class'].'" name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'">' ; 
							  if( count( $this->parameters['value'] ) > 0 )
							  foreach( $this->parameters['value'] as $k => $v ){
								  if( $this->value && $this->value != '' ){
									$out .= '<option value="'.$k.'" '.( $this->value  == $k ? ' selected ' : ' ' ).' >'.$v.'</option> ';
								  }else{
									$out .= '<option value="'.$k.'" '.( $this->parameters['default']  == $k ? ' selected ' : ' ' ).' >'.$v.'</option> ';
								  }
							  }
						$out .= '		
							  </select>  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p> 
							</div>  
					</div>	 
						';
				break;
				case "checkbox":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group mb-0">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
						
							  <label class="checkbox">  
								<input  class="'.$this->parameters['class'].'   ml-2" type="checkbox" name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" value="on" '.( $this->value == 'on' ? ' checked ' : '' ).' > &nbsp; 
								'.$this->parameters['text'].'  
								 
							  </label>';
							  if( $this->parameters['sub_text'] != '' ){
								  $out .= '<p class="help-block">'.$this->parameters['sub_text'].'</p> ';
							  }
							  
							  $out .= '
						  </div>  
					</div>
						';
				break;
				case "radio":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label><br/>';
								foreach( $this->parameters['value'] as $k => $v ){
									$out .= '
									<label class="radio mr-3">  
										<input  class="'.$this->parameters['class'].'" type="radio" name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" value="'.$k.'" '.( $this->value == $k ? ' checked ' : '' ).' >&nbsp;  
										'.$v.'  
										
									  </label> ';
								}
							$out .= '
							<p class="help-block">'.$this->parameters['sub_text'].'</p> 
						  </div>  
					</div>
						';
				break;
				case "textarea":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
						
							  <textarea style="'.$this->parameters['style'].'" class="form-control '.$this->parameters['class'].'" name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" rows="'.$this->parameters['rows'].'">'.( $this->parameters['name'] && $this->parameters['name'] != '' ?  esc_html( stripslashes( $this->value ) ) : $this->parameters['default'] ).'</textarea>  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p> 
						 
						  </div> 
					</div>
						';
				break;
				case "multiselect":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
							 
							  <select  multiple="multiple" style="'.$this->parameters['style'].'" class="form-control '.$this->parameters['class'].'" name="'.$this->parameters['name'].'[]" id="'.$this->parameters['id'].'">' ; 
							  foreach( $this->parameters['value'] as $k => $v ){
								  $out .= '<option value="'.$k.'" '.( @in_array( $k, $this->value )   ? ' selected ' : ' ' ).' >'.$v.'</option> ';
							  }
						$out .= '		
							  </select>  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p> 
							 
						  </div>  
					</div>
						';
				break;
				case "wide_editor":
					$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="input01">'.$this->parameters['title'].'</label>
							<div class="form-control1">
							';  
							
							ob_start();
							wp_editor( $this->value, $this->parameters['name'] );
							$editor_contents = ob_get_clean();	
						 
							$out .= $editor_contents;  
						$out .= '
							</div>
						  </div> 
					</div>';	 
					 
				break;
				case "file":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
				 
							<input type="file" class="form-control-file '.$this->parameters['class'].'" name="'.$this->parameters['name'].''.( $this->parameters['multi'] ? '[]' : '' ).'" id="'.$this->parameters['id'].'" '.( $this->parameters['multi'] ? ' multiple ' : '' ).' >
							  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p> 
						 
						  </div>
					</div>
						';
				break;
				case "mediafile_single":
					$attach_url = wp_get_attachment_url( $this->value );
					
					$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group media_upload_block">  
						<label class="control-label" for="input01">'.$this->parameters['title'].'</label>  
						 
						  <input type="hidden" class="form-control input-xlarge mediafile_single item_id" name="'.$this->parameters['name'].'" id="'.$this->parameters['name'].'" value="'.$this->value.'"> 
						  
						
						  <input type="button" class="btn btn-success upload_file" data-single="1" value="'.$this->parameters['upload_text'].'" />
						  <div class="image_preview">'.( $attach_url ?  $attach_url  : '' ).'</div>
						</div> 
					</div>';	
					break;
					
					case "save":
				 
					$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-actions">  
							<button type="submit" class="btn btn-primary">'.$this->parameters['title'].'</button>  
						</div> 
					</div>
					';	
					break;
					case "link":
				 
					$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-actions">  
							<a href="'.$this->parameters['href'].'" class="'.$this->parameters['class'].'">'.$this->parameters['title'].'</a>  
						</div> 
					</div>
					';	
					break;
					
					case "text_out":
				 
					$out .= '
					<div class="'.( $this->parameters['title'] ? $this->parameters['title'] : 'col-12' ).'">
						'.$this->parameters['class'].'
					</div>
					';	
					break;
			}
			$this->content = $out;
		 
		}
		public function  get_code(){
			return $this->content;
		}
	}

 
?>