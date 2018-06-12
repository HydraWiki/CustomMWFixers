<?php

/*
 * This file is part of Hydra Custom Media Wiki Fixers.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CustomMWFixers;

use Symfony\Component\Finder\Finder;

final class Fixers implements \IteratorAggregate
{
    public function getIterator()
    {
        $finder = Finder::create()
            ->files()
            ->in(__DIR__.'/Fixer/')
            ->sortByName();

        $arrayIterator = new \ArrayIterator();

        foreach ($finder as $fileInfo) {
            $className = __NAMESPACE__.'\\Fixer\\'.$fileInfo->getBasename('.php');
            $arrayIterator->append(new $className());
        }

        return $arrayIterator;
    }
}
