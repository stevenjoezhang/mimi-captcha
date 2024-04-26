<?php
/**
 * Plugin Name: Mimi Captcha
 * Plugin URI: https://github.com/stevenjoezhang/mimi-captcha
 * Description: 简洁的中文验证码插件。在WordPress登陆、注册或评论表单中加入验证码功能，支持字母、数字、中文和算术验证码。
 * Version: 0.6.1
 * Author: Shuqiao Zhang
 * Author URI: https://zhangshuqiao.org
 * Text Domain: mimi-captcha
 * Domain Path: /languages
 * License: GPL3
 */

/*  Copyright 2018  Shuqiao Zhang  (email : stevenjoezhang(at)gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain('mimi-captcha', false, dirname(plugin_basename(__FILE__)).'/languages');
define('MICAPTCHA_DIR_URL', plugin_dir_url(__FILE__));

switch (get_option('micaptcha_loading_mode')) {
	case 'onload':
		define('MICAPTCHA_SCRIPT', '<script>
		(function() {
			window.addEventListener("load", function() {
				var captcha = document.getElementById("micaptcha");
				captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
				captcha.onclick = function() {
					captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
				}
			});
		})();
		</script>');
		break;
	case 'oninput':
		define('MICAPTCHA_SCRIPT', '<script>
		(function() {
			var captcha = document.getElementById("micaptcha");
			function loadMiCaptcha() {
				captcha.setAttribute("loaded", true);
				captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
			}
			window.addEventListener("load", function() {
				document.querySelectorAll("input, textarea").forEach(function(element) {
					element.addEventListener("input", function() {
						if (captcha.getAttribute("loaded")) return;
						loadMiCaptcha();
					});
				});
			});
			captcha.onclick = loadMiCaptcha;
		})();
		</script>');
		break;
	default:
		define('MICAPTCHA_SCRIPT', '<script>
		(function() {
			var captcha = document.getElementById("micaptcha");
			captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
			captcha.onclick = function() {
				captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
			}
		})();
		</script>');
		break;
}
define('MICAPTCHA_WHITELIST', '<p class="form-captcha">
		<label>'.__('Captcha', 'mimi-captcha').' <span class="required">*</span></label>
		<span style="display: block; clear: both;"></span>
		<label>'.__('You are in the allowlist', 'mimi-captcha').'</label>
		</p>');
define('MICAPTCHA_CONTENT', '<p class="form-captcha">
		<img alt="Captcha Code" id="micaptcha" src="'.MICAPTCHA_DIR_URL.'default.png" style="max-width: 100%;">
		<span style="display: block; clear: both;"></span>
		<label>'.__('Click the image to refresh', 'mimi-captcha').'</label>
		<span style="display: block; clear: both;"></span>'.MICAPTCHA_SCRIPT);
define('MICAPTCHA_INPUT', '<label for="url">'.__('Captcha', 'mimi-captcha').' <span class="required">*</span></label>
		<!-- Don`t Ask Why Not `for="captcha_code"`. You are Not Expected to Understand This. -->
		<input id="captcha_code" name="captcha_code" type="text" size="30" maxlength="200" autocomplete="off" style="display: block;">
		</p>');

// Hook to initialize sessions
add_action('init', 'micaptcha_init_sessions');
add_filter('pre_http_request', 'micaptcha_pre_http_request', 10, 3);

// Hook to initalize the admin menu
add_action('admin_menu', 'micaptcha_admin_menu');

// Hook to initialize admin notices
add_action('admin_notices', 'micaptcha_admin_notice');

add_filter('plugin_action_links', 'micaptcha_plugin_actions', 10, 2);
add_filter('admin_footer_text', 'micaptcha_admin_footer', 1, 2);

function micaptcha_init_sessions() {
	if (!session_id()) {
		@session_start();
	}
	$_SESSION['captcha_type'] = get_option('micaptcha_type');
	$_SESSION['captcha_letters'] = get_option('micaptcha_letters');
	$_SESSION['total_no_of_characters'] = get_option('micaptcha_total_no_of_characters');
	$_SESSION['captcha_flag'] = ((get_option('micaptcha_use_curve') === 'yes') << 2) | ((get_option('micaptcha_use_noise') === 'yes') << 1) | (get_option('micaptcha_distort') === 'yes');
}

// Write session to disk to prevent cURL time-out which may occur with
// WordPress (since 4.9.2, see https://core.trac.wordpress.org/ticket/43358),
// or plugins such as "Health Check".

// See: https://plugins.trac.wordpress.org/browser/ninjafirewall/trunk/lib/utils.php
function micaptcha_pre_http_request($preempt, $r, $url) {
	// NFW_DISABLE_SWC can be defined in wp-config.php (undocumented):
	if (!defined('NFW_DISABLE_SWC') && isset($_SESSION)) {
		if (function_exists('get_site_url')) {
			$parse = parse_url(get_site_url());
			$s_url = @$parse['scheme']."://{$parse['host']}";
			if (strpos($url, $s_url) === 0) {
				@session_write_close();
			}
		}
	}
	return false;
}

// Get rid of the Site Health php_sessions test, it returns a scary message
// although everything is working as expected
function micaptcha_remove_php_sessions_test($tests) {
	unset($tests['direct']['php_sessions']);
	return $tests;
}
add_filter('site_status_tests', 'micaptcha_remove_php_sessions_test');

// To add the menus in the admin section
function micaptcha_admin_menu() {
	add_options_page(
		'Mimi Captcha',
		'Mimi Captcha',
		'manage_options',
		'micaptcha_slug',
		'micaptcha_general_options'
	);
}

require_once('general-options.php');

function micaptcha_admin_notice() {
	if (substr($_SERVER['PHP_SELF'], -11) === 'plugins.php' && function_exists('admin_url')) {
		if (!get_option('micaptcha_type')) {
			echo '<div class="notice notice-warning"><p><strong>'.sprintf(__('Thank you for using Mimi Captcha. The plugin is not configured yet, please go to the <a href="%1$s">plugin admin page</a> to check settings.', 'mimi-captcha'), admin_url('options-general.php?page=micaptcha_slug')).'</strong></p></div>';
		}
		if (!function_exists('gd_info')) {
			echo '<div class="notice notice-error"><p>'.sprintf(__('<strong>ERROR: PHP GD extension is not installed or turned on. Mimi Captcha plugin can not run correctly.</strong><br>Please see the <a href="%1$s">PHP documentation</a> for more information.', 'mimi-captcha'), 'https://secure.php.net/manual/book.image.php').'</p></div>';
		}
	}
}

function micaptcha_plugin_actions($links, $file) {
	if ($file === 'mimi-captcha/mimi-captcha.php' && function_exists('admin_url')) {
		$settings_link = '<a href="'.admin_url('options-general.php?page=micaptcha_slug').'">'.__('Settings').'</a>';
		array_unshift($links, $settings_link); // Before other links
	}
	return $links;
}

function micaptcha_admin_footer($text) {
	if (isset($_GET['page']) && $_GET['page'] === 'micaptcha_slug' && function_exists('admin_url')) {
		$url = 'https://wordpress.org/support/plugin/mimi-captcha/reviews/?filter=5#new-post';
		$text = sprintf(
			// Translators: %1$s - WP.org link; %2$s - same WP.org link
			__('Please rate <strong>Mimi Captcha</strong> <a href="%1$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you from the Mimi Captcha team!', 'mimi-captcha'), $url, $url
		);
	}
	return $text;
}

function micaptcha_get_ip() {
	$ip = '';
	if (isset($_SERVER)) {
		$server_vars = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
		foreach ($server_vars as $var) {
			if (isset($_SERVER[$var]) && !empty($_SERVER[$var])) {
				if (filter_var($_SERVER[$var], FILTER_VALIDATE_IP)) {
					$ip = $_SERVER[$var];
					break;
				}
				else { // If proxy
					$ip_array = explode(',', $_SERVER[$var]);
					if (is_array($ip_array) && !empty($ip_array) && filter_var($ip_array[0], FILTER_VALIDATE_IP)) {
						$ip = $ip_array[0];
						break;
					}
				}
			}
		}
	}
	return $ip;
}

function micaptcha_ip_in_range($ip, $list) {
	if ($ip === '') {
		return false;
	}
	foreach ($list as $range) {
		$range = array_map('trim', explode('-', $range));
		if (count($range) === 1) {
			if ((string)$ip === (string)$range[0]) {
				return true;
			}
		}
		else {
			$low = ip2long($range[0]);
			$high = ip2long($range[1]);
			$needle = ip2long($ip);
			if ($low === false || $high === false || $needle === false) {
				continue;
			}

			$low = sprintf("%u", $low);
			$high = sprintf("%u", $high);
			$needle = sprintf("%u", $needle);
			if ($needle >= $low && $needle <= $high) {
				return true;
			}
		}
	}
	return false;
}

function micaptcha_allowlist() { // 黑名单同理
	$allowlist_ips = get_option('micaptcha_allowlist_ips');
	// $allowlist_usernames = get_option('micaptcha_allowlist_usernames');
	if (micaptcha_ip_in_range(micaptcha_get_ip(), (array)$allowlist_ips)) {
		return true;
	}
	// else if (in_array($username, (array)$allowlist_usernames)) return true;
	else {
		return false;
	}
}

function micaptcha_validate() {
	if (micaptcha_allowlist()) {
		return false;
	}
	if (!isset($_SESSION['captcha_time']) || !isset($_SESSION['captcha_code']) || !isset($_REQUEST['captcha_code'])) {
		return __('Incorrect Captcha confirmation!', 'mimi-captcha');
	}
	// Captcha timeout
	if (get_option('micaptcha_timeout_time') && get_option('micaptcha_timeout_time') != '0') {
		if (time() - intval($_SESSION['captcha_time']) >= intval(get_option('micaptcha_timeout_time'))) {
			return __('Captcha timeout!', 'mimi-captcha');
		}
	}
	// If captcha is blank - add error
	if ($_REQUEST['captcha_code'] === '') {
		return __('Captcha cannot be empty. Please complete the Captcha.', 'mimi-captcha');
	}
	if ($_SESSION['captcha_code'] === $_REQUEST['captcha_code']) {
		return false;
	}
	if (get_option('micaptcha_case_sensitive') === 'insensitive') {
		if (strtoupper($_SESSION['captcha_code']) === strtoupper($_REQUEST['captcha_code'])) {
			return false;
		}
	}
	// Captcha was not matched
	return __('Incorrect Captcha confirmation!', 'mimi-captcha');
}

/* Captcha for login authentication starts here */

