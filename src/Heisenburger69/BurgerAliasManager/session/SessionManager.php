<?php

namespace Heisenburger69\BurgerAliasManager\session;

use Heisenburger69\BurgerAliasManager\Main;
use pocketmine\Player;
use pocketmine\Server;
use function array_merge;
use function strtolower;

class SessionManager
{

    /** @var array */
    public static $sessions = [];

    /**
     * @param AliasPlayer $player
     */
    public static function startSession(AliasPlayer $player): void {
        self::$sessions = array_merge(self::$sessions, [strtolower($player->getPlayer()->getName()) => $player]);
    }

    /**
     * @return array
     */
    public static function getAllSessions(): array
    {
        return self::$sessions;
    }

    /**
     * @param string $playerName
     * @return AliasPlayer|null
     */
    public static function getSessionByName(string $playerName) {
        if(isset(self::$sessions[strtolower($playerName)])) {
            return self::$sessions[strtolower($playerName)];
        }
        return null;
    }

    /**
     * @param Player $player
     * @return AliasPlayer|null
     */
    public static function getSessionByPlayer(Player $player) {
        return self::getSessionByName($player->getName());
    }

    /**
     * @param Player $player
     * @return bool
     */
    public static function hasSession(Player $player) {
        return self::getSessionByPlayer($player) !== null;
    }

    public static function endAllSessions(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            self::endSession($player);
        }
    }

    /**
     * @param Player $player
     */
    public static function endSession(Player $player): void {
        if(self::hasSession($player)) {
            self::saveSession(self::getSessionByPlayer($player));
            unset(self::$sessions[strtolower($player->getName())]);
        }
    }

    /**
     * @param AliasPlayer $session
     */
    private static function saveSession(AliasPlayer $session)
    {
        Main::getInstance()->savePlayerAliasData($session->getName(), $session->getAllAliases());
    }
}