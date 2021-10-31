<?php

declare(strict_types=1);

/**
 * Number Argument type.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

class Number_Type extends Argument {

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_NUMBER );
	}

	/**
	 * Sets the min length of the value
	 *
	 * @param int $min
	 * @return static
	 */
	public function minimum( int $min ): self {
		$this->attributes['minimum'] = $min;
		return $this;
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_minimum(): ?int {
		return \array_key_exists( 'minimum', $this->attributes )
			? $this->attributes['minimum']
			: null;
	}

	/**
	 * Sets the min length of the value
	 *
	 * @param bool $min
	 * @return static
	 */
	public function exclusive_minimum( bool $min ): self {
		$this->attributes['exclusiveMinimum'] = $min;
		return $this;
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return bool|null
	 */
	public function get_exclusive_minimum(): ?bool {
		return \array_key_exists( 'exclusiveMinimum', $this->attributes )
			? $this->attributes['exclusiveMinimum']
			: null;
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param int $max
	 * @return static
	 */
	public function maximum( int $max ): self {
		$this->attributes['maximum'] = $max;
		return $this;
	}

	/**
	 * Gets the set max length, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_maximum(): ?int {
		return \array_key_exists( 'maximum', $this->attributes )
			? $this->attributes['maximum']
			: null;
	}

	/**
	 * Sets the min length of the value
	 *
	 * @param bool $min
	 * @return static
	 */
	public function exclusive_maximum( bool $min ): self {
		$this->attributes['exclusiveMaximum'] = $min;
		return $this;
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return bool|null
	 */
	public function get_exclusive_maximum(): ?bool {
		return \array_key_exists( 'exclusiveMaximum', $this->attributes )
			? $this->attributes['exclusiveMaximum']
			: null;
	}

	/**
	 * Sets the multiple_of to validate
	 *
	 * @param float|int $multiple_of
	 * @return static
	 */
	public function multiple_of( $multiple_of ): self {
		$this->attributes['multipleOf'] = (float) $multiple_of;
		return $this;
	}

	/**
	 * Gets the set multiple_of, returns null if not set.
	 *
	 * @return float|null
	 */
	public function get_multiple_of(): ?float {
		return \array_key_exists( 'multipleOf', $this->attributes )
			? $this->attributes['multipleOf']
			: null;
	}

}