if (get_option('micaptcha_login') === 'yes') {
	add_action('login_form', 'micaptcha_login');
	add_filter('login_errors', 'micaptcha_login_errors');
	add_filter('login_redirect', 'micaptcha_login_redirect', 10, 3);
}

// Function to include captcha for login form
function micaptcha_login() {
	if (micaptcha_allowlist()) {
		echo MICAPTCHA_WHITELIST;
	}
	else {
		echo MICAPTCHA_CONTENT;
		// Will retrieve the get varibale and prints a message from url if the captcha is wrong
		if (isset($_GET['captcha']) && $_GET['captcha'] === 'confirm_error') {
			echo '<label style="color: #FF0000;">'.$_SESSION['captcha_error'].'</label>
			<span style="display: block; clear: both;"></span>';
			$_SESSION['captcha_error'] = '';
		}
		echo MICAPTCHA_INPUT;
	}
	return true;
}

// Hook to find out the errors while logging in
function micaptcha_login_errors($errors) {
	if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'register') {
		return $errors;
	}
	if (micaptcha_validate()) {
		return $errors.__('<strong>ERROR</strong>: ', 'mimi-captcha').micaptcha_validate();
	}
	return $errors;
}

// Hook to redirect after captcha confirmation
function micaptcha_login_redirect($url) {
	// Captcha mismatch
	if (isset($_SESSION['captcha_code']) && isset($_REQUEST['captcha_code']) && micaptcha_validate()) {
		$_SESSION['captcha_error'] = micaptcha_validate();
		wp_clear_auth_cookie();
		return $_SERVER["REQUEST_URI"]."/?captcha='confirm_error'";
		// 登陆限制（IP或者用户名）应在此完成，使用数据库而非SESSION记录
	}
	// Captcha match: take to the admin panel
	else {
		return home_url('/wp-admin/');
	}
}

