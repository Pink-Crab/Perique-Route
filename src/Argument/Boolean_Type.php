<?php

declare(strict_types=1);

/**
 * Boolean Argument type.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use PinkCrab\Route\Argument\Argument;

class Boolean_Type extends Argument {

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_BOOLEAN );
	}
}
