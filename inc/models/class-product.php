<?php
/**
 * The Product model.
 *
 * @package WP_Ultimo
 * @subpackage Models
 * @since 2.0.0
 */

namespace WP_Ultimo\Models;

// Exit if accessed directly
defined('ABSPATH') || exit;

use WP_Ultimo\Database\Products\Product_Type;
use WP_Ultimo\Models\Interfaces\Limitable;

/**
 * Product model class. Implements the Base Model.
 *
 * @since 2.0.0
 */
class Product extends Base_Model implements Limitable {

	use Traits\Limitable;
	use \WP_Ultimo\Traits\WP_Ultimo_Plan_Deprecated;

	/**
	 * The product name.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $name = '';

	/**
	 * The product slug.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $slug = '';

	/**
	 * The product description.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $description = '';

	/**
	 * Currency for this product. 3-letter currency code.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $currency = 'USD';

	/**
	 * The type of billing associated with this product.
	 *
	 * Can be one of 'free', 'paid', and 'contact_us'.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $pricing_type = 'paid';

	/**
	 * The product setup fee.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $setup_fee = 0;

	/**
	 * Product that this product relates to.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $parent_id;

	/**
	 * Is this product recurring?
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $recurring = 1;

	/**
	 * Duration of the trial period.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $trial_duration = 0;

	/**
	 * Unit of the trial period duration.
	 *
	 * - day
	 * - week
	 * - month
	 * - year
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $trial_duration_unit = 'day';

	/**
	 * Time interval between charges.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $duration = 1;

	/**
	 * Time interval unit between charges.
	 *
	 * - day
	 * - week
	 * - month
	 * - year
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $duration_unit = 'month';

	/**
	 * The product amount.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $amount = 0;

	/**
	 * The number of times we should charge this product.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $billing_cycles = 0;

	/**
	 * The product list order. Useful when ordering products in a list.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $list_order = 10;

	/**
	 * Is this product active?
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $active = true;

	/**
	 * Type of the product.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $type = 'plan';

	/**
	 * ID of the featured image being used on this product.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $featured_image_id;

	/**
	 * Is the product taxable?
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $taxable = true;

	/**
	 * What is the tax category that should be used.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $tax_category;

	/**
	 * Feature list for pricing tables.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $feature_list;

	/**
	 * Customer role on sites under this plan.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $customer_role;

	/**
	 * Price variations.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $price_variations;

	/**
	 * Available add-ons.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $available_addons;

	/**
	 * The group of this product.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $product_group;

	/**
	 * Contact us Label.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $contact_us_label;

	/**
	 * Contact us Link.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $contact_us_link;

	/**
	 * Legacy options
	 *
	 * @since 2.0.0
	 * @var bool
	 */
	protected $legacy_options;

	/**
	 * Query Class to the static query methods.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $query_class = \WP_Ultimo\Database\Products\Product_Query::class;

	/**
	 * Map setters to other parameters.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $_mappings = [
		'product_group' => 'group',
	];

	/**
	 * Set the validation rules for this particular model.
	 *
	 * To see how to setup rules, check the documentation of the
	 * validation library we are using: https://github.com/rakit/validation
	 *
	 * @since 2.0.0
	 * @link https://github.com/rakit/validation
	 * @return array
	 */
	public function validation_rules() {

		$id = $this->get_id();

		$duration = $this->get_duration();

		$duration_unit = $this->get_duration_unit();

		$allowed_types = Product_Type::get_allowed_list(true);

		$currency = wu_get_setting('currency_symbol', 'USD');

		return [
			'featured_image_id'   => 'integer',
			'currency'            => "required|default:{$currency}",
			'pricing_type'        => 'required|in:free,paid,contact_us',
			'trial_duration'      => 'integer',
			'trial_duration_unit' => 'in:day,week,month,year|default:month',
			'parent_id'           => 'integer',
			'amount'              => 'numeric|default:0',
			'recurring'           => 'default:0',
			'setup_fee'           => 'numeric',
			'duration'            => 'numeric|default:1',
			'duration_unit'       => 'in:day,week,month,year|default:month',
			'billing_cycles'      => 'integer|default:0',
			'active'              => 'default:1',
			'price_variations'    => "price_variations:{$duration},{$duration_unit}",
			'type'                => "required|default:plan|in:{$allowed_types}",
			'slug'                => "required|unique:\WP_Ultimo\Models\Product,slug,{$id}|min:2",
			'taxable'             => 'boolean|default:0',
			'tax_category'        => 'default:',
			'contact_us_label'    => 'default:',
			'contact_us_link'     => 'url:http,https',
			'customer_role'       => 'alpha_dash',
		];
	}

