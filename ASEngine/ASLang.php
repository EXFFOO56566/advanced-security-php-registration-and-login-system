<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net/as
 */

/**
 * Class ASLang
 */
class ASLang {

    /**
     * Get whole language file with all terms.
     * @param bool $jsonEncode Determine should data be encoded in json or not
     * @return mixed|string Array or JSON that contains whole language file of current language.
     */
    public static function all($jsonEncode = true) {
        // determine lanuage
		$language = self::getLanguage();

        // get translation for determined language
		$trans = self::getTrans($language);
		
		if ( $jsonEncode )
			return json_encode($trans);
		else
			return $trans;
	}

    /**
     * Get translation for specific term represented by $key param
     * @param $key Term
     * @param array $bindings If term value contains some variables (:name, :username or similar)
     * this array should contain values that those variables should be replaced with.
     * @return mixed|string
     */
    public static function get($key, $bindings = array() ) {
		// determine language
		$language = self::getLanguage();

		// get translation array
		$trans = self::getTrans($language);

        // if term (key) doesn't exist, return empty string
		if ( ! isset ( $trans[$key] ) )
			return '';

        // term exist, get the value
		$value = $trans[$key];

        // replace variables with provided bindings
		if ( ! empty($bindings) ) {
			foreach ( $bindings as $key => $val )
				$value = str_replace('{'.$key.'}', $val, $value);
		}

		return $value;
	}

    /**
     * Set script language
     * @param $language Language that should be set
     */
    public static function setLanguage($language) {

        // check if language is valid
		if ( self::isValidLanguage($language) ) {
			//set language cookie to 1 year
			setcookie('as_lang', $language, time() * 60 * 60 * 24 * 365, '/');

            // update session
			ASSession::set('as_lang', $language);

            //refresh the page
			header('Location: ' . $_SERVER['PHP_SELF']);
		}
		
	}

    /**
     * Get current language
     * @return mixed String abbreviation of current language
     */
    public static function getLanguage() {
        // check if cookie exist and language value in cookie is valid
        if ( isset ( $_COOKIE['as_lang'] ) && self::isValidLanguage ( $_COOKIE['as_lang'] ) )
            return $_COOKIE['as_lang']; // return lang from cookie
        else
            return ASSession::get('as_lang', DEFAULT_LANGUAGE);
    }

    /**
     * Get translation array for provided language
     * @param $language Language to get translation array for
     * @return mixed Translation array.
     */
    private static function getTrans($language) {
		$file = self::getFile($language);

		if ( ! self::isValidLanguage($language) )
			die('Language file doesn\'t exist!');
		else {
			$language = include $file;
			return $language;
		}
	}

    /**
     * Get language file path from lang directory
     * @param $language
     * @return string
     */
    private static function getFile($language) {
		return dirname(__DIR__) . '/Lang/' . $language . '.php';
	}

    /**
     * Check if lanuage is valid (if file for givena language exist in Lang folder)
     * @param $lang Language to validate
     * @return bool TRUE if language file exist, FALSE otherwise
     */
    private static function isValidLanguage($lang) {
		$file = self::getFile($lang);

		if ( ! file_exists( $file ) )
			return false;
		else
			return true;
	}

}