/**
 * Add Password and Repeat Password fields to WordPress registration
 * All credit goes to http://thematosoup.com
 * Original code is from http://thematosoup.com/development/allow-users-set-password-wordpress-registration/
 * The page is gone, you can browse it via https://web.archive.org/web/20120618002355/http://thematosoup.com:80/development/allow-users-set-password-wordpress-registration
 */

if (get_option('micaptcha_password') === 'yes') {
	add_action('register_form', 'micaptcha_show_extra_register_fields');
	add_action('register_post', 'micaptcha_check_extra_register_fields', 10, 3);
	add_action('signup_extra_fields', 'micaptcha_show_extra_register_fields');
	add_action('user_register', 'micaptcha_register_extra_fields', 100);
	add_filter('gettext', 'micaptcha_edit_password_email_text', 20, 3);
}

function micaptcha_show_extra_register_fields() {
	?>
	<p>
		<label for="password"><?php _e('Password'); ?>
			<br>
			<input id="password" class="input" type="password" tabindex="30" size="25" value="" name="password">
		</label>
	</p>
	<p>
		<label for="repeat_password"><?php _e('Repeat password', 'mimi-captcha'); ?>
			<br>
			<input id="repeat_password" class="input" type="password" tabindex="40" size="25" value="" name="repeat_password">
		</label>
	</p>
	<?php
}

