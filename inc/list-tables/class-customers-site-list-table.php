<?php
/**
 * Customers Site List Table class.
 *
 * @package WP_Ultimo
 * @subpackage List_Table
 * @since 2.0.0
 */

namespace WP_Ultimo\List_Tables;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Site List Table class.
 *
 * @since 2.0.0
 */
class Customers_Site_List_Table extends Site_List_Table {

	/**
	 * Initializes the table.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct();

		$this->current_mode = 'list';
	}

	/**
	 * Returns the list of columns for this particular List Table.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_columns() {

		$columns = [
			'responsive' => '',
		];

		return $columns;
	}

	/**
	 * Renders the inside column responsive.
	 *
	 * @since 2.0.0
	 *
	 * @param object $item The item being rendered.
	 * @return void
	 */
	public function column_responsive($item): void {

		$m = $item->get_membership();

		$redirect = current_user_can('wu_edit_sites') ? 'wp-ultimo-edit-site' : 'wp-ultimo-sites';

		echo wu_responsive_table_row( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			[
				'id'     => $item->get_id(),
				'title'  => $item->get_title(),
				'url'    => wu_network_admin_url(
					$redirect,
					[
						'id' => $item->get_id(),
					]
				),
				'image'  => $this->column_featured_image_id($item),
				'status' => $this->column_type($item),
			],
			[
				'link'       => [
					'icon'  => 'dashicons-wu-link1 wu-align-middle wu-mr-1',
					'label' => __('Visit Site', 'multisite-ultimate'),
					'url'   => $item->get_active_site_url(),
					'value' => $item->get_active_site_url(),
				],
				'dashboard'  => [
					'icon'  => 'dashicons-wu-browser wu-align-middle wu-mr-1',
					'label' => __('Go to the Dashboard', 'multisite-ultimate'),
					'value' => __('Dashboard', 'multisite-ultimate'),
					'url'   => get_admin_url($item->get_id()),
				],
				'membership' => [
					'icon'  => 'dashicons-wu-rotate-ccw wu-align-middle wu-mr-1',
					'label' => __('Go to the Membership', 'multisite-ultimate'),
					'value' => $m ? $m->get_hash() : '',
					'url'   => $m ? wu_network_admin_url(
						'wp-ultimo-edit-membership',
						[
							'id' => $m->get_id(),
						]
					) : '',
				],
			],
			[
				'date_created' => [
					'icon'  => 'dashicons-wu-calendar1 wu-align-middle wu-mr-1',
					'label' => '',
					'value' => $item->get_type() === 'pending' ?
						__('Not Available', 'multisite-ultimate') :
						// translators: %s is a placeholder for the human-readable time difference, e.g., "2 hours ago"
						sprintf(__('Created %s', 'multisite-ultimate'), wu_human_time_diff(strtotime((string) $item->get_date_registered()))),
				],
			]
		);
	}

	/**
	 * Overrides the parent method to add pending sites.
	 *
	 * @since 2.0.0
	 *
	 * @param integer $per_page Number of items to display per page.
	 * @param integer $page_number Current page.
	 * @param boolean $count If we should count records or return the actual records.
	 * @return array
	 */
	public function get_items($per_page = 5, $page_number = 1, $count = false) {

		$sites = parent::get_items($per_page, $page_number, $count);

		if ($count) {
			return $sites;
		}

		$pending_sites = [];

		$page = wu_request('page');

		$id = wu_request('id');

		if ( ! $id) {
			return $sites;
		}

		switch ($page) {
			case 'wp-ultimo-edit-membership':
				$membership    = wu_get_membership($id);
				$pending_sites = $membership && $membership->get_pending_site() ? [$membership->get_pending_site()] : [];
				break;
			case 'wp-ultimo-edit-customer':
				$customer      = wu_get_customer($id);
				$pending_sites = $customer ? $customer->get_pending_sites() : [];
				break;
		}

		foreach ($pending_sites as &$site) {
			$site->set_type('pending');
			$site->set_blog_id('--');
		}

		return array_merge($pending_sites, $sites);
	}
}
