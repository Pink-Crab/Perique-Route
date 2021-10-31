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
		$this->attributes['minLength'] = $min;
		return $this;
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_min_length(): ?int {
		return \array_key_exists( 'minLength', $this->attributes )
			? $this->attributes['minLength']
			: null;
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param int $max
	 * @return static
	 */
	public function max_length( int $max ): self {
		$this->attributes['maxLength'] = $max;
		return $this;
	}

	/**
	 * Gets the set max length, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_max_length(): ?int {
		return \array_key_exists( 'maxLength', $this->attributes )
			? $this->attributes['maxLength']
			: null;
	}

	/**
	 * Sets the pattern to validate
	 *
	 * @param string $pattern
	 * @return static
	 */
	public function pattern( string $pattern ): self {
		$this->attributes['pattern'] = $pattern;
		return $this;
	}

	/**
	 * Gets the set pattern, returns null if not set.
	 *
	 * @return string|null
	 */
	public function get_pattern(): ?string {
		return \array_key_exists( 'pattern', $this->attributes )
			? $this->attributes['pattern']
			: null;
	}

}
