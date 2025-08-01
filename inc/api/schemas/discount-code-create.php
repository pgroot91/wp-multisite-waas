<?php
/**
 * Schema for discount@code-create.
 *
 * @package WP_Ultimo\API\Schemas
 * @since 2.0.11
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Schema for discount@code-create.
 *
 * @since 2.0.11
 * @internal last-generated in 2022-12
 * @generated class generated by our build scripts, do not change!
 *
 * @since 2.0.11
 */
return [
	'name'              => [
		'description' => __('Your discount code name, which is used as discount code title as well.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => true,
	],
	'code'              => [
		'description' => __('A unique identification to redeem the discount code. E.g. PROMO10.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => true,
	],
	'description'       => [
		'description' => __('A description for the discount code, usually a short text.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'uses'              => [
		'description' => __('Number of times this discount was applied.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'max_uses'          => [
		'description' => __('The number of times this discount can be used before becoming inactive.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'apply_to_renewals' => [
		'description' => __('Wether or not we should apply the discount to membership renewals.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'type'              => [
		'description' => __("The type of the discount code. Can be 'percentage' (e.g. 10%% OFF), 'absolute' (e.g. $10 OFF).", 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
		'enum'        => [
			'percentage',
			'absolute',
		],
	],
	'value'             => [
		'description' => __('Amount discounted in cents.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => true,
	],
	'setup_fee_type'    => [
		'description' => __('Type of the discount for the setup fee value. Can be a percentage or absolute.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
		'enum'        => [
			'percentage',
			'absolute',
		],
	],
	'setup_fee_value'   => [
		'description' => __('Amount discounted for setup fees in cents.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'active'            => [
		'description' => __('Set this discount code as active (true), which means available to be used, or inactive (false).', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'date_start'        => [
		'description' => __('Start date for the coupon code to be considered valid.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_expiration'   => [
		'description' => __('Expiration date for the coupon code.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_created'      => [
		'description' => __('Date when this discount code was created.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'allowed_products'  => [
		'description' => __('The list of products that allows this discount code to be used. If empty, all products will accept this code.', 'multisite-ultimate'),
		'type'        => 'array',
		'required'    => false,
	],
	'limit_products'    => [
		'description' => __('This discount code will be limited to be used in certain products? If set to true, you must define a list of allowed products.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'date_modified'     => [
		'description' => __('Model last modification date.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'migrated_from_id'  => [
		'description' => __('The ID of the original 1.X model that was used to generate this item on migration.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'skip_validation'   => [
		'description' => __('Set true to have field information validation bypassed when saving this event.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
];
