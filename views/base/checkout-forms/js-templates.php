<?php
/**
 * JS templates view.
 *
 * @since 2.0.0
 */
defined( 'ABSPATH' ) || exit;

?>
<script type="text/x-template" id="wu-table">

<table class="wp-list-table widefat fixed striped">

	<thead>

		<tr>

			<th v-for="(header_label, header) in headers" :key="header" scope="col" v-html="header_label" :class="'manage-column column-' + header"></th>

		</tr>

	</thead>

	<tbody v-if="list.length === 0">
		<tr class="no-items">
			<td :colspan="Object.keys(headers).length" class="colspanchange">
				<div class="wu-p-6 wu-text-gray-600 wu-text-base wu-text-center">
					<span><?php esc_html_e('Add the first field!', 'multisite-ultimate'); ?></span>
				</div>
			</td>
		</tr>
	</tbody>

	<draggable
		:list="list"
		:tag="'tbody'"
		group="field"
		handle=".wu-placeholder-sortable"
		ghost-class="wu-draggable-field-ghost"
		drag-class="wu-bg-white"
	>

		<tr v-for="(field, idx) in list" :key="field.id" :id="'wp-ultimo-field-' + field.id">

			<td class="order column-order has-row-actions column-primary" data-colname="<?php esc_html_e('Order', 'multisite-ultimate'); ?>">

				<span
					class="wu-inline-block wu-bg-gray-100 wu-text-center wu-align-middle wu-p-1 wu-font-mono wu-px-3 wu-border wu-border-gray-300 wu-border-solid wu-rounded">
					{{ parseInt(idx, 10) + 1 }}
				</span>

				<button type="button" class="toggle-row">
					<span class="screen-reader-text"><?php esc_html_e('Show more details', 'multisite-ultimate'); ?></span>
				</button>

			</td>

			<td class="name column-name" data-colname="<?php esc_html_e('Name', 'multisite-ultimate'); ?>">

				<span class="wu-inline-block wu-font-medium">

					{{ field.name ? field.name : "<?php echo esc_html__('(no label)', 'multisite-ultimate'); ?>" }}

					<!-- Visibility -->
			<span 
						v-if="field.logged && field.logged == 'guests_only'" 
						class="wu-px-1 wu-ml-1 wu-text-xs wu-align-text-bottom wu-inline-block wu-rounded wu-bg-blue-100 wu-text-blue-600"
					>
			<?php echo wu_tooltip('Guests only', 'dashicons-wu-eye'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>

			<span 
						v-if="field.logged && field.logged == 'logged_only'" 
						class="wu-px-1 wu-ml-1 wu-text-xs wu-align-text-bottom wu-inline-block wu-rounded wu-bg-blue-100 wu-text-blue-600"
					>
			<?php echo wu_tooltip('Logged-in users only', 'dashicons-wu-eye'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<!-- Visibility - End -->

				</span>

				<div class="row-actions">
					<span class="edit">
						<a
							title="Edit Field"
							class="wubox"
							:href="'<?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
							echo esc_attr(
								wu_get_form_url(
									'add_new_form_field',
									[
										'checkout_form' => $checkout_form,
										'step'          => '',
									]
								)
							);
							?>=' + step_name + '&amp;field=' + field.id" <?php // phpcs:ignore Squiz.PHP.EmbeddedPhp ?>
							>
								<?php esc_html_e('Edit', 'multisite-ultimate'); ?>
						</a>
						|
					</span>
					<span class="delete">

						<a
							v-show="delete_field_id !== field.id"
							v-on:click.prevent="delete_field_id = field.id"
							title="<?php esc_html_e('Delete', 'multisite-ultimate'); ?>"
							href="#"
						><?php esc_html_e('Delete', 'multisite-ultimate'); ?></a>

						<a
							v-show="delete_field_id === field.id"
							v-on:click.prevent="remove_field(field.id)"
							title="<?php esc_html_e('Delete', 'multisite-ultimate'); ?>"
							href="#"
							class="wu-font-bold"
						><?php esc_html_e('Confirm?', 'multisite-ultimate'); ?></a>

					</span>
				</div>

				<button type="button" class="toggle-row">
					<span class="screen-reader-text">
						<?php esc_html_e('Show more details', 'multisite-ultimate'); ?>
					</span>
				</button>

			</td>

			<td class="type column-type" data-colname="<?php esc_html_e('Type', 'multisite-ultimate'); ?>">
				<span class="wu-bg-gray-200 wu-text-gray-700 wu-py-1 wu-px-2 wu-rounded-sm wu-text-xs wu-font-mono">{{ field.type }}</span>
			</td>

			<td class="type column-slug" data-colname="<?php esc_html_e('Slug', 'multisite-ultimate'); ?>">
				<span class="wu-bg-gray-200 wu-text-gray-700 wu-py-1 wu-px-2 wu-rounded-sm wu-text-xs wu-font-mono">{{ field.id }}</span>
			</td>

			<td class="move column-move wu-text-right" data-colname="<?php esc_html_e('Move', 'multisite-ultimate'); ?>">

				<span class="wu-placeholder-sortable dashicons-wu-menu"></span>

			</td>

		</tr>

	</draggable>

</table>

</script>
