<?php
namespace TwentyFifth\Imager\Image;
use TwentyFifth\Imager\Calculate;
use TwentyFifth\Imager\ImageCreator;
use TwentyFifth\Imager\Php;

/**
 * Abstract Base Image Class
 */
abstract class AbstractImage implements ImageInterface
{
    protected $_height;
    protected $_width;
    protected $_type;

	protected $_channels;
	protected $_bits;

    public function __construct()
    {
        $this->populate();
    }

    /**
     * populate vars using Information from getimagesize
     */
    protected function populate()
    {
        $filename = $this->getPath();

        $imgInfo       = Php::getimagesize($filename);
        $this->_width  = $imgInfo[0];
        $this->_height = $imgInfo[1];
        $this->_type   = $imgInfo[2];
	    
	    // if not set use maximum
	    $this->_channels = isset($imgInfo['channels']) ? $imgInfo['channels'] : 4;
	    // if not set use maximum
	    $this->_bits = isset($imgInfo['bits']) ? $imgInfo['bits'] : 4;
    }

    /**
     * return full path to source image
     *
     * @return string
     */
    abstract public function getPath();

    /**
     * return calculations for thumbnail
     *
     * @param $width
     * @param $height
     *
     * @return array
     */
    public function calculate($width, $height)
    {
        return Calculate::calculateThumbnail($this->getWidth(), $this->getHeight(), $width, $height);
    }

    /**
     * check if actual is zero or the same as expected
     *
     * @static
     *
     * @param $expected
     * @param $actual
     *
     * @return bool
     */
    protected static function isSameOrZero($expected, $actual)
    {
        if ($expected == $actual) {
            return true;
        }
        if ($actual > 0) {
            return false;
        }
        return true;
    }

    /**
     * return image resource
     *
     * @return object
     */
    public function getImage()
    {
        return ImageCreator::imagecreatefrom($this->getPath(), $this->getType());
    }

	/**
	 * return thumbnail image resource
	 *
	 * @param null $width
	 * @param null $height
	 *
	 * @param bool $crop
	 *
	 * @return object|resource
	 */
    public function getThumbnail($width = null, $height = null, $crop = false)
    {
        # get object
        $sourceImage = $this->getImage();

        # no calculations
        if (self::isSameOrZero($this->getWidth(), $width) && self::isSameOrZero($this->getHeight(), $height)) {
            return $sourceImage;
        }

        # don't crop if width or height is not set
        if ($crop && ($width <= 0 || $height <= 0)) {
            $crop = false;
        }

        if ($crop) {
            return $this->getThumbnailCropped($sourceImage, $width, $height);
        }

        return $this->getThumbnailNormal($sourceImage, $width, $height);
    }

    /**
     * get cropped Thumbnail
     *
     * @param $sourceImage
     * @param $width
     * @param $height
     *
     * @return resource
     */
    protected function getThumbnailCropped($sourceImage, $width, $height)
    {
        list($x, $y, $sourceWidth, $sourceHeight) = Calculate::calculateCropStart($this->getWidth(), $this->getHeight(), $width, $height);

        # create thumbnail
        $thumbnail = Php::imagecreatetruecolor($width, $height);

        Php::imagecopyresampled(
            $thumbnail, $sourceImage,
            0, 0, $x, $y,
            $width, $height,
            $sourceWidth, $sourceHeight);

        return $thumbnail;
    }

    /**
     * get Thumbnail
     *
     * @param $sourceImage
     * @param $width
     * @param $height
     *
     * @return resource
     */
    protected function getThumbnailNormal($sourceImage, $width, $height)
    {
        list($width, $height) = $this->calculate($width, $height);

        # create thumbnail
        $thumbnail = Php::imagecreatetruecolor($width, $height);

        Php::imagecopyresampled(
            $thumbnail, $sourceImage,
            0, 0, 0, 0,
            $width, $height,
            $this->getWidth(), $this->getHeight());

        return $thumbnail;
    }

    abstract function __toString();

    public function getHeight()
    {
        return $this->_height;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getWidth()
    {
        return $this->_width;
    }

	public function getBits()
	{
		return $this->_bits;
	}

	public function getChannels()
	{
		return $this->_channels;
	}

	public function getMemoryNeedForFile()
	{
		return Calculate::calculateMemoryUsage($this->getWidth(), $this->getHeight(), $this->getBits(), $this->getChannels());
	}
}
