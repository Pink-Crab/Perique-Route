<?php

declare(strict_types=1);

/**
 * Integer Argument type.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use PinkCrab\Route\Argument\Argument;
use PinkCrab\Route\Argument\Attribute\Number_Attributes;

class Integer_Type extends Argument {

	/**
	 * @method static exclusive_minimum( bool $min ): self
	 * @method static exclusive_maximum( bool $min ): self
	 * @method static exclusive_maximum( float $multiple_of ): self
	 * @method bool|null get_exclusive_maximum(): ?bool
	 * @method bool|null get_exclusive_minimum(): ?bool
	 * @method float|null get_multiple_of(): ?float
	 */
	use Number_Attributes;

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_INTEGER );
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

}
