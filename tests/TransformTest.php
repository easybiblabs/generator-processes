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

use EasyBib\Process\Transform;

class TransformTest extends TestCase
{
    public function flatData()
    {
        return [
            [[1, 2, 3], 2, [[1, 2], [3]]],
            [[1, 2, 3], 1, [[1], [2], [3]]],
        ];
    }

    /**
     * @dataProvider flatData
     */
    public function testBulkify($in, $size, $out)
    {
        $bulkify = Transform::bindBulkify($size);
        $result = iterator_to_array($bulkify($in));
        $this->assertEquals($out, $result);
    }

    public function bulkData()
    {
        return [
            [[[1, 2], [3]], [1, 2, 3]],
            [[[1], [2], [3]], [1, 2, 3]],
        ];
    }

    /**
     * @dataProvider bulkData
     */
    public function testUnbulkify($in, $out)
    {
        $result = iterator_to_array(Transform::unbulkify($in));
        $this->assertEquals($out, $result);
    }
}
