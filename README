# SteamAPI

This project is a PHP wrapper for the Steam Community XML API which is used for Neoseeker's Steam features. This project only grabs data from the XML, how it is managed is up to you.

# Contributing
Please note that you must have PHPUnit setup as this is a requirement for contributing to this project so that we can retain a bug-free project.

* If you make a modification please make sure you run the unit test and that it passes all tests before you push your commit(s) to github!
* If you create a new function please add a test(s) for it in the unit test and make sure your test(s) passes

Incompletion of the following steps will result in an unaccepted commit.

# Unit Test
For the SteamAPI we are using PHPUnit. All tests can be found in /tests.
While running the test we rely on a copy of the Steam Community API in a flat file .xml this ensures that you can unit test with consistant data and is not coupled with Steam's API; Using the live API would mean it requires an active internet connections, and may be slow.

## Offline Mode
In cases where your net connection is limited or non-existant you can test in offline mode; however, this is a bit limited based on what static XML files you have. The files used are in the /tests/xml/ directory.
To enable offline mode you can do the following:
$steamdriver->set_offline_mode(true);
$steamdriver->set_static_xml_path('PATH TO SteamAPI/tests/xml/'); //Make sure you modify this to YOUR path!

# Usage
$steamdriver = new steam\SteamAPIDriver;
$api = new steam\SteamAPI($steamdriver);
$api->load('STEAMID'); //You can use the users steamid or steamid64

Note: The SteamAPI has been modified to use Auto Loading with PdInject. If you have this set up you do not need to pass in $steamdriver when instantiating the SteamAPI.

## Getting Games
$games = $api->get_games();

## Get Achievements for a Game
$achievements = $api->get_achievements_for_game('portal'); //by game title

## Get Games w/ Achievements
$games = $api->get_games_and_achievements(); //note that this is an n+ function, thus will be slower to run depending on n (where n = the total number of games the user has)
