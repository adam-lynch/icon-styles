<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam
 * Date: 11/04/13
 * Time: 21:45
 * To change this template use File | Settings | File Templates.
 */

namespace IconStyles\test;

use IconStyles\Image;

require_once( '../Image.php' );


class ImageTest extends \PHPUnit_Framework_TestCase
{

	/** @var string $testResourceRoot */
	private static $testResourceRoot;

	public function setup() {

		self::$testResourceRoot = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
	}

	/**
	 * @test
	 */
	public function constructor() {

		$expectedFilename      = 'octocat';
		$expectedFileExtension = $expectedType = 'png';
		$expectedFilePath      = self::$testResourceRoot;

		$this->fullImageConstructionTest( $expectedFilePath, $expectedFilename, $expectedFileExtension, $expectedType );
	}

	/**
	 * @test
	 */
	public function jpgUsesJpegImageCreationFunction() {

		//run full test to be sure image is created correctly
		$expectedFilename      = 'images';
		$expectedFileExtension = 'jpg';
		$expectedType          = 'jpeg';
		$expectedFilePath      = self::$testResourceRoot;

		$this->fullImageConstructionTest( $expectedFilePath, $expectedFilename, $expectedFileExtension, $expectedType );
	}

	/**
	 * Runs full test; constructs image and tests contents
	 *
	 * @param $expectedFilePath
	 * @param $expectedFilename
	 * @param $expectedFileExtension
	 * @param $expectedType
	 */
	private function fullImageConstructionTest( $expectedFilePath, $expectedFilename, $expectedFileExtension, $expectedType ) {

		$expectedFileFullPath = $expectedFilePath . DIRECTORY_SEPARATOR . $expectedFilename . '.' . $expectedFileExtension;

		$actualImage = new Image( $expectedFilePath, $expectedFilename, $expectedFileExtension );

		//check stored file path segments
		$this->assertEquals( $expectedFilePath, $actualImage->getPath() );
		$this->assertEquals( $expectedFilename, $actualImage->getFilename() );
		$this->assertEquals( $expectedFileExtension, $actualImage->getExtension() );
		$this->assertEquals( $expectedType, $actualImage->getFormat() );
		$this->assertEquals( $expectedFileFullPath, $actualImage->getFullPath() );

		//generate local test image resource from the original image
		$expectedImageResource = imagecreatefrompng( $expectedFilePath );

		//test that the width and height matches orginal
		$actualWidth  = $actualImage->getWidth();
		$actualHeight = $actualImage->getHeight();
		$this->assertEquals( imagesx( $expectedImageResource ), $actualWidth, 'Width should be equal to that of real image' );
		$this->assertEquals( imagesy( $expectedImageResource ), $actualHeight, 'Height should be equal to that of real image' );

		$this->assertImagesAreEqual( $actualImage->getResource(), $expectedImageResource, $actualWidth, $actualHeight );
	}

	/**
	 * asserts that both images are the same, pixel by pixel
	 *
	 * @param     $first  (image resource)
	 * @param     $second (image resource)
	 * @param int $height
	 * @param int $width
	 */
	private function assertImagesAreEqual( $first, $second, $width = 0, $height = 0 ) {

		if ( empty( $width ) ) {
			$width = imagesx( $first );
		}

		if ( empty( $height ) ) {
			$height = imagesy( $first );
		}

		for ( $x = 0; $x < $width; $x++ ) {
			for ( $y = 0; $y < $height; $y++ ) {
				$this->assertEquals(
					imagecolorat( $first, $x, $y ),
					imagecolorat( $second, $x, $y ),
					"Pixel colour does not match between original and stored image at ({$x}, {$y})"
				);
			}
		}
	}
}