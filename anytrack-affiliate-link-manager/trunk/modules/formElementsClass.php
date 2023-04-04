<?php
 
	class aalmFormElementsClass{
		
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
						<div class="lead">'.$this->parameters['title'].'</div> 
					</div>
						';
				break;
				
				case "text":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
							
							  <input type="text"  class="form-control '.$this->parameters['class'].'"  name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" placeholder="'.$this->parameters['placeholder'].'" value="'.( $this->value && $this->value != '' ? esc_html( stripslashes( $this->value ) ) : $this->parameters['default'] ).'">  
							  <p class="help-block">'.$this->parameters['sub_text'].'</p>  
							
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
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>  
						
							  <label class="checkbox">  
								<input  class="'.$this->parameters['class'].'" type="checkbox" name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" value="on" '.( $this->value == 'on' ? ' checked ' : '' ).' > &nbsp; 
								'.$this->parameters['text'].'  
								<p class="help-block">'.$this->parameters['sub_text'].'</p> 
							  </label>  
							 
						  </div>  
					</div>
						';
				break;
				case "radio":
						$out .= '
					<div class="'.( $this->parameters['width'] ? $this->parameters['width'] : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.$this->parameters['id'].'">'.$this->parameters['title'].'</label>';
								foreach( $this->parameters['value'] as $k => $v ){
									$out .= '
									<label class="radio">  
										<input  class="'.$this->parameters['class'].'" type="radio" name="'.$this->parameters['name'].'" id="'.$this->parameters['id'].'" value="'.$k.'" '.( $this->value == $k ? ' checked ' : '' ).' >&nbsp;  
										'.$v.'  
										<p class="help-block">'.$this->parameters['sub_text'].'</p> 
									  </label> ';
								}
							$out .= '
							
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