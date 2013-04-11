<?php

namespace IconStyles;

class File
{

	/** @var string $path */
	private $path;
	/** @var string $filename */
	private $filename;
	/** @var string $extension */
	private $extension;

	public function __construct( $path, $filename, $extension ) {

		$this->setPath( $path );
		$this->setFilename( $filename );
		$this->setExtension( $extension );
	}

	/**
	 * @param string $extension
	 */
	public function setExtension( $extension ) {

		$this->extension = $extension;
	}

	/**
	 * @return string
	 */
	public function getExtension() {

		return $this->extension;
	}

	/**
	 * @param string $filename
	 */
	public function setFilename( $filename ) {

		$this->filename = $filename;
	}

	/**
	 * @return string
	 */
	public function getFilename() {

		return $this->filename;
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

	public function getFullPath() {

		$extension = $this->getExtension();

		return $this->getPath() . DIRECTORY_SEPARATOR . $this->getFilename()
			   . (empty( $extension ) ? '' : ( '.' . $extension ));
	}
}