	/**
	 * @return $this
	 */
	public function load_attributes_from_post() {
		parent::load_attributes_from_post();

		if (isset($_POST['description'])) { // phpcs:ignore WordPress.Security.NonceVerification
			$this->set_description(sanitize_post_field('post_content', wp_unslash($_POST['description']), $this->get_id(), 'db')); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification
		}

		return $this;
	}

	/**
	 * Get featured image ID.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_featured_image_id() {

		if (null === $this->featured_image_id) {
			$this->featured_image_id = $this->get_meta('wu_featured_image_id');
		}

		return $this->featured_image_id;
	}

	/**
	 * Get featured image url.
	 *
	 * @since 2.0.0
	 * @param string $size The size of the image to retrieve.
	 * @return string
	 */
	public function get_featured_image($size = 'medium') {

		is_multisite() && switch_to_blog(wu_get_main_site_id());

		$image_attributes = wp_get_attachment_image_src($this->get_featured_image_id(), $size);

		is_multisite() && restore_current_blog();

		return $image_attributes ? $image_attributes[0] : '';
	}

	/**
	 * Set featured image ID.
	 *
	 * @since 2.0.0
	 * @param int $image_id The ID of the feature image of the product.
	 * @return void
	 */
	public function set_featured_image_id($image_id): void {

		$this->meta['wu_featured_image_id'] = $image_id;

		$this->featured_image_id = $image_id;
	}

	/**
	 * Get the product slug.
	 *
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;
	}

	/**
	 * Set the slug name.
	 *
	 * @param string $slug The product slug. It needs to be unique and preferably make it clear what it is about. Example: my_new_product.
	 */
	public function set_slug($slug): void {

		$this->slug = $slug;
	}

	/**
	 * Get the product name.
	 *
	 * @return string
	 */
	public function get_name() {

		return $this->name;
	}

	/**
	 * Set the product name.
	 *
	 * @param string $name Your product name, which is used as product title as well.
	 */
	public function set_name($name): void {

		$this->name = $name;
	}

	/**
	 * Get the product description.
	 *
	 * @return string
	 */
	public function get_description() {

		return stripslashes($this->description);
	}

	/**
	 * Set the product description.
	 *
	 * @param string $description A description for the product, usually a short text.
	 */
	public function set_description($description): void {

		$this->description = $description;
	}

	/**
	 * Get the value of currency.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_currency() {

		$should_use_saved_currency = apply_filters('wu_should_use_saved_currency', false);

		$currency = wu_get_setting('currency_symbol', 'USD');

		if ($should_use_saved_currency) {
			$currency = $this->currency;
		}

		return $currency;
	}

	/**
	 * Set the value of currency.
	 *
	 * @since 2.0.0
	 * @param string $currency The currency that this product accepts. It's a 3-letter code. E.g. 'USD'.
	 * @return void
	 */
	public function set_currency($currency): void {

		$this->currency = $currency;
	}

	/**
	 * Get can be one of 'free', 'paid', and 'contact_us'.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_pricing_type() {

		return $this->pricing_type;
	}

	/**
	 * Set pricing type can be one of 'free', 'paid', and 'contact_us'.
	 *
	 * @since 2.0.0
	 * @param string $pricing_type The pricing type can be 'free', 'paid' or 'contact_us'.
	 * @options free,paid,contact_us
	 * @return void
	 */
	public function set_pricing_type($pricing_type): void {

		$this->pricing_type = $pricing_type;

		if ('free' === $pricing_type || 'contact_us' === $pricing_type) {
			$this->set_amount(0);

			$this->set_recurring(false);
		}
	}

