<?php

declare(strict_types=1);

/**
 * Creates a union of values.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Schema;

class Union_Type extends Abstract_Type {

	/**
	 * Types union of
	 *
	 * @var Abstract_Type[]
	 */
	protected $types;

	/**
	 * Set types union of
	 *
	 * @param Abstract_Type[] $types  Types union of
	 *
	 * @return self
	 */
	public function __construct( array $types ) {
		$this->types = $types;
	}

	/**
	 * Creates a union of types.
	 *
	 * @param Abstract_Type ...$types
	 * @return self
	 */
	public static function of( Abstract_Type ...$types ): self {
		return new self( $types );
	}

	/**
	 * Get types union of
	 *
	 * @return Abstract_Type[]
	 */
	public function get_types(): array {
		return $this->types;
	}

}
