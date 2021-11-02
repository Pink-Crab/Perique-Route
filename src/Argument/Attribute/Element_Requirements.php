<?php

declare(strict_types=1);

/**
 * Allows for all, any or one of relationship with array items and object properties.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument\Attribute;

trait Element_Requirements {

	/**
	 * Denotes the requirement of the elements
	 * Works with arrays and object properties.
	 *
	 * @var string
	 */
	protected $relationship = 'allOf';

	/**
	 * Sets if all of the elements are present.
	 *
	 * @return self
	 */
	public function all_of(): self {
		$this->relationship = 'allOf';
		return $this;
	}

	/**
	 * Sets if any of the elements are present.
	 *
	 * @return self
	 */
	public function any_of(): self {
		$this->relationship = 'anyOf';
		return $this;
	}

	/**
	 * Checks if one of the elements is present.
	 *
	 * @return self
	 */
	public function one_of(): self {
		$this->relationship = 'oneOf';
		return $this;
	}

	/**
	 * Gets the current relationship
	 *
	 * @return string
	 */
	public function get_relationship(): string {
		return $this->relationship;
	}
}
