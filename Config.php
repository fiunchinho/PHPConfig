<?php
namespace PHPConfig;

require __DIR__ . '/File.php';

/**
 * @author fiunchinho
 */
class Config
{
	/**
	 * Name of the key containing the config environment where a certain environment inherits from.
	 */
	const		INHERITS_FROM		= '_parent';

	/**
	 * @var array Contains the raw config values.
	 */
	protected	$config_values		= array();

	/**
	 *
	 * @var array Contains the final values, taking inheritance in account.
	 */
	protected	$calculated_values	= array();

	/**
	 * @var string Environment where we are.
	 */
	public		$environment;

	/**
	 * It includes the config file with the config values, and save them in the calculated_values property, taking care of inheritance.
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
		$this->config_values		= $file->requireFile();
		$this->calculated_values	= $this->getConfigFromEnvironment( $environment );
		$this->config_values		= null;
	}

	/**
	 * It calculates the config values. Environments can inherit from other environments.
	 *
	 * @param string $environment The environment where you are in.
	 * @return array The calculated values for the given environment
	 */
	private function getConfigFromEnvironment( $environment )
	{
		if ( !\array_key_exists( $environment, $this->config_values ) )
		{
			throw new PHPConfigException( 'Missing environment: problem trying to get config values for \'' . $environment . '\' environment' );
		}

		$environment_values			= $this->config_values[$environment];
		// If this environment does not inherit from another, return its config values.
		if ( !\array_key_exists( self::INHERITS_FROM, $environment_values ) )
		{
			return $environment_values ;
		}

		$parent_environment_values	= $this->config_values[$environment][self::INHERITS_FROM];
		return \array_merge( $this->getConfigFromEnvironment( $parent_environment_values ), $environment_values );
	}

	/**
	 * It returns the requested config key.
	 * @param string $config_key Which config key you want to know the value
	 * @return mixed The config value
	 */
	public function get( $config_key )
	{
		if ( isset( $this->calculated_values[$config_key] ) )
		{
			return $this->calculated_values[$config_key];
		}
		return null;
	}
}

class PHPConfigException extends \Exception {}
