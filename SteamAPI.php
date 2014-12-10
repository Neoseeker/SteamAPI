<?php
namespace Neoseeker\SteamAPI;
class SteamAPI {
	var $information;
	/** @var SteamAPIDriver $driver */
	var $driver;

	/**
	 * @PdInject steamapidriver
	 * @param steam\SteamAPIDriver $driver
	 */
	public function __construct($driver) {
		$this->driver = $driver;
	}

	public function load($steamid) {
		$this->driver->set_user($steamid);
		$this->information = $this->driver->get_xml_as_obj();
		return $this;
	}

	public function get_games() {
		$games = array();
		/** @var \stdClass $xml_object */
		$xml_object = $this->driver->get_games_xml_as_obj();
		if ($this->check_if_user_has_no_games($xml_object)) {
			$games['error'] = 'no games';
			return $games;
		}
		if ($xml_object == false && $this->check_if_users_profile_is_private()) {
			$games['error'] = 'private_user_profile';
			return $games;
		}
		if (isset($xml_object->games->game)) {
			$games = $this->create_games_array($xml_object->games->game);
		}
		return $games;
	}

	public function get_achievements_for_game($game) {
		$achievements = array();

		if (isset($game->statsLink)) {
			$game_title = preg_replace('#http.*/stats/(.*)#', '\\1', $game->statsLink);
			$achievements = $this->get_achievements_by_appid($game_title);
		}
		return $achievements;
	}

	public function get_achievements_by_appid($appid) {
		$achievements = array();
		$this->driver->set_stats_xml_url($appid);
		$xml_object = $this->driver->get_stats_xml_as_obj();

		if (isset($xml_object->achievements->achievement)) {
			$achievements = $this->create_achievements_array($xml_object->achievements->achievement);
		}
		return $achievements;
	}

	/**
	 * Please note that this can be a slow function as it is n+ where n is the number of
	 * games the user has which have achievements. Use this at your own discretion.
	 *
	 * @see $this->get_games();
	 * @see $this->get_achievements_for_game();
	 * @return array	$games
	 */
	public function get_games_and_achievements() {
		$games = $this->get_games();
		if (count($games) > 0) {
			foreach ($games as $game) {
				$game->achievements = $this->get_achievements_for_game($game);
			}
		}
		return $games;
	}

	private function create_games_array($games) {
		$games_array = array();
		if (is_array($games) && count($games) > 0) {
			foreach ($games as $game) {
				$games_array[$game->appID] = $game;
			}
		} elseif (is_object($games)) {
			if (isset($games->appID)) {
				$games_array[$games->appID] = $games;
			}
		}
		return $games_array;
	}

	private function create_achievements_array($achievements) {
		$achievements_array = array();
		if (count($achievements) > 0) {
			if (count($achievements) == 1) {
				$achievements = array($achievements);
			}
			foreach ($achievements as $achievement) {
				if (is_object($achievement)) {
					$achievements_array['list'][$achievement->apiname] = $achievement;
					if ($achievement->{'@attributes'}->closed == 1) {
						$achievements_array['completed'][] = $achievement->apiname;
					}
				}
			}
		}
		return $achievements_array;
	}

	private function check_if_users_profile_is_private() {
		$xml_location = $this->driver->get_games_xml_url();
		$headers = get_headers($xml_location);
		foreach ($headers as $header) {
			if (preg_match('/302/', $header)) {
				return true;
			}
		}
		return false;
	}

	private function check_if_user_has_no_games($xml_object) {
		if (isset($xml_object) && isset($xml_object->games) && !isset($xml_object->games->game)) {
			return true;
		}
		return false;
	}
}
?>