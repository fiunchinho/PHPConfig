<?php
namespace PHPConfig;

/**
 * @author fiunchinho
 */
class File
{
	public function __construct( $filename )
	{
		$this->filename = $filename;
	}
	public function requireFile()
	{
		if ( !\file_exists( $this->filename ) || !\is_readable( $this->filename ) )
			throw new FileNotFoundException( "The file " . $this->filename . " was not found or it's not readable." );
		else
			return require $this->filename;
	}
	public function getName()
	{
		return $this->filename;
	}
}

class FileNotFoundException extends \Exception{}