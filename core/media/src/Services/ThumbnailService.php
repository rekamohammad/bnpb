<?php

namespace Botble\Media\Services;

use Exception;
use Intervention\Image\ImageManager;
use Log;

class ThumbnailService
{

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var string
     */
    protected $imagePath;

    /**
     * @var float
     */
    protected $thumbRate;

    /**
     * @var int
     */
    protected $thumbWidth;

    /**
     * @var int
     */
    protected $thumbHeight;

    /**
     * @var string
     */
    protected $destPath;

    /**
     * @var string
     */
    protected $xCoordinate;

    /**
     * @var string
     */
    protected $yCoordinate;

    /**
     * @var string
     */
    protected $fitPosition;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * ThumbnailService constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        if (extension_loaded('imagick')) {
            $this->imageManager = new ImageManager([
                'driver' => 'imagick'
            ]);
        } else {
            $this->imageManager = new ImageManager([
                'driver' => 'gd'
            ]);
        }

        $this->thumbRate = 0.75;
        $this->xCoordinate = null;
        $this->yCoordinate = null;
        $this->fitPosition = 'center';
    }

    /**
     * @param string $imagePath
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setImage($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return string $imagePath
     * @author Sang Nguyen
     */
    public function getImage()
    {
        return $this->imagePath;
    }

    /**
     * @param double $rate
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setRate($rate)
    {
        $this->thumbRate = $rate;

        return $this;
    }

    /**
     * @return double $thumbRate
     * @author Sang Nguyen
     */
    public function getRate()
    {
        return $this->thumbRate;
    }

    /**
     * @param $width
     * @param null $height
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setSize($width, $height = null)
    {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;

        if (empty($height)) {
            $this->thumbHeight = ($this->thumbWidth * $this->thumbRate);
        }

        return $this;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getSize()
    {
        return [$this->thumbWidth, $this->thumbHeight];
    }

    /**
     * @param string $destPath
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setDestPath($destPath)
    {
        $this->destPath = $destPath;

        return $this;
    }

    /**
     * @return string $destPath
     * @author Sang Nguyen
     */
    public function getDestPath()
    {
        return $this->destPath;
    }

    /**
     * @param integer $xCoord
     * @param integer $yCoord
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setCoordinates($xCoord, $yCoord)
    {
        $this->xCoordinate = $xCoord;
        $this->yCoordinate = $yCoord;

        return $this;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getCoordinates()
    {
        return [$this->xCoordinate, $this->yCoordinate];
    }

    /**
     * @param string $position
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setFitPosition($position)
    {
        $this->fitPosition = $position;

        return $this;
    }

    /**
     * @return string $fitPosition
     * @author Sang Nguyen
     */
    public function getFitPosition()
    {
        return $this->fitPosition;
    }

    /**
     * @param string $fileName
     * @return ThumbnailService
     * @author Sang Nguyen
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string $fileName
     * @author Sang Nguyen
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $type
     * @param integer $quality
     * @return mixed
     * @author Sang Nguyen
     */
    public function save($type = 'fit', $quality = 80)
    {
        $fileName = pathinfo($this->imagePath, PATHINFO_BASENAME);

        if ($this->fileName) {
            $fileName = $this->fileName;
        }

        $destPath = sprintf('%s/%s', trim($this->destPath, '/'), $fileName);

        $thumbImage = $this->imageManager->make($this->imagePath);

        switch ($type) {
            case 'resize':
                $thumbImage->resize($this->thumbWidth, $this->thumbHeight);
                break;
            case 'crop':
                $thumbImage->crop($this->thumbWidth, $this->thumbHeight, $this->xCoordinate, $this->yCoordinate);
                break;
            case 'fit':
                $thumbImage->fit($this->thumbWidth, $this->thumbHeight, null, $this->fitPosition);
        }

        try {
            $thumbImage->save($destPath, $quality);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }

        return $destPath;
    }
}