<?php
/**
 * Schema for membership@update.
 *
 * @package WP_Ultimo\API\Schemas
 * @since 2.0.11
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

use WP_Ultimo\Database\Memberships\Membership_Status;

/**
 * Schema for membership@update.
 *
 * @since 2.0.11
 * @internal last-generated in 2022-12
 * @generated class generated by our build scripts, do not change!
 *
 * @since 2.0.11
 */
return [
	'customer_id'                 => [
		'description' => __('The ID of the customer attached to this membership.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'user_id'                     => [
		'description' => __('The user ID attached to this membership.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'plan_id'                     => [
		'description' => __('The plan ID associated with the membership.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'addon_products'              => [
		'description' => __('Additional products related to this membership. Services, Packages or other types of products.', 'multisite-ultimate'),
		'type'        => 'mixed',
		'required'    => false,
	],
	'currency'                    => [
		'description' => __("The currency that this membership. It's a 3-letter code. E.g. 'USD'.", 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'duration'                    => [
		'description' => __('The interval period between a charge. Only the interval amount, the unit will be defined in another property.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'duration_unit'               => [
		'description' => __("The duration amount type. Can be 'day', 'week', 'month' or 'year'.", 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
		'enum'        => [
			'day',
			'month',
			'week',
			'year',
		],
	],
	'amount'                      => [
		'description' => __('The product amount.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'initial_amount'              => [
		'description' => __('The initial amount charged for this membership, including the setup fee.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'date_created'                => [
		'description' => __('Date of creation of this membership.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_activated'              => [
		'description' => __('Date when this membership was activated.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_trial_end'              => [
		'description' => __('Date when the trial period ends, if this membership has or had a trial period.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_renewed'                => [
		'description' => __('Date when the membership was cancelled.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_cancellation'           => [
		'description' => __('Date when the membership was cancelled.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_expiration'             => [
		'description' => __('Date when the membership will expiry.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_payment_plan_completed' => [
		'description' => __('Change of the payment completion for the plan value.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'auto_renew'                  => [
		'description' => __('If this membership should auto-renewal.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'times_billed'                => [
		'description' => __('Amount of times this membership got billed.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'billing_cycles'              => [
		'description' => __('Maximum times we should charge this membership.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'status'                      => [
		'description' => __("The membership status. Can be 'pending', 'active', 'on-hold', 'expired', 'cancelled' or other values added by third-party add-ons.", 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
		'enum'        => Membership_Status::get_allowed_list(),
	],
	'gateway_customer_id'         => [
		'description' => __('The ID of the customer on the payment gateway database.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'gateway_subscription_id'     => [
		'description' => __('The ID of the subscription on the payment gateway database.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'gateway'                     => [
		'description' => __('ID of the gateway being used on this subscription.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'signup_method'               => [
		'description' => __('Signup method used to create this membership.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'upgraded_from'               => [
		'description' => __('Plan that this membership upgraded from.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'date_modified'               => [
		'description' => __('Date this membership was last modified.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'disabled'                    => [
		'description' => __('If this membership is a disabled one.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'recurring'                   => [
		'description' => __('If this membership is recurring (true), which means the customer paid a defined amount each period of time, or not recurring (false).', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'migrated_from_id'            => [
		'description' => __('The ID of the original 1.X model that was used to generate this item on migration.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'skip_validation'             => [
		'description' => __('Set true to have field information validation bypassed when saving this event.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
];
