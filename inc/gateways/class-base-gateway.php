<?php
/**
 * Base Gateway.
 *
 * Base Gateway class. Should be extended to add new payment gateways.
 *
 * @package WP_Ultimo
 * @subpackage Managers/Site_Manager
 * @since 2.0.0
 */

namespace WP_Ultimo\Gateways;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Base Gateway class. Should be extended to add new payment gateways.
 *
 * For more info on actual implementations,
 * check the Gateway_Manual class and the Gateway_Stripe class.
 *
 * @since 2.0.0
 */
abstract class Base_Gateway {

	/**
	 * The gateway ID.
	 *
	 * A simple string that the class should set.
	 * e.g. stripe, manual, paypal, etc.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * Allow gateways to declare multiple additional ids.
	 *
	 * These ids can be retrieved alongside the main id,
	 * via the method get_all_ids().
	 *
	 * This is useful when dealing with different gateway implementations
	 * that share the same base code, or that have code that is applicable
	 * to other gateways.
	 *
	 * A classical example is the way Stripe is setup on Multisite Ultimate now:
	 * - We have two stripe gateways - stripe and stripe-checkout;
	 * - Both of those gateways inherit from class-base-stripe-gateway.php,
	 *   which deals with appending the remote gateway links to the admin panel,
	 *   for example.
	 * - The problem arises when the hooks are id-bound. If you have customer
	 *   that signup via stripe and later on you deactivate stripe in favor of
	 *   stripe-checkout, the admin panel links will stop working, as the hooks
	 *   are only triggered for stripe-checkout integrations, and old memberships
	 *   have stripe as the gateway.
	 * - If you declare the other ids here, the hooks will be loaded for the
	 *   other gateways, and that will no longer be a problem.
	 *
	 * @since 2.0.7
	 * @var array
	 */
	protected $other_ids = [];

	/**
	 * The order cart object.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Checkout\Cart
	 */
	protected $order;

	/**
	 * The customer.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Customer
	 */
	protected $customer;

	/**
	 * The membership.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Membership
	 */
	protected $membership;

	/**
	 * The payment.
	 *
	 * @since 2.0.0
	 * @var \WP_Ultimo\Models\Payment
	 */
	protected $payment;

	/**
	 * The URL to return.
	 *
	 * @since 2.1
	 * @var string
	 */
	protected $return_url;

	/**
	 * The cancel URL.
	 *
	 * @since 2.1
	 * @var string
	 */
	protected $cancel_url;

	/**
	 * The confirm URL.
	 *
	 * @since 2.1
	 * @var string
	 */
	protected $confirm_url;

	/**
	 * The discount code, if any.
	 *
	 * @since 2.0.0
	 * @var null|\WP_Ultimo\Models\Discount_Code
	 */
	protected $discount_code;

	/**
	 * Backwards compatibility for the old notify ajax url.
	 *
	 * @since 2.0.4
	 * @var bool|string
	 */
	protected $backwards_compatibility_v1_id = false;

	/**
	 * Initialized the gateway.
	 *
	 * @since 2.0.0
	 * @param null|\WP_Ultimo\Checkout\Cart $order A order cart object.
	 */
	public function __construct($order = null) {
		/*
		 * Loads the order, if any
		 */
		$this->set_order($order);

		/*
		 * Calls the init code.
		 */
		$this->init();
	}

	/**
	 * Sets an order.
	 *
	 * Useful for loading the order on a later
	 * stage, where the gateway object might
	 * have been already instantiated.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Checkout\Cart $order The order.
	 * @return void
	 */
	public function set_order($order): void {

		if (null === $order) {
			return;
		}

		/*
		 * The only thing we do is to set the order.
		 * It contains everything we need.
		 */
		$this->order = $order;

		/*
		 * Based on the order, we set the other
		 * useful parameters.
		 */
		$this->customer      = $this->order->get_customer();
		$this->membership    = $this->order->get_membership();
		$this->payment       = $this->order->get_payment();
		$this->discount_code = $this->order->get_discount_code();
	}

	/**
	 * Returns the id of the gateway.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	final public function get_id() {

		return $this->id;
	}

	/*
	 * Required Methods.
	 *
	 * The methods below are mandatory.
	 * You need to have them on your Gateway implementation
	 * even if they do nothing.
	 */

