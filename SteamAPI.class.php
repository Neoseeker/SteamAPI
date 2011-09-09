<?php
namespace steam;
class SteamAPI {
	var $information;
	/** @var SteamAPIDriver $driver */
	var $driver;

	/**
	 * @PdInject steamapidriver
	 * @param SteamAPIDriver $driver
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
		if (isset($xml_object->games->game)) {
			$games = $this->create_games_array($xml_object->games->game);
		}
		return $games;
	}

	public function get_achievements_for_game($game_title = false, $stats_url = false) {
		$achievements = array();

		$this->driver->set_stats_xml_url($game_title);
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
				if (isset($game->statsLink)) {
					$game->achievements = $this->get_achievements_for_game(false, $game->statsLink);
				}
			}
		}
		return $games;
	}

	private function create_games_array($games) {
		$games_array = array();
		if (count($games) > 0) {
			foreach ($games as $game) {
				$games_array[$game->appID] = $game;
			}
		}
		return $games_array;
	}

	private function create_achievements_array($achievements) {
		$achievements_array = array();
		if (count($achievements) > 0) {
			foreach ($achievements as $achievement) {
				$achievements_array['list'][$achievement->apiname] = $achievement;
				if ($achievement->{'@attributes'}->closed == 1) {
					$achievements_array['completed'][] = $achievement->apiname;
				}
			}
		}
		return $achievements_array;
	}
}
?>