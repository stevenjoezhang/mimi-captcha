<?php
/**
 * Plugin Name: Mimi Captcha
 * Plugin URI: https://github.com/stevenjoezhang/mimi-captcha
 * Description: 在WordPress登陆、评论表单中加入验证码功能，支持字母、数字、中文和算术验证码。
 * Version: 0.0.1
 * Author: Shuqiao Zhang
 * Author URI: https://zsq.im
 * Text Domain: micaptcha
 * Domain Path: /languages
 * License: GPL2
 */

load_plugin_textdomain('micaptcha', false, dirname(plugin_basename(__FILE__)).'/languages');
define('MICAPTCHA_DIR_URL', plugin_dir_url(__FILE__));
//define('MICAPTCHA_DIR', dirname(__FILE__));
define('MICAPTCHA_CONTENT', '<label><b>'.__('Captcha', 'micaptcha').' </b></label>
		<span class="required">*</span>
		<div style="clear: both;"></div>
		<img alt="captcha code" id="micaptcha" src="'.MICAPTCHA_DIR_URL.'default.png"/>
		<div style="clear: both;"></div>');
define('MICAPTCHA_INPUT', '<label>'.__('Click the image to refresh', 'micaptcha').'</label>
		<input id="captcha_code" name="captcha_code" autocomplete="off" size="15" type="text" placeholder="'.__('Type the Captcha above', 'micaptcha').'"/>
		</p>');
switch (get_option('micaptcha_loading_mode')) {
	case 'onload':
		define('MICAPTCHA_SCRIPT', '<script>
			window.addEventListener("load", function() {
				var captcha = document.getElementById("micaptcha");
				captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand='.mt_rand().'";
				captcha.onclick = function() {
					captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
				}
			});
		</script>');
		break;
	case 'oninput':
		define('MICAPTCHA_SCRIPT', '<script>
			var captcha = document.getElementById("micaptcha"),
				MiCaptchaFlag = false;		
			function loadMiCaptcha() {
				if (MiCaptchaFlag) return;
				MiCaptchaFlag = true;
				captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand='.mt_rand().'";
				captcha.onclick = function() {
					captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
				}
			}
			window.addEventListener("load", function() {
				var input = document.getElementsByTagName("input"),
					textarea = document.getElementsByTagName("textarea");
				for (var i = 0; i < input.length; i++) {
					input[i].addEventListener("input", loadMiCaptcha);
				}
				for (var i = 0; i < textarea.length; i++) {
					textarea[i].addEventListener("input", loadMiCaptcha);
				}
			});
			captcha.onclick = loadMiCaptcha;
		</script>');
		break;
	default:
		define('MICAPTCHA_SCRIPT', '<script>
			var captcha = document.getElementById("micaptcha");
			captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand='.mt_rand().'";
			captcha.onclick = function() {
				captcha.src = "'.MICAPTCHA_DIR_URL.'captcha.php?rand=" + Math.random();
			}
		</script>');
		break;
}

require_once 'general-options.php';

/* Hook to store the plugin status */
register_activation_hook(__FILE__, 'micaptcha_enabled');
register_deactivation_hook(__FILE__, 'micaptcha_disabled');

/* Hook to initalize the admin menu */
add_action('admin_menu', 'micaptcha_admin_menu');
/* Hook to initialize sessions */
//add_action('init', 'micaptcha_init_sessions');
/* Hook to initialize admin notices */
add_action('admin_notices', 'micaptcha_admin_notice');

function micaptcha_enabled() {
	update_option('micaptcha_status', 'enabled');
}
function micaptcha_disabled() {
	update_option('micaptcha_status', 'disabled');
}

/* To add the menus in the admin section */
function micaptcha_admin_menu() {
	add_options_page(
		__('Mimi Captcha'),
		__('Mimi Captcha'),
		'manage_options',
		'micaptcha_slug',
		'micaptcha_general_options'
	);
}

function micaptcha_init_sessions() {
	if (!session_id()) {
		session_start();
	}
	$_SESSION['captcha_type'] = get_option('micaptcha_type');
	$_SESSION['captcha_letters'] = get_option('micaptcha_letters');
	$_SESSION['total_no_of_characters'] = get_option('micaptcha_total_no_of_characters');
}

function micaptcha_admin_notice() {
	if (substr($_SERVER['PHP_SELF'], -11) == 'plugins.php' && function_exists('admin_url') && !get_option('micaptcha_type')) {
		echo '<div class="notice notice-warning"><p><strong>'.sprintf(__('Mimi Captcha is not configured yet. Please go to the <a href="%s">plugin admin page</a> to change settings.', 'micaptcha'), admin_url('options-general.php?page=micaptcha_slug')).'</strong></p></div>';
	}
}

function micaptcha_validate() {
	micaptcha_init_sessions();

	if (!isset($_SESSION['captcha_time']) || !isset($_SESSION['captcha_code']) || !isset($_REQUEST['captcha_code'])) {
		return __('Incorrect Captcha confirmation!', 'micaptcha');
	}
	//Captcha timeout
	if (get_option('micaptcha_timeout_time') && get_option('micaptcha_timeout_time') != '0') {
		if (time() - intval($_SESSION['captcha_time']) >= intval(get_option('micaptcha_timeout_time'))) {
			return __('Captcha timeout!', 'micaptcha');
		}
	}
	//If captcha is blank - add error
	if ($_REQUEST['captcha_code'] == "") {
		return __('Captcha cannot be empty. Please complete the Captcha.', 'micaptcha');
	}
	if ($_SESSION['captcha_code'] == $_REQUEST['captcha_code']) return false;
	if (get_option('micaptcha_case_sensitive') == 'insensitive') {
		if (strtoupper($_SESSION['captcha_code']) == strtoupper($_REQUEST['captcha_code'])) return false;
	}
	//Captcha was not matched
	return __('Incorrect Captcha confirmation!', 'micaptcha');
}

/* Captcha for login authentication starts here */
$login_captcha = get_option('micaptcha_login');
if ($login_captcha == 'yes') {
	add_action('login_form', 'micaptcha_login');
	add_filter('login_errors', 'micaptcha_login_errors');
	add_filter('login_redirect', 'micaptcha_login_redirect', 10, 3);
}

/* Function to include captcha for login form */
function micaptcha_login() {
	micaptcha_init_sessions();
	echo '<p class="login-form-captcha">'.MICAPTCHA_CONTENT.MICAPTCHA_SCRIPT;

	/* Will retrieve the get varibale and prints a message from url if the captcha is wrong */
	if (isset($_GET['captcha']) && $_GET['captcha'] == 'confirm_error') {
		echo '<label style="color: #FF0000;" id="capt_err">'.$_SESSION['captcha_error'].'</label><div style="clear: both;"></div>';
		$_SESSION['captcha_error'] = '';
	}

	echo MICAPTCHA_INPUT;
	return true;
}

/* Hook to find out the errors while logging in */
function micaptcha_login_errors($errors) {
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {
		return $errors;
	}
	if (micaptcha_validate()) {
		return $errors.'<label id="capt_err" for="captcha_code_error">'.micaptcha_validate().'</label>';
	}
	return $errors;
}

/* Hook to redirect after captcha confirmation */
function micaptcha_login_redirect($url) {
	micaptcha_init_sessions();
	/* Captcha mismatch */
	if (isset($_SESSION['captcha_code']) && isset($_REQUEST['captcha_code']) && micaptcha_validate()) {
		$_SESSION['captcha_error'] = micaptcha_validate();
		wp_clear_auth_cookie();
		return $_SERVER["REQUEST_URI"]."/?captcha='confirm_error'";
	}
	/* Captcha match: take to the admin panel */
	else {
		return home_url('/wp-admin/');
	}
}

/* Captcha for Register form starts here */
$register_captcha = get_option('micaptcha_register');
if ($register_captcha == 'yes') {
	add_action('register_form', 'micaptcha_register');
	add_action('register_post', 'micaptcha_register_post', 10, 3);
	add_action('signup_extra_fields', 'micaptcha_register');
	add_filter('wpmu_validate_user_signup', 'micaptcha_register_validate');
}

/* Function to include captcha for register form */
function micaptcha_register($default) {
	micaptcha_init_sessions();
	echo '<p class="register-form-captcha">	'.MICAPTCHA_CONTENT.MICAPTCHA_SCRIPT.MICAPTCHA_INPUT;
	return true;
}

/* This function checks captcha posted with registration */
function micaptcha_register_post($login, $email, $errors) {
	if (micaptcha_validate()) {
		$errors->add('captcha_wrong', '<strong>'.__('ERROR', 'micaptcha').'</strong>'.__(': ', 'micaptcha').micaptcha_validate());
		return $errors;
	}
}

function micaptcha_register_validate($results) {
	if (micaptcha_validate()) {
		$results['errors']->add('captcha_wrong', '<strong>'.__('ERROR', 'micaptcha').'</strong>'.__(': ', 'micaptcha').micaptcha_validate());
		return $results;
	}
}

/* Captcha for lost password form starts here */
$lost_captcha = get_option('micaptcha_lost');
if ($lost_captcha == 'yes') {
	add_action('lostpassword_form', 'micaptcha_lostpassword');
	add_action('lostpassword_post', 'micaptcha_lostpassword_post', 10, 3);
}

/* Function to include captcha for lost password form */
function micaptcha_lostpassword($default) {
	micaptcha_init_sessions();
	echo '<p class="lost-form-captcha">'.MICAPTCHA_CONTENT.MICAPTCHA_SCRIPT.MICAPTCHA_INPUT;	
}

function micaptcha_lostpassword_post() {
	if (isset($_REQUEST['user_login']) && "" == $_REQUEST['user_login']) {
		return;
	}
	if (!micaptcha_validate()) {
		wp_die(__('ERROR', 'micaptcha').__(': ', 'micaptcha').micaptcha_validate().' '.__('Press your browser\'s back button and try again.', 'micaptcha'));
	}
}

/* Captcha for Comments starts here */
$comment_captcha = get_option('micaptcha_comments');
if ($comment_captcha == 'yes') {
	global $wp_version;
	if (version_compare($wp_version, '3', '>=')) { //wp 3.0 +
		add_action('comment_form_after_fields', 'micaptcha_comment_form_wp3', 1);
		add_action('comment_form_logged_in_after', 'micaptcha_comment_form_wp3', 1);
	}
	//for WP before WP 3.0
	add_action('comment_form', 'micaptcha_comment_form');	
	add_filter('preprocess_comment', 'micaptcha_comment_post');
}

/* Function to include captcha for comments form */
function micaptcha_comment_form() {
	micaptcha_init_sessions();
	$c_registered = get_option('micaptcha_registered');
	if (is_user_logged_in() && $c_registered == 'yes') {
		return true;
	}
	echo '<p class="comment-form-captcha">'.MICAPTCHA_CONTENT.MICAPTCHA_SCRIPT.MICAPTCHA_INPUT;
	return true;
}

/* Function to include captcha for comments form > wp3 */
function micaptcha_comment_form_wp3() {
	micaptcha_init_sessions();
	$c_registered = get_option('micaptcha_registered');
	if (is_user_logged_in() && $c_registered == 'yes') {
		return true;
	}
	echo '<p class="comment-form-captcha">'.MICAPTCHA_CONTENT.MICAPTCHA_SCRIPT.MICAPTCHA_INPUT;
	remove_action('comment_form', 'micaptcha_comment_form');
	return true;
}

/* Function to check captcha posted with the comment */
function micaptcha_comment_post($comment) {
	$c_registered = get_option('micaptcha_registered');
	if (is_user_logged_in() && $c_registered == 'yes') {
		return $comment;
	}
	/* Added for compatibility with WP Wall plugin */
	/* This does NOT add CAPTCHA to WP Wall plugin, */
	/* It just prevents the "Error: You did not enter a Captcha phrase." when submitting a WP Wall comment */
	if (function_exists('WPWall_Widget') && isset($_REQUEST['wpwall_comment'])) {
		/* Skip capthca */
		return $comment;
	}
	//Skip captcha for comment replies from the admin menu
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'replyto-comment' &&
		(check_ajax_referer('replyto-comment', '_ajax_nonce', false) || check_ajax_referer('replyto-comment', '_ajax_nonce-replyto-comment', false))) {
		//Skip capthca
		return $comment;
	}

	//Skip captcha for trackback or pingback
	if ($comment['comment_type'] != '' && $comment['comment_type'] != 'comment') {
		//Skip captcha
		return $comment;
	}
	if (micaptcha_validate()) {
		wp_die(__('ERROR', 'micaptcha').__(': ', 'micaptcha').micaptcha_validate().' '.__('Press your browser\'s back button and try again.', 'micaptcha'));
	}
	return $comment;
}
?>
