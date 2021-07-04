<?php

declare(strict_types=1);

/**
 * General utility functions
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

class Utils {

	/**
	 * Creates a single conditional function from many.
	 * The passed value will be passed through each callbale,
	 * if any result is not truthy, will return false.
	 *
	 * @param callable(mixed):bool ...$callables
	 * @return callable(mixed):bool
	 */
	public static function compose_conditional_all_true( callable ...$callables ): callable {
		return function( $value ) use ( $callables ): bool {
			foreach ( $callables as $callable ) {
				$result = (bool) $callable( $value );
				if ( true !== $result ) {
					return false;
				}
			}
			return true;
		};
	}

	/**
	 * Creates a single conditional function from many.
	 * The passed value will be passed through each callbale,
	 * if any result is truthy, will return true, only false as a all failed..
	 *
	 * @param callable(mixed):bool ...$callables
	 * @return callable(mixed):bool
	 */
	public static function compose_conditional_any_true( callable ...$callables ): callable {
		return function( $value ) use ( $callables ): bool {
			foreach ( $callables as $callable ) {
				$result = (bool) $callable( $value );
				if ( true === $result ) {
					return true;
				}
			}
			return false;
		};
	}

	/**
	 * Creates a single function, which pipes trough each callable in the order passed.
	 *
	 * @param callable(mixed):mixed ...$callables
	 * @return callable(mixed):mixed
	 */
	public static function compose_piped_callable( callable ...$callables ): callable {
		return function( $value ) use ( $callables ) {
			foreach ( $callables as $callable ) {
				$value = $callable( $value );
			}
			return $value;
		};
	}
}
