<?php

declare(strict_types=1);

/**
 * Factory to create routes for a namespace
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Schema;

class Array_Type extends Abstract_Type {

	/**
	 * All items in array
	 *
	 * @var Abstract_Type[]
	 */
	protected $items = array();

	public function __construct( Abstract_Type ...$items ) {
		$this->items = $items;
	}

	/**
	 * Get all items in array
	 *
	 * @return Abstract_Type[]
	 */
	public function get_items(): Abstract_Type[] {
		return $this->items;
	}
}
