<?php
namespace Neoseeker\SteamAPI;

class SteamPrivateAPI {
	var $url = "http://api.steampowered.com/";

	public function get_player_summaries($steamid) {
		if (!is_numeric($steamid)) {
			return null;
		}
//		$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".STEAM_API_KEY."&steamids=".$steamid;
		$api = new \HttpRequest($this->url.'ISteamUser/GetPlayerSummaries/v0002/', \HttpRequest::METH_GET);
		$api->addQueryData(array(   'key'   =>  STEAM_API_KEY,
									'steamids'  =>  $steamid,
		));
		try {
			$api->send();
			if ($api->getResponseCode() == 200) {
				return $api->getResponseBody();
			}
		} catch (\HttpException $ex) {
			echo $ex;
		}

	}

	public function get_player_achievements($appid, $steamid) {
		if (!is_numeric($appid) || !is_numeric($steamid)) {
			return null;
		}
//		$url = "http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=".$appid."&key=".STEAM_API_KEY."&steamid=".$steamid."&l=1";
		$api = new \HttpRequest($this->url.'ISteamUserStats/GetPlayerAchievements/v0001/', \HttpRequest::METH_GET );
		$api->addQueryData(array(       'appid'     =>  $appid, 
										'key'       =>  STEAM_API_KEY,
										'steamid'   =>  $steamid,
										'l'         =>  1
		));
		try {
			$api->send();
			if ($api->getResponseCode() == 200) {
				return $api->getResponseBody();
			}
		} catch (\HttpException $ex) {
			echo $ex;
		}
	}

}

?>