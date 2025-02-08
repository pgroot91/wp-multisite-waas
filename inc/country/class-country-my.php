<?php // phpcs:ignore - @generation-checksum MY-16-179
/**
 * Country Class for Malaysia (MY).
 *
 * State/province count: 16
 * City count: 179
 * City count per state/province:
 * - 13: 14 cities
 * - 12: 16 cities
 * - 10: 24 cities
 * - 08: 14 cities
 * - 07: 15 cities
 * - 06: 11 cities
 * - 04: 13 cities
 * - 02: 14 cities
 * - 16: 1 cities
 * - 15: 1 cities
 * - 14: 1 cities
 * - 01: 31 cities
 * - 11: 8 cities
 * - 09: 3 cities
 * - 05: 6 cities
 * - 03: 7 cities
 *
 * @package WP_Ultimo\Country
 * @since 2.0.11
 */

namespace WP_Ultimo\Country;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Country Class for Malaysia (MY).
 *
 * IMPORTANT:
 * This file is generated by build scripts, do not
 * change it directly or your changes will be LOST!
 *
 * @since 2.0.11
 *
 * @property-read string $code
 * @property-read string $currency
 * @property-read int $phone_code
 */
class Country_MY extends Country {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * General country attributes.
	 *
	 * This might be useful, might be not.
	 * In case of doubt, keep it.
	 *
	 * @since 2.0.11
	 * @var array
	 */
	protected $attributes = array(
		'country_code' => 'MY',
		'currency'     => 'MYR',
		'phone_code'   => 60,
	);

	/**
	 * The type of nomenclature used to refer to the country sub-divisions.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $state_type = 'unknown';

	/**
	 * Return the country name.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	public function get_name() {

		return __('Malaysia', 'wp-ultimo-locations');
	}

	/**
	 * Returns the list of states for MY.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return array(
			'10' => __('Selangor', 'wp-ultimo-locations'),
			'11' => __('Terengganu', 'wp-ultimo-locations'),
			'12' => __('Sabah', 'wp-ultimo-locations'),
			'13' => __('Sarawak', 'wp-ultimo-locations'),
			'14' => __('Kuala Lumpur', 'wp-ultimo-locations'),
			'15' => __('Labuan', 'wp-ultimo-locations'),
			'16' => __('Putrajaya', 'wp-ultimo-locations'),
			'01' => __('Johor', 'wp-ultimo-locations'),
			'02' => __('Kedah', 'wp-ultimo-locations'),
			'03' => __('Kelantan', 'wp-ultimo-locations'),
			'04' => __('Malacca', 'wp-ultimo-locations'),
			'05' => __('Negeri Sembilan', 'wp-ultimo-locations'),
			'06' => __('Pahang', 'wp-ultimo-locations'),
			'07' => __('Penang', 'wp-ultimo-locations'),
			'08' => __('Perak', 'wp-ultimo-locations'),
			'09' => __('Perlis', 'wp-ultimo-locations'),
		);
	}
}
