<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Tools;

use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Tools\ExtensionGuesser;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class Namer implements NamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function rename(BaseFileInterface $file)
    {
        return sprintf('%s.%s',
            uniqid(),
            ExtensionGuesser::guess($file->getContentType())
        );
    }
}
