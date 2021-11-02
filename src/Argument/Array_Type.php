<?php

declare(strict_types=1);

/**
 * Array Argument type.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use PinkCrab\Route\Argument\Attribute\Children;
use PinkCrab\Route\Argument\Attribute\Element_Requirements;

class Array_Type extends Argument {

	use Element_Requirements, Children;

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_ARRAY );
	}

	/**
	 * Adds a single item
	 *
	 * @param Argument $item
	 * @return self
	 */
	public function item( Argument $item ): self {
		// Set the array if it doesnt already exist.
		if ( ! \array_key_exists( 'items', $this->attributes ) ) {
			$this->attributes['items'] = array();
		}
		$this->attributes['items'][] = $item;
		return $this;
	}

	/**
	 * Creates a item based on the passed type.
	 *
	 * @param string $type
	 * @param callable|null $config
	 * @return self
	 */
	protected function create_item_type( string $type, ?callable $config = null ): self {
		// Set the array if it doesn't already exist.
		if ( ! \array_key_exists( 'items', $this->attributes ) ) {
			$this->attributes['items'] = array();
		}

		// Create the item
		$last_index = array_key_last( $this->attributes['items'] );
		$item_key   = sprintf( 'item_type_%d', is_null( $last_index ) ? 0 : ( $last_index + 1 ) );
		$item       = $this->create_child( $item_key, $type );

		return $this->item(
			is_null( $config ) ? $item : $config( $item )
		);
	}

	/**
	 * Add a string type to items
	 *
	 * @param callable(String_Type $item):String_Type $config
	 * @return self
	 */
	public function string_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_STRING, $config );
	}

	/**
	 * Add a number type to items
	 *
	 * @param callable(Number_Type $item):Number_Type $config
	 * @return self
	 */
	public function number_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_NUMBER, $config );
	}

	/**
	 * Add a integer type to items
	 *
	 * @param callable(Integer_Type $item):Integer_Type $config
	 * @return self
	 */
	public function integer_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_INTEGER, $config );
	}

	/**
	 * Add a Boolean type to items
	 *
	 * @param callable(Boolean_Type $item):Boolean_Type $config
	 * @return self
	 */
	public function boolean_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_BOOLEAN, $config );
	}

	/**
	 * Add a Array type to items
	 *
	 * @param callable(Array_Type $item):Array_Type $config
	 * @return self
	 */
	public function array_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_ARRAY, $config );
	}

	/**
	 * Add a Null type to items
	 *
	 * @param callable(Null_Type $item):Null_Type $config
	 * @return self
	 */
	public function null_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_NULL, $config );
	}

	/**
	 * Add a Object type to items
	 *
	 * @param callable(Object_Type $item):Object_Type $config
	 * @return self
	 */
	public function object_item( ?callable $config = null ): self {
		return $this->create_item_type( Argument::TYPE_OBJECT, $config );
	}

	/**
	 * Get the item schema
	 *
	 * @return array<int,Argument>|null
	 */
	public function get_items(): ?array {
		return $this->get_attribute( 'items' );
	}

	/**
	 * Checks if Array has items.
	 *
	 * @return bool
	 */
	public function has_items(): bool {
		$items = $this->get_attribute( 'items' );
		return is_array( $items ) && ! empty( $items );
	}

	/**
	 * Gets the count of items.
	 *
	 * @return int
	 */
	public function item_count(): int {
		$items = $this->get_attribute( 'items' );
		return ! is_array( $items )
			? 0
			: count( $items );
	}

	/**
	 * Sets the min items of the value
	 *
	 * @param int $min
	 * @return static
	 */
	public function min_items( int $min ): self {
		return $this->add_attribute( 'minItems', $min );
	}

	/**
	 * Gets the set min items, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_min_items(): ?int {
		return $this->get_attribute( 'minItems' );
	}

	/**
	 * Sets the max items of the value
	 *
	 * @param int $max
	 * @return static
	 */
	public function max_items( int $max ): self {
		return $this->add_attribute( 'maxItems', $max );
	}

	/**
	 * Gets the set max items, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_max_items(): ?int {
		return $this->get_attribute( 'maxItems' );
	}

	/**
	 * Sets the array should only contain unique items
	 *
	 * @param bool $unique
	 * @return self
	 */
	public function unique_items( bool $unique = true ): self {
		return $this->add_attribute( 'uniqueItems', $unique );
	}

	/**
	 * Gets if the array should contain only unique items or not
	 * Returns null if not set.
	 *
	 * @return bool|null
	 */
	public function get_unique_items(): ?bool {
		return $this->get_attribute( 'uniqueItems' );
	}

}
