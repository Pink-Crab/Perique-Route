<?php

declare(strict_types=1);

/**
 * Parses an argument into either array or JSON representations
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 1.1.0
 */

namespace PinkCrab\Route\Argument;

use PinkCrab\Route\Argument\Argument;

class Argument_Parser {

	/**
	 * The Argument to be parsed
	 *
	 * @var Argument
	 */
	protected $argument;

	public function __construct( Argument $argument ) {
		$this->argument = $argument;
	}

	/**
	 * Static constructor with array output
	 *
	 * @param \PinkCrab\Route\Argument\Argument $argument
	 * @return array<string, mixed>
	 */
	public static function as_array( Argument $argument ): array {
		return ( new self( $argument ) )->to_array();
	}

	/**
	 * Static constructor with JSON output
	 *
	 * @param \PinkCrab\Route\Argument\Argument $argument
	 * @return string
	 */
	public static function as_json( Argument $argument ): string {
		return ( new self( $argument ) )->to_json();
	}

	/**
	 * Returns the current argument as an array
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		$attributes = array();

		if ( $this->argument->get_validation() ) {
			$attributes['validate_callback'] = $this->argument->get_validation();
		}

		if ( $this->argument->get_sanitization() ) {
			$attributes['sanitize_callback'] = $this->argument->get_sanitization();
		}

		if ( ! is_null( $this->argument->get_type() ) ) {
			$attributes['type'] = $this->argument->get_type();
		}

		if ( ! is_null( $this->argument->get_required() ) ) {
			$attributes['required'] = $this->argument->get_required();
		}

		if ( '' !== $this->argument->get_description() ) {
			$attributes['description'] = $this->argument->get_description();
		}

		if ( ! is_null( $this->argument->get_default() ) ) {
			$attributes['default'] = $this->argument->get_default();
		}

		if ( ! is_null( $this->argument->get_format() ) ) {
			$attributes['format'] = $this->argument->get_format();
		}

		if ( is_array( $this->argument->get_expected() ) && ! empty( $this->argument->get_expected() ) ) {
			$attributes['enum'] = $this->argument->get_expected();
		}

		return array( $this->argument->get_key() => array_merge( $attributes, $this->get_type_attributes() ) );
	}

	/**
	 * Returns the current argument as JSON
	 *
	 * @return string
	 */
	public function to_json(): string {
		return \wp_json_encode( $this->to_array() );
	}

	/**
	 *
	 * Per Type Parsers
	 *
	 */

	/**
	 * Returns the current attributes specific attributes.
	 *
	 * @return array
	 */
	protected function get_type_attributes(): array {
		switch ( $this->argument->get_type() ) {
			case Argument::TYPE_STRING:
				return $this->string_attributes();

			default:
				return array();
		}
	}

	/**
	 * Populate string args.
	 *
	 * @return array<string, int|string>
	 */
	protected function string_attributes(): array {

		// Bail if not a String Argument.
		if ( ! is_a( $this->argument, String_Type::class ) ) {
			return array();
		}

		/** @var String_Type $argument */
		$argument = $this->argument;

		$attributes = array();
		if ( ! is_null( $argument->get_min_length() ) ) {
			$attributes['minLength'] = $argument->get_min_length();
		}
		if ( ! is_null( $argument->get_max_length() ) ) {
			$attributes['maxLength'] = $argument->get_max_length();
		}
		if ( ! is_null( $argument->get_pattern() ) ) {
			$attributes['pattern'] = $argument->get_pattern();
		}
		return $attributes;
	}

}
