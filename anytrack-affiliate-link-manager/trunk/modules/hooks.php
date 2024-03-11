<?php
// insertion of code to page
add_Action('wp_head', function () {
	$ANYTRACK_ASSETS_URL = defined('ANYTRACK_ASSETS_URL') ? ANYTRACK_ASSETS_URL : 'https://assets.anytrack.io/';

	$setting = get_option('aalm_options');
	$tracking_code = '
	<!-- AnyTrack Tracking Code -->
	<script>!function(e,t,n,s,a){(a=t.createElement(n)).async=!0,a.src="' . $ANYTRACK_ASSETS_URL . '{Property_Id}.js",(t=t.getElementsByTagName(n)[0]).parentNode.insertBefore(a,t),e[s]=e[s]||function(){(e[s].q=e[s].q||[]).push(arguments)}}(window,document,"script","AnyTrack");</script>
	<!-- End AnyTrack Tracking Code -->
	';
	if (isset($setting['property_id'])) {
		if ($setting['property_id'] && $setting['property_id'] != '') {
			echo str_replace('{Property_Id}', $setting['property_id'], $tracking_code);
		}
	}
});


// orderable
add_filter('manage_edit-custom_redirect_sortable_columns',  'aalm_make_column_sortable');
add_action('pre_get_posts',  'aalm_column_custom_ordering');
function aalm_make_column_sortable($columns)
{
	$columns['count'] = 'count';
	return $columns;
}

function aalm_column_custom_ordering($query)
{
	if (!is_admin())
		return;

	$orderby = $query->get('orderby');
	if ('count' == $orderby) {
		$query->set('meta_key', 'redirect_count');
		$query->set('orderby', 'meta_value');
	}
}


// redirect processing
add_action('init', function () {


	if (isset($_GET['custom_delete'])) {
		wp_delete_post($_GET['post'], true);
		wp_redirect(admin_url('edit.php?post_type=custom_redirect'), 302);
		die();
	}

	if (!is_admin()) {
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


		// actual processing
		$actual_link_parts = explode('?', $actual_link);

		if (isset($actual_link_parts[1]))
			$actual_link_parameters = $actual_link_parts[1];
		else
			$actual_link_parameters = '';

		if (isset($actual_link_parts[0]))
			$actual_link = $actual_link_parts[0];
		else
			$actual_link = '';

		$all_redirects = get_posts([
			'post_type' => 'custom_redirect',
			'showposts' => -1
		]);

		foreach ($all_redirects as $s_post) {
			$source_url = get_post_meta($s_post->ID, 'source_url', true);
			$target_url = get_post_meta($s_post->ID, 'target_url', true);

			// get target URL params patch
			$target_url_parts = explode('?', $target_url);

			if (isset($target_url_parts[1]))
				$target_url_params = $target_url_parts[1];
			else
				$target_url_params = '';


			if (isset($target_url_parts[0]))
				$target_url = $target_url_parts[0];
			else
				$target_url = '';



			$is_equal_lines = 0;



			$home_url = '';
			$source_url = str_replace($home_url, '', $source_url);

			// {click_id} processing
			if (substr_count($source_url, '{click_id}') > 0) {

				$home_url = get_option('home');
				$home_url = str_replace('http://', 'https://', $home_url);

				$actual_link = str_replace('http://', 'https://', $actual_link);

				// get url without domain
				$source_url_tmp = str_replace($home_url, '', $source_url);
				$source_url_tmp_array = explode('/', $source_url_tmp);
				//var_dump( $source_url_tmp_array );

				// find click position
				$key = array_search('{click_id}', $source_url_tmp_array);

				// get relation from current url
				//var_dump( get_option('home') );
				//var_dump( $actual_link );
				$actual_link_tmp = str_replace($home_url, '', $actual_link);
				$actual_link_tmp_array = explode('/', $actual_link_tmp);
				//var_dump( $actual_link_tmp_array );

				$click_data_value = $actual_link_tmp_array[$key];
				$target_url = str_replace('{click_id}', $click_data_value,  $target_url);

				// equal cehckeing

				$source_url_tmp_array[$key] = 'INSERT';

				$actual_link_tmp_array[$key] = 'INSERT';

				//var_dump( $source_url_tmp_array );
				//var_dump( $actual_link_tmp_array );
				if (implode('-', $source_url_tmp_array) == implode('-', $actual_link_tmp_array)) {
					$is_equal_lines = 1;
				}
			} else {
				$home_url = get_option('home');

				$home_url = str_replace('http://', 'https://', $home_url);
				$actual_link =  str_replace('http://', 'https://', $actual_link);

				$actual_link =  str_replace($home_url, '', $actual_link);
			}

			if ($actual_link == $source_url || $is_equal_lines == 1) {


				$current_count = (int)get_post_meta($s_post->ID, 'redirect_count', true);
				$current_count++;
				update_post_meta($s_post->ID, 'redirect_count', $current_count);
				update_post_meta($s_post->ID, 'last_access_date', time());


				if ($actual_link_parameters != '' || $target_url_params != '') {

					// all Target params
					parse_str($target_url_params, $output_target_arr);
					parse_str($actual_link_parameters, $output_actual_arr);
					$all_params = array_merge($output_target_arr, $output_actual_arr);

					$target_url = $target_url . '?' . http_build_query($all_params);
				}

				wp_redirect($target_url, 302);
				exit();
			}
		}
	}
});

