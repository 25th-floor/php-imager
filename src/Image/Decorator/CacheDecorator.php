<?php
namespace TwentyFifth\Imager\Image\Decorator;

use TwentyFifth\Imager\Cache;
use TwentyFifth\Imager\Image\AbstractDecorator;

/**
 * CacheDecorator AbstractDecorator
 *
 * looks first in the cache and if file is not there or lifetime is exhausted create a new one.
 */
class CacheDecorator extends AbstractDecorator
{
	/** @var Cache */
    private $cache;

	/** @var string  */
    private $postfix = '';

	/**
	 * @param ToStringDecorator $image
	 * @param Cache             $cache
	 */
	public function __construct(ToStringDecorator $image, Cache $cache)
    {
        parent::__construct($image);

        $this->cache = $cache;
    }

	/**
	 * @return string
	 */
	protected function getFilename()
    {
        return str_replace(".", "-", $this->image->__toString());
    }

    // nothing to cache
    public function getImage()
    {
        return $this->image->getImage();
    }

	/**
	 * @param null $width
	 * @param null $height
	 * @param bool $crop
	 *
	 * @return mixed|string
	 */
	public function getThumbnail($width = null, $height = null, $crop = false)
    {
        $filename = sprintf('%s-%d-%d%s-%s.jpg',
            $this->getFilename(),
            $width,
            $height,
            $crop ? '-crop' : '',
            $this->postfix
        );

        if ($this->cache->check($filename)) {
            return $this->cache->load($filename);
        }

        return $this->cache->create($filename, $this->image->getThumbnail($width, $height, $crop));
    }

    /**
     * add postfix text to caching filename
     *
     * @param $postfix
     */
    public function setPostfix($postfix)
    {
        $this->postfix = $postfix;
    }

}
