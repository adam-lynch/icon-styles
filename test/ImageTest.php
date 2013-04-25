<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam
 * Date: 11/04/13
 * Time: 21:45
 * To change this template use File | Settings | File Templates.
 */

namespace IconStyles\test;

require_once('..\IconStylesGenerator.php');
require_once('..\Image.php');
require_once('..\SpriteSheet.php');
require_once( 'AbstractIconStylesTest.php' );

use IconStyles\test\AbstractIconStylesTest;
use IconStyles\IconStylesGenerator;
use IconStyles\Image;
use IconStyles\SpriteSheet;



class ImageTest extends AbstractIconStylesTest
{

	/** @var string $testResourceRoot */
	private static $testResourceRoot;
	/** @var string $outputDirectory */
	private static $outputDirectory;

	public function setup() {

		self::$testResourceRoot = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
		$outputDirectory = self::$testResourceRoot . DIRECTORY_SEPARATOR . 'temp';

		//clean output dir (i.e. delete it) and re-create it
		if(file_exists( $outputDirectory)){
			exec( 'rm -r ' . $outputDirectory);
		}
		mkdir($outputDirectory);

		self::$outputDirectory = $outputDirectory;
	}

	/**
	 * @test
	 */
	public function constructor() {

		$expectedFileExtension = $expectedType = 'png';
		$expectedFilePath      = self::$testResourceRoot . DIRECTORY_SEPARATOR . 'fixtures'
								 . DIRECTORY_SEPARATOR . 'octocat.' . $expectedFileExtension;

		$this->fullImageConstructionTest( $expectedFilePath, $expectedFileExtension, $expectedType );
	}

	/**
	 * @test
	 */
	public function jpgUsesJpegImageCreationFunction() {

		//run full test to be sure image is created correctly
		$expectedFileExtension = 'jpg';
		$expectedType          = 'jpeg';
		$expectedFilePath      = self::$testResourceRoot . DIRECTORY_SEPARATOR . 'fixtures'
								 . DIRECTORY_SEPARATOR . 'images.' . $expectedFileExtension;

		$this->fullImageConstructionTest( $expectedFilePath, $expectedFileExtension, $expectedType );
	}

	/**
	 * Runs full test; constructs image and tests contents
	 *
	 * @param $expectedFilePath
	 * @param $expectedFilename
	 * @param $expectedFileExtension
	 * @param $expectedType
	 */
	protected function fullImageConstructionTest( $expectedFilePath, $expectedFileExtension, $expectedType ) {

		$actualImage = new Image( $expectedFilePath, $expectedFileExtension );

		//check stored file path segments
		$this->assertEquals( $expectedFilePath, $actualImage->getPath() );
		$this->assertEquals( $expectedFileExtension, $actualImage->getExtension() );//double check to be sure
		$this->assertEquals( $expectedType, $actualImage->getFormat() );

		//generate local test image resource from the original image
		$imageCreationFunctionName = 'imagecreatefrom' . $expectedType;
		$expectedImageResource = $imageCreationFunctionName( $expectedFilePath );

		//test that the width and height matches orginal
		$actualWidth  = $actualImage->getWidth();
		$actualHeight = $actualImage->getHeight();
		$this->assertEquals( imagesx( $expectedImageResource ), $actualWidth, 'Width should be equal to that of real image' );
		$this->assertEquals( imagesy( $expectedImageResource ), $actualHeight, 'Height should be equal to that of real image' );

		$this->assertImageResourcesAreEqual( $actualImage->getResource(), $expectedImageResource, $actualWidth, $actualHeight );
	}

	/**
	 * @test
	 */
	public function recursion(){

		$directory = self::$testResourceRoot . DIRECTORY_SEPARATOR . 'fixtures';
		$spriteSheet = new SpriteSheet();
		//deliberately using different method to parse directory than actual implementation
		$it = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $directory ) );

		while ( $it->valid() ) {

			$fullFilePath = $it->key();

			if ( !$it->isDot() && preg_match('%^(.+\\\\)?(.+?)\.(.+)$%', $fullFilePath, $filenameSegments) ) {

				$extension = $filenameSegments[3];
				$subPath = $it->getSubPath();
				$path = $directory . ( DIRECTORY_SEPARATOR === $subPath ? '' : DIRECTORY_SEPARATOR ) . $subPath
						. DIRECTORY_SEPARATOR . $filenameSegments[2] . '.' . $extension;

				$spriteSheet->addImage( $path, $extension);
			}

			$it->next();
		}

		$destinationFilename = 'spritesheet.png';
		$destinationFullFilePath = self::$outputDirectory . DIRECTORY_SEPARATOR . $destinationFilename;
		$spriteSheet->generate( $destinationFullFilePath);

		$this->assertFileExists( $destinationFullFilePath);

		$outputImage = imagecreatefrompng( $destinationFullFilePath );
		$this->assertNotEmpty($outputImage);
		$this->assertEquals( 2037, imagesx($outputImage));
		$this->assertEquals( 512, imagesy($outputImage));

		$this->assertImageResourcesAreEqual(imagecreatefrompng(self::$testResourceRoot . DIRECTORY_SEPARATOR . $destinationFilename), $outputImage);
	}
}