add_Action('admin_head', function () {
	echo '
		<style>
		.post-type-custom_redirect .row-actions .inline.hide-if-no-js,
		.post-type-custom_redirect .row-actions .edit,
		#bulk-action-selector-top option:nth-child(2){
			display:none !important;
		}
		.post-type-custom_redirect .tablenav .alignleft.actions:not(.bulkactions){
			display:none !important;
		}
		.post-type-custom_redirect .subsubsub .publish{
			display:none !important;
		}
		.post-type-custom_redirect .search-box{
			display:none !important;
		}
		.post-type-custom_redirect .wp-list-table th#title{
			width:200px;
		}
		.post-type-custom_redirect .wp-list-table th#count{
			width:100px;
		}
		.post-type-custom_redirect .wp-list-table th#strategy{
			width:100px;
		}
		.menu-icon-custom_redirect ul li:nth-child(3){
			display:none !important;
		}
		</style>
	';
});

add_filter('manage_edit-custom_redirect_columns', 'aalm_custom_redirect_columns');
function aalm_custom_redirect_columns($columns)
{
	$locale = 'aalm';
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Redirect Name', $locale),
		'data' => __('Data', $locale),
		'count' => __('Count', $locale),
		'last_access' => __('Last access', $locale),
	);

	return $columns;
}

add_action('manage_custom_redirect_posts_custom_column', 'aalm_custom_redirect_posts_custom_column', 10, 2);

function aalm_custom_redirect_posts_custom_column($column, $post_id)
{
	$locale = 'aalm';
	global $post;
	switch ($column) {
			/* If displaying the 'duration' column. */
		case 'data':
			$all_options = ['exact' => __('Exact match all parameters in any order', $locale), 'ignore' => __('Ignore all parameters', $locale), 'pass' => __('Ignore & pass parameters to the target', $locale)];

			echo 	'<div class="params_container edit_big_' . $post_id . '  tw-bs4">
						<div class=""><b>' . __('Source URL', $locale) . ':</b> <span class="place_source_url">' . get_post_meta($post->ID, 'source_url', true) . '</span></div>
						<!-- <div class=""><b>' . __('Query Parameters', $locale) . ':</b> <span class="place_query_params">' . (isset($all_options[get_post_meta($post->ID, 'query_params', true)]) ? $all_options[get_post_meta($post->ID, 'query_params', true)] : '') . '</span></div> -->
						<div class=""><b>' . __('Target URL', $locale) . ':</b> <span class="place_target_url">' . get_post_meta($post->ID, 'target_url', true) . '</span></div><br/>

						<div class="editor_block"  style="display:none;" >
							
							<div class="mb-2">
								<b>' . __('Redirect name', $locale) . ':</b> <br/>
								<input class="form-control input_redirect_name"   value="' . $post->post_title . '" >
							</div>
							<div class="mb-2">
								<b>' . __('Source URL', $locale) . ':</b> <br/>
								<input class="form-control input_source_url"   value="' . esc_html(get_post_meta($post->ID, 'source_url', true)) . '" >
							</div>
							<!--
							<div class="mb-2">
								<b>' . __('Query Parameters', $locale) . ':</b> <br/>
								<select class="form-control input_query_params" >';



			foreach ($all_options as $key => $value) {
				echo '<option data-text="' . htmlentities($value) . '" value="' . $key . '" ' . (get_post_meta($post->ID, 'query_params', true) == $key ? ' selected ' : '') . ' >' . $value;
			}
			echo '
								</select>
							</div>
							-->
							<div class="mb-2">
								<b>' . __('Target URL', $locale) . ':</b> <br/>
								<input class="form-control input_target_url"   value="' . esc_html(get_post_meta($post->ID, 'target_url', true)) . '" >
							</div><br/>
						</div>

						<button type="button" class="btn btn-info btn-sm  edit_data" data-id="' . $post->ID . '">' . __('Edit', $locale) . '</button>

						<button type="button" class="btn btn-success btn-sm save_data " style="display:none;" data-id="' . $post->ID . '">' . __('Save', $locale) . '</button>
						<button type="button" class="btn btn-warning btn-sm cancel_data  " style="display:none;" data-id="' . $post->ID . '">' . __('Cancel', $locale) . '</button>

						<div class="processing_result"></div>
					</div>';
			break;


		case 'count':
			echo (int)get_post_meta($post->ID, 'redirect_count', true);
			break;
		case 'last_access':
			$last_access_date = (int)get_post_meta($post->ID, 'last_access_date', true);
			if ($last_access_date == 0) {
				echo '';
			} else {
				echo date('Y-m-d H:i:s', $last_access_date);
			}

			break;

			/* Just break out of the switch statement for everything else. */
		default:
			break;
	}
}


add_Action('admin_footer', function () {
	if (isset($_GET['post_type'])) {
		if ($_GET['post_type'] == 'custom_redirect') {
			echo '
			<div id="redirect_pagination" class="" style="display: none;">
				<div class="tw-bs4">
					<nav aria-label="breadcrumb" class="mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item active" aria-current="page">' . __('All Redirections', 'aalm') . '</li>	
							<li class="breadcrumb-item"><a href="' . admin_url('edit.php?post_type=custom_redirect&page=aalmredirects_seetings') . '">' . __('Property Settings', 'aalm') . '</a></li>
							
						</ol>
					</nav>
				</div>
			</div>
			
			';
		}
	}

	if (isset($_GET['post_type']) && isset($_GET['page'])) {
		if ($_GET['post_type'] == 'custom_redirect' && $_GET['page'] == 'aalmredirects_seetings') {
			echo '
			<div id="redirect_settings" class="" style="display: none;">
				<div class="tw-bs4">
					<nav aria-label="breadcrumb "  class="mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item " aria-current="page"><a href="' . admin_url('edit.php?post_type=custom_redirect') . '">' . __('All Redirections', 'aalm') . '</a></li>	
							<li class="breadcrumb-item active">' . __('Property Settings', 'aalm') . '</li>
							
						</ol>
					</nav>
				</div>
			</div>
			
			';
		}
	}
});
