<?php
namespace TwentyFifth\Imager\Image;
/**
 * Created by JetBrains PhpStorm.
 * User: tsubera
 * Date: 23.07.12
 * Time: 09:33
 * To change this template use File | Settings | File Templates.
 */
interface ImageInterface
{
	/**
	 * @return string
	 */
	public function getPath();

	/**
	 * @param int $width
	 * @param int $height
	 *
	 * @return array
	 */
	public function calculate($width, $height);

	/**
	 * @return mixed
	 */
	public function getImage();

	/**
	 * @param null $width
	 * @param null $height
	 * @param bool $crop
	 *
	 * @return mixed
	 */
	public function getThumbnail($width = null, $height = null, $crop = false);

	/**
	 * @return mixed
	 */
	public function __toString();
}
