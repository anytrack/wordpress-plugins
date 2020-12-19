<?php 
function aalm_gnerate_palceholder(){
	$locale = 'aalm';
	 $data_block = '
	 <tr id="post-41726" class="iedit author-self level-0 post-41726 type-custom_redirect status-publish hentry">
			<th scope="row" class="check-column">			<label class="screen-reader-text" for="cb-select-41726">
				Redirect 41726		</label>
			<input id="cb-select-41726" type="checkbox" name="post[]" value="41726">
			<div class="locked-indicator">
				<span class="locked-indicator-icon" aria-hidden="true"></span>
				<span class="screen-reader-text">
				Redirect 41726 is locked				</span>
			</div>
			</th><td class="title column-title has-row-actions column-primary page-title" data-colname="Redirect Name"><div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
<strong><a class="row-title" href="%url%post.php?post=41726&amp;action=edit" aria-label="Redirect 41726 (Edit)">Redirect 41726</a></strong>

<div class="hidden" id="inline_41726">
	<div class="post_title">Redirect 41726</div><div class="post_name">41726</div>
	<div class="post_author">1</div>
	<div class="comment_status">closed</div>
	<div class="ping_status">closed</div>
	<div class="_status">publish</div>
	<div class="jj">26</div>
	<div class="mm">11</div>
	<div class="aa">2020</div>
	<div class="hh">14</div>
	<div class="mn">38</div>
	<div class="ss">13</div>
	<div class="post_password"></div><div class="page_template">default</div><div class="sticky"></div></div><div class="row-actions"><span class="edit"><a href="%url%post.php?post=41726&amp;action=edit" aria-label="Edit Redirect 41726">Edit</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Quick edit Redirect 41726 inline" aria-expanded="false">Quick&nbsp;Edit</button> | </span><span class="trash"><a href="%delete%" class="submitdelete" aria-label="Move Redirect 41726 to the Trash">Trash</a></span></div><button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="data column-data" data-colname="Data"><div class="params_container edit_big_41726  tw-bs4">
						<div class=""><b>Source URL:</b> <span class="place_source_url"></span></div>
						<!-- <div class=""><b>Query Parameters:</b> <span class="place_query_params"></span></div> -->
						<div class=""><b>Target URL:</b> <span class="place_target_url"></span></div><br>

						<div class="editor_block" style="">
							
							<div class="mb-2">
								<b>Redirect name:</b> <br>
								<input class="form-control input_redirect_name" value="">
							</div>
							<div class="mb-2">
								<b>Source URL:</b> <br>
								<input class="form-control input_source_url" value="">
							</div>
							<!--
							<div class="mb-2">
								<b>Query Parameters:</b> <br/>
								<select class="form-control input_query_params" ><option data-text="Exact match all parameters in any order" value="exact"  >Exact match all parameters in any order<option data-text="Ignore all parameters" value="ignore"  >Ignore all parameters<option data-text="Ignore &amp; pass parameters to the target" value="pass"  >Ignore & pass parameters to the target
								</select>
							</div>
							-->
							<div class="mb-2">
								<b>Target URL:</b> <br>
								<input class="form-control input_target_url" value="">
							</div><br>
						</div>

						<button type="button" class="btn btn-info btn-sm  edit_data" data-id="41726" style="display: none;">Edit</button>

						<button type="button" class="btn btn-success btn-sm save_data " style="" data-id="41726">Save</button>
						<button type="button" class="btn btn-warning btn-sm cancel_data  " style="" data-id="41726">Cancel</button>

						<div class="processing_result"></div>
					</div></td><td class="count column-count" data-colname="Count">0</td><td class="last_access column-last_access" data-colname="Last access"></td>		</tr>
	 ';
	$new_id = wp_insert_post([
		'post_type' => 'custom_redirect',
		'post_status' => 'publish'
	]);
	wp_update_post([
		'ID' => $new_id,
		'post_title' => __('Redirect ', $locale ).' '.$new_id
	]);
	$new_content = str_replace( '41726', $new_id, $data_block );
	$new_content = str_replace( '%url%', admin_url(), $new_content );
 
	$url = admin_url('post.php?post='.$new_id.'&action=trash&custom_delete=1');
	$delete_link = wp_nonce_url( $url );

	$new_content = str_replace( '%delete%', $delete_link, $new_content );

	return $new_content;

}

 

?>