	/**
	 * Checks if a given product offers a trial period.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function has_trial() {

		return $this->get_trial_duration() > 0;
	}

	/**
	 * Get duration of the trial.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_trial_duration() {

		return $this->trial_duration;
	}

	/**
	 * Set duration of the trial.
	 *
	 * @since 2.0.0
	 * @param int $trial_duration The duration of the trial period of this product, if the product has one.
	 * @return void
	 */
	public function set_trial_duration($trial_duration): void {

		$this->trial_duration = $trial_duration;
	}

	/**
	 * Get the trial duration unit.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_trial_duration_unit() {

		return $this->trial_duration_unit;
	}

	/**
	 * Set the trial duration unit.
	 *
	 * @since 2.0.0
	 * @param string $trial_duration_unit The unit of the trial duration amount. Can be day, week, month or year.
	 * @options day,week,month,year
	 * @return void
	 */
	public function set_trial_duration_unit($trial_duration_unit): void {

		$this->trial_duration_unit = $trial_duration_unit;
	}

	/**
	 * Get time interval between charges.
	 *
	 * @return int
	 */
	public function get_duration() {

		return absint($this->duration);
	}

	/**
	 * Set time interval between charges.
	 *
	 * @param int $duration Time interval between charges.
	 */
	public function set_duration($duration): void {

		$this->duration = $duration;
	}

	/**
	 * Get time interval unit between charges.
	 *
	 * @return string
	 */
	public function get_duration_unit() {

		return $this->duration_unit;
	}

	/**
	 * Set time interval unit between charges.
	 *
	 * @param string $duration_unit Time interval unit between charges.
	 */
	public function set_duration_unit($duration_unit): void {

		$this->duration_unit = $duration_unit;
	}

	/**
	 * Get the product amount.
	 *
	 * @return int
	 */
	public function get_amount() {

		if ($this->get_pricing_type() === 'free') {
			return 0;
		}

		if ($this->get_pricing_type() === 'contact_us') {
			return 0;
		}

		return $this->amount;
	}

	/**
	 * Get the formatted price amount.
	 *
	 * @since 2.0.0
	 * @param string $key The key. This is ignored here.
	 * @return string
	 */
	public function get_formatted_amount($key = 'amount') {

		if ($this->is_free()) {
			return __('Free!', 'multisite-ultimate');
		}

		if ($this->get_pricing_type() === 'contact_us') {
			return $this->get_contact_us_label() ?: __('Contact Us', 'multisite-ultimate');
		}

		return wu_format_currency($this->get_amount(), $this->get_currency());
	}

	/**
	 * Set the product amount.
	 *
	 * @param int $amount The value of this product. E.g. 19.99.
	 */
	public function set_amount($amount): void {

		$this->amount = wu_to_float($amount);
	}

	/**
	 * Get the product setup fee..
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_setup_fee() {

		return $this->setup_fee;
	}

	/**
	 * Set the product setup fee..
	 *
	 * @since 2.0.0
	 * @param int $setup_fee The setup fee value, if the product has one. E.g. 159.99.
	 * @return void
	 */
	public function set_setup_fee($setup_fee): void {

		$this->setup_fee = wu_to_float($setup_fee);
	}

	/**
	 * Checks if a given product haw a setup fee.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function has_setup_fee() {

		return $this->get_setup_fee() > 0;
	}

	/**
	 * Get the product initial amount
	 *
	 * @return int
	 */
	public function get_initial_amount() {

		return $this->get_amount() + $this->get_setup_fee();
	}

