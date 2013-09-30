<?php
namespace Neoseeker\SteamAPI;
class SteamAPIDriverCached extends SteamAPIDriver {
	protected $cacheDir = './cache/';
	protected $cacheTime = 300;
	protected $enabled = true;
	public function __construct() {
		parent::__construct();
	}

	public function get_xml($resource = null) {
		$location = $this->get_xml_location();
		$cacheFile = $this->cacheDir.md5($location).'.dat';
		if($this->enabled && file_exists($cacheFile) && filemtime($cacheFile) > time()-$this->cacheTime){
			$content = file_get_contents($cacheFile);
		}
		else{
			$content = file_get_contents($location);
			file_put_contents($cacheFile, $content);
		}
		$xml_response = @simplexml_load_string($content, null, LIBXML_NOCDATA);
		if ($xml_response == false) {
			return false;
		}
		return $xml_response;
	}
}

?>