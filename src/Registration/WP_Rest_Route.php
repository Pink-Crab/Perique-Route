<?php

declare(strict_types=1);

/**
 * Basic model of a WP Rest route
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Registration;

class WP_Rest_Route {

	/**
	 * The rest namespace
	 *
	 * @var string
	 */
	public string $namespace;

	/**
	 * The rest route
	 *
	 * @var string
	 */
	public string $route;

	/**
	 * The route args
	 *
	 * @var array<mixed>
	 */
	public array $args = array();

	/**
	 * Should this override an existing namespace if set
	 *
	 * @var boolean
	 */
	public bool $override = false;
}
