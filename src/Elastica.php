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

use \Elastica\Client;
use \Elastica\Document;
use \React\Partial;

/**
 * Processes using Elastica to work with Elasticsearch
 */
class Elastica
{
    /**
     * Write all items from the iterator into Elasticsearch
     *
     * @param \Elastica\Type $elasticaType The elastica type to write to
     * @param Traversable $itemSets Arrays of items, each item must either be
     *      an array containing an id key or an object with a getId() method
     *
     * @return Traversable An iterator returning the input values
     */
    public static function write(\Elastica\Type $elasticaType, $itemSets)
    {
        foreach ($itemSets as $items) {
            $documents = array();

            foreach ($items as $item) {
                $id = (is_array($item)) ? $item['id'] : $item->getId();
                $documents[] = new Document($id, $item);
            }

            $elasticaType->addDocuments($documents);

            yield $items;
        }
    }

    /**
     * Returns Elastica::write bound to the given elastica type
     *
     * @param \Elastica\Type $elasticaType The elastica type to bind to
     *
     * @return callable
     */
    public static function bindWrite(\Elastica\Type $elasticaType)
    {
        return Partial\bind(array(Elastica::class, 'write'), $elasticaType);
    }
}
