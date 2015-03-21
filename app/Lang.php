<?php namespace Jvd;
use Locale;

/**
 * Author: Ross Kuyper <rosskuyper@gmail.com>
 */
class Lang
{
	/**
	 * The name of the current lang
	 * @var string
	 */
	protected $lang;

	/**
	 * The data associated with the current lang
	 * @var array|null
	 */
	protected $langData;

	/**
	 * Default if nothing is given
	 * @var string
	 */
	protected $defaultLang = 'en';

	/**
	 * Supported langs
	 * @var array
	 */
	protected $supported = ['en', 'fr'];

	/**
	 * The 'Accept-Language' header
	 * @var string
	 */
	protected $acceptLang;

	/**
	 * Use either a custom user agent string or default to the $_SERVER one
	 */
	public function __construct($acceptLang = null) {
		if ( is_null($acceptLang) ) {
			if ( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
				$this->acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}
		} else {
			$this->acceptLang = $acceptLang;
		}
	}

	/**
	 * Manually set the lang
	 */
	public function setLang($lang){
		if ($this->isSupported($lang))
			$this->lang = $lang;
	}

	/**
	 * Get the currnet lang
	 * @return string
	 */
	public function getLang(){
		return isset($this->lang) ? $this->lang : $this->detectLang();
	}

	/**
	 * Get the lang data
	 * @return array
	 */
	public function getLangData(){
		// Grab the lang data or throw an error
		$file = __DIR__ . '/Lang/'. $this->getLang() .'.php';

		if ( ! file_exists($file) )
			throw new \RuntimeError('Lang file not found: ' . $this->getLang());

		$this->langData = new LangData(include $file);

		return $this->langData;
	}

	/**
	 * Detect what lang should be used based on the current request
	 * @return void
	 */
	protected function detectLang(){
		$this->lang = null;

		if (! is_null($this->acceptLang) ) {
			$locale = Locale::acceptFromHttp($this->acceptLang);

			if ($locale) {
				$this->lang = Locale::getPrimaryLanguage($locale);
			}
		}

		if (is_null($this->lang) || ! $this->isSupported($this->lang))
			$this->lang = $this->default;

		return $this->lang;
	}

	/**
	 * Check if the given lang is supported
	 * @param  string  $lang
	 * @return boolean
	 */
	protected function isSupported($lang){
		return in_array($lang, $this->supported);
	}
}

class LangData {
	/**
	 * Content
	 * @var array
	 */
	protected $lang;

	/**
	 * Constructor
	 * @param Array $lang
	 */
	public function __construct($lang) {
		$this->lang = $lang;
	}

	/**
	 * Get a piece of content
	 * @param  string $key
	 * @return string
	 */
	public function get($key) {
		return isset($this->lang[$key]) ? $this->lang[$key] : '';
	}

	/**
	 * Take a key and split put it into paragraph tags.
	 * If the value is an array, it will be split over multiple
	 * If scalar, just one paragraph will be output.
	 * @return string
	 */
	public function paragraph($key) {
		if (! isset($this->lang[$key]) )
			return '';

		return '<p>' . ( is_array($this->lang[$key]) ? implode('</p><p>', $this->lang[$key]) : $this->lang[$key] ) . '</p>';
	}
}