	/**
	 * Returns the product price structure in a way human can understand it.
	 *
	 * @since 2.0.0
	 *
	 * @param bool $include_fees If we need to include fees.
	 * @return string
	 */
	public function get_price_description($include_fees = true) {

		$pricing = [];

		if ($this->get_pricing_type() === 'contact_us') {
			return __('Contact us', 'multisite-ultimate');
		}

		if ($this->is_free()) {
			return __('Free!', 'multisite-ultimate');
		}

		if ($this->is_recurring()) {
			$duration = $this->get_duration();

			$message = sprintf(
				// translators: %1$s is the formatted price, %2$s the duration, and %3$s the duration unit (day, week, month, etc)
				_n('%1$s every %3$s', '%1$s every %2$s %3$s', $duration, 'multisite-ultimate'), // phpcs:ignore
				wu_format_currency($this->get_amount(), $this->get_currency()),
				$duration,
				wu_get_translatable_string($duration <= 1 ? $this->get_duration_unit() : $this->get_duration_unit() . 's')
			);

			$pricing['subscription'] = $message;

			if ( ! $this->is_forever_recurring()) {
				$billing_cycles_message = sprintf(
					// translators: %s is the number of billing cycles.
					_n('for %s cycle', 'for %s cycles', $this->get_billing_cycles(), 'multisite-ultimate'),
					$this->get_billing_cycles()
				);

				$pricing['subscription'] .= ' ' . $billing_cycles_message;
			}
		} else {
			$pricing['subscription'] = sprintf(
				// translators: %1$s is the formatted price of the product
				__('%1$s one time payment', 'multisite-ultimate'),
				wu_format_currency($this->get_amount(), $this->get_currency())
			);
		}

		if ($this->has_setup_fee() && $include_fees) {
			$pricing['fee'] = sprintf(
				// translators: %1$s is the formatted price of the setup fee
				__('Setup Fee of %1$s', 'multisite-ultimate'),
				wu_format_currency($this->get_setup_fee(), $this->get_currency())
			);
		}

		return implode(' + ', $pricing);
	}

	/**
	 * Returns the amount recurring in a human-friendly way.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_recurring_description() {

		if ($this->is_free() || $this->get_pricing_type() === 'contact_us') {
			return '--';
		}

		if ( ! $this->is_recurring()) {
			return __('one-time payment', 'multisite-ultimate');
		}

		$description = sprintf(
			// translators: %1$s the duration, and %2$s the duration unit (day, week, month, etc)
			_n('every %2$s', 'every %1$s %2$s', $this->get_duration(), 'multisite-ultimate'), // phpcs:ignore
			$this->get_duration(),
			wu_get_translatable_string($this->get_duration() <= 1 ? $this->get_duration_unit() : $this->get_duration_unit() . 's')
		);

		return $description;
	}

	/**
	 * Get the product list order. Useful when ordering products in a list.
	 *
	 * @return int
	 */
	public function get_list_order() {

		return $this->list_order;
	}

	/**
	 * Set the product list order. Useful when ordering products in a list.
	 *
	 * @param int $list_order The product list order. Useful when ordering products in a list.
	 */
	public function set_list_order($list_order): void {

		$this->list_order = $list_order;
	}

	/**
	 * Get is this product active?.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_active() {

		return (bool) $this->active;
	}

	/**
	 * Set is this product active?
	 *
	 * @since 2.0.0
	 * @param boolean $active Set this product as active (true), which means available to be used, or inactive (false).
	 * @return void
	 */
	public function set_active($active): void {

		$this->active = (bool) $active;
	}

	/**
	 * Get type of the product.
	 *
	 * @return string
	 */
	public function get_type() {

		return $this->type;
	}

	/**
	 * Set type of the product.
	 *
	 * @param string $type The default product types are 'product', 'service' and 'package'. More types can be add using the product type filter.
	 * @options plan,service,package
	 */
	public function set_type($type): void {

		$this->type = $type;
	}

	/**
	 * Returns the Label for a given type.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_type_label() {

		$type = new Product_Type($this->get_type());

		return $type->get_label();
	}

	/**
	 * Gets the classes for a given class.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_type_class() {

		$type = new Product_Type($this->get_type());

		return $type->get_classes();
	}

	/**
	 * Get product that this product relates to..
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_parent_id() {

		return $this->parent_id;
	}

	/**
	 * Set product that this product relates to..
	 *
	 * @since 2.0.0
	 * @param int $parent_id The ID from another Product that this product is related to.
	 * @return void
	 */
	public function set_parent_id($parent_id): void {

		$this->parent_id = $parent_id;
	}

