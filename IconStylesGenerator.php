<?php

	namespace IconStylesGenerator;

	/**
	 * Class IconStylesGenerator
	 *
	 * @package IconStylesGenerator
	 */
	class IconStylesGenerator
	{
		/** @var array $quickFilenameExclusions */
		private static $quickFilenameExclusions = array(
			'.',
			'..'
		);
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
		/** @var array $supportedFileExtensions */
		private static $supportedOutputFormats = array(
			'less'
		);
		/** @var string $sourceDirectory */
		private $sourceDirectory;
		/** @var string $desiredFileExtensions */
		private $desiredFileExtensions;
		/** @var string $filenameFormatRegex */
		private $filenameFormatRegex;

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
		}

		public function generate($destinationFilename, $outputFormat = null, $options = array()){
			//todo

			if(empty($outputFormat)){
				$outputFormat = self::$supportedOutputFormats[0];
			}

			$sourceDirectory = new Directory($this->getSourceDirectory());

			$directories = new \ArrayObject();
			$directories->append( $sourceDirectory );

			while(!!($directory = $directories->next)){
				var_dump($directory);
			}

			/*if ( empty( $fileExtensions ) ) {
				$this->setDesiredFileExtensions(self::$supportedFileExtensions);
			}
			elseif ( !$this->isSubset( $fileExtensions, self::$supportedFileExtensions ) ) {
				//at least one file extension passed isn't supported

				$exceptionMessage = 'At least one of the file extensions given is not in the list of supported file '
									. 'extensions; ' . implode( ', ' );
				throw new \Exception( $exceptionMessage ); //todo: throw custom exception?
			}
			else {
				$this->setDesiredFileExtensions($fileExtensions);
			}*/
		}

		private function get($name){

		}

		/**
		 * @param string $filenameFormatRegex
		 */
		public function setFilenameFormatRegex( $filenameFormatRegex ) {

			$this->filenameFormatRegex = $filenameFormatRegex;
		}

		/**
		 * @return string
		 */
		public function getFilenameFormatRegex() {

			return $this->filenameFormatRegex;
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

		/**
		 * @param string $desiredFileExtensions
		 */
		public function setDesiredFileExtensions( $desiredFileExtensions ) {

			$this->desiredFileExtensions = $desiredFileExtensions;
		}

		/**
		 * @return string
		 */
		public function getDesiredFileExtensions() {

			return $this->desiredFileExtensions;
		}
	}
