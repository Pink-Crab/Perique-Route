<?php

declare(strict_types=1);

/**
 * Base class for all types.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Schema;

abstract class Abstract_Type {

	/**
	 * Is required
	 *
	 * @var bool
	 */
	protected $required;

	/**
	 * The default value
	 *
	 * @var mixed
	 */
	protected $default;

	/**
	 * Checks if required
	 *
	 * @return bool
	 */
	public function is_required(): bool {
		return $this->required;
	}

	/**
	 * Sets if the type is required
	 *
	 * @param bool $required  Holds the items key
	 * @return self
	 */
	public function required( bool $required = true ): self {
		$this->required = $required;
		return $this;
	}

	/**
	 * Get the default value
	 *
	 * @return mixed
	 */
	public function get_default(): mixed {
		return $this->default;
	}

	/**
	 * Set the default value
	 *
	 * @param mixed $default  The default value
	 * @return self
	 */
	public function default( $default ): self {
		$this->default = $default;
		return $this;
	}
}
