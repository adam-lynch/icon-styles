<?php

	/**
	 * Creates regular expression which files in source directory will be tested against
	 *
	 * Example return values;
	 *
	 * if $supportFileExtensions is array('jpg', 'png')
	 * %\.(jpg|png)$%
	 *
	 * if $supportFileExtensions is empty
	 * %\.(.+)$%
	 *
	 *
	 * @param array $supportedFileExtensions
	 *
	 * @return string
	 */
	function buildImageFilenameFormatRegex( array $supportedFileExtensions ) {

		$delimiter = '%';

		return $delimiter . '\.('
			   . ( count( $supportedFileExtensions ) ? implode( '|', $supportedFileExtensions ) : '.+' )
			   . ')$' . $delimiter;
	}

	$sourceDirectory         = 'test/resources';
	$destinationFile         = 'icons.png';
	$supportedFileExtensions = array(
		'gif',
		'jpeg',
		'jpg',
		'png',
		'webp',
		'wbmp',
		'xbm',
		'xpm'
	);
	$gutter                  = 0;

	if ( !is_dir( $sourceDirectory ) ) {
		exit( 'The sourceDirectory argument (' . $sourceDirectory . ') is not a directory' );
	}

	$result              = array();
	$files               = scandir( $sourceDirectory );
	$numberOfImagesFound = $overallWidth = $overallHeight = $positionX = $positionY = 0;
	$imageFilenameRegex  = buildImageFilenameFormatRegex( $supportedFileExtensions );

	foreach ( $files as $filename ) {
		if ( '.' !== $filename && '..' !== $filename ) {
			$lowerFilename = strtolower( $filename );

			//if the filename matches the desired format (basically a supported file extension)
			if ( preg_match( $imageFilenameRegex, $lowerFilename, $filenameExtensionMatches ) ) {

				$numberOfImagesFound++;
				$filenameExtension = $filenameExtensionMatches[1];
				$fullFilePath      = $sourceDirectory . DIRECTORY_SEPARATOR . $filename;

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

				imagecolortransparent($image, imagecolorallocate($image, 0, 255, 0));

				$imageWidth  = imagesx( $image );
				$imageHeight = imagesy( $image );

				$result[] = array( 'filePath' => $fullFilePath, 'width' => $imageWidth, 'height' => $imageWidth,
								   'X'        => $positionX, 'Y' => $positionY, 'resource' => $image );

				$positionX += $gutter + $imageWidth;
				$overallWidth += $gutter + $imageWidth;
				if ( $imageHeight > $overallHeight ) {
					$overallHeight = $imageHeight;
				}
			}
		}

	}

	if ( $numberOfImagesFound ) {

		$resultImage = imagecreatetruecolor( $overallWidth, $overallHeight );
		imagecolortransparent($resultImage, imagecolorallocate($resultImage, 0, 0, 0));
		foreach ( $result as $icon ) {
			imagecopy( $resultImage, $icon['resource'], $icon['X'], $icon['Y'], 0, 0, $icon['width'], $icon['height']);
		}

		imagepng($resultImage, $destinationFile);//todo call jpg/png based on destination file

		echo $numberOfImagesFound, ' images combined into ', $destinationFile, ' (width: ', $overallWidth, 'px, height: ',
		$overallHeight, 'px)';

		var_dump( $result );

		exit( 0 );
	}
	else {
		exit( 'No images found in sourceDirectory (' . $sourceDirectory . ')' );
	}


