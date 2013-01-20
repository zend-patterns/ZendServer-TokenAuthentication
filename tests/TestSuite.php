<?php
namespace Application;
use \PHPUnit_Framework_TestSuite, \RegexIterator, \RecursiveIteratorIterator, \RecursiveDirectoryIterator;

require_once 'PHPUnit/Framework/TestSuite.php';

/**
 * Static test suite.
 */
class TestSuite extends PHPUnit_Framework_TestSuite {
	
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		$this->setName ( 'TokenAuthentication Module Tests' );
		$it = new RegexIterator (new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( __DIR__ ) ), '/^[^\.].+Test\.php$/' );
		foreach ($it as $filePath) { /* @var $filePath SplFileInfo */
			$this->addTestFile($filePath->getPathname());
		}
	}
	
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ();
	}
}

