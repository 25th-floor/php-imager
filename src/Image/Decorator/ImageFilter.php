<?php
namespace TwentyFifth\Imager\Image\Decorator;

use TwentyFifth\Imager\Image\AbstractDecorator;

/**
 * Image Filter AbstractDecorator
 *
 * defaults to GaussianBlur
 *
 * can be set through setFilter method
 */
class ImageFilter extends AbstractDecorator
{
	/** @var int  */
	private $_filter = IMG_FILTER_GAUSSIAN_BLUR;

	/** @var array  */
    private $_filterArgs = array();

	/**
	 * @param $image
	 *
	 * @return mixed
	 */
	protected function applyFilter($image) {
        imagefilter($image, $this->_filter, $this->_filterArgs[0]);

        return $image;
    }

	/**
	 * @return mixed
	 */
	public function getImage()
    {
        $image = $this->image->getImage();

        return $this->applyFilter($image);
    }

	/**
	 * @param null $width
	 * @param null $height
	 * @param bool $crop
	 *
	 * @return mixed
	 */
	public function getThumbnail($width = null, $height = null, $crop = false)
    {
        $image = $this->image->getThumbnail($width, $height, $crop);

        return $this->applyFilter($image);
    }

	/**
	 * set Filter
	 *
	 * uses imagefilter therefore all arguments of imagefilter are allowed
	 *
	 * @link http://php.net/manual/en/function.imagefilter.php
	 *
	 * param resource $image
	 * param int $filtertype <p>
	 *
	 * filtertype can be one of the following:
	 * IMG_FILTER_NEGATE: Reverses all colors of
	 * the image.
	 * param int $arg1 [optional] <p>
	 * IMG_FILTER_BRIGHTNESS: Brightness level.
	 * param int $arg2 [optional] <p>
	 * IMG_FILTER_COLORIZE: Value of green component.
	 * param int $arg3 [optional] <p>
	 * IMG_FILTER_COLORIZE: Value of blue component.
	 * param int $arg4 [optional] <p>
	 * IMG_FILTER_COLORIZE: Alpha channel, A value
	 * between 0 and 127. 0 indicates completely opaque while 127 indicates
	 * completely transparent.
	 *
	 * @return self
	 */
	public function setFilter()
    {
        $args = func_get_args();
        $this->_filter = array_shift($args);
        $this->_filterArgs = $args;

	    return $this;
    }
}
