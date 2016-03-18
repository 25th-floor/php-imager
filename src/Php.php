<?php
namespace TwentyFifth\Imager;

/**
 * Wrapper around Image PHP calls throwing Exceptions if failure occurs (oldschool ftw)
 *
 * User: tsubera
 * Date: 20.07.12
 * Time: 13:39
 * To change this template use File | Settings | File Templates.
 */
class Php
{
    /**
     * The call to getimagesize() uses the silence operator '@' to disable
     * any warnings which might occur if the file is not readable. In this
     * case an exception is thrown.
     */
    static function getimagesize($sFilename)
    {
        if (!file_exists($sFilename)) {
            throw new Exception("Image file not found");
        }
        if (!is_readable($sFilename)) {
            throw new Exception("Image file cannot be read");
        }
        if (false == ($ret = @getimagesize($sFilename))) {
            throw new Exception("Cannot get image size");
        }
        return $ret;
    }

    static function imagecreatefromstring($sImageData)
    {
        if (!function_exists('imagecreatefromstring')) {
            throw new Exception("function not available in this build");
        }
        $rImage = imagecreatefromstring($sImageData);
        if (false === $rImage) {
            throw new Exception("Cannot create image from string");
        }
        return $rImage;
    }

    static function imagecreatefromjpeg($sFilename)
    {
        if (!function_exists('imagecreatefromjpeg')) {
            throw new Exception("function not available in this build");
        }
        if (!is_readable($sFilename)) {
            throw new Exception("Image file cannot be read");
        }
        $rImage = imagecreatefromjpeg($sFilename);
        if (false === $rImage) {
            throw new Exception("Cannot create image from jpeg file");
        }
        return $rImage;
    }

    static function imagecreatefrompng($sFilename)
    {
        if (!function_exists('imagecreatefrompng')) {
            throw new Exception("function not available in this build");
        }
        if (!is_readable($sFilename)) {
            throw new Exception("Image file cannot be read");
        }
        $rImage = imagecreatefrompng($sFilename);
        if (false === $rImage) {
            throw new Exception("Cannot create image from png file");
        }
        return $rImage;
    }

    static function imagecreatefromgif($sFilename)
    {
        if (!function_exists('imagecreatefromgif')) {
            throw new Exception("function not available in this build");
        }
        if (!is_readable($sFilename)) {
            throw new Exception("Image file cannot be read");
        }
        $rImage = imagecreatefromgif($sFilename);
        if (false === $rImage) {
            throw new Exception("Cannot create image from gif file");
        }
        return $rImage;
    }

    static function imagecreatetruecolor($iWidth, $iHeight)
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new Exception("function not available in this build");
        }
        if (false === ($ret = imagecreatetruecolor($iWidth, $iHeight))) {
            throw new Exception("Cannot create truecolor image width size $iWidth x $iHeight");
        }
        return $ret;
    }

    static function imagecopyresampled($rDstImage, $rSrcImage, $iDstX, $iDstY, $iSrcX, $iSrcY, $iDstW, $iDstH, $iSrcW, $iSrcH)
    {
        if (!function_exists('imagecopyresampled')) {
            throw new Exception("function not available in this build");
        }
        if (false === imagecopyresampled($rDstImage, $rSrcImage, $iDstX, $iDstY, $iSrcX, $iSrcY, $iDstW, $iDstH, $iSrcW, $iSrcH)) {
            throw new Exception("Unable to resample image");
        }
        return true;
    }
}
