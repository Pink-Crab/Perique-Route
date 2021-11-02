<?php

declare(strict_types=1);

/**
 * Shared attributes between Number (FLOAT) and Integer (INT) types.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument\Attribute;

trait Number_Attributes {

	/**
	 * Sets the min length of the value
	 *
	 * @param bool $min
	 * @return static
	 */
	public function exclusive_minimum( bool $min = true ): self {
		return $this->add_attribute( 'exclusiveMinimum', $min );
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return bool|null
	 */
	public function get_exclusive_minimum(): ?bool {
		return $this->get_attribute( 'exclusiveMinimum' );
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param bool $max
	 * @return static
	 */
	public function exclusive_maximum( bool $max = true ): self {
		return $this->add_attribute( 'exclusiveMaximum', $max );
	}

	/**
	 * Gets the set max length, returns null if not set.
	 *
	 * @return bool|null
	 */
	public function get_exclusive_maximum(): ?bool {
		return $this->get_attribute( 'exclusiveMaximum' );
	}

		/**
	 * Sets the multiple_of to validate
	 *
	 * @param float $multiple_of
	 * @return static
	 */
	public function multiple_of( float $multiple_of ): self {
		return $this->add_attribute( 'multipleOf', $multiple_of );
	}

	/**
	 * Gets the set multiple_of, returns null if not set.
	 *
	 * @return float|null
	 */
	public function get_multiple_of(): ?float {
		return $this->get_attribute( 'multipleOf' );
	}
}
