<?php
// Function to configure Mimi Captcha for Wordpress
function micaptcha_general_options() {
	// Display only for those who can actually deactivate plugins
	if (!current_user_can('manage_options')) {
		return;
	}
?>
<div class="wrap">
	<h1>Mimi Captcha</h1>
	<div class="notice notice-info is-dismissible">
		<p>
		<?php printf(__('<strong>Thank you for using Mimi Captcha. Remember to save changes manually after changing settings.</strong><br/>Visit our <a href="%1$s" target="_blank" rel="noopener noreferrer">GitHub Repo</a> for more information.', 'mimi-captcha'), 'https://github.com/stevenjoezhang/mimi-captcha'); ?>
		</p>
	</div>

<?php
	if (!function_exists('gd_info')) {
		echo '<div class="notice notice-error"><p>'.sprintf(__('<strong>ERROR: PHP GD extension is not installed or turned on. Mimi Captcha plugin can not run correctly.</strong><br/>Please see the <a href="%1$s">PHP documentation</a> for more information.', 'mimi-captcha'), 'https://secure.php.net/manual/book.image.php').'</p></div>';
	}

	$mi_options = [
		'type' => ['alphanumeric', 'alphabets', 'numbers', 'chinese', 'math'],
		'letters' => ['capital', 'small', 'capitalsmall'],
		'case_sensitive' => ['sensitive', 'insensitive'],
		'total_no_of_characters' => ['2', '3', '4', '5', '6'],
		'timeout_time' => ['30', '60', '120', '300', '600', '0'],
		'loading_mode' => ['default', 'onload', 'oninput'],
		'use_curve' => ['yes', 'no'],
		'use_noise' => ['yes', 'no'],
		'distort' => ['yes', 'no'],
		'login' => ['yes', 'no'],
		'register' => ['yes', 'no'],
		'password' => ['yes', 'no'],
		'lost' => ['yes', 'no'],
		'comments' => ['yes', 'no'],
		'registered' => ['yes', 'no'],
		'allowlist_ips' => []
		// 'allowlist_usernames' => []
	];
	if (isset($_POST['submit']) && check_admin_referer(plugin_basename(__FILE__), 'micaptcha_settings_nonce')) {
?>
	<div id="message" class="updated fade">
		<p>
			<strong><?php _e('Options saved.', 'mimi-captcha'); ?></strong>
		</p>
	</div>
<?php
		foreach ($mi_options as $mi_option => $mi_value) {
			if (isset($_POST[$mi_option])) {
				if (empty($mi_value)) {
					$data = (!empty($_POST[$mi_option])) ? explode("\n", str_replace("\r", "", stripslashes($_POST[$mi_option]))) : [];
					if (!empty($data)) {
						foreach ($data as $key => $ip) {
							if ('' === $ip) {
								unset($data[$key]);
							}
							else {
								$data[$key] = sanitize_text_field($data[$key]);
							}
						}
					}
					update_option('micaptcha_'.$mi_option, $data);
				}
				else if (in_array($_POST[$mi_option], $mi_value, true)) { // Validate POST calls
					$mi_index = array_search($_POST[$mi_option], $mi_value, true);
					if (isset($mi_value[$mi_index])) {
						update_option('micaptcha_'.$mi_option, $mi_value[$mi_index]);
					}
				}
				else {
					update_option('micaptcha_'.$mi_option, $mi_value[0]);
				}
			}
		}
	}
	$mi_opt = [];
	foreach ($mi_options as $mi_option => $mi_value) {
		$mi_opt[$mi_option] = get_option('micaptcha_'.$mi_option);
	}
	$allowlist_ips = (is_array($mi_opt['allowlist_ips']) && !empty($mi_opt['allowlist_ips'])) ? implode("\n", $mi_opt['allowlist_ips']) : '';
	// $allowlist_usernames = (is_array($mi_opt['allowlist_usernames']) && !empty($mi_opt['allowlist_usernames'])) ? implode("\n", $mi_opt['allowlist_usernames']) : '';
?>
	<form method="post" action="" id="micaptcha">
		<?php wp_nonce_field(plugin_basename(__FILE__), 'micaptcha_settings_nonce'); ?>
		<style>
		#micaptcha tr p {
			float: left;
			margin-right: 25px;
		}
		</style>
		<h2><?php _e('Captcha configuration', 'mimi-captcha'); ?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Captcha type', 'mimi-captcha'); ?></th>
				<td>
					<select name="type">
						<option value="alphanumeric" <?php if ($mi_opt['type'] === 'alphanumeric') echo 'selected="selected"'; ?>><?php _e('Alphanumeric', 'mimi-captcha'); ?></option>
						<option value="alphabets" <?php if ($mi_opt['type'] === 'alphabets') echo 'selected="selected"'; ?>><?php _e('Alphabets', 'mimi-captcha'); ?></option>
						<option value="numbers" <?php if ($mi_opt['type'] === 'numbers') echo 'selected="selected"'; ?>><?php _e('Numbers', 'mimi-captcha'); ?></option>
						<option value="chinese" <?php if ($mi_opt['type'] === 'chinese') echo 'selected="selected"'; ?>><?php _e('Chinese chars', 'mimi-captcha'); ?></option>
						<option value="math" <?php if ($mi_opt['type'] === 'math') echo 'selected="selected"'; ?>><?php _e('Math Captcha', 'mimi-captcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha letters type', 'mimi-captcha'); ?></th>
				<td>
					<select name="letters">
						<option value="capital" <?php if ($mi_opt['letters'] === 'capital') echo 'selected="selected"'; ?>><?php _e('Capital letters only', 'mimi-captcha'); ?></option>
						<option value="small" <?php if ($mi_opt['letters'] === 'small') echo 'selected="selected"'; ?>><?php _e('Small letters only', 'mimi-captcha'); ?></option>
						<option value="capitalsmall" <?php if ($mi_opt['letters'] === 'capitalsmall') echo 'selected="selected"'; ?>><?php _e('Capital & small letters', 'mimi-captcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Case sensitive', 'mimi-captcha'); ?></th>
				<td>
					<select name="case_sensitive">
						<option value="sensitive" <?php if ($mi_opt['case_sensitive'] === 'sensitive') echo 'selected="selected"'; ?>><?php _e('Sensitive', 'mimi-captcha'); ?></option>
						<option value="insensitive" <?php if ($mi_opt['case_sensitive'] === 'insensitive') echo 'selected="selected"'; ?>><?php _e('Insensitive', 'mimi-captcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Total number of Captcha characters', 'mimi-captcha'); ?></th>
				<td>
					<select name="total_no_of_characters">
					<?php
						for ($i = 2; $i <= 6; $i++) {
							echo '<option value="'.$i.'" ';
							if ($mi_opt['total_no_of_characters'] === strval($i)) {
								echo 'selected="selected"';
							}
							echo '>'.$i.'</option>';
						}
					?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha expiration time', 'mimi-captcha'); ?></th>
				<td>
					<select name="timeout_time">
						<option value="30" <?php if ($mi_opt['timeout_time'] === '30') echo 'selected="selected"'; ?>><?php _e('30 seconds', 'mimi-captcha'); ?></option>
						<option value="60" <?php if ($mi_opt['timeout_time'] === '60') echo 'selected="selected"'; ?>><?php _e('1 min', 'mimi-captcha'); ?></option>
						<option value="120" <?php if ($mi_opt['timeout_time'] === '120') echo 'selected="selected"'; ?>><?php _e('2 min', 'mimi-captcha'); ?></option>
						<option value="300" <?php if ($mi_opt['timeout_time'] === '300') echo 'selected="selected"'; ?>><?php _e('5 min', 'mimi-captcha'); ?></option>
						<option value="600" <?php if ($mi_opt['timeout_time'] === '600') echo 'selected="selected"'; ?>><?php _e('10 min', 'mimi-captcha'); ?></option>
						<option value="0" <?php if ($mi_opt['timeout_time'] === '0') echo 'selected="selected"'; ?>><?php _e('Unlimited', 'mimi-captcha'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Captcha loading mode', 'mimi-captcha'); ?></th>
				<td>
					<select name="loading_mode">
						<option value="default" <?php if ($mi_opt['loading_mode'] === 'default') echo 'selected="selected"'; ?>><?php _e('Default', 'mimi-captcha'); ?></option>
						<option value="onload" <?php if ($mi_opt['loading_mode'] === 'onload') echo 'selected="selected"'; ?>><?php _e('On page load', 'mimi-captcha'); ?></option>
						<option value="oninput" <?php if ($mi_opt['loading_mode'] === 'oninput') echo 'selected="selected"'; ?>><?php _e('On user input', 'mimi-captcha'); ?></option>
					</select>
				</td>
			</tr>
		</table>

		<h2><?php _e('Captcha obfuscation settings', 'mimi-captcha'); ?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Adding curved lines', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="use_curve" value="yes" <?php if ($mi_opt['use_curve'] === false || $mi_opt['use_curve'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Yes'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="use_curve" value="no" <?php if ($mi_opt['use_curve'] === 'no') echo 'checked="checked"'; ?>/><?php _e('No'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Adding noise', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="use_noise" value="yes" <?php if ($mi_opt['use_noise'] === false || $mi_opt['use_noise'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Yes'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="use_noise" value="no" <?php if ($mi_opt['use_noise'] === 'no') echo 'checked="checked"'; ?>/><?php _e('No'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Nonlinear distortion', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="distort" value="yes" <?php if ($mi_opt['distort'] === false || $mi_opt['distort'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Yes'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="distort" value="no" <?php if ($mi_opt['distort'] === 'no') echo 'checked="checked"'; ?>/><?php _e('No'); ?>
						</label>
					</p>
				</td>
			</tr>
		</table>

		<h2><?php _e('Captcha display options', 'mimi-captcha'); ?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Login form', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="login" value="yes" <?php if ($mi_opt['login'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'mimi-captcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="login" value="no" <?php if ($mi_opt['login'] === false || $mi_opt['login'] === 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'mimi-captcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Register form', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="register" value="yes" <?php if ($mi_opt['register'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'mimi-captcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="register" value="no" <?php if ($mi_opt['register'] === false || $mi_opt['register'] === 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'mimi-captcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Allow new users to enter a password', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="password" value="yes" <?php if ($mi_opt['password'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Yes'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="password" value="no" <?php if ($mi_opt['password'] === false || $mi_opt['password'] === 'no') echo 'checked="checked"'; ?>/><?php _e('No'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Lost password form', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="lost" value="yes" <?php if ($mi_opt['lost'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'mimi-captcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="lost" value="no" <?php if ($mi_opt['lost'] === false || $mi_opt['lost'] === 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'mimi-captcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Comments form', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="comments" value="yes" <?php if ($mi_opt['comments'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Enable', 'mimi-captcha'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="comments" value="no" <?php if ($mi_opt['comments'] === false || $mi_opt['comments'] === 'no') echo 'checked="checked"'; ?>/><?php _e('Disable', 'mimi-captcha'); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Hide Captcha for logged in users', 'mimi-captcha'); ?></th>
				<td>
					<p>
						<label>
							<input type="radio" name="registered" value="yes" <?php if ($mi_opt['registered'] === false || $mi_opt['registered'] === 'yes') echo 'checked="checked"'; ?>/><?php _e('Yes'); ?>
						</label>
					</p>
					<p>
						<label><input type="radio" name="registered" value="no" <?php if ($mi_opt['registered'] === 'no') echo 'checked="checked"'; ?>/><?php _e('No'); ?>
						</label>
					</p>
				</td>
			</tr>
		</table>

		<h2><?php _e('Captcha fonts', 'mimi-captcha'); ?></h2>
		<p><?php _e('You can upload fonts (.ttf) to /wp-content/plugins/mimi-captcha/fonts folder. Fonts will be chosen randomly when generating Captcha.', 'mimi-captcha'); ?></p>

		<h2><?php _e('Allowlist', 'mimi-captcha'); ?></h2>
		<div>
			<p><?php _e('One IP or IP range (1.2.3.4-5.6.7.8) per line.', 'mimi-captcha'); ?></p>
			<textarea name="allowlist_ips" rows="10" cols="50"><?php echo esc_textarea($allowlist_ips); ?></textarea>
		</div>

		<h2><?php _e('Blocklist', 'mimi-captcha'); ?></h2>
		<p><?php _e('Coming soon...', 'mimi-captcha'); ?></p>

		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>
