<?php

declare(strict_types=1);

/**
 * A route argument definition.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use TypeError;

class Argument {


	/**
	 * All valid value types.
	 */
	/** @var string */
	public const TYPE_STRING = 'string';
	/** @var string */
	public const TYPE_BOOLEAN = 'boolean';
	/** @var string */
	public const TYPE_INTEGER = 'integer';
	/** @var string */
	public const TYPE_NUMBER = 'number';
	/** @var string */
	public const TYPE_ARRAY = 'array';
	/** @var string */
	public const TYPE_OBJECT = 'object';
	/** @var string */
	public const TYPE_NULL = 'null';

	/**
	 * All valid format types.
	 */
	/** @var string */
	public const FORMAT_DATE_TIME = 'date-time';
	/** @var string */
	public const FORMAT_EMAIL = 'email';
	/** @var string */
	public const FORMAT_IP = 'ip';
	/** @var string */
	public const FORMAT_URL = 'url';
	/** @var string */
	public const FORMAT_UUID = 'uuid';
	/** @var string */
	public const FORMAT_HEX = 'hex-color';


	/**
	 * The argument key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Callback to validate value
	 *
	 * @var callable(param:string,request::\WP_REST_Request,key:string):bool|null
	 */
	protected $validation;

	/**
	 * Sanitizes the output
	 *
	 * @var callable(value:mixed):bool|null
	 */
	protected $sanitization;

	/**
	 * Is this argument required
	 *
	 * @var bool|null
	 */
	protected $required;

	/**
	 * The data type of the argument.
	 *
	 * @var string|array<int, string>|null
	 */
	protected $type;

	/**
	 * The argument description.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * An array of all the attributes for the Argument.
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = array();

	/**
	 * The default value
	 *
	 * @var string|int|float|bool|null
	 */
	protected $default;

	/**
	 * Optional format to expect value.
	 *
	 * @var string|null
	 */
	protected $format;

	/**
	 * Enum of all accepted values
	 *
	 * @var array<string|float|int|bool>|null
	 */
	protected $expected;


	public function __construct( string $key ) {
		$this->key = $key;
	}

	/**
	 * Static constructor.
	 *
	 * @param string $key
	 * @param callable $config
	 * @return static
	 */
	final public static function on( string $key, ?callable $config = null ): self {
		$class    = get_called_class();
		$argument = new $class( $key );
		return $config
			? $config( $argument )
			: $argument;
	}

	/**
	 * Get the argument key
	 *
	 * @return string
	 */
	public function get_key(): string {
		return $this->key;
	}

	/**
	 * Get callback to validate value
	 *
	 * @return callable(string, \WP_REST_Request, string): bool|null
	 */
	public function get_validation(): ?callable {
		return $this->validation;
	}

	/**
	 * Set callback to validate value
	 *
	 * @param callable(string, \WP_REST_Request, string): bool $validation  Callback to validate value
	 *
	 * @return static
	 */
	public function validation( callable $validation ): self {
		$this->validation = $validation;
		return $this;
	}

	/**
	 * Get sanitizes the output
	 *
	 * @return callable(mixed):mixed|null
	 * bool
	 */
	public function get_sanitization(): ?callable {
		return $this->sanitization;
	}

	/**
	 * Set sanitizes the output
	 *
	 * @param callable(mixed): bool $sanitization  Sanitizes the output
	 *
	 * @return static
	 */
	public function sanitization( callable $sanitization ): self {
		$this->sanitization = $sanitization;
		return $this;
	}

	/**
	 * Get the default value
	 *
	 * @return string|int|float|bool|null
	 */
	public function get_default() {
		return $this->default;
	}

	/**
	 * Checks if the argument has a default assigned.
	 *
	 * @return bool
	 */
	public function has_default(): bool {
		return ! is_null( $this->default );
	}

	/**
	 * Set the default value
	 *
	 * @param string|int|float|bool $default  The default value
	 * @return static
	 */
	public function default( $default ): self {
		$this->default = $default;
		return $this;
	}

	/**
	 * Get is this argument required
	 *
	 * @return bool
	 */
	public function is_required(): bool {
		return $this->required ?? false;
	}

	/**
	 * Get the data type of the argument.
	 *
	 * @return bool|null
	 */
	public function get_required(): ?bool {
		return $this->required;
	}

	/**
	 * Set is this argument required
	 *
	 * @param bool $required  Is this argument required
	 * @return static
	 */
	public function required( bool $required = true ): self {
		$this->required = $required;
		return $this;
	}

	/**
	 * Get the data type of the argument.
	 *
	 * @return string|string[]|null
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Set the data type of the argument.
	 *
	 * @param string|string[]|mixed $type  The data type of the argument.
	 *
	 * @return static
	 */
	public function type( $type ): self {
		if ( ! is_string( $type ) && ! is_array( $type ) ) {
			throw new TypeError( 'Only single types or array of types are allowed with arguments.' );
		}

		$this->type = $type;
		return $this;
	}

	/**
	 * Adds an extra type to use as a union type with.
	 * Please note doesn't add attributes for additional types.
	 *
	 * @param string $type
	 * @return self
	 */
	public function union_with_type( string $type ): self {
		// Cast the current types to array, if not already.
		if ( ! is_array( $this->type ) ) {
			// If the current type value is null, set an empty array, else create as an array with the value.
			$this->type = is_null( $this->type ) ? array() : array( $this->type );
		}
		$this->type[] = $type;
		$this->type   = \array_unique( $this->type );
		return $this;
	}

	/**
	 * Get the argument description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Set the argument description.
	 *
	 * @param string $description  The argument description.
	 * @return static
	 */
	public function description( string $description ): self {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get optional format to expect value.
	 *
	 * @return string|null
	 */
	public function get_format(): ?string {
		return $this->format;
	}

	/**
	 * Set optional format to expect value.
	 *
	 * @param string $format  Optional format to expect value.
	 * @return static
	 */
	public function format( string $format ): self {
		$this->format = $format;
		return $this;
	}

	/**
	 * Get attributes
	 *
	 * @return array<string, mixed>
	 */
	public function get_attributes(): array {
		return $this->attributes;
	}

	/**
	 * Set attributes
	 *
	 * @param array<string,mixed> $attributes
	 *
	 * @return static
	 */
	public function set_attributes( array $attributes ): self {
		$this->attributes = $attributes;
		return $this;
	}

	/**
	 * Adds a single attribute
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return static
	 */
	public function add_attribute( string $key, $value ): self {
		$this->attributes[ $key ] = $value;
		return $this;
	}

	/**
	 * Gets an attribute based on its key, allows for a fallback
	 *
	 * @param string $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	public function get_attribute( string $key, $fallback = null ) {
		return \array_key_exists( $key, $this->attributes )
			? $this->attributes[ $key ]
			: $fallback;
	}

	/**
	 * Get expected of all accepted values
	 *
	 * @return array<string|float|int|bool>|null
	 */
	public function get_expected(): ?array {
		return $this->expected;
	}

	/**
	 * Set expected of all accepted values
	 *
	 * @param mixed ...$expected  Accept value for argument.
	 * @return static
	 */
	public function expected( ...$expected ): self {
		$this->expected = is_array( $this->expected )
			? array_merge( $this->expected, $expected )
			: $expected;
		return $this;
	}

	/**
	 * Gets the set min length, returns null if not set.
	 *
	 * @return string|null
	 */
	public function get_name(): ?string {
		return $this->get_attribute( 'name' );
	}

	/**
	 * Sets the max length of the value
	 *
	 * @param string $name
	 * @return static
	 */
	public function name( string $name ): self {
		return $this->add_attribute( 'name', $name );
	}

}
