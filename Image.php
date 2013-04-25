<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam
 * Date: 10/04/13
 * Time: 20:27
 * To change this template use File | Settings | File Templates.
 */

namespace IconStyles;

require_once('File.php');
use IconStyles\File;

class Image extends File
{

	private $resource;
	/** @var int $width */
	private $width;
	/** @var int $height */
	private $height;
	/** @var string $format */
	private $format;

	public function __construct( $fullFilePath, $extension ) {

		if(gettype($fullFilePath) !== 'string'){
			throw new \Exception( print_r($fullFilePath, 1) );
		}

		$this->setPath( $fullFilePath );
		$this->setExtension( $extension );
		$this->setFormat( strtolower($extension) );

		$imageResource = $this->createImageResource();
		$this->setResource( $imageResource );
		$this->setWidth( imagesx( $imageResource ) );
		$this->setHeight( imagesy( $imageResource ) );
	}

	/**
	 * @param int $height
	 */
	public function setHeight( $height ) {

		$this->height = $height;
	}

	/**
	 * @return int
	 */
	public function getHeight() {

		return $this->height;
	}

	public function setResource( $resource ) {

		$this->resource = $resource;
	}

	public function getResource() {

		return $this->resource;
	}

	/**
	 * @param int $width
	 */
	public function setWidth( $width ) {

		$this->width = $width;
	}

	/**
	 * @return int
	 */
	public function getWidth() {

		return $this->width;
	}

	/**
	 * @param string $format
	 */
	public function setFormat( $format ) {

		$lowerFormat = strtolower($format);

		if('jpg' === $lowerFormat){
			$lowerFormat = 'jpeg';
		}

		$this->format = $lowerFormat;
	}

	/**
	 * @return string
	 */
	public function getFormat() {

		return $this->format;
	}

	private function createImageResource(){

		$fullFilePath = $this->getPath();
		$format = $this->getFormat();

		/*
		 * build the GD function name first based on image format
		 * e.g. imagecreatefrompng() for PNGs
		 * Exception: imagecreatefromjpeg() for JPGs
		 */
		$createImageFunctionName = 'imagecreatefrom' . $format;

		//create the image
		$imageResource = $createImageFunctionName( $fullFilePath );
		if ( !$imageResource ) {
			throw new \Exception( 'Error loading (' . strtoupper($format) . ') image: ' . $fullFilePath );
		}

		return $imageResource;
	}

}
