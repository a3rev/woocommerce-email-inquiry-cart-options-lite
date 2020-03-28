<?php
/**
 * Class SampleTest
 *
 * @package Sample_Plugin
 */

/**
 * Sample test case.
 */
class a3Rev_Tests_WC_EMAIL_INQUIRY extends WP_UnitTestCase {

	function test_constants_defined() {
		$this->assertTrue( defined( 'WC_EMAIL_INQUIRY_KEY' ) );
		$this->assertTrue( defined( 'WC_EMAIL_INQUIRY_PREFIX' ) );
		$this->assertTrue( defined( 'WC_EMAIL_INQUIRY_VERSION' ) );
	}
}
