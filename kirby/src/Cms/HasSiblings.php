<?php

namespace Kirby\Cms;

/**
 * This trait is used by pages, files and users
 * to handle navigation through parent collections
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://getkirby.com/license
 */
trait HasSiblings
{
	/**
	 * Returns the position / index in the collection
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 */
	public function indexOf($collection = null): int|false
	{
		$collection ??= $this->siblingsCollection();
		return $collection->indexOf($this);
	}

	/**
	 * Returns the next item in the collection if available
	 * @todo `static` return type hint is not 100% accurate because of
	 *       quirks in the `Form` classes; would break if enforced
	 *       (https://github.com/getkirby/kirby/pull/5175)
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 *
	 * @return static|null
	 */
	public function next($collection = null)
	{
		$collection ??= $this->siblingsCollection();
		return $collection->nth($this->indexOf($collection) + 1);
	}

	/**
	 * Returns the end of the collection starting after the current item
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 *
	 * @return \Kirby\Cms\Collection
	 */
	public function nextAll($collection = null)
	{
		$collection ??= $this->siblingsCollection();
		return $collection->slice($this->indexOf($collection) + 1);
	}

	/**
	 * Returns the previous item in the collection if available
	 * @todo `static` return type hint is not 100% accurate because of
	 *       quirks in the `Form` classes; would break if enforced
	 *       (https://github.com/getkirby/kirby/pull/5175)
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 *
	 * @return static|null
	 */
	public function prev($collection = null)
	{
		$collection ??= $this->siblingsCollection();
		return $collection->nth($this->indexOf($collection) - 1);
	}

	/**
	 * Returns the beginning of the collection before the current item
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 *
	 * @return \Kirby\Cms\Collection
	 */
	public function prevAll($collection = null)
	{
		$collection ??= $this->siblingsCollection();
		return $collection->slice(0, $this->indexOf($collection));
	}

	/**
	 * Returns all sibling elements
	 *
	 * @return \Kirby\Cms\Collection
	 */
	public function siblings(bool $self = true)
	{
		$siblings = $this->siblingsCollection();

		if ($self === false) {
			return $siblings->not($this);
		}

		return $siblings;
	}

	/**
	 * Checks if there's a next item in the collection
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 */
	public function hasNext($collection = null): bool
	{
		return $this->next($collection) !== null;
	}

	/**
	 * Checks if there's a previous item in the collection
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 */
	public function hasPrev($collection = null): bool
	{
		return $this->prev($collection) !== null;
	}

	/**
	 * Checks if the item is the first in the collection
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 */
	public function isFirst($collection = null): bool
	{
		$collection ??= $this->siblingsCollection();
		return $collection->first()->is($this);
	}

	/**
	 * Checks if the item is the last in the collection
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 */
	public function isLast($collection = null): bool
	{
		$collection ??= $this->siblingsCollection();
		return $collection->last()->is($this);
	}

	/**
	 * Checks if the item is at a certain position
	 *
	 * @param \Kirby\Cms\Collection|null $collection
	 */
	public function isNth(int $n, $collection = null): bool
	{
		return $this->indexOf($collection) === $n;
	}
}
