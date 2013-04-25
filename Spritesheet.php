<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam
 * Date: 13/04/13
 * Time: 18:04
 * To change this template use File | Settings | File Templates.
 */

namespace IconStyles;
use IconStyles\Image;

class SpriteSheet {
	/** @var array $sprites */
	private $sprites;
	/** @var int $width */
	private $width;
	/** @var int $height */
	private $height;
	/**
	 * @param array $images
	 */
	private function setSprites( array $images ) {

		$this->sprites = $images;
	}

	/**
	 * @return array
	 */
	public function getSprites() {

		return $this->sprites;
	}

	/**
	 * @param string $fullFilePath
	 * @param string $extension
	 */
	public function addImage($fullFilePath, $extension){

		$image = new Image( $fullFilePath, $extension );

		$sprites = $this->getSprites();
		$imageHeight = $image->getHeight();
		$spriteSheetWidth = $this->getWidth();

		$sprites[] = array( 'X' => $spriteSheetWidth, 'image' => $image);
		$this->setWidth( $spriteSheetWidth + $image->getWidth());

		if($this->getHeight() < $imageHeight){
			$this->setHeight($imageHeight);
		}

		$this->setSprites( $sprites);
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

	public function generate( $destinationFilename, $format = 'png'){

		$spriteSheet = imagecreatetruecolor( $this->getWidth(), $this->getHeight() );

		imagecolortransparent( $spriteSheet, imagecolorallocate( $spriteSheet, 0, 0, 0 ) );//necessary?

		foreach ( $this->getSprites() as $sprite ) {
			/** @var Image $image  */
			$image = $sprite['image'];

			imagecopy( $spriteSheet, $image->getResource(), $sprite['X'], 0, 0, 0, $image->getWidth(), $image->getHeight() );
		}

		imagepng( $spriteSheet, $destinationFilename ); //todo call jpg/png based on destination file
	}

}