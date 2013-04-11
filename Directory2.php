<?php

	namespace IconStylesGenerator;

	/**
	 * Class Directory
	 *
	 * @package IconStylesGenerator
	 */
	class Directory2
	{

		/** @var array $quickFilenameExclusions */
		private static $quickFilenameExclusions = array( '.', '..' );
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
		/** @var string $path */
		private $path;
		/** @var array $desiredFileExtensions */
		private $desiredFileExtensions;
		/** @var string $filenameFormatRegex */
		private $filenameFormatRegex;


		/**
		 * @param string $path
		 *
		 * @throws \Exception
		 */
		public function __construct( $path ) {

			if ( !is_dir( $path ) ) {
				throw new \Exception( 'The path given (' . $path . ') is not a directory' );
			}

			$this->setPath( $path );
		}

		/**
		 * @param array $fileExtensions
		 *
		 * @throws \Exception
		 * @return Image[]
		 */
		public function getImages( array $fileExtensions = array() ) {

			if ( empty( $fileExtensions ) ) {
				$this->desiredFileExtensions = self::$supportedFileExtensions;
			}
			elseif ( !$this->isSubset( $fileExtensions, self::$supportedFileExtensions ) ) {
				//at least one file extension passed isn't supported

				$exceptionMessage = 'At least one of the file extensions given is not in the list of supported file '
									. 'extensions; ' . implode( ', ' );
				throw new \Exception( $exceptionMessage ); //todo: throw custom exception?
			}
			else {
				$this->desiredFileExtensions = $fileExtensions;
			}

			return $this->scan();
		}

		/**
		 *
		 */
		private function scan() {

			if ( empty( $path ) ) { //first time
				$path            = $this->getPath();
				$fileFormatRegex = $this->buildFilenameFormatRegex();
			}
			else {
				//this is a recursive call. Assumption: $path is a directory
				$fileFormatRegex = $this->getFilenameFormatRegex();
			}

			$files               = new \ArrayObject( scandir( $path ) );
			$numberOfImagesFound = 0;

			$quickFilenameExclusions = array( '.', '..' );

			while($filename = $files->next){
				if ( !in_array( $filename, $quickFilenameExclusions ) {


					$fullFilePath = $path . DIRECTORY_SEPARATOR . $filename;

					if ( is_dir( $fullFilePath ) ) {

					}
					else {

						$lowerFilename = strtolower( $filename );

						if ( preg_match( $fileFormatRegex, $lowerFilename, $filenameExtensionMatches ) ) {


							//if the filename matches the desired format (basically a supported file extension)

							$numberOfImagesFound++;
							$filenameExtension = $filenameExtensionMatches[1];

							/*
							 * build the GD function name first based on file extension
							 * e.g. imagecreatefrompng() for PNGs
							 * Exception: imagecreatefromjpeg() for JPGs
							 */
							$createImageFunctionName = 'imagecreatefrom'
													   . ( 'jpg' === $filenameExtension ? 'jpeg' : $filenameExtension );

							//create the image
							$image = $createImageFunctionName( $fullFilePath );
							if ( !$image ) {
								exit( 'Error loading (' . $filenameExtension . ') image: ' . $fullFilePath );
							}

							imagecolortransparent( $image, imagecolorallocate( $image, 0, 255, 0 ) );

							$imageWidth  = imagesx( $image );
							$imageHeight = imagesy( $image );

							$result[] = array( 'filePath' => $fullFilePath, 'width' => $imageWidth,
											   'height'   => $imageWidth,
											   'X'        => $positionX, 'Y' => $positionY, 'resource' => $image );

							$positionX += $gutter + $imageWidth;
							$overallWidth += $gutter + $imageWidth;
							if ( $imageHeight > $overallHeight ) {
								$overallHeight = $imageHeight;
							}
						}
					}
				}
			}
			return array();//todo
		}

		/**
		 * @return string
		 */
		private function buildFilenameFormatRegex() {

			$desiredFileExtensions = $this->getDesiredFileExtensions();

			$delimiter                 = '%';
			$disjunction               = '|';
			$fileExtensionRegexSegment = '';

			foreach ( $desiredFileExtensions as $fileExtension ) {
				$fileExtensionRegexSegment .= $fileExtension . ( '' === $fileExtensionRegexSegment ? ''
					: $disjunction );
			}

			$regex = $delimiter . '\.(' . $fileExtensionRegexSegment . ')$' . $delimiter;

			$this->setFilenameFormatRegex( $regex );

			return $regex;
		}

		/**
		 * Checks if all of one array's element values exist in another
		 *
		 * @param array $haystackJunior
		 * @param array $haystackSenior
		 *
		 * @return bool
		 */
		private function isSubset( array $haystackJunior, array $haystackSenior ) {

			foreach ( $haystackJunior as $straw ) {
				if ( !in_array( $straw, $haystackSenior ) ) {
					//$straw isn't supported
					return false;
				}
			}

			return true;
		}

		/**
		 * @param string $path
		 */
		public function setPath( $path ) {

			$this->path = $path;
		}

		/**
		 * @return string
		 */
		public function getPath() {

			return $this->path;
		}

		/**
		 * @param array $desiredFileExtensions
		 */
		public function setDesiredFileExtensions( $desiredFileExtensions ) {

			$this->desiredFileExtensions = $desiredFileExtensions;
		}

		/**
		 * @return array
		 */
		public function getDesiredFileExtensions() {

			return $this->desiredFileExtensions;
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
	}
