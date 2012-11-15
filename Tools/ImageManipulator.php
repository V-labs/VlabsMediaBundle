<?php

namespace Vlabs\MediaBundle\Tools;

use Symfony\Component\Finder\Finder;
use Imagine\Image\ImageInterface,
    Imagine\Image\Box,
    Imagine\Gd\Imagine;

class ImageManipulator implements ImageManipulatorInterface
{
    protected $newPath;
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function handleImage($path, $name, $formats)
    {
        $this->newPath = sprintf('%s/%sx%s/%s',
                $this->cacheDir,
                $formats['width'],
                $formats['height'],
                $name
        );

        if (!is_file($this->newPath)) {
            @mkdir(sprintf('%s/', $this->cacheDir));
            @mkdir(sprintf('%s/%sx%s', $this->cacheDir, $formats['width'], $formats['height']));

            $imagine = new Imagine();
            $imagine
                ->open($path)
                ->thumbnail(new Box($formats['width'], $formats['height']), ImageInterface::THUMBNAIL_OUTBOUND)
                ->save($this->newPath);
        }
    }

    public function getAllCachedPaths($name)
    {
        $paths = array();

        $finder = new Finder();
        $finder->name($name);

        foreach ($finder->in($this->cacheDir) as $file) {
            /* @var $file \Symfony\Component\Finder\SplFileInfo */
            $paths[] = sprintf('%s/%s', $this->cacheDir, $file->getRelativePathname());
        }

        return $paths;
    }

    public function getCachePath()
    {
        return $this->newPath;
    }
}
