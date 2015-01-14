<?php

namespace Wikibase\DataModel\Tests\Term;

use Wikibase\DataModel\Term\AliasGroup;
use Wikibase\DataModel\Term\AliasGroupFallback;

/**
 * @covers Wikibase\DataModel\Term\AliasGroupFallback
 *
 * @licence GNU GPL v2+
 * @author Jan Zerebecki < jan.wikimedia@zerebecki.de >
 */
class AliasGroupFallbackTest extends \PHPUnit_Framework_TestCase {

	public function testConstructorSetsValues() {
		$language = 'en-real';
		$aliases = array( 'foo', 'bar', 'baz' );
		$actual = 'en-actual';
		$source = 'en-source';

		$group = new AliasGroupFallback( $language, $aliases, $actual, $source );

		$this->assertEquals( $language, $group->getLanguageCode() );
		$this->assertEquals( $aliases, $group->getAliases() );
		$this->assertEquals( $actual, $group->getActualLanguageCode() );
		$this->assertEquals( $source, $group->getSourceLanguageCode() );
	}

	public function testConstructorWithNullForSource() {
		$language = 'en-real';
		$aliases = array();
		$actual = 'en-actual';
		$source = null;

		$group = new AliasGroupFallback( $language, $aliases, $actual, $source );

		$this->assertEquals( $source, $group->getSourceLanguageCode() );
	}

	public function testGivenInvalidActualLanguageCode_constructorThrowsException() {
		$language = 'en-real';
		$aliases = array();
		$actual = null;
		$source = 'en-source';

		$this->setExpectedException( 'InvalidArgumentException' );
		$group = new AliasGroupFallback( $language, $aliases, $actual, $source );
	}

	public function testGivenInvalidSourceLanguageCode_constructorThrowsException() {
		$language = 'en-real';
		$aliases = array();
		$actual = 'en-actual';
		$source = 21;

		$this->setExpectedException( 'InvalidArgumentException' );
		$group = new AliasGroupFallback( $language, $aliases, $actual, $source );
	}

	public function testGroupEqualsItself() {
		$group = new AliasGroupFallback( 'en-real', array( 'foo', 'bar' ), 'en-actual', 'en-source' );

		$this->assertTrue( $group->equals( $group ) );
		$this->assertTrue( $group->equals( clone $group ) );
	}

	/**
	 * @dataProvider inequalAliasGroupProvider
	 */
	public function testGroupDoesNotEqualOnesWithMoreOrFewerValues( $inequalGroup ) {
		$group = new AliasGroupFallback( 'en-real', array( 'foo' ), 'en-actual', 'en-source' );

		$this->assertFalse( $group->equals( $inequalGroup ) );
	}

	public function inequalAliasGroupProvider() {
		return array(
			'aliases' => array( new AliasGroupFallback( 'en-real', array( 'moo' ), 'en-actual', 'en-source' ) ),
			'language' => array( new AliasGroupFallback( 'en-moo', array( 'foo' ), 'en-actual', 'en-source' ) ),
			'actualLanguage' => array( new AliasGroupFallback( 'en-real', array( 'foo' ), 'en-moo', 'en-source' ) ),
			'sourceLanguage' => array( new AliasGroupFallback( 'en-real', array( 'foo' ), 'en-actual', 'en-moo' ) ),
			'null sourceLanguage' => array( new AliasGroupFallback( 'en-real', array( 'foo', ), 'en-actual', null ) ),
			'all' => array( new AliasGroupFallback( 'en-moo', array( 'moo' ), 'en-moo', 'en-moo' ) ),
			'class AliasGroup' => array( new AliasGroup( 'en-real', array( 'foo' ) ) ),
		);
	}

}
