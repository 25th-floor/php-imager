<?php
namespace TwentyFifth\Imager;

/**
 * Created by JetBrains PhpStorm.
 * User: tsubera
 * Date: 20.07.12
 * Time: 13:37
 * To change this template use File | Settings | File Templates.
 */
class ImageCreator
{

    /**
     * Get the Image Type and create Object Ressource from ImageFile.
     *
     * @param    string    $sFileName
     * @param              $type
     *
     * @return    object    Image
     * @throws    \TwentyFifth\Imager\Exception if FileType is unknown.
     * @author    Thomas Subera<thomas.subera@gmail.com>
     * @access    public
     * @since    21.12.2006
     * @static
     */
    static public function imagecreatefrom($sFileName = '', $type = false)
    {
        if (!$type) {
            $aImgInfo = Php::getimagesize($sFileName);
            $type     = $aImgInfo[2];
        }
        # What Type of Image do we have
        switch ($type) {
            case IMAGETYPE_GIF: # GIF
                $oSourceImage = Php::imagecreatefromgif($sFileName);
                break;
            case IMAGETYPE_JPEG: # JPG
                $oSourceImage = Php::imagecreatefromjpeg($sFileName);
                break;
            case IMAGETYPE_PNG: # PNG
                $oSourceImage = Php::imagecreatefrompng($sFileName);
                break;
            default:
                throw new Exception(sprintf("File: '%s' not a known Image type", htmlentities($sFileName)));
        }
        return $oSourceImage;
    }

    /**
     * Converts Image Resource into String, depending on the image mime
     * type
     *
     * @param    resource  $oImage     Image
     * @param    integer    $iMimeType  MimeType, defaults to Jpeg
     *
     * @return   string    Image
     * @author   Thomas Subera<thomas.subera@gmail.com>
     * @access   public
     * @see      http://www.php-development.ru/php-scripts/image-output.php
     * @since    10.01.2007
     * @static
     */
    static function imagetostring($oImage, $iMimeType = -1)
    {
        if ($iMimeType == -1) {
            $iMimeType = IMAGETYPE_JPEG;
        }

        $sContents = ob_get_contents();

        if ($sContents !== false) {
            ob_clean();
        } else {
            ob_start();
        }

        $sFunction = self::getOutputName($iMimeType);
        $sFunction($oImage);
        $sData = ob_get_contents();

        if ($sContents !== false) {
            ob_clean();
            echo $sContents;
        }

        ob_end_clean();
        return $sData;
    }

	/**
	 * Returns a string with the gd function name depending on the Image
	 * type.
	 *
	 * @param    integer $type Image Type
	 *
	 * @throws Exception
	 * @return    string    function name
	 * @author    Thomas Subera<thomas.subera@gmail.com>
	 * @access    public
	 * @since     10.01.2007
	 * @static
	 */
    static function getOutputName($type)
    {
        switch ($type) {
            case IMAGETYPE_PNG:
                return 'imagepng';
	        case IMAGETYPE_GIF:
                return 'imagegif';
	        case IMAGETYPE_JPEG:
                return 'imagejpeg';
            default:
                throw new Exception("Unsupported image output type '$type'");
        }
    }
}
