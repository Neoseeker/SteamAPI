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
				return json_decode($api->getResponseBody());
			}
		} catch (\HttpException $ex) {
			echo $ex;
		}
		return null;
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
				return json_decode($api->getResponseBody());
			}
		} catch (\HttpException $ex) {
			echo $ex;
		}
		return null;
	}

	public function get_schema_for_game($appid) {
		if (is_numeric($appid)) {
			$api = new \HttpRequest($this->url."ISteamUserStats/GetSchemaForGame/v2/", \HttpRequest::METH_GET);
			$api->addQueryData(array(
				'appid'		=> $appid,
				'key'		=> STEAM_API_KEY,
			));
			try {
				$api->send();
				if ($api->getResponseCode() == 200) {
					return json_decode($api->getResponseBody());
				}
			} catch (\HttpException $ex) {
				echo $ex;
			}
		}
		return null;
	}

	public function get_owned_games($steamid) {
		if (is_numeric($steamid)) {
			$api = new \HttpRequest($this->url."IPlayerService/GetOwnedGames/v0001/", \HttpRequest::METH_GET);
			$api->addQueryData(array(
				'steamid'					=> $steamid,
				'key'						=> STEAM_API_KEY,
				'include_appinfo'			=> 1,
//				'include_played_free_games'	=> 1,
			));
			try {
				$api->send();
				if ($api->getResponseCode() == 200) {
					return json_decode($api->getResponseBody());
				}
			} catch(\HttpException $ex) {
				echo $ex;
			}
		}
		return null;
	}

}

?>