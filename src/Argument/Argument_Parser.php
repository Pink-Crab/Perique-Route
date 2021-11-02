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

		if ( ! is_null( $this->argument->get_name() ) ) {
			$attributes['name'] = $this->argument->get_name();
		}

		if ( is_array( $this->argument->get_expected() ) && ! empty( $this->argument->get_expected() ) ) {
			$attributes['enum'] = $this->argument->get_expected();
		}

		return array( $this->argument->get_key() => array_merge( $attributes, $this->get_type_attributes() ) );
	}

	/**
	 *
	 * Per Type Parsers
	 *
	 */

	/**
	 * Returns the current attributes specific attributes.
	 *
	 * @return array<string, mixed>
	 */
	protected function get_type_attributes(): array {
		switch ( $this->argument->get_type() ) {
			case Argument::TYPE_STRING:
				return $this->string_attributes();

			case Argument::TYPE_NUMBER:
			case Argument::TYPE_INTEGER:
				return $this->numeric_attributes();

			case Argument::TYPE_ARRAY:
				return $this->array_attributes();

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

	/**
	 * Populate number and integer args.
	 *
	 * @return array<string, int|float|bool>
	 */
	public function numeric_attributes(): array {

		// Bail if not a String Argument.
		if ( ! is_a( $this->argument, Number_Type::class )
		&& ! is_a( $this->argument, Integer_Type::class )
		) {
			return array();
		}

		/** @var Number_Type|Integer_Type $argument */
		$argument = $this->argument;

		$attributes = array();
		if ( ! is_null( $argument->get_multiple_of() ) ) {
			$attributes['multipleOf'] = $argument->get_multiple_of();
		}

		if ( ! is_null( $argument->get_minimum() ) ) {
			$attributes['minimum'] = $argument->get_minimum();
			if ( ! is_null( $argument->get_exclusive_minimum() ) ) {
				$attributes['exclusiveMinimum'] = $argument->get_exclusive_minimum();
			}
		}

		if ( ! is_null( $argument->get_maximum() ) ) {
			$attributes['maximum'] = $argument->get_maximum();
			if ( ! is_null( $argument->get_exclusive_maximum() ) ) {
				$attributes['exclusiveMaximum'] = $argument->get_exclusive_maximum();
			}
		}

		return $attributes;
	}

	/**
	 * Populate Array args
	 *
	 * @return array<string, int|float|bool|mixed[]>
	 */
	public function array_attributes(): array {
		// Bail if not a String Argument.
		if ( ! is_a( $this->argument, Array_Type::class ) ) {
			return array();
		}

		/** @var Array_Type $argument */
		$argument = $this->argument;

		$attributes = array();

		// Map items.
		$items = $this->parse_array_items( $argument );
		if ( ! empty( $items ) ) {
			// Based on relationship type.
			$relationship        = $argument->get_relationship();
			$attributes['items'] = 'allOf' === $relationship
				? $items
				: array( $relationship => $items );
		}

		// Min items
		if ( ! is_null( $argument->get_min_items() ) ) {
			$attributes['minItems'] = $argument->get_min_items();
		}

		// Max items
		if ( ! is_null( $argument->get_max_items() ) ) {
			$attributes['maxItems'] = $argument->get_max_items();
		}

		// Unique items
		if ( ! is_null( $argument->get_unique_items() ) ) {
			$attributes['uniqueItems'] = $argument->get_unique_items();
		}

		return $attributes;
	}

	/**
	 * Parses the arrays items
	 *
	 * @param Array_Type $argument
	 * @return array<int, mixed>
	 */
	protected function parse_array_items( Array_Type $argument ) : array {
		$items = array();

		if ( ! $argument->has_items() ) {
			return $items;
		}

		// If we only have 1 item, return as a simple array.
		if ( $argument->item_count() === 1 ) {
			$parser = new self( array_values( $argument->get_items() )[0] ); /** @phpstan-ignore-line, already checked if contains items.*/
			$items  = array_values( $parser->to_array() )[0];
		} else {
			foreach ( $argument->get_items() ?? array() as $key => $value ) {
				$parser = new self( $value );
				$items  = array_merge( $items, array_values( $parser->to_array() ) );
			}
		}

		return $items;
	}

}
