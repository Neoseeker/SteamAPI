<?php
namespace steam;
class SteamAPIDriver {
	private $_community_url;
	private $_profile_path;
	private $_api_format;
	private $xml_location;
	private $in_offline_mode = false;
	private $static_xml_path = "/tests/xml/";

	private $users_xml_url;
	private $games_xml_url;
	private $stats_xml_url;

	public function __construct() {
		$this->_community_url = "http://steamcommunity.com/";
		$this->_api_format = "?xml=1";
	}

	public function set_user($steamid) {
		$this->set_users_xml_url($steamid);
		$this->set_xml_location($this->get_users_xml_url());
		$this->set_games_xml_url();
	}

	public function set_offline_mode($offline_mode = false) {
		$this->in_offline_mode = (is_bool($offline_mode)) ? $offline_mode : false;
	}

	/**
	 * SimpleXMLElement is a resource, not an object so we'll use a
	 * hack to make it into a useable object by encoding and decoding
	 * it to and from JSON.
	 *
	 * @return	bool|\stdClass
	 */
	public function get_xml_as_obj() {
		$xml_response = $this->get_xml();
		$xml_object = $this->convert_to_object($xml_response);
		return $xml_object;
	}

	public function get_xml($resource = null) {
		try {
			$xml_response = simplexml_load_file($this->get_xml_location(), null, LIBXML_NOCDATA);
			$this->unset_location();
			return $xml_response;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function get_games_xml_as_obj() {
		$this->set_xml_location($this->get_games_xml_url());
		return $this->get_xml_as_obj();
	}

	public function get_stats_xml_as_obj() {
		$this->set_xml_location($this->get_stats_xml_url());
		return $this->get_xml_as_obj();
	}

	public function set_users_xml_url($steamid) {
		$this->_profile_path = (is_numeric($steamid)) ? "profiles/$steamid/" : "id/$steamid/";
		$this->users_xml_url = (!$this->in_offline_mode) ? $this->_community_url.$this->_profile_path.$this->_api_format : $this->static_xml_path.'tekmosis.xml';
	}

	public function get_users_xml_url() {
		return $this->users_xml_url;
	}

	public function set_games_xml_url() {
		$this->games_xml_url = (!$this->in_offline_mode) ? $this->_community_url.$this->_profile_path.'games'.$this->_api_format : $this->static_xml_path.'games.xml';
	}

	public function get_games_xml_url() {
		return $this->games_xml_url;
	}

	public function set_stats_xml_url($game_title) {
		$this->stats_xml_url = (!$this->in_offline_mode) ? $this->_community_url.$this->_profile_path.'stats/'.$game_title.$this->_api_format : $this->static_xml_path.'stats.xml';
	}

	public function get_stats_xml_url() {
		return $this->stats_xml_url;
	}

	public function get_xml_location() {
		return $this->xml_location;
	}

	public function set_xml_location($location) {
		$this->xml_location = $location;
	}

	public function set_static_xml_path($static_xml_path) {
		$this->static_xml_path = $static_xml_path;
	}

	private function convert_to_object($simplexml_object) {
		return json_decode(json_encode($simplexml_object));
	}

	private function unset_location() {
		unset($this->xml_location);
	}
}
?>