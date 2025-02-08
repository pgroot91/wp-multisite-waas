<?php // phpcs:ignore - @generation-checksum ES-28-6692
/**
 * Country Class for Spain (ES).
 *
 * State/province count: 28
 * City count: 6692
 * City count per state/province:
 * - LE: 1948 cities
 * - CM: 808 cities
 * - AN: 724 cities
 * - AR: 620 cities
 * - CT: 557 cities
 * - VC: 477 cities
 * - EX: 349 cities
 * - GA: 227 cities
 * - MD: 188 cities
 * - NC: 176 cities
 * - RI: 159 cities
 * - PV: 149 cities
 * - CN: 105 cities
 * - PM: 87 cities
 * - CB: 58 cities
 * - MC: 57 cities
 * - CE: 2 cities
 * - ML: 1 cities
 *
 * @package WP_Ultimo\Country
 * @since 2.0.11
 */

namespace WP_Ultimo\Country;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Country Class for Spain (ES).
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
class Country_ES extends Country {

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
		'country_code' => 'ES',
		'currency'     => 'EUR',
		'phone_code'   => 34,
	);

	/**
	 * The type of nomenclature used to refer to the country sub-divisions.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $state_type = 'autonomous_community';

	/**
	 * Return the country name.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	public function get_name() {

		return __('Spain', 'wp-ultimo-locations');
	}

	/**
	 * Returns the list of states for ES.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return array(
			'AN' => __('Andalusia', 'wp-ultimo-locations'),
			'AR' => __('Aragon', 'wp-ultimo-locations'),
			'AS' => __('Asturias', 'wp-ultimo-locations'),
			'PM' => __('Balearic Islands', 'wp-ultimo-locations'),
			'PV' => __('Basque Country', 'wp-ultimo-locations'),
			'BU' => __('Burgos Province', 'wp-ultimo-locations'),
			'CN' => __('Canary Islands', 'wp-ultimo-locations'),
			'CB' => __('Cantabria', 'wp-ultimo-locations'),
			'CL' => __('Castile and León', 'wp-ultimo-locations'),
			'CM' => __('Castilla La Mancha', 'wp-ultimo-locations'),
			'CT' => __('Catalonia', 'wp-ultimo-locations'),
			'CE' => __('Ceuta', 'wp-ultimo-locations'),
			'EX' => __('Extremadura', 'wp-ultimo-locations'),
			'GA' => __('Galicia', 'wp-ultimo-locations'),
			'RI' => __('La Rioja', 'wp-ultimo-locations'),
			'LE' => __('Léon', 'wp-ultimo-locations'),
			'MD' => __('Madrid', 'wp-ultimo-locations'),
			'ML' => __('Melilla', 'wp-ultimo-locations'),
			'MC' => __('Murcia', 'wp-ultimo-locations'),
			'NC' => __('Navarra', 'wp-ultimo-locations'),
			'P'  => __('Palencia Province', 'wp-ultimo-locations'),
			'SA' => __('Salamanca Province', 'wp-ultimo-locations'),
			'SG' => __('Segovia Province', 'wp-ultimo-locations'),
			'SO' => __('Soria Province', 'wp-ultimo-locations'),
			'VC' => __('Valencia', 'wp-ultimo-locations'),
			'VA' => __('Valladolid Province', 'wp-ultimo-locations'),
			'ZA' => __('Zamora Province', 'wp-ultimo-locations'),
			'AV' => __('Ávila', 'wp-ultimo-locations'),
		);
	}
}
