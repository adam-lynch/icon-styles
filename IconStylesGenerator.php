<?php

namespace IconStyles;

require_once( 'SpriteSheet.php' );
require_once( 'Image.php' );
use IconStyles\SpriteSheet;

/**
 * Class IconStylesGenerator
 *
 * @package IconStylesGenerator
 */
class IconStylesGenerator
{

	/** @var array $supportedFileExtensions */
	private static $supportedFileExtensions = array(
		'gif',
		'jpeg',
		'jpg',
		'png',
		'webp',
		'wbmp',
		'xbm',
		'xpm'
	);
	/** @var string $sourceDirectory */
	private $sourceDirectory;
	/** @var string $filenameFormatRegex */
	private static $filenameFormatRegex;

	/**
	 * @param string $imageSourceDirectory
	 *
	 * @throws \Exception
	 */
	public function __construct( $imageSourceDirectory ) {

		if ( !is_dir( $imageSourceDirectory ) ) {
			throw new \Exception( 'The image source directory path given (' . $imageSourceDirectory . ') is not a directory' );
		}

		$this->setSourceDirectory( $imageSourceDirectory );
		self::$filenameFormatRegex = $this->buildFilenameFormatRegex();
	}

	private function buildFilenameFormatRegex() {

		$supportedFileExtensions = self::$supportedFileExtensions;
		$delimiter               = '%';

		return $delimiter . '^(.+)\.('
			   . ( count( $supportedFileExtensions ) ? implode( '|', $supportedFileExtensions ) : '.+?' )
			   . ')$' . $delimiter;
	}

	protected function getImagesFromDirectory( $directory, $regex ) {

		return new \RegexIterator(
			new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator( $directory, \FilesystemIterator::SKIP_DOTS ),
				\RecursiveIteratorIterator::LEAVES_ONLY
			),
			$regex,
			\RecursiveRegexIterator::GET_MATCH
		);

	}

	public function generate( $destinationFilename ) {

		$spriteSheet = new SpriteSheet();

		$filenames = $this->getImagesFromDirectory( $this->getSourceDirectory(), self::$filenameFormatRegex );

		foreach ( $filenames as $pathGroups ) {

			$spriteSheet->addImage( $pathGroups[0], $pathGroups[2] );//pass full path (everything) and extension
		}

		$spriteSheet->generate( $destinationFilename );
	}

	/**
	 * @param string $sourceDirectory
	 */
	public function setSourceDirectory( $sourceDirectory ) {

		$this->sourceDirectory = $sourceDirectory;
	}

	/**
	 * @return string
	 */
	public function getSourceDirectory() {

		return $this->sourceDirectory;
	}
}
