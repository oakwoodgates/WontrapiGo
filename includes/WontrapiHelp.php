<?php 
/**
 * WontrapiHelp
 *
 * Class for helping WontrapiGo
 *
 * @author 		github.com/oakwoodgates 
 * @copyright 	2017 	WPGuru4u
 * @link   		https://api.ontraport.com/doc/ 			OP API Documentation
 * @link   		https://api.ontraport.com/live/ 		OP API Docs
 * @link   		https://github.com/Ontraport/SDK-PHP/ 	Ontraport's SDK for PHP
 * @license 	https://opensource.org/licenses/MIT/ 	MIT
 */

class WontrapiHelp {

	/**
	 * Singleton instance
	 *
	 * @var WontrapiHelp
	 * @since  0.3.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 * 
	 * @return WontrapiHelp A single instance of this class.
	 * @since  0.1.0	Initial	 
	 */
	public static function init() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	protected function __construct() {}

	/** 
	 * ************************************************************
	 * General helper methods 
	 * ************************************************************
	 */

	/**
	 * Get the ID of an object (contact, form, etc ) from a successfully
	 * created, updated, or retrieved request. 
	 * WARNING: If multiple objects passed, this returns the first ID found. 
	 * To return an array of all ID's in response, use get_ids_from_response()
	 * 
	 * @param  json $response JSON response from Ontraport
	 * @return int            ID of the object
	 * @author github.com/oakwoodgates 
	 * @since  0.3.0 Initial
	 */
	public static function get_id_from_response( $response ) {
		if( is_string( $response ) ) {
			$response = json_decode( $response );
		} elseif ( is_array( $response ) ) {
			$response = (object) $response;
		}
		$id = 0;
		if ( isset( $response->data->id ) ) {
			return (int) $response->data->id;
		} elseif ( isset( $response->data->attrs->id ) ) {
			return (int) $response->data->attrs->id;
		} elseif ( isset( $response->data->{'0'}->id ) ) {
			return (int) $response->data->{'0'}->id;
		} elseif ( isset( $response->id ) ) {
			return (int) $response->id;
		}
		return (int) $id;
	}

	/**
	 * Get the IDs of the objects (contact, form, etc ) from a successfully
	 * created, updated, or retrieved request.
	 * 
	 * @param  json $response JSON response from Ontraport
	 * @return array          IDs of the objects
	 * @author github.com/oakwoodgates 
	 * @since  0.3.2 Initial
	 */
	public static function get_ids_from_response( $response ) {
		if( is_string( $response ) ) {
			$response = json_decode( $response, true );
		} elseif ( is_object( $response ) ) {
			$response = (array) $response;
		}
		$ids = array();
		if ( isset( $response['data']['id'] ) ) {
			$ids[] = $response['data']['id'];
		} elseif ( isset( $response['data']['attrs']['id'] ) ) {
			$ids[] = $response['data']['attrs']['id'];
		} elseif ( isset( $response['data'][0]['id'] ) ) {
			foreach ( $response['data'] as $array ) {
				$ids[] = $array['id'];
			}
		} elseif ( isset( $response['id'] ) ) {
			$ids[] = $response['id'];
		// if response data was passed after get_data_from_response() 
		} elseif ( isset( $response['attrs']['id'] ) ) {
			$ids[] = $response['attrs']['id'];
		} elseif ( isset( $response[0]['id'] ) ) {
			foreach ( $response as $array ) {
				$ids[] = $array['id'];
			}
		} 
		return $ids;
	}

