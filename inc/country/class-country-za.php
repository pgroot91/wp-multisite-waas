<?php // phpcs:ignore - @generation-checksum ZA-9-314
/**
 * Country Class for South Africa (ZA).
 *
 * State/province count: 9
 * City count: 314
 * City count per state/province:
 * - WC: 52 cities
 * - KZN: 43 cities
 * - FS: 41 cities
 * - GP: 39 cities
 * - EC: 38 cities
 * - NC: 28 cities
 * - MP: 26 cities
 * - NW: 24 cities
 * - LP: 23 cities
 *
 * @package WP_Ultimo\Country
 * @since 2.0.11
 */

namespace WP_Ultimo\Country;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Country Class for South Africa (ZA).
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
class Country_ZA extends Country {

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
		'country_code' => 'ZA',
		'currency'     => 'ZAR',
		'phone_code'   => 27,
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

		return __('South Africa', 'wp-ultimo-locations');
	}

	/**
	 * Returns the list of states for ZA.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return array(
			'EC'  => __('Eastern Cape', 'wp-ultimo-locations'),
			'FS'  => __('Free State', 'wp-ultimo-locations'),
			'GP'  => __('Gauteng', 'wp-ultimo-locations'),
			'KZN' => __('KwaZulu-Natal', 'wp-ultimo-locations'),
			'LP'  => __('Limpopo', 'wp-ultimo-locations'),
			'MP'  => __('Mpumalanga', 'wp-ultimo-locations'),
			'NW'  => __('North West', 'wp-ultimo-locations'),
			'NC'  => __('Northern Cape', 'wp-ultimo-locations'),
			'WC'  => __('Western Cape', 'wp-ultimo-locations'),
		);
	}
}
