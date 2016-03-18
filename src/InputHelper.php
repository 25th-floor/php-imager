<?php

namespace TwentyFifth\Imager;

/**
 * Created by JetBrains PhpStorm.
 * User: tsubera
 * Date: 24.08.12
 * Time: 15:27
 * To change this template use File | Settings | File Templates.
 */
class InputHelper
{
	/**
	 * sanitizes the resolution input using x as separator between width and height
	 *
	 * @static
	 *
	 * @param $sResolution
	 *
	 * @return array
	 */
	static public function sanitizeResolution($sResolution)
	{
		$width  = null;
		$height = null;

		if (is_null($sResolution)
			|| !is_string($sResolution) && !is_numeric($sResolution)
			|| strlen($sResolution) == 0
		) {
			return array($width, $height);
		}

		$width = (int)$sResolution;
		if (strpos($sResolution, 'x') !== false) {
			list($width, $height) = explode('x', $sResolution);

			$width  = (int)$width;
			$height = (int)$height;
		}

		return array($width, $height);
	}

	/**
	 * validate cropping
	 *
	 * @static
	 *
	 * @param $width
	 * @param $height
	 *
	 * @return bool
	 * @throws Exception
	 */
	static public function validateCropping($width, $height)
	{
		foreach (array('width' => $width, 'height' => $height) as $key => $value) {
			if (!is_string($value) && !is_integer($value)) {
				throw new Exception("$key is not a string or integer");
			}
			if ($value <= 0) {
				throw new Exception("$key must be set");
			}
		}

		return true;
	}
}
