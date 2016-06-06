<?php
namespace Neoseeker\SteamAPI;

use Curl\Curl;

class SteamPrivateAPI {
	var $url = "http://api.steampowered.com/";

	private function request($url, $data) {
		$curl = new Curl();
		$curl->get($url, $data);
		$response = $curl->response;
		$http_response_header = $curl->httpStatusCode;
		$curl->close();
		if ($http_response_header == 200) {
			return $response;
		}
		return null;
	}

	public function get_player_summaries($steamid) {
		if (!is_numeric($steamid)) {
			return null;
		}
		return $this->request($this->url."ISteamUser/GetPlayerSummaries/v0002/", array(
			'key'		=> STEAM_API_KEY,
			'steamids'	=> $steamid,
		));
	}

	public function get_player_achievements($appid, $steamid) {
		if (!is_numeric($appid) || !is_numeric($steamid)) {
			return null;
		}
		return $this->request($this->url."ISteamUserStats/GetPlayerAchievements/v0001/", array(
			'appid'		=> $appid,
			'key'		=> STEAM_API_KEY,
			'steamid'	=> $steamid,
			'l'			=> 1,
		));
	}

	public function get_schema_for_game($appid) {
		if (is_numeric($appid)) {
			return $this->request($this->url."ISteamUserStats/GetSchemaForGame/v2/", array(
				'appid'		=> $appid,
				'key'		=> STEAM_API_KEY,
			));
		}
		return null;
	}

	public function get_owned_games($steamid) {
		if (is_numeric($steamid)) {
			return $this->request($this->url."IPlayerService/GetOwnedGames/v0001/", array(
				'steamid'			=> $steamid,
				'key'				=> STEAM_API_KEY,
				'include_appinfo'	=> 1,
			));
		}
		return null;
	}

}

?>