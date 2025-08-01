<?php
/**
 * Webhook List Table class.
 *
 * @package WP_Ultimo
 * @subpackage List_Table
 * @since 2.0.0
 */

namespace WP_Ultimo\List_Tables;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Webhook List Table class.
 *
 * @since 2.0.0
 */
class Webhook_List_Table extends Base_List_Table {

	/**
	 * Holds the query class for the object being listed.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $query_class = \WP_Ultimo\Database\Webhooks\Webhook_Query::class;

	/**
	 * Initializes the table.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			[
				'singular' => __('Webhook', 'multisite-ultimate'),  // singular name of the listed records
				'plural'   => __('Webhooks', 'multisite-ultimate'), // plural name of the listed records
				'ajax'     => true,                        // does this table support ajax?
				'add_new'  => [
					'url'     => wu_get_form_url('add_new_webhook_modal'),
					'classes' => 'wubox',
				],
			]
		);
	}

	/**
	 * Displays the content of the name column.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Webhook $item Webhook object.
	 */
	public function column_name($item): string {

		$url_atts = [
			'id'    => $item->get_id(),
			'model' => 'webhook',
		];

		$title = sprintf(
			'<a href="%s"><strong>%s</strong></a>
				<span data-loading="wu_action_button_loading_%s" id="wu_action_button_loading" class="wu-blinking-animation wu-text-gray-600 wu-my-1 wu-text-2xs wu-uppercase wu-font-semibold hidden" >%s</span>',
			wu_network_admin_url('wp-ultimo-edit-webhook', $url_atts),
			$item->get_name(),
			$item->get_id(),
			__('Sending Test..', 'multisite-ultimate')
		);

		$actions = [
			'edit'   => sprintf('<a href="%s">%s</a>', wu_network_admin_url('wp-ultimo-edit-webhook', $url_atts), __('Edit', 'multisite-ultimate')),
			'test'   => sprintf('<a id="action_button" data-title="' . $item->get_name() . '" data-page="list" data-action="wu_send_test_event" data-event="' . $item->get_event() . '" data-object="' . $item->get_id() . '" data-url="%s" href="">%s</a>', $item->get_webhook_url(), __('Send Test', 'multisite-ultimate')),
			'delete' => sprintf(
				'<a title="%s" class="wubox" href="%s">%s</a>',
				__('Delete', 'multisite-ultimate'),
				wu_get_form_url(
					'delete_modal',
					$url_atts
				),
				__('Delete', 'multisite-ultimate')
			),
		];

		return $title . $this->row_actions($actions);
	}

	/**
	 * Displays the content of the webhook url column.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Webhook $item Webhook object.
	 * @return string
	 */
	public function column_webhook_url($item) {

		$trimmed_url = mb_strimwidth((string) $item->get_webhook_url(), 0, 50, '...');

		return "<span class='wu-py-1 wu-px-2 wu-bg-gray-200 wu-rounded-sm wu-text-gray-700 wu-text-xs wu-font-mono'>{$trimmed_url}</span>";
	}

	/**
	 * Displays the content of the event column.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Webhook $item Webhook object.
	 * @return string
	 */
	public function column_event($item) {

		$event = $item->get_event();

		return "<span class='wu-py-1 wu-px-2 wu-bg-gray-200 wu-rounded-sm wu-text-gray-700 wu-text-xs wu-font-mono'>{$event}</span>";
	}

	/**
	 * Displays the content of the count column.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Webhook $item Webhook object.
	 */
	public function column_count($item): string {

		$count = $item->get_count();

		$actions = [
			'edit' => sprintf('<a href="%s">%s</a>', '', __('See Events', 'multisite-ultimate')),
		];

		return $count . $this->row_actions($actions);
	}

	/**
	 * Displays the content of the integration column.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Webhook $item Webhook object.
	 */
	public function column_integration($item): string {

		return ucwords(str_replace(['_', '-'], ' ', (string) $item->get_integration()));
	}

	/**
	 * Displays the content of the active column.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Webhook $item Webhook object.
	 * @return string
	 */
	public function column_active($item) {

		return $item->is_active() ? __('Yes', 'multisite-ultimate') : __('No', 'multisite-ultimate');
	}

	/**
	 * Returns the list of columns for this particular List Table.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'cb'          => '<input type="checkbox" />',
			'name'        => __('Name', 'multisite-ultimate'),
			'webhook_url' => __('Target URL', 'multisite-ultimate'),
			'event'       => __('Trigger Event', 'multisite-ultimate'),
			'event_count' => __('Count', 'multisite-ultimate'),
			'integration' => __('Integration', 'multisite-ultimate'),
			'active'      => __('Active', 'multisite-ultimate'),
			'id'          => __('ID', 'multisite-ultimate'),
		];

		return $columns;
	}

	/**
	 * Returns the filters for this page.
	 *
	 * @since 2.0.0
	 */
	public function get_filters(): array {

		return [
			'filters'      => [],
			'date_filters' => [],
		];
	}
}
