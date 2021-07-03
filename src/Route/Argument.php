<?php

declare(strict_types=1);

/**
 * A route argument definition.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Route;

class Argument {

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

	/**
	 * The argument key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Callback to validate value
	 *
	 * @var callable(param:string,request::WP_REST_Request,key:string):bool
	 */
	protected $validation;

	/**
	 * Sanitizes the output
	 *
	 * @var callable(value:mixed):bool
	 */
	protected $sanitization;

	/**
	 * Is this argument required
	 *
	 * @var bool
	 */
	protected $required;

	/**
	 * The data type of the argument.
	 *
	 * @var string
	 */
	protected $type = self::TYPE_STRING;

	/**
	 * The argument description.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Optional format to expect value.
	 *
	 * @var string|null
	 */
	protected $format;

	/**
	 * Enum of all accepted values
	 *
	 * @var array|null
	 */
	protected $expected;

	/**
	 * The minimum int|float value accepted
	 *
	 * @var int|null
	 */
	protected $minimum;

	/**
	 * The maximum int|float value accepted
	 *
	 * @var int|null
	 */
	protected $maximum;

	/**
	 * Should the minimum range exclude the value set
	 *
	 * @var bool
	 */
	protected $exclusive_minimum = false;

	/**
	 * Should the maximum range exclude the value set
	 *
	 * @var bool
	 */
	protected $exclusive_maximum = false;

	/**
	 * The default value
	 *
	 * @var string|int|float|bool
	 */
	protected $default;

	public function __construct( string $key ) {
		$this->key = $key;
	}

	/**
	 * Static constrcutor.
	 *
	 * @param string $key
	 * @param callable $config
	 * @return self
	 */
	public static function on( string $key, ?callable $config = null ): self {
		$argument = new self( $key );
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
	 * @return callable(param:string,request::WP_REST_Request,key:string):bool
	 */
	public function get_validation(): callable {
		return $this->validation;
	}

	/**
	 * Set callback to validate value
	 *
	 * @param callable(param:string,request::WP_REST_Request,key:string):bool $validation  Callback to validate value
	 *
	 * @return self
	 */
	public function validation( callable $validation ): self {
		$this->validation = $validation;
		return $this;
	}

	/**
	 * Get sanitizes the output
	 *
	 * @return callable(value:mixed):bool
	 */
	public function get_sanitization(): callable {
		return $this->sanitization;
	}

	/**
	 * Set sanitizes the output
	 *
	 * @param callable(value:mixed):bool $sanitization  Sanitizes the output
	 *
	 * @return self
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
	 * @return self
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
	 * Set is this argument required
	 *
	 * @param bool $required  Is this argument required
	 * @return self
	 */
	public function required( bool $required = true ): self {
		$this->required = $required;
		return $this;
	}

	/**
	 * Get the data type of the argument.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * Set the data type of the argument.
	 *
	 * @param string $type  The data type of the argument.
	 *
	 * @return self
	 */
	public function type( string $type ): self {
		$this->type = $type;
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
	 * @return self
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
	 * @param string|null $format  Optional format to expect value.
	 * @return self
	 */
	public function format( string $format ): self {
		$this->format = $format;
		return $this;
	}

	/**
	 * Get expected of all accepted values
	 *
	 * @return array|null
	 */
	public function get_expected(): ?array {
		return $this->expected;
	}

	/**
	 * Set expected of all accepted values
	 *
	 * @param mixed ...$expected  Accept value for argument.
	 * @return self
	 */
	public function expected( ...$expected ): self {
		$this->expected = array_merge( $this->expected ?? array(), $expected );
		return $this;
	}


	/**
	 * Get the minimum int|float value accepted
	 *
	 * @return int|null
	 */
	public function get_minimum(): ?int {
		return $this->minimum;
	}

	/**
	 * Set the minimum int|float value accepted
	 *
	 * @param int|null $minimum  The minimum int|float value accepted
	 *
	 * @return self
	 */
	public function minimum( $minimum ): self {
		$this->minimum = $minimum;
		return $this;
	}

	/**
	 * Get the maximum int|float value accepted
	 *
	 * @return int|null
	 */
	public function get_maximum(): ?int {
		return $this->maximum;
	}

	/**
	 * Set the maximum int|float value accepted
	 *
	 * @param int|null $maximum  The maximum int|float value accepted
	 *
	 * @return self
	 */
	public function maximum( $maximum ): self {
		$this->maximum = $maximum;
		return $this;
	}

	/**
	 * Get should the minimum range exclude the value set
	 *
	 * @return bool
	 */
	public function get_exclusive_minimum(): bool {
		return $this->exclusive_minimum;
	}

	/**
	 * Set should the minimum range exclude the value set
	 *
	 * @param bool $exclusive_minimum  Should the minimum range exclude the value set
	 * @return self
	 */
	public function exclusive_minimum( bool $exclusive_minimum = true ): self {
		$this->exclusive_minimum = $exclusive_minimum;
		return $this;
	}

	/**
	 * Get should the maximum range exclude the value set
	 *
	 * @return bool
	 */
	public function get_exclusive_maximum(): bool {
		return $this->exclusive_maximum;
	}

	/**
	 * Set should the maximum range exclude the value set
	 *
	 * @param bool $exclusive_maximum  Should the maximum range exclude the value set
	 * @return self
	 */
	public function exclusive_maximum( bool $exclusive_maximum = true ): self {
		$this->exclusive_maximum = $exclusive_maximum;
		return $this;
	}
}
