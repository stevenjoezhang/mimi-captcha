<?php
/* Function to configure Mimi Captcha for Wordpress */
function micaptcha_general_options() {
?>
<div class="wrap">
	<h1>Mimi Captcha</h1>
	<div class="notice notice-info is-dismissible"><p><strong><?php _e('Thank you for using Mimi Captcha. Remember to save changes manually after changing settings.</strong><br/>Visit our official website <a href="https://galaxymimi.com">Galaxy Mimi</a> for more infomation.', 'micaptcha'); ?></p></div>
<?php
	if (!current_user_can('manage_options')) return;
	//Display only for those who can actually deactivate plugins.
	$mi_options = array(
		'type' => array('alphanumeric', 'alphabets', 'numbers', 'chinese', 'math'),
		'letters' => array('capital', 'small', 'capitalsmall'),
		'case_sensitive' => array('sensitive', 'insensitive'),
		'total_no_of_characters' => array('2', '3', '4', '5', '6'),
		'timeout_time' => array('30', '60', '120', '300', '600', '0'),
		'loading_mode' => array('default', 'onload', 'oninput'),
		'login' => array('yes', 'no'),
		'register' => array('yes', 'no'),
		'lost' => array('yes', 'no'),
		'comments' => array('yes', 'no'),
		'registered' => array('yes', 'no')
	);
	if (isset($_POST['submit']) && check_admin_referer(plugin_basename(__FILE__), 'micaptcha_settings_nonce')) {
?>
	<div id="message" class="updated fade">
		<p>
			<strong><?php _e('Options saved.', 'micaptcha'); ?></strong>
		</p>
	</div>
<?php
		foreach ($mi_options as $mi_option => $mi_value) {
			if (isset($_POST[$mi_option])) {
				if (in_array($_POST[$mi_option], $mi_value, true)) { //Validate POST calls
					$mi_index = array_search($_POST[$mi_option], $mi_value, true);
					if (isset($mi_value[$mi_index])) update_option('micaptcha_'.$mi_option, $mi_value[$mi_index]);
					//update_option() function receives $mi_value[$mi_index] as the second parameter, which is safe
				}
				else {
					update_option('micaptcha_'.$mi_option, $mi_value[0]);
				}
			}
		}
	}
	$mi_opt = array();
	foreach ($mi_options as $mi_option => $mi_value) {
		$mi_opt[$mi_option] = get_option('micaptcha_'.$mi_option);
	}
?>
	<form method="post" action="" id="micaptcha">
		<?php wp_nonce_field(plugin_basename(__FILE__), 'micaptcha_settings_nonce');//?>
		<style>
			#micaptcha tr p {
				float: left;
				margin-right: 25px;
			}
		</style>
		<h2><?php _e('Configuration', 'micaptcha'); ?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Captcha type', 'micaptcha'); ?></th>
				<td>
					<select name="type">
						<option value="alphanumeric" <?php if ($mi_opt['type'] == 'alphanumeric') echo 'selected="selected"'; ?>><?php _e('Alphanumeric', 'micaptcha'); ?></option>
						<option value="alphabets" <?php if ($mi_opt['type'] == 'alphabets') echo 'selected="selected"'; ?>><?php _e('Alphabets', 'micaptcha'); ?></option>
						<option value="numbers" <?php if ($mi_opt['type'] == 'numbers') echo 'selected="selected"'; ?>><?php _e('Numbers', 'micaptcha'); ?></option>
						<option value="chinese" <?php if ($mi_opt['type'] == 'chinese') echo 'selected="selected"'; ?>><?php _e('Chinese Chars', 'micaptcha'); ?></option>
						<option value="math" <?php if ($mi_opt['type'] == 'math') echo 'selected="selected"'; ?>><?php _e('Math Captcha', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha letters type', 'micaptcha'); ?></th>
				<td>
					<select name="letters">
						<option value="capital" <?php if ($mi_opt['letters'] == 'capital') echo 'selected="selected"'; ?>><?php _e('Capital letters only', 'micaptcha'); ?></option>
						<option value="small" <?php if ($mi_opt['letters'] == 'small') echo 'selected="selected"'; ?>><?php _e('Small letters only', 'micaptcha'); ?></option>
						<option value="capitalsmall" <?php if ($mi_opt['letters'] == 'capitalsmall') echo 'selected="selected"'; ?>><?php _e('Capital & Small letters', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Case sensitive', 'micaptcha'); ?></th>
				<td>
					<select name="case_sensitive">
						<option value="sensitive" <?php if ($mi_opt['case_sensitive'] == 'sensitive') echo 'selected="selected"'; ?>><?php _e('Sensitive', 'micaptcha'); ?></option>
						<option value="insensitive" <?php if ($mi_opt['case_sensitive'] == 'insensitive') echo 'selected="selected"'; ?>><?php _e('Insensitive', 'micaptcha'); ?></option>
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
							if ($mi_opt['total_no_of_characters'] == $i) echo 'selected="selected"';
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
						<option value="30" <?php if ($mi_opt['timeout_time'] == 30) echo 'selected="selected"'; ?>><?php _e('30 seconds', 'micaptcha'); ?></option>
						<option value="60" <?php if ($mi_opt['timeout_time'] == 60) echo 'selected="selected"'; ?>><?php _e('1 min', 'micaptcha'); ?></option>
						<option value="120" <?php if ($mi_opt['timeout_time'] == 120) echo 'selected="selected"'; ?>><?php _e('2 min', 'micaptcha'); ?></option>
						<option value="300" <?php if ($mi_opt['timeout_time'] == 300) echo 'selected="selected"'; ?>><?php _e('5 min', 'micaptcha'); ?></option>
						<option value="600" <?php if ($mi_opt['timeout_time'] == 600) echo 'selected="selected"'; ?>><?php _e('10 min', 'micaptcha'); ?></option>
						<option value="0" <?php if ($mi_opt['timeout_time'] == 0) echo 'selected="selected"'; ?>><?php _e('Unlimited', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha loading mode', 'micaptcha'); ?></th>
				<td>
					<select name="loading_mode">
						<option value="default" <?php if ($mi_opt['loading_mode'] == 'default') echo 'selected="selected"'; ?>><?php _e('Default', 'micaptcha'); ?></option>
						<option value="onload" <?php if ($mi_opt['loading_mode'] == 'onload') echo 'selected="selected"'; ?>><?php _e('On page load', 'micaptcha'); ?></option>
						<option value="oninput" <?php if ($mi_opt['loading_mode'] == 'oninput') echo 'selected="selected"'; ?>><?php _e('On user input', 'micaptcha'); ?></option>
					</select>
				</td>
			</tr>
		</table>

		<h2><?php _e('Captcha Display Options', 'micaptcha'); ?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Login form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="login" value="yes" <?php if ($mi_opt['login'] == 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="login" value="no" <?php if ($mi_opt['login'] === false || $mi_opt['login'] == 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Register form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="register" value="yes" <?php if ($mi_opt['register'] == 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="register" value="no" <?php if ($mi_opt['register'] === false || $mi_opt['register'] == 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Lost Password form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="lost" value="yes" <?php if ($mi_opt['lost'] == 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="lost" value="no" <?php if ($mi_opt['lost'] === false || $mi_opt['lost'] == 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Comments form', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="comments" value="yes" <?php if ($mi_opt['comments'] == 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="comments" value="no" <?php if ($mi_opt['comments'] === false || $mi_opt['comments'] == 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Hide Captcha for logged in users', 'micaptcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="registered" value="yes" <?php if ($mi_opt['registered'] === false || $mi_opt['registered'] == 'yes') echo 'checked="checked"'; ?>/><?php _e('Yes', 'micaptcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="registered" value="no" <?php if ($mi_opt['registered'] == 'no') echo 'checked="checked"'; ?>/><?php _e('No', 'micaptcha'); ?>
						</label>
					</p>
				</td>
			</tr>
		</table>

		<h2><?php _e('Captcha Fonts', 'micaptcha'); ?></h2>
		<p><?php _e('You can upload fonts (.ttf) to /wp-content/plugins/mimi-captcha/fonts folder. Fonts will be chosen randomly when generating Captcha.', 'micaptcha'); ?></p>

		<h2><?php _e('Whitelist', 'micaptcha'); ?></h2>
		<p>Coming Soon...</p>

		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>
