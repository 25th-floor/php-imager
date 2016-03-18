<?php
namespace TwentyFifth\Imager;

/**
 * Cache Class for images
 *
 * uses file based cache for now
 */
class Cache
{
	/** @var string where to save the files */
    private $_directory;

	/** @var int lifetime of an image, false is forever */
    private $_lifetime = false;

	/**
	 * @param string $directory cache directory
	 */
	public function __construct($directory)
    {
        $this->_directory = $directory;
    }

    /**
     * set lifetime if old files should be purged automatically
     *
     * @param $lifetime
     *
     * @return Cache
     */
    public function setLifetime($lifetime)
    {
        $this->_lifetime = $lifetime * 86400;
        return $this;
    }

    /**
     * check if file is present
     *
     * @param $filename
     *
     * @return bool
     */
    public function check($filename)
    {
        # check cache files
        $this->_checkLifeTime();

        if (!is_readable($this->getPath($filename))) {
            return false;
        }

        return true;
    }

	/**
	 * Delete a File
	 *
	 * @param  string $filename
	 *
	 * @throws Exception
	 */
    public function delete($filename)
    {
        if (!is_readable($filename)) {
            throw new Exception("File '$filename' not found!");
        }
        if (!unlink($filename)) {
            throw new Exception("Unlink File '$filename' failed!");
        }
    }

    /**
     * read file
     *
     * @param $filename
     *
     * @return string
     * @throws Exception
     */
    public function load($filename)
    {
        $filename = $this->getPath($filename);
        $ret      = file_get_contents($filename);
        if ($ret === false) {
            throw new Exception("Unable to read file '$filename'");
        }
        return $ret;
    }

    /**
     * create cache file
     *
     * @param $filename
     * @param $data
     *
     * @return mixed
     */
    public function create($filename, $data) {
        $this->save($filename, $data);
        return $data;
    }

    /**
     * saves data to file
     *
     * @param $filename
     * @param $data
     *
     * @throws Exception
     */
    protected function save($filename, $data)
    {
        $filename = $this->getPath($filename);

        $ret = file_put_contents($filename, $data);

        if ($ret === false) {
            throw new Exception("Unable to save file '$filename");
        }
    }

    /**
     * get full path of image
     *
     * @param $filename
     *
     * @return string
     */
    protected function getPath($filename)
    {
        return $this->_directory . $filename;
    }

    /**
     * Check for old cached Files
     *
     * clear all cache files which lifetime is longer than allowed in that
     * directory, only works if $this->_iLifetime is set.
     */
    protected function _checkLifeTime()
    {
        # LifeTime is not set
        if ($this->_lifetime === false) {
            return;
        }
        # get all files of that directory
        $aFiles = glob($this->_directory . '*');
        $iToday = time();
        foreach ($aFiles as $sFile) {
            $iTime = $iToday - filemtime($sFile); # seconds
            # check filetime
            if ($iTime > $this->_lifetime) {
                # clear file if older
                $this->delete($sFile);
            }
        }
    }
}
