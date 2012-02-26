<?php
namespace PHPConfig;

require __DIR__ . '/../src/Config.php';


/**
 * @author fiunchinho
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var File A file handler to include the config file
	 */
	public $fileHandler;

	public function testConfigClassIsInstantiatedCorrectly()
	{
		$this->setExpectedException( 'PHPConfig\PHPConfigException' );
		$config = new Config( 'dev', null );
	}

	public function testConfigFindsFileFromSpecificEnvironment()
	{
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = 'localhost';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'prod';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );
		
		$config = new Config( 'dev', $this->fileHandler );
		
		$this->assertEquals( 'dev', $config->environment );
		$this->assertEquals( 'fiunchinho', $config->get( 'user' ) );
		$this->assertEquals( 'wc3', $config->get( 'pass' ) );
	}

	public function testGetReturnsValueWhenNoInheritance()
	{
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = 'localhost';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'prod', $this->fileHandler );
		$this->assertEquals( 'ONe', $config->get( 'user' ) );
		$this->assertEquals( 'por', $config->get( 'pass' ) );
		$this->assertEquals( 'localhost', $config->get( 'db' ) );
	}

	public function testGetReturnsValueFromParentWhenSeveralEnvironmentsDefined()
	{
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = 'localhost';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'prod', $this->fileHandler );
		$this->assertEquals( 'ONe', $config->get( 'user' ) );
		$this->assertEquals( 'por', $config->get( 'pass' ) );
		$this->assertEquals( 'localhost', $config->get( 'db' ) );
	}

	public function testGetReturnsValueFromChildWhenSeveralEnvironmentsDefined()
	{
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = 'localhost';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'dev', $this->fileHandler );
		$this->assertEquals( 'fiunchinho', $config->get( 'user' ) );
		$this->assertEquals( 'wc3', $config->get( 'pass' ) );
	}

	public function testGetReturnsValueFromChildInherited()
	{
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = 'localhost';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'prod';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'dev', $this->fileHandler );
		$this->assertEquals( 'fiunchinho', $config->get( 'user' ), 'The user must be fiunchinho' );
		$this->assertEquals( 'wc3', $config->get( 'pass' ), 'The pass must be wc3' );
		$this->assertEquals( 'localhost', $config->get( 'db' ), 'The db must be localhost' );
	}

	public function testGetReturnsValueFromParentHavingInheritance()
	{
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = 'localhost';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'prod';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'prod', $this->fileHandler );
		$this->assertEquals( 'ONe', $config->get( 'user' ), 'The user must be fiunchinho' );
		$this->assertEquals( 'por', $config->get( 'pass' ), 'The pass must be wc3' );
		$this->assertEquals( 'localhost', $config->get( 'db' ), 'The db must be localhost' );
	}

	public function testGetReturnsInheritedValueFromGrandSonWithMultipleInheritanceAsking()
	{
		$db_expected_value = array(
			'host'	=> 'localhost',
			'driver'=> 'pdo'
		);
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = $db_expected_value;

		$config['pre']['user']		= 'preONe';
		$config['pre']['pass']		= 'prepor';
		$config['pre']['baseUrl']	= 'myBase';
		$config['pre']['_parent']	= 'prod';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'pre';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'dev', $this->fileHandler );
		$this->assertEquals( 'fiunchinho', $config->get( 'user' ), 'The user must be fiunchinho' );
		$this->assertEquals( 'wc3', $config->get( 'pass' ) );
		$this->assertEquals( 'myBase', $config->get( 'baseUrl' ) );
		$this->assertEquals( $db_expected_value, $config->get( 'db' ), 'The db must be localhost' );
	}

	public function testGetReturnsInheritedValueFromParentWithMultipleInheritance()
	{
		$db_expected_value = array(
			'host'	=> 'localhost',
			'driver'=> 'pdo'
		);
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = $db_expected_value;

		$config['pre']['user']		= 'preONe';
		$config['pre']['pass']		= 'prepor';
		$config['pre']['baseUrl']	= 'myBase';
		$config['pre']['_parent']	= 'prod';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'pre';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'pre', $this->fileHandler );
		$this->assertEquals( 'preONe', $config->get( 'user' ), 'The user must be preONe' );
		$this->assertEquals( 'prepor', $config->get( 'pass' ) );
		$this->assertEquals( 'myBase', $config->get( 'baseUrl' ) );
		$this->assertEquals( $db_expected_value, $config->get( 'db' ), 'The db must be localhost' );
	}

	public function testGetReturnsInheritedValueFromGrandParentWithMultipleInheritanceAsking()
	{
		$db_expected_value = array(
			'host'	=> 'localhost',
			'driver'=> 'pdo'
		);
		$config['prod']['user'] = 'ONe';
		$config['prod']['pass'] = 'por';
		$config['prod']['db'] = $db_expected_value;

		$config['pre']['user']		= 'preONe';
		$config['pre']['pass']		= 'prepor';
		$config['pre']['baseUrl']	= 'myBase';
		$config['pre']['_parent']	= 'prod';

		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'pre';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$config = new Config( 'prod', $this->fileHandler );
		$this->assertEquals( 'ONe', $config->get( 'user' ), 'The user must be fiunchinho' );
		$this->assertEquals( 'por', $config->get( 'pass' ) );
		$this->assertNull( $config->get( 'baseUrl' ) );
		$this->assertEquals( $db_expected_value, $config->get( 'db' ), 'The db must be localhost' );
	}

	public function testAskingConfigValuesForNonExistingEnvironment()
	{
		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'pre';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile', 'getName' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$this->setExpectedException( 'PHPConfig\PHPConfigException' );
		$config = new Config( 'prod', $this->fileHandler );
	}

	public function testAskingConfigValuesForNonExistingInheritedEnvironment()
	{
		$config['dev']['user']		= 'fiunchinho';
		$config['dev']['pass']		= 'wc3';
		$config['dev']['_parent']	= 'pre';

		$this->fileHandler = $this->getMock( 'File', array( 'requireFile' ) );
		$this->fileHandler->expects( $this->once() )
					->method( 'requireFile' )
					->will( $this->returnValue( $config ) );

		$this->setExpectedException( 'PHPConfig\PHPConfigException' );
		$config = new Config( 'dev', $this->fileHandler );
	}
}
