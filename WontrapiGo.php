<?php

/**
 * WontrapiGo.
 *
 * Class for accessing the Ontraport API via Ontraport's SDK for PHP.
 * This was created to make the SDK more accessable and add methods
 * for common use cases.
 *
 * @author github.com/oakwoodgates 
 * @copyright 2017 	WPGuru4u
 * @link   https://api.ontraport.com/doc/ OP API Documentation
 * @link   https://api.ontraport.com/live/ OP API Docs
 * @link   https://github.com/Ontraport/SDK-PHP/ Ontraport's SDK for PHP
 * @license https://opensource.org/licenses/MIT/ MIT
 * @version 0.1.0 Initial
 */
/**
 * MIT License
 *
 * Copyright (c) 2017 WPGuru4u
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class WontrapiGo {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  0.1.0
	 */
	const VERSION = '0.1.0';

	/**
	 * Singleton instance of plugin
	 *
	 * @var WontrapiGo
	 * @since  0.1.0
	 */
	protected static $single_instance = null;

	/**
	 * App ID for Ontraport
	 *
	 * @var string
	 * @since  0.1.0
	 */
	public static $id = '';

	/**
	 * App Key for Ontraport
	 *
	 * @var string
	 * @since  0.1.0
	 */
	public static $key = '';

	/**
	 * Namespace for Ontraport SDK
	 *
	 * @var string
	 * @since  0.1.0
	 */
	public static $namespace = 'OntraportAPI';

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @param  string $id        App ID for Ontraport
	 * @param  string $key       App Key for Ontraport
	 * @param  string $namespace Namespace for Ontraport SDK
	 * @return Wontrapi A single instance of this class.
	 * @since  0.1.0	Initial	 
	 */
	public static function init( $id, $key, $namespace = 'OntraportAPI' ) {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self( $id, $key, $namespace );
		}

		return self::$single_instance;
	}

	protected function __construct( $id, $key, $namespace ) {
		self::setID( $id );
		self::setKey( $key );
		self::setNamespace( $namespace );
	}

	/**
	 * Set the App ID for Ontraport
	 * 
	 * @param string $id App ID for Ontraport
	 * @since  0.1.0
	 */
	public static function setID( $id ) {
		self::$id = $id;
	}

	/**
	 * Set the App Key for Ontraport
	 * 
	 * @param string $key App Key for Ontraport
	 * @since  0.1.0
	 */
	public static function setKey( $key ) {
		self::$key = $key;
	}

	/**
	 * Set the Namespace for Ontraport SDK
	 * 
	 * @param string $id Namespace for Ontraport SDK
	 * @since  0.1.0
	 */
	public static function setNamespace( $namespace ) {
		self::$namespace = $namespace;
	}

	/**
	 * Connect to Ontraport API
	 * 
	 * @return [type] [description]
	 * @since  0.1.0
	 */
	public static function client() {
		return new self::$namespace . \Ontraport( self::$id, self::$key );
	}

}