	/**
	 * Get is this product recurring?
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_recurring() {

		return (bool) $this->recurring && (float) $this->get_amount() > 0;
	}

	/**
	 * Checks if this plan is free or not.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_free() {

		return empty($this->get_amount()) && empty($this->get_initial_amount());
	}

	/**
	 * Set is this product recurring?
	 *
	 * @since 2.0.0
	 * @param boolean $recurring Set this product as a recurring one (true), which means the customer paid a defined amount each period of time, or not recurring (false).
	 * @return void
	 */
	public function set_recurring($recurring): void {

		$this->recurring = (bool) $recurring;
	}

	/**
	 * Get the number of times we should charge this product.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_billing_cycles() {

		return (int) $this->billing_cycles;
	}

	/**
	 * Checks if this product recurs forever.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_forever_recurring() {

		return empty($this->get_billing_cycles()) || $this->get_billing_cycles() <= 0;
	}

	/**
	 * Set the number of times we should charge this product.
	 *
	 * @since 2.0.0
	 * @param int $billing_cycles The number of times we should charge this product.
	 * @return void
	 */
	public function set_billing_cycles($billing_cycles): void {

		$this->billing_cycles = (int) $billing_cycles;
	}

	/**
	 * Get date when this was created..
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_date_created() {

		return $this->date_created;
	}

	/**
	 * Set date when this was created..
	 *
	 * @since 2.0.0
	 * @param string $date_created Date when this was created.
	 * @return void
	 */
	public function set_date_created($date_created): void {

		$this->date_created = $date_created;
	}

	/**
	 * Get date when this was last modified..
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_date_modified() {

		return $this->date_modified;
	}

	/**
	 * Set date when this was last modified..
	 *
	 * @since 2.0.0
	 * @param string $date_modified Date when this was last modified.
	 * @return void
	 */
	public function set_date_modified($date_modified): void {

		$this->date_modified = $date_modified;
	}

	/**
	 * By default, we just use the to_array method, but you can rewrite this.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function to_search_results() {

		$image = $this->get_featured_image('thumbnail');

		$search_result = $this->to_array();

		$search_result['type']            = $this->get_type_label();
		$search_result['formatted_price'] = $this->get_price_description();
		$search_result['image']           = $image ? sprintf('<img class="wu-rounded wu-mr-3" height="40" width="40" src="%s">', esc_attr($image)) : '';

		return $search_result;
	}

	// Secondary Info, to be saved as meta

	/**
	 * Checks if the product is taxable.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_taxable() {

		$is_taxable = (bool) $this->get_meta('taxable', true);

		return apply_filters('wu_product_is_taxable', $is_taxable, $this);
	}

	/**
	 * Sets the taxable status of the product.
	 *
	 * @since 2.0.0
	 *
	 * @param bool $is_taxable Set this product as a taxable one (true), which means tax rules are applied to, or not taxable (false).
	 * @return void
	 */
	public function set_taxable($is_taxable): void {

		$this->meta['taxable'] = (bool) $is_taxable;

		$this->taxable = $this->meta['taxable'];
	}

	/**
	 * Returns the tax category to apply.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_tax_category() {

		if (null === $this->tax_category) {
			$this->tax_category = $this->get_meta('tax_category', 'default');
		}

		return apply_filters('wu_product_tax_category', $this->tax_category, $this);
	}

	/**
	 * Sets the tax category to apply.
	 *
	 * @since 2.0.0
	 *
	 * @param string $tax_category Category of taxes applied to this product. You need to set this if taxable is set to true.
	 * @return void
	 */
	public function set_tax_category($tax_category): void {

		$this->meta['tax_category'] = $tax_category;

		$this->tax_category = $this->meta['tax_category'];
	}

	/**
	 * Get the contact us label.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_contact_us_label() {

		if (null === $this->contact_us_label) {
			$this->contact_us_label = $this->get_meta('wu_contact_us_label', '');
		}

		return $this->contact_us_label;
	}

	/**
	 * Set the contact us label.
	 *
	 * @since 2.0.0
	 * @param string $contact_us_label If the product is the 'contact_us' type, it will need a label for the contact us button.
	 * @return void
	 */
	public function set_contact_us_label($contact_us_label): void {

		$this->meta['wu_contact_us_label'] = $contact_us_label;

		$this->contact_us_label = $this->meta['wu_contact_us_label'];
	}

