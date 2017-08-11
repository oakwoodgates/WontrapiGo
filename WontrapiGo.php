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

	/**
	 * Create an object
	 * 
	 * This endpoint will add a new object to your database. 
	 * It can be used for any object type as long as the 
	 * correct parameters are supplied. 
	 * 
	 * This endpoint allows duplication. If you want to avoid duplicates,
	 * you should use - wontrapiGo_create_or_update_object()
	 * 
	 * @param  string $type Required - Object type (not for Custom Objects). Converts to objectID.
	 * @param  array  $args Parameters depend upon the object. Some may be required.
	 * @return json   		Response from Ontraport
	 * @link   https://api.ontraport.com/doc/#create-an-object OP API Documentation
	 * @author github.com/oakwoodgates 
	 * @since  0.1.0 Initial
	 */
	public static function create_object( $type, $args = array() ) {
		$args = self::params( $type, $args );
		return self::connect()->object()->create( $args );
	}

	/**
	 * Create or merge an object
	 * 
	 * Looks for an existing object with a matching unique field and 
	 * merges supplied data with existing data. If no unique field is 
	 * supplied or if no existing object has a matching unique field, 
	 * a new object will be created.
	 * 
	 * @param  string $type Required - Object type (not for Custom Objects). Converts to objectID.
	 * @param  array  $args Parameters depend upon the object. Some may be required.
	 * @return json   		Response from Ontraport
	 * @link   https://api.ontraport.com/doc/#create-an-object OP API Documentation
	 * @author github.com/oakwoodgates 
	 * @since  0.1.0 Initial
	 */
	public static function create_or_update_object( $type, $args = array() ) {
		$args['objectID'] = self::objectID( $type );
		return self::connect()->object()->saveOrUpdate( $args );
	}

	/**
	 * Retrieve a single object
	 * 
	 * Retrieves all the information for an existing object of the specified object type.
	 * 
	 * @param  string  $type Required - Object type (not for Custom Objects). Converts to objectID.
	 * @param  integer $id   Required - ID of object to get
	 * @return json   		 Response from Ontraport
	 * @link   https://api.ontraport.com/doc/#retrieve-a-single-object OP API Documentation
	 * @author github.com/oakwoodgates 
	 * @since  0.1.0 Initial        
	 */
	public static function get_object( $type, $id ) {
		$args = array(
			'objectID' 	=> self::objectID( $type ),
			'id'		=> $id 
		);
		return self::connect()->object()->retrieveSingle( $args );
	}

	/**
	 * Retrieve object meta
	 *
	 * Retrieves the field meta data for the specified object.
	 * 
	 * @param  string $type   Object type (not for Custom Objects). Converts to objectID.
	 *                        If none is supplied, meta for all objects will be retrieved.
	 * @param  string $format Indicates whether the list should be indexed by object name or object type ID. 
	 *                        Possible values: 'byId' | 'byName'
	 * @return json Response from Ontraport
	 * @link   https://api.ontraport.com/doc/#retrieve-object-meta OP API Documentation
	 * @author github.com/oakwoodgates 
	 * @since  0.1.0 Initial 
	 */
	function get_object_meta( $type = '', $format = 'byId' ) {
		$args = array(
			'objectID' 	=> self::objectID( $type ),
			'format' => $format
		);
		return self::connect()->object()->retrieveMeta( $args );
	}


	/**
	 * General helper methods
	 */

	/**
	 * Adds objectID to request params
	 * 
	 * @param  string $type Object type
	 * @param  array  $args Array of data
	 * @return array        Array of data
	 * @since  0.1.0
	 */
	public static function params( $type = '', $args = array() ) {
		$a2 = array(
			'objectID' => self::objectID( $type ), 
		);

		return array_merge( $args, $a2 );
	}

	/**
	 * Get objectID for type
	 * 
	 * @param  string  $type Type of object
	 * @return integer       Object's objectID
	 * @since  0.1.0
	 */
	public static function objectID( $type ) {
		// let's not deal with strangeLetterCasing, lowercase ftw
		$type = strtolower( $type );
		// find the objectID
		switch( $type ) {
			case 'automationlogitems':
				$id = 100;
				break;
			case 'blasts':
				$id = 13;
				break;
			case 'campaigns':
				$id = 75;
				break;
			case 'commissions':
				$id = 38;
				break;
			case 'contacts':
				$id = 0;
				break;
			case 'contents':
				$id = 78;
				break;
			case 'couponcodes':
				$id = 124;
				break;
			case 'couponproducts':
				$id = 125;
				break;
			case 'coupons':
				$id = 123;
				break;
			case 'customdomains':
				$id = 58;
				break;
			case 'customervalueitems':
				$id = 96;
				break;
			case 'customobjectrelationships':
				$id = 102;
				break;
			case 'customobjects':
				$id = 99;
				break;
			case 'deletedorders':
				$id = 146;
				break;
			case 'facebookapps':
				$id = 53;
				break;
			case 'forms':
				$id = 122;
				break;
			case 'fulfillmentlists':
				$id = 19;
				break;
			case 'gateways':
				$id = 70;
				break;
			case 'groups':
				$id = 3;
				break;
			case 'imapsettings':
				$id = 101;
				break;
			case 'landingpages':
				$id = 20;
				break;
			case 'leadrouters':
				$id = 69;
				break;
			case 'leadsources':
				$id = 76;
				break;
			case 'logitems':
				$id = 4;
				break;
			case 'mediums':
				$id = 77;
				break;
			case 'messages':
				$id = 7;
				break;
			case 'messagetemplates':
				$id = 68;
				break;
			case 'notes':
				$id = 12;
				break;
			case 'offers':
				$id = 65;
				break;
			case 'openorders':
				$id = 44;
				break;
			case 'orders':
				$id = 52;
				break;
			case 'partnerproducts':
				$id = 87;
				break;
			case 'partnerprograms':
				$id = 35;
				break;
			case 'partnerpromotionalitems':
				$id = 40;
				break;
			case 'partners':
				$id = 36;
				break;
			case 'postcardorders':
				$id = 27;
				break;
			case 'products':
				$id = 16;
				break;
			case 'productsaleslogs':
				$id = 95;
				break;
			case 'purchasehistorylogs':
				$id = 30;
				break;
			case 'purchases':
				$id = 17;
				break;
			case 'referrals':
				$id = 37;
				break;
			case 'roles':
				$id = 61;
				break;
			case 'rules':
				$id = 6;
				break;
			case 'salesreportitems':
				$id = 94;
				break;
			case 'scheduledbroadcasts':
				$id = 23;
				break;
			case 'sequences':
				$id = 5;
				break;
			case 'sequencesubscribers':
				$id = 8;
				break;
			case 'shippedpackages':
				$id = 47;
				break;
			case 'shippingcollecteditems':
				$id = 97;
				break;
			case 'shippingfulfillmentruns':
				$id = 49;
				break;
			case 'shippingmethods':
				$id = 64;
				break;
			case 'smartforms':
				$id = 22; // informed guess from https://api.ontraport.com/doc/#retrieve-smartform-meta
				break;
			case 'staffs':
				$id = 2;
				break;
			case 'subscriberretentionitems':
				$id = 92;
				break;
			case 'subscriptionsaleitems':
				$id = 93;
				break;
			case 'tags':
				$id = 14;
				break;
			case 'tagsubscribers':
				$id = 138;
				break;
			case 'taskhistoryitems':
				$id = 90;
				break;
			case 'tasknotes':
				$id = 89;
				break;
			case 'taskoutcomes':
				$id = 66;
				break;
			case 'tasks':
				$id = 1;
				break;
			case 'taxes':
				$id = 63;
				break;
			case 'taxescollecteditems':
				$id = 98;
				break;
			case 'terms':
				$id = 79;
				break;
			case 'trackedlinks':
				$id = 80;
				break;
			case 'transactions':
				$id = 46;
				break;
			case 'upsellforms':
				$id = 42;
				break;
			case 'urlhistoryitems':
				$id = 88;
				break;
			case 'wordpressmemberships':
				$id = 43;
				break;
			case 'wordpresssites':
				$id = 67;
				break;
			default:
				$id = '';
				break;
		}
		return $id;
	}

}