	/**
	 * Get the important stuff from a successfully created, updated, or retrieved request.
	 * 
	 * @param  json $response 	JSON response from Ontraport
	 * @param  bool $all 		Return first dataset (false) or all datasets (true)
	 * @param  bool $array 		To decode as object (false) or array (true)
	 * @return obj|arr   		Object or array (empty string if no valid response passed)
	 * @author github.com/oakwoodgates 
	 * @since  0.3.2 Initial
	 */
	public static function get_data_from_response( $response, $all = false, $array = false ) {
		if( is_string( $response ) ) {
			$response = json_decode( $response, $array );
	//	} elseif ( is_array( $response ) && ! $array ) {
	//		$response = (object) $response;
		} else {
			return 0;
		}
	
		if ( is_object( $response ) ) {
			if ( isset( $response->data->id ) ) {
				return $response->data;
			} elseif ( isset( $response->data->attrs->id ) ) {
				return $response->data->attrs;
			} elseif ( isset( $response->data->{'0'}->id ) ) {
				if ( $all ) {
					return $response->data;
				} else {
					return $response->data->{'0'};
				}
			} elseif ( isset( $response->id ) ) {
				return $response;
			} elseif ( isset( $response->data ) ) {
				if ( is_array( $response->data ) && isset( $response->data[0] ) ) {
					if ( $all ) {
						return $response->data;
					} else { 
					//	if ( ) {
						return $response->data[0];
					//	}
					}
				}
				return $response->data;
			}
		} else {
			if ( isset( $response['data']['id'] ) ) {
				return $response['data'];
			} elseif ( isset( $response['data']['attrs']['id'] ) ) {
				return $response['data']['attrs'];
			} elseif ( isset( $response['data'][0]['id'] ) ) {
				if ( $all ) {
					return $response['data'];
				} else {
					return $response['data'][0];
				}
			} elseif ( isset( $response['id'] ) ) {
				return $response;
			} elseif ( isset( $response['data'] ) ) {
				return $response['data'];
			}
		}

		return 0;
	}

	/**
	 * Get the important stuff from a successfully created, updated, or retrieved request.
	 * 
	 * @param  json $response 	JSON response from Ontraport
	 * @return obj|array 		Object or array (empty string if no valid response passed)
	 * @author github.com/oakwoodgates 
	 * @since  0.3.1 Initial
	 * @since  0.3.3 Depreciated - Use get_data_from_response()
	 */
	public static function get_object_from_response( $response ) {
		return get_data_from_response( $response );
		/*
		if( is_string( $response ) ) {
			$response = json_decode( $response );
		} elseif ( is_array( $response ) ) {
			$response = (object) $response;
		}
		$data = '';
		if ( isset( $response->data->id ) ) {
			$data = $response->data;
		} elseif ( isset( $response->data->attrs->id ) ) {
			$data = $response->data->attrs;
		} elseif ( isset( $response->data->{'0'}->id ) ) {
			$data = $response->data->{'0'};
		} elseif ( isset( $response->id ) ) {
			$data = $response;
		}
		return $data;
		*/
	}

	/**
	 * Prepare a simple A JSON encoded string to more specifically set criteria 
	 * for which contacts to bring back. For example, to check that a field 
	 * equals a certain value. See criteria examples for more details.
	 * 
	 * @param  string  $field      Field to search
	 * @param  string  $operand    Possible values: > < >= <= = IN
	 * @param  str|int $value      Value to compare
	 * @return string              String of data like "{field}{=}{value}"
	 * @link   https://api.ontraport.com/doc/#criteria Ontraport criteria docs
	 * @author github.com/oakwoodgates 
	 * @since  0.3.0 Initial
	 */
	public static function prepare_search_condition( $field, $operand = '=', $value ) {

		if ( is_numeric ( $value ) ) {
			$condition = "{$field}{$operand}{$value}";
		} else {
			$condition = "{$field}{$operand}'{$value}'";
		}

		return $condition;
	}

	/**
	 * See if contact has a tag
	 *
	 * @param  json $contact_data JSON response from WontrapiGo::get_contact();
	 * @param  int  $tag          Tag ID in Ontraport
	 * @return bool               true if contact has tag
	 * @author github.com/oakwoodgates 
	 * @since  0.3.1 Initial
	 */
	public static function contact_has_tag( $contact, $tag ) {
		$contact = self::get_object_from_response($contact);
		if ( isset( $contact->contact_cat ) ) {
			$contact_tags = $contact->contact_cat;
			if ( $contact_tags ) {
				$contact_tags = array_filter( explode( '*/*',$contact_tags ) );
				if ( in_array( $tag, $contact_tags ) ){
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Get objectID for type
	 * 
	 * @param  string  $type Type of object
	 * @return integer       Object's objectID
	 * @author github.com/oakwoodgates 
	 * @since  0.1.0
	 */
	public static function objectID( $type ) {
		// return a numeric ID straight away
		if ( is_numeric( $type ) )
			return $type;
		// let's not deal with strangeLetterCasing; lowercase ftw
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
			case 'invoices': // not an actual ontraport type, but when transactions are returned with WontrapiGo::get_object_meta( 'Transactions' ) they are referred to as "Invoice"
				$id = 46;
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
				$id = 0;
				break;
		}
		return $id;
	}

}
