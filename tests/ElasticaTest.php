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
    public function testWrite()
    {
        $item1 = ['id' => 1, 'foo' => 'bar'];
        $item2 = ['id' => 2, 'foo' => 'baz'];
        $item3 = ['id' => 3, 'foo' => 'biz'];

        $itemSets = [
            [$item1, $item2],
            [$item3]
        ];
        $documentSets = [
            [new Document(1, $item1), new Document(2, $item2)],
            [new Document(3, $item3)]
        ];

        $type = $this->getMockBuilder('Elastica\Type')
                     ->disableOriginalConstructor()
                     ->getMock();
        $type->expects($this->at(0))
             ->method('addDocuments')
             ->with($this->equalTo($documentSets[0]))
             ->will($this->returnValue(null));
        $type->expects($this->at(1))
             ->method('addDocuments')
             ->with($this->equalTo($documentSets[1]))
             ->will($this->returnValue(null));

        $write = Elastica::bindWrite($type);
        $result = iterator_to_array($write($itemSets));
        $this->assertEquals($itemSets, $result);
    }
}
