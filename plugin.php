<?php
/*
Plugin Name: Commits and Tickets
Plugin URI: http://yourls.org/
Description: Adds special redirects for t-<id> and c-<id> to tickets and commits
Version: 1.0
Author: Brett Profitt
Author URI: http://elgg.org/

Todo:
	* Auto add keywords for stats?
		pre_add_new_link
		pre_edit_link
	* Check for collisions with auto-gen'd keywords
*/

yourls_add_action('redirect_keyword_not_found', 'cat_redirect');

/**
 * Catch special c- or t- keywords and forward along.
 */
function cat_redirect($args) {
	$keyword = $args[0];
	list($type, $id) = split('-', $keyword);
	
	switch ($type) {
		case 'c':
			$url = yourls_get_option('commits_url');
			break;
			
		case 't':
			$url = yourls_get_option('tickets_url');
			break;
			
		default:
			return null;
	}
	
	// they haven't set it up yet.
	if (!$url) {
		return null;
	}
	
	$forward = sprintf($url, $id);

	if ($forward != $url) {
		// yourls_do_action('redirect_shorturl', $url, $keyword );
		yourls_redirect($forward, 301);
	}
    
	return null;
}

yourls_add_action('pre_add_new_link', 'cat_check_keywords');

/**
 * Checks for keyword collisions
 */
function cat_check_keywords($args) {
	$url = $args[0];
	$keyword = $args[1];
	$check = substr($keyword, 0, 2);
	$reserved = array('c-', 't-');
		
	if (in_array($check, $reserved)) {
		$result = array(
			'status' => 'fail',
			'code' => 'error:keyword',
			'message' => 'Reserved prefix t- or c-',
			'statusCode' => 200
		);
		echo json_encode($result);
		die();
	}
}


/**
 * Admin area
 */

yourls_add_action('plugins_loaded', 'cat_admin_page');
yourls_add_action('html_head', 'cat_admin_css');

/**
 * Register admin page
 */
function cat_admin_page() {
	yourls_register_plugin_page('cat_admin', 'Tickets and Commits Config', 'cat_draw_admin_page');
}

/**
 * Draw admin page
 */
function cat_draw_admin_page() {
	if (isset($_POST['save'])) {
		cat_update_admin_settings();
	}

	$commits_url = yourls_get_option('commits_url');
	$tickets_url = yourls_get_option('tickets_url');

	echo <<<HTML
			<h2>Commit and Tickets</h2>
			<p>Enter the URLs to your commits and tickets. %s will be replaced by the ID.<br /><br />
			
			Note: It is up to your service to handle invalid tickets. This just passes them!</p>
			
			<form method="post" class="cat_admin">
				<p>
					<label for="commits_url">Commits URL:</label>
					<input type="text" id="commits_url" name="commits_url" value="$commits_url" />
				</p>
				
				<p>
					<label for="tickets_url">Tickets URL:</label>
					<input type="text" id="tickets_url" name="tickets_url" value="$tickets_url" />
				</p>

				<input type="hidden" name="save" value="1" />				
				
				<p><input type="submit" value="Save" /></p>
			</form>
HTML;
}

/**
 * Update settings
 */
function cat_update_admin_settings() {
	$commits_url = (string)$_POST['commits_url'];
	$tickets_url = (string)$_POST['tickets_url'];
	
	if ($commits_url) {
		yourls_update_option('commits_url', $commits_url);
	}
	
	if ($tickets_url) {
		yourls_update_option('tickets_url', $tickets_url);
	}
}

/**
 * Add CSS
 */ 
function cat_admin_css() {
        echo <<<CSS
<style type="text/css">
	form.cat_admin input[type=text] {
		width: 50%;
	}
</style>

CSS;
}
