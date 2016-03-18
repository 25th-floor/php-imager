<?php
namespace TwentyFifth\Imager;

/**
 * Calculation Class
 */
class Calculate
{
	/**
	 * Calulate the aspect ratio. If iDstHeight is -1 it scals to the
	 * width, else after the orientation of the image.
	 *
	 * @param integer $iSrcWidth    width of source image
	 * @param integer $iSrcHeight   height of source image
	 * @param integer $iDstWidth    width of destination image, -1 for automatic calculation
	 * @param integer $iDstHeight   height of desination image, -1 for automatic calculation
	 * @param boolean $bAspectRatio support the Aspect Ratio of the source image
	 *
	 * @throws Exception
	 * @return array(int,int) (calculated width, calculated height)
	 * @author    Thomas Subera<thomas.subera@gmail.com>
	 * @access    public
	 * @since     21.12.2006
	 * @static
	 */
    static public function calculateThumbnail($iSrcWidth,  $iSrcHeight, $iDstWidth = 0, $iDstHeight = 0, $bAspectRatio = true)
    {
        // validate input (since scalar are not allowed for typehint)
        foreach (array('source width' => $iSrcWidth, 'source height' => $iSrcHeight, 'destination width' => $iDstWidth, 'destination height' => $iDstHeight) as $key => $value) {
            if (!is_int($value)) {
                throw new Exception("$key has a wrong format!");
            }
        }
        // make aspect valid, don"t care what the input is
        $bAspectRatio = (bool) $bAspectRatio;

        // no source width or height is a broken image
        if ($iSrcWidth == 0 || $iSrcHeight == 0) {
            throw new Exception('Source Image has wrong Height or Width');
        }

        // no destination is also broken
        if ($iDstWidth == 0 && $iDstHeight == 0) {
            throw new Exception('No specific Height or Width for the Image has been set');
        }

        if ($bAspectRatio) {
            # calculate automatic Height or Width
            if ($iDstHeight == 0) {
                $iDstHeight = (int)round(($iDstWidth * $iSrcHeight / $iSrcWidth));
            } elseif ($iDstWidth == 0) {
                $iDstWidth = (int)round(($iDstHeight * $iSrcWidth / $iSrcHeight));
            } else {
                # now we have to look out for the aspect ratio
                $fSrcRatio = $iSrcWidth / $iSrcHeight;
                $fDstRatio = $iDstWidth / $iDstHeight;
                # The Calculated Image is never bigger than the DstWidth and Height.
                if ($fSrcRatio > $fDstRatio) {
                    $iDstHeight = (int)round(($iDstWidth * $iSrcHeight / $iSrcWidth));
                } else {
                    $iDstWidth = (int)round(($iDstHeight * $iSrcWidth / $iSrcHeight));
                }
            }
        } else if ($iDstWidth == 0) {
            $iDstWidth = $iSrcWidth;
        } else if ($iDstHeight == 0) {
            $iDstHeight = $iSrcHeight;
        }
        return array($iDstWidth, $iDstHeight);
    }

	/**
	 * calculate the cropstart and the width/height of the source image to copy from
	 *
	 * @static
	 *
	 * @param $sourceWidth
	 * @param $sourceHeight
	 * @param $destinationWidth
	 * @param $destinationHeight
	 *
	 * @throws Exception
	 * @return array
	 */
    static public function calculateCropStart($sourceWidth, $sourceHeight, $destinationWidth, $destinationHeight)
    {
        foreach(func_get_args() as $param) {
            if (!is_int($param)) {
                throw new Exception('Only integers are allowed!');
            }
            if ($param <= 0) {
                throw new Exception('Parameters must be positive!');
            }
        }

        $x = 0; $y = 0;
        $width = $sourceWidth;
        $height = $sourceHeight;

        # now we have to look out for the aspect ratio
        $sourceRatio      = $sourceWidth / $sourceHeight;
        $destinationRatio = $destinationWidth / $destinationHeight;

        if ($sourceRatio > $destinationRatio) {
            $width = (int) round($destinationWidth * ($sourceHeight/$destinationHeight));
            $x = (int) round(($sourceWidth - $width)/2);
        } else {
            $height = (int) round($destinationHeight * ($sourceWidth/$destinationWidth));
            $y = (int) round(($sourceHeight - $height)/2);
        }

        return array($x, $y, $width, $height);
    }

	/**
	 * calculate the needed memory usage for the data
	 *
	 * copied from Magento
	 * @link http://svn.magentocommerce.com/source/branches/1.6/app/code/core/Mage/Catalog/Model/Product/Image.php::_getNeedMemoryForFile
	 *
	 * @param $width
	 * @param $height
	 * @param $bits
	 * @param $channels
	 *
	 * @return float
	 */
	static public function calculateMemoryUsage($width, $height, $bits, $channels)
	{
		return round(($width * $height * $bits * $channels / 8 + pow(2, 16)) * 1.65);
	}
}
