<?php
namespace TwentyFifth\Imager\Image;

/**
 * Created by JetBrains PhpStorm.
 * User: tsubera
 * Date: 23.07.12
 * Time: 09:29
 * To change this template use File | Settings | File Templates.
 */
abstract class AbstractDecorator implements ImageInterface
{
    protected $image;

	/**
	 * @param ImageInterface $image
	 */
	public function __construct(ImageInterface $image)
    {
        $this->image = $image;
    }

	/**
	 * @return string
	 */
	public function getPath()
    {
        return $this->image->getPath();
    }

	/**
	 * @param $width
	 * @param $height
	 *
	 * @return mixed
	 */
	public function calculate($width, $height)
    {
        return $this->image->calculate($width, $height);
    }

    public function __toString()
    {
        return $this->image->__toString();
    }
}