// Check the form for errors
function micaptcha_check_extra_register_fields($login, $email, $errors) {
	if (!isset($_POST['password']) || !isset($_POST['repeat_password']) || $_POST['password'] === '' || $_POST['repeat_password'] === '') {
		$errors->add('password_not_set', __('<strong>ERROR</strong>: ', 'mimi-captcha').__("Passwords cannot be empty.", 'mimi-captcha'));
		return $errors;
	}
	else if ($_POST['password'] !== $_POST['repeat_password']) {
		$errors->add('passwords_not_matched', __('<strong>ERROR</strong>: ', 'mimi-captcha').__("Passwords must match.", 'mimi-captcha'));
		return $errors;
	}
	else if (strlen($_POST['password']) < 8) {
		$errors->add('password_too_short', __('<strong>ERROR</strong>: ', 'mimi-captcha').__("Passwords must be at least eight characters long.", 'mimi-captcha'));
		return $errors;
	}
	return $errors;
}

// Storing WordPress user-selected password into database on registration
function micaptcha_register_extra_fields($user_id) {
	$userdata = [];

	$userdata['ID'] = $user_id;
	if (isset($_POST['password']) && $_POST['password'] !== '') {
		$userdata['user_pass'] = sanitize_text_field($_POST['password']); // Sanitize
	}
	wp_update_user($userdata);
}

// Editing WordPress registration confirmation message
function micaptcha_edit_password_email_text($translated_text, $untranslated_text, $domain) {
	if (in_array($GLOBALS['pagenow'], ['wp-login.php'])) {
		if ($untranslated_text === 'A password will be e-mailed to you.') {
			$translated_text = __('If you leave password fields empty one will be generated for you. Password must be at least eight characters long.', 'mimi-captcha');
			// 邮件发送密码的方式已在WordPress 4.x中被弃用
		}
		elseif ($untranslated_text === 'Registration complete. Please check your email.' || $untranslated_text === 'Registration complete. Please check your e-mail.') {
			$translated_text = __('Registration complete. Please sign in or check your email.', 'mimi-captcha');
		}
	}
	return $translated_text;
}

/* Captcha for Register form starts here */

