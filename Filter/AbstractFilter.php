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

use Symfony\Component\Finder\Finder;
use Vlabs\MediaBundle\Entity\BaseFileInterface;

/**
 * Handle directory creation & file storing / retrieval
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
abstract class AbstractFilter implements FilterInterface
{
    protected $newPath;
    protected $cacheDir;
    protected $alias;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(BaseFileInterface $file, $baseUri, array $options)
    {
        $dir = sprintf('%s/%s/%s', $this->cacheDir, $this->getAlias(), md5(serialize($options)));
        $this->newPath = sprintf('%s/%s', $dir, $file->getName());

        if (!is_file($this->newPath)) {
            if(!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            $this->doFilter($file, $baseUri, $options);
        }
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function getCachePath()
    {
        return $this->newPath;
    }

    /**
     * {@inheritDoc}
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * This method must be overrided in child classes in order to apply the filtering operation
     *
     * @param \Vlabs\MediaBundle\Entity\BaseFileInterface $file
     * @param string $baseUri
     * @param array $options
     *
     * @return mixed
     */
    abstract protected function doFilter(BaseFileInterface $file, $baseUri, $options);
}