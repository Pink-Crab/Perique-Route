<?php

declare(strict_types=1);

/**
 * Route Execptions
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

use Exception;

class Route_Exception  extends Exception {

	/**
	 * Returns Route_Exception for namespace not defined.
	 *
	 * @param string $route
	 * @return self
	 * @code 101
	 */
	public static function namespace_not_defined( string $route ): self {
		return new self(
			sprintf( 'Namespace not defined in %s', $route ),
			101
		);
	}

	/**
	 * Returns an exception for attempting to redeclare the namespace for a group
	 * This exists as they share the same abstract base class.
	 *
	 * @return self
	 * @code 102
	 */
	public static function can_not_redecalre_namespace_in_group(): self
	{
		return new self(
			"You can not redeclare the namespace for a group",
			102
		);
	}
}
