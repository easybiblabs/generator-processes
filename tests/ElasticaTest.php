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

namespace EasyBib\Process\Test;

use EasyBib\Process\Elastica;
use Elastica\Document;

class ElasticaTest extends TestCase
{
    protected $itemSets;
    protected $documentSets;

    public function setUp()
    {
        $item1 = ['id' => 1, 'foo' => 'bar'];
        $item2 = ['id' => 2, 'foo' => 'baz'];
        $item3 = ['id' => 3, 'foo' => 'biz'];

        $this->itemSets = [
            [$item1, $item2],
            [$item3]
        ];
        $this->documentSets = [
            [new Document(1, $item1), new Document(2, $item2)],
            [new Document(3, $item3)]
        ];
    }

    public function testWrite()
    {
        $type = $this->getMockBuilder('Elastica\Type')
                     ->disableOriginalConstructor()
                     ->getMock();
        $type->expects($this->at(0))
             ->method('addDocuments')
             ->with($this->equalTo($this->documentSets[0]))
             ->will($this->returnValue(null));
        $type->expects($this->at(1))
             ->method('addDocuments')
             ->with($this->equalTo($this->documentSets[1]))
             ->will($this->returnValue(null));

        $write = Elastica::bindWrite($type);
        $result = iterator_to_array($write($this->itemSets));
        $this->assertEquals($this->itemSets, $result);
    }

    public function testSearch()
    {
        $index = $this->getMockBuilder('\Elastica\Index')
                      ->disableOriginalConstructor()
                      ->getMock();
        $index->expects($this->once())
              ->method('search')
              ->with($this->callback(function (\Elastica\Query $query) {
                  return $query->getParam('size') == 2 &&
                      $query->getParam('from') == 10 &&
                      $query->getParam('query')['query_string']['query'] == 'foo\\\\\/bar\\\\baz\/fub';
              }))
              ->will($this->returnValue($this->documentSets[0]));

        $keywords = 'foo\/bar\baz/fub';
        $search = Elastica::bindSearch($index, 2);
        $result = iterator_to_array($search($keywords, 10));
        $this->assertEquals($this->itemSets[0], $result);
    }
}
