<?php

declare(strict_types=1);

/**
 * Object Argument type.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use PinkCrab\Route\Argument\Argument;
use PinkCrab\Route\Argument\Attribute\Children;
use PinkCrab\Route\Argument\Attribute\Element_Requirements;

class Object_Type extends Argument {

	use Children, Element_Requirements;

	/**
	 * Properties
	 *
	 * @var array<string, Argument>
	 */
	protected $properties = array();

	/**
	 * Additional properties (optional)
	 *
	 * @var array<string, Argument>
	 */
	protected $additional_properties = array();

	/**
	 * Properties based on the pattern.
	 *
	 * @var array<string, Argument>
	 */
	protected $pattern_properties = array();

	public function __construct( string $key ) {
		parent::__construct( $key );
		$this->type( Argument::TYPE_OBJECT );
	}

	/**
	 * Sets the min properties of the value
	 *
	 * @param int $min
	 * @return static
	 */
	public function min_properties( int $min ): self {
		return $this->add_attribute( 'minProperties', $min );
	}

	/**
	 * Gets the set min properties, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_min_properties(): ?int {
		return $this->get_attribute( 'minProperties' );
	}

	/**
	 * Sets the max properties of the value
	 *
	 * @param int $max
	 * @return static
	 */
	public function max_properties( int $max ): self {
		return $this->add_attribute( 'maxProperties', $max );
	}

	/**
	 * Gets the set max properties, returns null if not set.
	 *
	 * @return int|null
	 */
	public function get_max_properties(): ?int {
		return $this->get_attribute( 'maxProperties' );
	}


	/**
	 * Regular Properties.
	 */

	/**
	 * Adds a property
	 *
	 * @param string $name           The property name
	 * @param string $type           The type class name
	 * @param callable|null $config
	 * @return static
	 */
	protected function add_property( string $name, string $type, ?callable $config = null ): self {
		$item                      = $this->create_child( $name, $type )->name( $name );
		$this->properties[ $name ] = is_null( $config ) ? $item : $config( $item );
		return $this;
	}

	/**
	 * Creates a string property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function string_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_STRING, $config );
	}

	/**
	 * Creates a number property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function number_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_NUMBER, $config );
	}

	/**
	 * Creates a integer property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function integer_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_INTEGER, $config );
	}

	/**
	 * Creates a null property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function null_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_NULL, $config );
	}

	/**
	 * Creates a boolean property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function boolean_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_BOOLEAN, $config );
	}

	/**
	 * Creates a array property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function array_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_ARRAY, $config );
	}

	/**
	 * Creates a object property
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function object_property( string $name, ?callable $config = null ): self {
		return $this->add_property( $name, Argument::TYPE_OBJECT, $config );
	}

	/**
	 * Gets all the properties for the object.
	 *
	 * @return array<string, Argument>
	 */
	public function get_properties(): array {
		return $this->properties;
	}

	/**
	 * ADDITIONAL PROPERTIES
	 */

	/**
	 * Adds a additional property
	 *
	 * @param string $name           The property name
	 * @param string $type           The type class name
	 * @param callable|null $config
	 * @return static
	 */
	protected function add_additional_property( string $name, string $type, ?callable $config = null ): self {
		$item                                 = $this->create_child( $name, $type )->name( $name );
		$this->additional_properties[ $name ] = is_null( $config ) ? $item : $config( $item );
		return $this;
	}

	/**
	 * Creates a string typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function string_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_STRING, $config );
	}

		/**
	 * Creates a number typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function number_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_NUMBER, $config );
	}

	/**
	 * Creates an integer typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function integer_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_INTEGER, $config );
	}

	/**
	 * Creates a null typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function null_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_NULL, $config );
	}

	/**
	 * Creates a boolean typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function boolean_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_BOOLEAN, $config );
	}

	/**
	 * Creates an array typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function array_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_ARRAY, $config );
	}

	/**
	 * Creates an object typed, additional property.
	 *
	 * @param string $name
	 * @param callable|null $config
	 * @return static
	 */
	public function object_additional_property( string $name, ?callable $config = null ): self {
		return $this->add_additional_property( $name, Argument::TYPE_OBJECT, $config );
	}

	/**
	 * Gets all the addition properties for the object.
	 *
	 * @return array<string, Argument>
	 */
	public function get_additional_properties(): array {
		return $this->additional_properties;
	}

		/**
	 * ADDITIONAL PROPERTIES
	 */

	/**
	 * Adds a pattern property
	 *
	 * @param string $pattern        The property pattern
	 * @param string $type           The type class name
	 * @param callable|null $config
	 * @return static
	 */
	protected function add_pattern_property( string $pattern, string $type, ?callable $config = null ): self {
		$item                                 = $this->create_child( $pattern, $type )->name( $pattern );
		$this->pattern_properties[ $pattern ] = is_null( $config ) ? $item : $config( $item );
		return $this;
	}

	/**
	 * Creates a string typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function string_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_STRING, $config );
	}

		/**
	 * Creates a number typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function number_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_NUMBER, $config );
	}

	/**
	 * Creates an integer typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function integer_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_INTEGER, $config );
	}

	/**
	 * Creates a null typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function null_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_NULL, $config );
	}

	/**
	 * Creates a boolean typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function boolean_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_BOOLEAN, $config );
	}

	/**
	 * Creates an array typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function array_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_ARRAY, $config );
	}

	/**
	 * Creates an object typed, pattern property.
	 *
	 * @param string $pattern
	 * @param callable|null $config
	 * @return static
	 */
	public function object_pattern_property( string $pattern, ?callable $config = null ): self {
		return $this->add_pattern_property( $pattern, Argument::TYPE_OBJECT, $config );
	}

	/**
	 * Gets all the addition properties for the object.
	 *
	 * @return array<string, Argument>
	 */
	public function get_pattern_properties(): array {
		return $this->pattern_properties;
	}
}
