<?php

/*
 * This file is part of Hydra Custom Media Wiki Fixers.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CustomMWFixers\Tests\Fixer;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @internal
 * @coversNothing
 */
final class AddSpacesInsideParenthesisFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     *
     * @param mixed      $expected
     * @param null|mixed $input
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    public function provideFixCases()
    {
        return [];
    }
}
