<?php
/*
 * This file is part of easybib/generator-processes
 *
 * (c) Imagine Easy Solutions, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author   Nils Adermann <naderman@naderman.de>
 * @license  BSD-2-Clause
 * @link     http://www.imagineeasy.com
 */

namespace EasyBib\Process;

use React\Partial;

/**
 * A namespace class for transformation processes
 */
class Transform
{
    /**
     * Aggregates items into an array of the given size and yields them
     *
     * @param int $bulkSize
     * @param Traversable $items
     *
     * @return Traversable
     */
    public static function bulkify($bulkSize, $items)
    {
        $bulk = array();
        foreach ($items as $item) {
            $bulk[] = $item;

            if (count($bulk) == $bulkSize) {
                yield $bulk;
                $bulk = array();
            }
        }

        if (!empty($bulk)) {
            yield $bulk;
        }
    }

    /**
     * Returns Transform::bulkify bound to the given bulk size
     *
     * @param int $bulkSize The number of items to put into one bin
     *
     * @return callable
     */
    public static function bindBulkify($bulkSize)
    {
        return Partial\bind(array(Transform::class, 'bulkify'), $bulkSize);
    }

    /**
     * Single level flatten
     *
     * @param Traversable $items
     *
     * @return Traversable
     */
    public static function unbulkify($bulks)
    {
        foreach ($bulks as $bulk) {
            foreach ($bulk as $item) {
                yield $item;
            }
        }
    }
}