	/**
	 * Process a checkout.
	 *
	 * It takes the data concerning
	 * a new checkout and process it.
	 *
	 * Here's where you will want to send
	 * API calls to the gateway server,
	 * set up recurring payment profiles, etc.
	 *
	 * This method is required and MUST
	 * be implemented by gateways extending the
	 * Base_Gateway class.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment    $payment The payment associated with the checkout.
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @param \WP_Ultimo\Checkout\Cart     $cart The cart object.
	 * @param string                       $type The checkout type. Can be 'new', 'retry', 'upgrade', 'downgrade', 'addon'.
	 * @return bool
	 */
	abstract public function process_checkout($payment, $membership, $customer, $cart, $type);

	/**
	 * Process a cancellation.
	 *
	 * It takes the data concerning
	 * a membership cancellation and process it.
	 *
	 * Here's where you will want to send
	 * API calls to the gateway server,
	 * to cancel a recurring profile, etc.
	 *
	 * This method is required and MUST
	 * be implemented by gateways extending the
	 * Base_Gateway class.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @return bool|\WP_Error
	 */
	abstract public function process_cancellation($membership, $customer);

	/**
	 * Process a refund.
	 *
	 * It takes the data concerning
	 * a refund and process it.
	 *
	 * Here's where you will want to send
	 * API calls to the gateway server,
	 * to issue a refund.
	 *
	 * This method is required and MUST
	 * be implemented by gateways extending the
	 * Base_Gateway class.
	 *
	 * @since 2.0.0
	 *
	 * @param float                        $amount The amount to refund.
	 * @param \WP_Ultimo\Models\Payment    $payment The payment associated with the checkout.
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @return bool
	 */
	abstract public function process_refund($amount, $payment, $membership, $customer);

	/*
	 * Optional Methods.
	 *
	 * The methods below are good to have,
	 * but are not mandatory.
	 *
	 * You can implement the ones you need only.
	 * The base class provides defaults so you
	 * don't have to worry about the ones you
	 * don't need.
	 */

	/**
	 * Initialization code.
	 *
	 * This method gets called by the constructor.
	 * It is a good chance to set public properties to the
	 * gateway object and run preparations.
	 *
	 * For example, it's here that the Stripe Gateway
	 * sets its sandbox mode and API keys
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {}

	/**
	 * Adds Settings.
	 *
	 * This method allows developers to use
	 * Multisite Ultimate apis to add settings to the settings
	 * page.
	 *
	 * Gateways can use wu_register_settings_field
	 * to register API key fields and other options.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function settings() {}

	/**
	 * Checkout fields.
	 *
	 * This method gets called during the printing
	 * of the gateways section of the payment page.
	 *
	 * Use this to add the pertinent fields to your gateway
	 * like credit card number fields, for example.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function fields() {}

	/**
	 * Declares support for recurring payments.
	 *
	 * Not all gateways support the creation of
	 * automatically recurring payments.
	 *
	 * For those that don't, we need to manually
	 * create pending payments when the time comes
	 * and we use this declaration to decide that.
	 *
	 * If your gateway supports recurring payments
	 * (like Stripe or PayPal, for example)
	 * override this method to return true instead.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function supports_recurring() {

		return false;
	}

	/**
	 * Declares support for free trials.
	 *
	 * Multisite Ultimate offers to ways of dealing with free trials:
	 * (1) By asking for a payment method upfront; or
	 * (2) By not asking for a payment method until the trial is over.
	 *
	 * If you go the second route, Multisite Ultimate uses
	 * the free gateway to deal with the first payment (which will be 0)
	 *
	 * If you go the first route, though, the payment gateway
	 * must be able to handle delayed first payments.
	 *
	 * If that's the case for your payment gateway,
	 * override this method to return true.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function supports_free_trials() {

		return false;
	}

	/**
	 * Declares support for recurring amount updates.
	 *
	 * Some gateways can update the amount of a recurring
	 * payment. For example, Stripe allows you to update
	 * the amount of a subscription.
	 *
	 * If your gateway supports this, override this
	 * method to return true. You will also need to
	 * implement the process_membership_update() method.
	 *
	 * @since 2.1.2
	 * @return bool
	 */
	public function supports_amount_update() {

		return false;
	}

