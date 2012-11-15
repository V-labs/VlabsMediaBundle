<?php

namespace Vlabs\MediaBundle\Tools;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

class Namer implements NamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function rename(File $file)
    {
        $guesser = ExtensionGuesser::getInstance();

        return sprintf('%s.%s',
            uniqid(),
            $guesser->guess($file->getMimeType())
        );
    }
}
