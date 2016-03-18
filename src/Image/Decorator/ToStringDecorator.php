<?php
namespace TwentyFifth\Imager\Image\Decorator;

use TwentyFifth\Imager\Image\AbstractDecorator;
use TwentyFifth\Imager\ImageCreator;

/**
 * returns image as string
 *
 * type is default to JPEG but can be changed using the setType setter.
 */
class ToStringDecorator extends AbstractDecorator
{
	/** @var int Image Type */
	private $type = IMAGETYPE_JPEG;

	/**
	 * @param int $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getImage()
	{
		return ImageCreator::imagetostring($this->image->getImage(), $this->type);
	}

	/**
	 * @param null $width
	 * @param null $height
	 * @param bool $crop
	 *
	 * @return string
	 */
	public function getThumbnail($width = null, $height = null, $crop = false)
	{
		return ImageCreator::imagetostring(
			$this->image->getThumbnail($width, $height, $crop), $this->type);
	}
}