	/**
	 * Handles payment method updates.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function update_payment_method() {}

	/**
	 * Defines a public title.
	 *
	 * This is useful to be able to define a nice-name
	 * for a gateway that will make more sense for customers.
	 *
	 * Stripe, for example, sets this value to 'Credit Card'
	 * as showing up simply as Stripe would confuse customers.
	 *
	 * By default, we use the title passed when calling
	 * wu_register_gateway().
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_public_title() {

		$gateways = wu_get_gateways();

		$registered_gateway = wu_get_isset($gateways, $this->get_id());

		if ( ! $registered_gateway) {
			$default = $this->get_id();
			$default = str_replace('-', ' ', $default);

			return ucwords($default);
		}

		return $registered_gateway['title'];
	}

	/**
	 * Adds additional hooks.
	 *
	 * Useful to add additional hooks and filters
	 * that do not need to be set during initialization.
	 *
	 * As this runs later on the wp lifecycle, user apis
	 * and other goodies are available.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function hooks() {}

	/**
	 * Run preparations before checkout processing.
	 *
	 * This runs during the checkout form validation
	 * and it is a great chance to do preflight stuff
	 * if the gateway requires it.
	 *
	 * If you return an array here, Ultimo
	 * will append the key => value of that array
	 * as hidden fields to the checkout field,
	 * and those get submitted with the rest of the form.
	 *
	 * As an example, this is how we create payment
	 * intents for Stripe to make the experience more
	 * streamlined.
	 *
	 * @since 2.0.0
	 * @return void|array
	 */
	public function run_preflight() {}

	/**
	 * Registers and Enqueue scripts.
	 *
	 * This method gets called during the rendering
	 * of the checkout page, so you can use it
	 * to register and enqueue custom scripts
	 * and styles.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts() {}

	/**
	 * Gives gateways a chance to run things before backwards compatible webhooks are run.
	 *
	 * @since 2.0.7
	 * @return void
	 */
	public function before_backwards_compatible_webhook() {}

	/**
	 * Handles webhook calls.
	 *
	 * This is the endpoint that gets called
	 * when a webhook message is posted to the gateway
	 * endpoint.
	 *
	 * You should process the message, if necessary,
	 * and take the appropriate actions, such as
	 * renewing memberships, marking payments as complete, etc.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function process_webhooks() {}

	/**
	 * Handles confirmation windows and extra processing.
	 *
	 * This endpoint gets called when we get to the
	 * /confirm/ URL on the registration page.
	 *
	 * For example, PayPal needs a confirmation screen.
	 * And it uses this method to handle that.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function process_confirmation() {}

	/**
	 * Returns the external link to view the payment on the payment gateway.
	 *
	 * Return an empty string to hide the link element.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_payment_id The gateway payment id.
	 * @return void|string
	 */
	public function get_payment_url_on_gateway($gateway_payment_id) {}

	/**
	 * Returns the external link to view the membership on the membership gateway.
	 *
	 * Return an empty string to hide the link element.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_subscription_id The gateway subscription id.
	 * @return void|string.
	 */
	public function get_subscription_url_on_gateway($gateway_subscription_id) {}

	/**
	 * Returns the external link to view the membership on the membership gateway.
	 *
	 * Return an empty string to hide the link element.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_customer_id The gateway customer id.
	 * @return void|string.
	 */
	public function get_customer_url_on_gateway($gateway_customer_id) {}

	/**
	 * Reflects membership changes on the gateway.
	 *
	 * By default, this method will process tha cancellation of current gateway subscription
	 *
	 * @since 2.1.3
	 *
	 * @param \WP_Ultimo\Models\Membership $membership The membership object.
	 * @param \WP_Ultimo\Models\Customer   $customer   The customer object.
	 * @return bool|\WP_Error true if it's all done or error object if something went wrong.
	 */
	public function process_membership_update(&$membership, $customer) {

		$original = $membership->_get_original();

		$has_amount_change   = (float) $membership->get_amount() !== (float) wu_get_isset($original, 'amount');
		$has_duration_change = $membership->get_duration() !== absint(wu_get_isset($original, 'duration')) || $membership->get_duration_unit() !== wu_get_isset($original, 'duration_unit');

		// If there is no change in amount or duration, we don't do anything here.
		if ( ! $has_amount_change && ! $has_duration_change) {
			return true;
		}

		// Cancel the current gateway integration.
		$cancellation = $this->process_cancellation($membership, $customer);

		if (is_wp_error($cancellation)) {
			return $cancellation;
		}

		// Reset the gateway in the membership object.
		$membership->set_gateway('');
		$membership->set_gateway_customer_id('');
		$membership->set_gateway_subscription_id('');
		$membership->set_auto_renew(false);

		return true;
	}

	/*
	 * Helper methods
	 */

	/**
	 * Returns a message about what will happen to the gateway subscription
	 * when the membership is updated.
	 *
	 * @since 2.1.2
	 *
	 * @param bool $to_customer Whether the message is being shown to the customer or not.
	 * @return string
	 */
	public function get_amount_update_message($to_customer = false) {

		if ( ! $this->supports_amount_update()) {
			$message = __('The current payment integration will be cancelled.', 'multisite-ultimate');

			if ($to_customer) {
				$message .= ' ' . __('You will receive a new invoice on the next billing cycle.', 'multisite-ultimate');
			} else {
				$message .= ' ' . __('The customer will receive a new invoice on the next billing cycle.', 'multisite-ultimate');
			}

			return $message;
		}

		return __('The current payment integration will be updated.', 'multisite-ultimate');
	}

