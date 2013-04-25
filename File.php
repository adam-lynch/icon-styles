<?php

namespace IconStyles;

class File
{

	/** @var string $path */
	private $path;

	/** @var string $extension */
	private $extension;

	public function __construct( $path, $extension ) {

		$this->setPath( $path );
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
}