<?php

declare(strict_types=1);

/**
 * String Argument type.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use PinkCrab\Route\Argument\Argument;

class String_Type extends Argument {

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_STRING );
	}

	/**
	 * Sets the min length of the value
	 *
	 * @param int $min
	 * @return static
	 */
	public function min_length( int $min ): self {
		return $this->add_attribute( 'minLength', $min );
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_min_length(): ?int {
		return $this->get_attribute( 'minLength' );
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param int $max
	 * @return static
	 */
	public function max_length( int $max ): self {
		return $this->add_attribute( 'maxLength', $max );
	}

	/**
	 * Gets the set max length, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_max_length(): ?int {
		return $this->get_attribute( 'maxLength' );
	}

	/**
	 * Sets the pattern to validate
	 *
	 * @param string $pattern
	 * @return static
	 */
	public function pattern( string $pattern ): self {
		return $this->add_attribute( 'pattern', $pattern );
	}

	/**
	 * Gets the set pattern, returns null if not set.
	 *
	 * @return string|null
	 */
	public function get_pattern(): ?string {
		return $this->get_attribute( 'pattern' );
	}

}