	/**
	 * Get the return URL.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_return_url() {

		if (empty($this->return_url)) {
			$this->return_url = wu_get_current_url();
		}

		$return_url = is_admin() ? admin_url('admin.php') : $this->return_url;

		$return_url = remove_query_arg(
			[
				'wu-confirm',
				'token',
				'PayerID',
			],
			$return_url
		);

		if (is_admin()) {
			$args = ['page' => 'account'];

			if ($this->order) {
				$args['updated'] = $this->order->get_cart_type();
			}

			$return_url = add_query_arg($args, $return_url);
		} else {
			$return_url = add_query_arg(
				[
					'payment' => $this->payment->get_hash(),
					'status'  => 'done',
				],
				$return_url
			);
		}

		/**
		 * Allow developers to change the gateway return URL used after checkout processes.
		 *
		 * @since 2.0.20
		 *
		 * @param string                    $return_url the URL to redirect after process.
		 * @param self                      $gateway the gateway instance.
		 * @param \WP_Ultimo\Models\Payment $payment the Multisite Ultimate payment instance.
		 * @param \WP_Ultimo\Checkout\Cart  $cart the current Multisite Ultimate cart order.
		 * @return string
		 */
		return apply_filters('wu_return_url', $return_url, $this, $this->payment, $this->order);
	}

	/**
	 * Get the cancel URL.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_cancel_url() {

		if (empty($this->cancel_url)) {
			$this->cancel_url = wu_get_current_url();
		}

		return add_query_arg(
			[
				'payment' => $this->payment->get_hash(),
			],
			$this->cancel_url
		);
	}

	/**
	 * Get the confirm URL.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_confirm_url() {

		if (empty($this->confirm_url)) {
			$this->confirm_url = wu_get_current_url();
		}

		return add_query_arg(
			[
				'payment'    => $this->payment->get_hash(),
				'wu-confirm' => $this->get_id(),
			],
			$this->confirm_url
		);
	}

	/**
	 * Returns the webhook url for the listener of this gateway events.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_webhook_listener_url() {

		$site_url = defined('WU_GATEWAY_LISTENER_URL') ? WU_GATEWAY_LISTENER_URL : get_site_url(wu_get_main_site_id(), '/');

		return add_query_arg('wu-gateway', $this->get_id(), $site_url);
	}

	/**
	 * Set the payment.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Models\Payment $payment The payment.
	 * @return void
	 */
	public function set_payment($payment): void {

		$this->payment = $payment;
	}

	/**
	 * Set the membership.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @return void
	 */
	public function set_membership($membership): void {

		$this->membership = $membership;
	}

	/**
	 * Set the customer.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Models\Customer $customer The customer.
	 * @return void
	 */
	public function set_customer($customer): void {

		$this->customer = $customer;
	}

	/**
	 * Triggers the events related to processing a payment.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment    $payment The payment model.
	 * @param \WP_Ultimo\Models\Membership $membership The membership object.
	 * @return void
	 */
	public function trigger_payment_processed($payment, $membership = null): void {

		if (null === $membership) {
			$membership = $payment->get_membership();
		}

		do_action('wu_gateway_payment_processed', $payment, $membership, $this);
	}

	/**
	 * Save a cart for a future swap.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Checkout\Cart $cart The cart to swap to.
	 * @return string
	 */
	public function save_swap($cart) {

		$swap_id = uniqid('wu_swap_');

		set_site_transient($swap_id, $cart, DAY_IN_SECONDS);

		return $swap_id;
	}

	/**
	 * Gets a saved swap based on the id.
	 *
	 * @since 2.0.0
	 *
	 * @param string $swap_id The saved swap id.
	 * @return \WP_Ultimo\Checkout\Cart|false
	 */
	public function get_saved_swap($swap_id) {

		return get_site_transient($swap_id);
	}

	/**
	 * Get the compatibility ids for this gateway.
	 *
	 * @since 2.0.7
	 * @return array
	 */
	public function get_all_ids() {

		$all_ids = array_merge([$this->get_id()], (array) $this->other_ids);

		return array_unique($all_ids);
	}

	/**
	 * Returns the backwards compatibility id of the gateway from v1.
	 *
	 * @since 2.0.4
	 * @return string
	 */
	public function get_backwards_compatibility_v1_id() {

		return $this->backwards_compatibility_v1_id;
	}
}
