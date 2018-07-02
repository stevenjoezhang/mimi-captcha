<?php
/* Function to configure Mimi Captcha for Wordpress */
function micaptcha_general_options() {
?>
<div class="wrap">
	<h1><?php _e('CAPTCHA', 'micaptcha'); ?></h1>
	<div class="notice notice-info is-dismissible"><p><strong><?php _e('Thank you for using Mimi Captcha. Remember to save changes manually after changing settings.</strong><br/>Visit our official website <a href="https://galaxymimi.com">Galaxy Mimi</a> for more infomation.', 'micaptcha'); ?></p></div>
<?php
if (isset($_POST['submit'])) {
?>
	<div id="message" class="updated fade">
		<p>
			<strong><?php _e('Options saved.', 'micaptcha'); ?></strong>
		</p>
	</div>
<?php
	$mi_options = array(
		'type' => array('alphanumeric', 'alphabets', 'numbers', 'chinese', 'math'),
		'letters' => array('capital', 'small', 'capitalsmall'),
		'case_sensitive' => array('sensitive', 'insensitive'),
		'total_no_of_characters' => array(2, 3, 4, 5, 6),
		'timeout_time' => array(30, 60, 120, 300, 600, 0),
		'loading_mode' => array('default', 'onload', 'oninput'),
		'login' => array('yes', 'no'),
		'register' => array('yes', 'no'),
		'lost' => array('yes', 'no'),
		'comments' => array('yes', 'no'),
		'registered' => array('yes', 'no')
	);
	foreach ($mi_options as $mi_option => $mi_value) {
		if (isset($_POST[$mi_option])) {
			if (in_array($_POST[$mi_option], $mi_value)) { //Validate POST calls
				update_option('micaptcha_'.$mi_option, $_POST[$mi_option]);
			}
			else {
				update_option('micaptcha_'.$mi_option, $mi_value[0]);
			}
		}
	}
}
$c_type = get_option('micaptcha_type');
$c_letters = get_option('micaptcha_letters');
$c_case_sensitive = get_option('micaptcha_case_sensitive');
$c_total_no_of_characters = get_option('micaptcha_total_no_of_characters');
$c_timeout_time = get_option('micaptcha_timeout_time');
$c_loading_mode = get_option('micaptcha_loading_mode');
$c_login = get_option('micaptcha_login');
$c_register = get_option('micaptcha_register');
$c_lost = get_option('micaptcha_lost');
$c_comments = get_option('micaptcha_comments');
$c_registered = get_option('micaptcha_registered');

?>
	<form method="post" action="" id="micaptcha">
		<style>
			#micaptcha p {
				float: left;
				margin-right: 25px;
			}
		</style>
		<h3><?php _e('Configuration', 'micaptcha'); ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Captcha type', 'micaptcha'); ?></th>
				<td>
					<select name="type">
						<option value="alphanumeric" <?php if ($c_type == 'alphanumeric') echo 'selected="selected"'; ?>><?php _e('Alphanumeric', 'micaptcha'); ?></option>
						<option value="alphabets" <?php if ($c_type == 'alphabets') echo 'selected="selected"'; ?>><?php _e('Alphabets', 'micaptcha'); ?></option>
						<option value="numbers" <?php if ($c_type == 'numbers') echo 'selected="selected"'; ?>><?php _e('Numbers', 'micaptcha'); ?></option>
						<option value="chinese" <?php if ($c_type == 'chinese') echo 'selected="selected"'; ?>><?php _e('Chinese Chars', 'micaptcha'); ?></option>
						<option value="math" <?php if ($c_type == 'math') echo 'selected="selected"'; ?>><?php _e('Math Captcha', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha letters type', 'micaptcha'); ?></th>
				<td>
					<select name="letters">
						<option value="capital" <?php if ($c_letters == 'capital') echo 'selected="selected"'; ?>><?php _e('Capital letters only', 'micaptcha'); ?></option>
						<option value="small" <?php if ($c_letters == 'small') echo 'selected="selected"'; ?>><?php _e('Small letters only', 'micaptcha'); ?></option>
						<option value="capitalsmall" <?php if ($c_letters == 'capitalsmall') echo 'selected="selected"'; ?>><?php _e('Capital & Small letters', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Case sensitive', 'micaptcha'); ?></th>
				<td>
					<select name="case_sensitive">
						<option value="sensitive" <?php if ($c_case_sensitive == 'sensitive') echo 'selected="selected"'; ?>><?php _e('Sensitive', 'micaptcha'); ?></option>
						<option value="insensitive" <?php if ($c_case_sensitive == 'insensitive') echo 'selected="selected"'; ?>><?php _e('Insensitive', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Total number of Captcha characters', 'micaptcha'); ?></th>
				<td>
					<select name="total_no_of_characters">
					<?php 
						for ($i = 2; $i <= 6; $i++) {
							print '<option value="'.$i.'" ';
							if ($c_total_no_of_characters == $i) echo 'selected="selected"';
							print '>'.$i.'</option>';
						}
					?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha timeout', 'micaptcha'); ?></th>
				<td>
					<select name="timeout_time">
						<option value="30" <?php if ($c_timeout_time == 30) echo 'selected="selected"'; ?>><?php _e('30 seconds', 'micaptcha'); ?></option>
						<option value="60" <?php if ($c_timeout_time == 60) echo 'selected="selected"'; ?>><?php _e('1 min', 'micaptcha'); ?></option>
						<option value="120" <?php if ($c_timeout_time == 120) echo 'selected="selected"'; ?>><?php _e('2 min', 'micaptcha'); ?></option>
						<option value="300" <?php if ($c_timeout_time == 300) echo 'selected="selected"'; ?>><?php _e('5 min', 'micaptcha'); ?></option>
						<option value="600" <?php if ($c_timeout_time == 600) echo 'selected="selected"'; ?>><?php _e('10 min', 'micaptcha'); ?></option>
						<option value="0" <?php if ($c_timeout_time == 0) echo 'selected="selected"'; ?>><?php _e('Unlimited', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha loading mode', 'micaptcha'); ?></th>
				<td>
					<select name="loading_mode">
						<option value="default" <?php if ($c_loading_mode == 'default') echo 'selected="selected"'; ?>><?php _e('Default', 'micaptcha'); ?></option>
						<option value="onload" <?php if ($c_loading_mode == 'onload') echo 'selected="selected"'; ?>><?php _e('On page load', 'micaptcha'); ?></option>
						<option value="oninput" <?php if ($c_loading_mode == 'oninput') echo 'selected="selected"'; ?>><?php _e('On user input', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
		</table>
		<h3><?php _e('Captcha display Options', 'micaptcha'); ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Enable Captcha for Login form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="login" value="yes" <?php if ($c_login === false || $c_login == 'yes') echo 'checked="checked"'; ?> /><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="login" value="no" <?php if ($c_login == 'no') echo 'checked="checked"'; ?> /><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Enable Captcha for Register form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="register" value="yes" <?php if ($c_register === false || $c_login == 'yes') echo 'checked="checked"'; ?> /><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="register" value="no" <?php if ($c_register == 'no') echo 'checked="checked"'; ?> /><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Enable Captcha for Lost Password form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="lost" value="yes" <?php if ($c_lost === false || $c_login == 'yes') echo 'checked="checked"'; ?> /><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="lost" value="no" <?php if ($c_lost == 'no') echo 'checked="checked"'; ?> /><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Enable Captcha for Comments form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="comments" value="yes" <?php if ($c_comments === false || $c_login == 'yes') echo 'checked="checked"'; ?> /><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="comments" value="no" <?php if ($c_comments == 'no') echo 'checked="checked"'; ?> /><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Hide Captcha for logged in users', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="registered" value="yes" <?php if ($c_registered === false || $c_registered == 'yes') echo 'checked="checked"'; ?> /><?php _e('Yes', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="registered" value="no" <?php if ($c_registered == 'no') echo 'checked="checked"'; ?> /><?php _e('No', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
		</table>
		<h3><?php _e('Captcha Fonts', 'micaptcha'); ?></h3>
		<h4><?php _e('You can upload fonts (.ttf) to /wp-content/plugins/mimi-captcha/fonts folder. Fonts will be chosen randomly when generating Captcha.', 'micaptcha'); ?></h4>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}

?>