	/**
	 * Get the contact us link.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_contact_us_link() {

		if (null === $this->contact_us_link) {
			$this->contact_us_link = $this->get_meta('wu_contact_us_link', '');
		}

		return $this->contact_us_link;
	}

	/**
	 * Set the contact us link.
	 *
	 * @since 2.0.0
	 * @param string $contact_us_link The url where the contact us button will lead to.
	 * @return void
	 */
	public function set_contact_us_link($contact_us_link): void {

		$this->meta['wu_contact_us_link'] = $contact_us_link;

		$this->contact_us_link = $this->meta['wu_contact_us_link'];
	}

	/**
	 * Get feature list for pricing tables..
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_feature_list() {

		if (null === $this->feature_list) {
			$this->feature_list = $this->get_meta('feature_list');
		}

		return $this->feature_list;
	}

	/**
	 * Set feature list for pricing tables..
	 *
	 * @since 2.0.0
	 * @param array $feature_list A list (array) of features of the product.
	 * @return void
	 */
	public function set_feature_list($feature_list): void {

		$this->meta['feature_list'] = $feature_list;

		$this->feature_list = $this->meta['feature_list'];
	}

	/**
	 * Get the customer role to force customers to be on this plan.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_customer_role() {

		if (null === $this->customer_role) {
			$this->customer_role = $this->get_limitations()->customer_user_role->get_limit();
		}

		return $this->customer_role;
	}

	/**
	 * Set the customer role to force customers to be on this plan.
	 *
	 * @deprecated 2.0.10
	 * @since 2.0.0
	 * @param string $customer_role  The customer role of this product.
	 * @return void
	 */
	public function set_customer_role($customer_role) {}

	/**
	 * Returns the same product, but with price and duration info changed to
	 * the ones of a existing pricing variation.
	 *
	 * Returns false if the price variation is not available.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $duration The duration.
	 * @param string $duration_unit The duration unit.
	 * @return self|false
	 */
	public function get_as_variation($duration, $duration_unit) {

		$duration      = $duration ?: 1;
		$duration_unit = $duration_unit ?: 'month';

		if ($this->is_free()) {
			return $this;
		}

		if ($this->get_duration() !== $duration || $this->get_duration_unit() !== $duration_unit) {
			$price_variation = $this->get_price_variation($duration, $duration_unit);
		}

		if (absint($duration) === $this->get_duration() && $this->get_duration_unit() === $duration_unit) {
			$price_variation = [
				'amount' => $this->get_amount(),
			];
		}

		$price_variation = apply_filters('wu_get_as_variation_price_variation', $price_variation, $duration, $duration_unit, $this);

		if ($price_variation) {
			$this->attributes((array) $price_variation);

			return $this;
		}

		return false;
	}

	/**
	 * Returns the price variations for this product.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_price_variations() {

		if (null === $this->price_variations) {
			$this->price_variations = array_map(
				function ($price_variation) {
					/**
					 * Ensure the amount is a float.
					 */
					$price_variation['amount'] = wu_to_float(wu_get_isset($price_variation, 'amount', 0));

