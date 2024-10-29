<div class="wrap">
	<h1><?php _e('Configuration Yandex.Metrica', 'ab-metrika') ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'ab_metrica_options_group' ); ?>
		<?php settings_errors(); ?>
		<table class="form-table">
			<tr valign="top">
				<td colspan="2">
					<?php _e('To display data, you must configure the following:', 'ab-metrika') ?>
					<ol>
						<li><a class="button" target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&amp;client_id=<?php echo $this->yandex_metrika_client_id; ?>"><?php _e('Allow access to the plugin and get the Token', 'ab-metrika') ?></a></li>
						<li><?php _e('Enter the Token and click "Save"', 'ab-metrika') ?></li>
						<li><?php _e('Choose the counter that need to display, and then click "Save"', 'ab-metrika') ?></li>
					</ol>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Yandex.metrica', 'ab-metrika') ?> <?php _e('Token', 'ab-metrika') ?></th>
				<td>
					<input type="text" name="yandex_metrika_token"  class="large-text code" value="<?php echo htmlspecialchars(get_option('yandex_metrika_token')); ?>"  placeholder="<?php _e('Yandex.metrica', 'ab-metrika') ?> <?php _e('Token', 'ab-metrika') ?>" />
				</td>
			</tr>
			<?php if ('' != get_option('yandex_metrika_token')) : ?>
				<tr valign="top">
					<th scope="row"><?php _e('Counter', 'ab-metrika') ?></th>
					<td><?php echo $this->get_all_counters_select(); ?></td>
				</tr>
			<?php endif; ?>
		</table>
		<?php submit_button(); ?>

</form>

</div>
