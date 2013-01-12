<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Filter;

use Vlabs\MediaBundle\Entity\BaseFileInterface;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
interface FilterInterface
{
    public function handle(BaseFileInterface $file, $baseUri, array $options);

    public function getAllCachedPaths($name);

    public function getCachePath();

    public function getAlias();

    public function setAlias($alias);
}