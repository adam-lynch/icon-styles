<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam
 * Date: 10/04/13
 * Time: 22:33
 * To change this template use File | Settings | File Templates.
 */

namespace IconStyles\test;

require_once('..\IconStylesGenerator.php');
require_once('AbstractIconStylesTest.php');

use IconStyles\IconStylesGenerator;
use IconStyles\test\AbstractIconStylesTest;

class IconStylesGeneratorTest extends AbstractIconStylesTest{

	/** @var string $resourcesDirectory */
	private $resourcesDirectory;
	/** @var string $outputDirectory*/
	private static $outputDirectory;
	/** @var IconStylesGenerator $iconStylesGenerator */
	private $iconStylesGenerator;

	public function setup(){
		$this->resourcesDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
		$this->iconStylesGenerator = new IconStylesGenerator( $this->resourcesDirectory . DIRECTORY_SEPARATOR . 'fixtures' );

		$outputDirectory = $this->resourcesDirectory . DIRECTORY_SEPARATOR . 'temp';

		//clean output dir (i.e. delete it) and re-create it
		if ( file_exists( $outputDirectory ) ) {
			exec( 'rm -r ' . $outputDirectory );
		}
		mkdir( $outputDirectory );

		self::$outputDirectory = $outputDirectory;
	}

	/**
	 * @test
	 */
	public function generate(){
		$destinationFilename     = 'spritesheet.png';
		$destinationFullFilePath = self::$outputDirectory . DIRECTORY_SEPARATOR . $destinationFilename;

		$this->iconStylesGenerator->generate( $destinationFullFilePath );

		$this->assertFileExists( $destinationFullFilePath );

		$outputImage = imagecreatefrompng( $destinationFullFilePath );
		$this->assertNotEmpty( $outputImage );
		$this->assertEquals( 2037, imagesx( $outputImage ) );
		$this->assertEquals( 512, imagesy( $outputImage ) );

		$this->assertImageResourcesAreEqual( imagecreatefrompng( $this->resourcesDirectory . DIRECTORY_SEPARATOR . 'spritesheet.png' ), $outputImage );
	}
}