					return $price_variation;
				},
				$this->get_meta('price_variations', [])
			);
		}

		return $this->price_variations;
	}

	/**
	 * Sets the new price variations.
	 *
	 * @since 2.0.0
	 * @param ?array $price_variations Price variations array.
	 * @return void
	 */
	public function set_price_variations(?array $price_variations): void {

		// Ensure the amount is a float.
		$price_variations = array_map(
			function ($price_variation) {

				$price_variation['amount'] = wu_to_float(wu_get_isset($price_variation, 'amount', 0));

				return $price_variation;
			},
			$price_variations ?? []
		);

		$this->meta['price_variations'] = $price_variations;

		$this->price_variations = $this->meta['price_variations'];
	}

	/**
	 * Get a particular price variation.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $duration The duration.
	 * @param string $duration_unit The duration unit.
	 * @return object|false
	 */
	public function get_price_variation($duration, $duration_unit) {

		$price_variation = false;

		$price_variations = $this->get_price_variations();

		if ( ! empty($price_variations)) {
			if (absint($duration) === 12 && 'month' === $duration_unit) {
				$duration = 1;

				$duration_unit = 'year';
			}

			foreach ($price_variations as $pv) {
				if (absint($pv['duration']) === absint($duration) && $pv['duration_unit'] === $duration_unit) {
					$pv['monthly_amount'] = $pv['amount'] / (wu_convert_duration_unit_to_month($duration_unit) * $pv['duration']);

					$price_variation = $pv;

					break;
				}
			}
		}

		return apply_filters('wu_product_get_price_variation', $price_variation, $duration, $duration_unit, $this);
	}

	/**
	 * Save (create or update) the model on the database.
	 *
	 * @since 2.0.0
	 *
	 * @return bool|\WP_Error
	 */
	public function save() {

		if (empty($this->slug) && $this->name) {
			$this->set_slug(sanitize_title($this->name));
		}

		if ($this->is_free() && $this->get_pricing_type() !== 'contact_us') {
			$this->set_pricing_type('free');
		}

		$results = parent::save();

		if ( ! is_wp_error($results) && has_action('save_post_wpultimo_plan')) {
			do_action_deprecated('save_post_wpultimo_plan', [$this->get_id()], '2.0.0');
		}

		if ( ! is_wp_error($results) && has_action('wu_save_plan')) {
			$compat_plan = new \WU_Plan($this);

			do_action_deprecated('wu_save_plan', [$compat_plan], '2.0.0', 'wu_product_post_save');
		}

		return $results;
	}

	/**
	 * Creates a copy of the given model adn resets it's id to a 'new' state.
	 *
	 * @since 2.0.0
	 * @return \WP_Ultimo\Model\Base_Model
	 */
	public function duplicate() {

		$this->meta['wu_limitations'] = $this->get_limitations(false);

		$new_product = parent::duplicate();

		return $new_product;
	}

	/**
	 * Get available add-ons.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_available_addons() {

		if (null === $this->available_addons) {
			$this->available_addons = $this->get_meta('wu_
			available_addons', []);

			if (is_string($this->available_addons)) {
				$this->available_addons = explode(',', $this->available_addons);
			}
		}

		return $this->available_addons;
	}

	/**
	 * Set available add-ons.
	 *
	 * @since 2.0.0
	 * @param array $available_addons The available addons of this product.
	 * @return void
	 */
	public function set_available_addons($available_addons): void {

		$this->meta['wu_available_addons'] = $available_addons;

		$this->available_addons = $this->meta['wu_available_addons'];
	}

	/**
	 * Get the shareable link for this product, depending on the permalinks structure.
	 *
	 * @since 1.9.0
	 * @param int|boolean $deprecated Used to be the product freq.
	 * @return string
	 */
	public function get_shareable_link($deprecated = false) {

		return wu_get_registration_url($this->get_slug());
	}

	/**
	 * Get available add-ons.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_group() {

		return $this->product_group;
	}

	/**
	 * Set the group of this product.
	 *
	 * @since 2.0.0
	 * @param array $group The group of this product, if has any.
	 * @return void
	 */
	public function set_group($group): void {

		$this->product_group = $group;
	}

	/**
	 * Get if legacy options are available.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function get_legacy_options() {

		if (null === $this->legacy_options) {
			$this->legacy_options = $this->get_meta('legacy_options', false);
		}

		return $this->legacy_options;
	}

	/**
	 * Set if legacy options are available.
	 *
	 * @since 2.0.0
	 * @param bool $legacy_options If the legacy options are enabled.
	 * @return void
	 */
	public function set_legacy_options($legacy_options): void {

		$this->meta['legacy_options'] = $legacy_options;

		$this->legacy_options = $this->meta['legacy_options'];
	}

	/**
	 * List of limitations that need to be merged.
	 *
	 * Every model that is limitable (imports this trait)
	 * needs to declare explicitly the limitations that need to be
	 * merged. This allows us to chain the merges, and gives us
	 * a final list of limitations at the end of the process.
	 *
	 * In the case of products, there is nothing to add.
	 *
	 * @see \WP_Ultimo\Models\Traits\Trait_Limitable
	 * @since 2.0.0
	 * @return array
	 */
	public function limitations_to_merge() {

		return [];
	}
}
