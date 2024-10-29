<div class="wrap">
	<h1><?php echo get_admin_page_title() ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'ab_metrica_options_group' ); ?>
		<?php settings_errors(); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Yandex.Webmaster', 'ab-metrika' ); ?></th>
				<td>
					<input type="text" name="yandex_webmaster"  class="large-text code" value="<?php echo htmlspecialchars(get_option('yandex_webmaster')); ?>"  placeholder="<?php _e('Yandex.Webmaster HTML verification code', 'ab-metrika' ); ?>" />
					<p><i><?php _e( 'Insert code', 'ab-metrika' ); ?>: <code>&lt;meta name="yandex-verification" content="xxxxxxxxxxxxxxxx" /&gt;</code></i></p>
					<p>
						<a href="https://webmaster.yandex.ru/sites/" target="_blank"><?php _e( 'My sites', 'ab-metrika' ); ?></a> |
						<a href="https://webmaster.yandex.ru/sites/add/" target="_blank"><?php _e( 'Add site', 'ab-metrika' ); ?></a>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'Yandex.Metrica', 'ab-metrika' ); ?></th>
				<td>
					<textarea name="yandex_metrika" type="text" rows="10" class="large-text code" placeholder="<?php _e( 'HTML code counter', 'ab-metrika' ); ?>"><?php echo get_option('yandex_metrika'); ?></textarea>
					<p>
						<a href="https://metrika.yandex.ru/list/" target="_blank"><?php _e( 'My counters', 'ab-metrika' ); ?></a> |
						<a href="https://metrika.yandex.ru/add/" target="_blank"><?php _e( 'Add counter', 'ab-metrika' ); ?></a>
					</p>
				</td>
			</tr>

			<?php
				$yandex_metrika_position = get_option('yandex_metrika_position');
				if (empty($yandex_metrika_position) || ((0 > $yandex_metrika_position) && (3 <= $yandex_metrika_position))) {
					$yandex_metrika_position = 0;
				}

			?>
			<tr valign="top">
				<th scope="row"><?php _e( 'Location counter', 'ab-metrika' ); ?> <?php _e( 'Yandex.Metrica', 'ab-metrika' ); ?></th>
				<td>
					<fieldset>
						<?php $checked = ((0 == $yandex_metrika_position)?' checked':''); ?>
						<label><input type="radio" name="yandex_metrika_position" value="0"<?php echo $checked;?>><?php _e('Towards the end of the page', 'ab-metrika' ); ?></label><br>
						<?php $checked = ((1 == $yandex_metrika_position)?' checked':''); ?>
						<label><input type="radio" name="yandex_metrika_position" value="1"<?php echo $checked;?>><?php _e('Closer to the top of the page', 'ab-metrika' ); ?></label><br>
						<?php $checked = ((2 == $yandex_metrika_position)?' checked':''); ?>
						<label><input type="radio" name="yandex_metrika_position" value="2"<?php echo $checked;?>><?php _e('Custom place', 'ab-metrika' ); ?></label><br>
					</fieldset>
					<p>
						<?php _e('<i>Select the place of installation of the counter: at the beginning of page, end of page or in custom place. For installation in custom place use shortcode </i><code>[ab-metrika-counter id="metrika"]</code><i> or in the theme file of the website </i><code>&lt;?php echo do_shortcode(\'[ab-metrika-counter id="metrika"]\'); ?&gt;</code>', 'ab-metrika' ); ?>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'Google Search Console', 'ab-metrika' ); ?></th>
				<td><input type="text" name="google_search_console" class="large-text code" value="<?php echo htmlspecialchars(get_option('google_search_console')); ?>" placeholder="<?php _e( 'Google Search Console HTML verification code', 'ab-metrika' ); ?>"  />
					<p><i><?php _e( 'Insert code', 'ab-metrika' ); ?>: <code>&lt;meta name="google-site-verification" content="xxx....xx" /&gt;</code></i></p>
					<p><a href="https://www.google.com/webmasters/tools/" target="_blank"><?php _e( 'My sites', 'ab-metrika' ); ?></a></p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'Google Analytics', 'ab-metrika' ); ?></th>
				<td>
					<textarea name="google_analytics" type="text" rows="10" class="large-text code" placeholder="<?php _e( 'HTML code counter', 'ab-metrika' ); ?>"><?php echo get_option('google_analytics'); ?></textarea>
					<p><a href="https://www.google.com/analytics/web/#home/" target="_blank"><?php _e( 'Google Analytics', 'ab-metrika' ); ?></a></p>
				</td>
			</tr>

			<?php
				$google_analytics_position = get_option('google_analytics_position');
				if (empty($google_analytics_position) || ((0 > $google_analytics_position) && (3 <= $google_analytics_position))) {
					$google_analytics_position = 0;
				}

			?>
			<tr valign="top">
				<th scope="row"><?php _e( 'Location counter', 'ab-metrika' ); ?> <?php _e( 'Google Analytics', 'ab-metrika' ); ?></th>
				<td>
					<fieldset>
						<?php $checked = ((0 == $google_analytics_position)?' checked':''); ?>
						<label><input type="radio" name="google_analytics_position" value="0"<?php echo $checked;?>><?php _e('Towards the end of the page', 'ab-metrika' ); ?></label><br>
						<?php $checked = ((1 == $google_analytics_position)?' checked':''); ?>
						<label><input type="radio" name="google_analytics_position" value="1"<?php echo $checked;?>><?php _e('Closer to the top of the page', 'ab-metrika' ); ?></label><br>
						<?php $checked = ((2 == $google_analytics_position)?' checked':''); ?>
						<label><input type="radio" name="google_analytics_position" value="2"<?php echo $checked;?>><?php _e('Custom place', 'ab-metrika' ); ?></label><br>
					</fieldset>
					<p>
						<?php _e('<i>Select the place of installation of the counter: at the beginning of page, end of page or in custom place. For installation in custom place use shortcode </i><code>[ab-metrika-counter id="analytics"]</code><i> or in the theme file of the website </i><code>&lt;?php echo do_shortcode(\'[ab-metrika-counter id="analytics"]\'); ?&gt;</code>', 'ab-metrika' ); ?>
					</p>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>

</form>

</div>
