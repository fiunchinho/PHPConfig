<?php
/**
 * Production environment config.
 */
$prod['db']	= array(
	'user'	=> 'prod_db_user',
	'pass'	=> 'prod_db_pass',
	'host'	=> 'prod_db_host'
);
$prod['cache_path']		= 'my/prod/path/cache';
$prod['base_url']	= 'http://config.com';
$prod['key3']		= 'prod key3';
$config['prod']		= $prod;

/**
 * Pre production environment config.
 */
$pre			= $prod; // Pre environment inherits from production environment.
$pre['db']	= array(
	'user'	=> 'pre_db_user',
	'pass'	=> 'pre_db_pass',
	'host'	=> 'pre_db_host'
);
$pre['cache_path']		= 'my/pre/path/cache';
$pre['base_url']= 'http://config.vm';
$pre['pass']	= 'pre_pass';
$pre['key2']	= 'pre key2';
$pre['key3']	= 'pre key3';
$config['pre']	= $pre;

/**
 * Dev environment config.
 */
$dev			= $pre; // Dev environment inherits from pre environment.
$dev['only_dev']= 'only_dev';
$dev['base_url']= 'http://config.local';
$dev['cache_path']		= 'my/dev/path/cache';
$config['dev']	= $dev;

return $config;

?>