if (get_option('micaptcha_register') === 'yes') {
	add_action('register_form', 'micaptcha_register');
	add_action('register_post', 'micaptcha_register_post', 10, 3);
	add_action('signup_extra_fields', 'micaptcha_register');
	add_filter('wpmu_validate_user_signup', 'micaptcha_register_validate');
}

// Function to include captcha for register form
function micaptcha_register($default) {
	echo (micaptcha_allowlist() ? MICAPTCHA_WHITELIST : MICAPTCHA_CONTENT.MICAPTCHA_INPUT);
	return true;
}

// This function checks captcha posted with registration
function micaptcha_register_post($login, $email, $errors) {
	if (micaptcha_validate()) {
		$errors->add('captcha_wrong', __('<strong>ERROR</strong>: ', 'mimi-captcha').micaptcha_validate());
	}
	return $errors;
}

function micaptcha_register_validate($results) {
	if (micaptcha_validate()) {
		$results['errors']->add('captcha_wrong', __('<strong>ERROR</strong>: ', 'mimi-captcha').micaptcha_validate());
		return $results;
	}
}

/* Captcha for lost password form starts here */

if (get_option('micaptcha_lost') === 'yes') {
	add_action('lostpassword_form', 'micaptcha_lostpassword');
	add_action('lostpassword_post', 'micaptcha_lostpassword_post', 10, 3);
}

// Function to include captcha for lost password form
function micaptcha_lostpassword($default) {
	echo (micaptcha_allowlist() ? MICAPTCHA_WHITELIST : MICAPTCHA_CONTENT.MICAPTCHA_INPUT);
}

function micaptcha_lostpassword_post() {
	if (micaptcha_validate()) {
		add_filter('allow_password_reset', 'micaptcha_lostpassword_errors_wp');
	}
}

function micaptcha_lostpassword_errors_wp() {
	return false;
	// 禁止找回密码行为，让前面的micaptcha_login_errors处理此问题
}

/* Captcha for comments starts here */

if (get_option('micaptcha_comments') === 'yes') {
	/*
	 * Common hooks to add necessary actions for the WP comment form,
	 * but some themes don't contain these hooks in their comments form templates
	 */
	add_action('comment_form_after_fields', 'micaptcha_comment_form_wp3', 1);
	add_action('comment_form_logged_in_after', 'micaptcha_comment_form_wp3', 1);
	/*
	 * Try to display the CAPTCHA before the close tag </form>
	 * in case if hooks 'comment_form_after_fields' or 'comment_form_logged_in_after'
	 * are not included to the theme comments form template
	 */
	add_action('comment_form', 'micaptcha_comment_form');
	add_filter('preprocess_comment', 'micaptcha_comment_post');
}

// Function to include captcha for comments form
function micaptcha_comment_form() {
	if (micaptcha_allowlist()) {
		echo MICAPTCHA_WHITELIST;
	}
	else {
		if (is_user_logged_in() && get_option('micaptcha_registered') === 'yes') {
			return true;
		}
		echo MICAPTCHA_CONTENT.MICAPTCHA_INPUT;
	}
	return true;
}

function micaptcha_comment_form_wp3() {
	remove_action('comment_form', 'micaptcha_comment_form');
	micaptcha_comment_form();
	return true;
}

// Function to check captcha posted with the comment
function micaptcha_comment_post($comment) {
	if (is_user_logged_in() && get_option('micaptcha_registered') === 'yes') {
		// Skip capthca
		return $comment;
	}

	// Skip captcha for comment replies from the admin menu
	if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'replyto-comment' &&
		(check_ajax_referer('replyto-comment', '_ajax_nonce', false) || check_ajax_referer('replyto-comment', '_ajax_nonce-replyto-comment', false))) {
		return $comment;
	}

	// Skip captcha for trackback or pingback
	if ($comment['comment_type'] != '' && $comment['comment_type'] != 'comment') {
		return $comment;
	}
	if (micaptcha_validate()) {
		wp_die(__('<strong>ERROR</strong>: ', 'mimi-captcha').micaptcha_validate(), __('Comment Submission Failure', 'mimi-captcha'), ['response' => 200, 'back_link' => true]);
	}
	return $comment;
}
?>
