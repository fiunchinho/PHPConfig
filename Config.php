<?php
namespace PHPConfig;

//require __DIR__ . '/File.php';

/**
 * @author fiunchinho
 */
class Config
{
	/**
	 * @var array Contains the raw config values.
	 */
	protected	$config_values		= array();

	/**
	 * @var string Environment where we are.
	 */
	public		$environment;

	/**
	 * It includes the config file with the config values, and save them in the config_values property.
	 *
	 * @param string $environment The environment where you are in.
	 * @param File $file A file handler to work with the filesystem.
	 */
	public function __construct( $environment, $file )
	{
		if ( empty( $file ) )
		{
			throw new PHPConfigException( 'A file handler is needed.' );
		}

		$this->environment			= $environment ;
		$included_values			= $file->requireFile();
		if ( !\array_key_exists( $environment, $included_values ) )
		{
			throw new PHPConfigException( 'Missing environment: problem trying to get config values for \'' . $environment . '\' environment' );
		}
		$this->config_values		= $included_values[$environment];
	}

	/**
	 * It returns the requested config key.
	 * @param string $config_key Which config key you want to know the value
	 * @param mixed $default_value In case that the key is not present, this value will be returned
	 * @return mixed The config value
	 */
	public function get( $config_key, $default_value = null )
	{
		if ( \array_key_exists( $config_key, $this->config_values ) )
		{
			return $this->config_values[$config_key];
		}
		return $default_value;
	}
}

class PHPConfigException extends \InvalidArgumentException {}