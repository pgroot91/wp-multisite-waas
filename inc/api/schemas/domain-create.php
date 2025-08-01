<?php
/**
 * Schema for domain@create.
 *
 * @package WP_Ultimo\API\Schemas
 * @since 2.0.11
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Schema for domain@create.
 *
 * @since 2.0.11
 * @internal last-generated in 2022-12
 * @generated class generated by our build scripts, do not change!
 *
 * @since 2.0.11
 */
return [
	'domain'           => [
		'description' => __("Your Domain name. You don't need to put http or https in front of your domain in this field. e.g: example.com.", 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => true,
	],
	'blog_id'          => [
		'description' => __('The blog ID attached to this domain.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => true,
	],
	'active'           => [
		'description' => __('Set this domain as active (true), which means available to be used, or inactive (false).', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'primary_domain'   => [
		'description' => __("Define true to set this as primary domain of a site, meaning it's the main url, or set false.", 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'secure'           => [
		'description' => __('If this domain has some SSL security or not.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
	'stage'            => [
		'description' => __('The state of the domain model object. Can be one of this options: checking-dns, checking-ssl-cert, done-without-ssl, done and failed.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => true,
		'enum'        => [
			'checking-dns',
			'checking-ssl-cert',
			'done-without-ssl',
			'done',
			'failed',
		],
	],
	'date_created'     => [
		'description' => __('Date when the domain was created. If no date is set, the current date and time will be used.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'date_modified'    => [
		'description' => __('Model last modification date.', 'multisite-ultimate'),
		'type'        => 'string',
		'required'    => false,
	],
	'migrated_from_id' => [
		'description' => __('The ID of the original 1.X model that was used to generate this item on migration.', 'multisite-ultimate'),
		'type'        => 'integer',
		'required'    => false,
	],
	'skip_validation'  => [
		'description' => __('Set true to have field information validation bypassed when saving this event.', 'multisite-ultimate'),
		'type'        => 'boolean',
		'required'    => false,
	],
];
