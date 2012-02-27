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
		if ( !$this->isReadable() )
			throw new FileNotFoundException( "The file " . $this->filename . " was not found or it's not readable." );
		else
			return require $this->filename;
	}
	public function getName()
	{
		return $this->filename;
	}
	public function isReadable()
	{
		return \is_readable( $this->filename ) ;
	}
}

class FileNotFoundException extends \InvalidArgumentException{}