<?php
/**
 * @author: adam-lynch
 */

namespace IconStyles\test;


Abstract class AbstractIconStylesTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Asserts that both images are the same; colour comparison per pixel
	 *
	 * @param     $expected  (image resource)
	 * @param     $actual    (image resource)
	 * @param int $height
	 * @param int $width
	 *
	 * @author adam-lynch
	 */
	protected function assertImageResourcesAreEqual( $expected, $actual, $width = 0, $height = 0 ) {

		if ( empty( $width ) ) {
			$width = imagesx( $expected );
		}

		if ( empty( $height ) ) {
			$height = imagesy( $expected );
		}

		//quick failure: check if actual image is as tall and wide as the expected width & height (optional params)
		$this->assertEquals($width, imagesx($actual), 'Width of the actual image is not as expected');
		$this->assertEquals($height, imagesy($actual), 'Height of the actual image is not as expected');

		for ( $x = 0; $x < $width; $x++ ) {
			for ( $y = 0; $y < $height; $y++ ) {
				$this->assertEquals(
					imagecolorat( $expected, $x, $y ),
					imagecolorat( $actual, $x, $y ),
					"Pixel colour does not match between original and stored image at ({$x}, {$y})"
				);
				//todo: check what imagecolorat returns and therefore see if it will it show the rgb values on failure
			}
		}
	}
}