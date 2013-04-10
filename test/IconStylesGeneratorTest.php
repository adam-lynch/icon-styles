<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam
 * Date: 10/04/13
 * Time: 22:33
 * To change this template use File | Settings | File Templates.
 */

namespace IconStyles\test;

use IconStylesGenerator\IconStylesGenerator;

require_once('..\IconStylesGenerator.php');
require_once('..\vendor\phpunit\phpunit\phpunit.php');

class IconStylesGeneratorTest extends \PHPUnit_Framework_TestCase{

	/** @var string $resourcesDirectory */
	private $resourcesDirectory;
	/** @var IconStylesGenerator $iconStylesGenerator */
	private $iconStylesGenerator;

	public function setup(){
		$this->resourcesDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
		$this->iconStylesGenerator = new IconStylesGenerator( $this->resourcesDirectory );
	}

	/**
	 * @test
	 */
	public function generate(){
		echo 'a';
		$this->iconStylesGenerator->generate( $this->resourcesDirectory );
	}
}
