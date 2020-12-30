<?php

namespace Heisenburger69\BurgerAliasManager;

use Heisenburger69\BurgerAliasManager\session\AliasPlayer;
use Heisenburger69\BurgerAliasManager\session\SessionManager;
use Heisenburger69\BurgerAliasManager\utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use pocketmine\timings\Timings;

class EventListener implements Listener
{

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $session = new AliasPlayer($player, Main::getInstance()->getPlayerAliasData($player->getName()));
        SessionManager::startSession($session);
        Utils::refreshAvailableCommands($session);
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        SessionManager::endSession($player);
    }

    public function onChat(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();
        if(($session = SessionManager::getSessionByPlayer($player)) === null) return;
        if(strpos($event->getMessage(), "/") === 0){
            $ogCommand = substr($event->getMessage(), 1);
            if(($command = $session->getCommandByAlias($ogCommand)) === null) return;
            Timings::$playerCommandTimer->startTiming();
            Server::getInstance()->dispatchCommand($player, $command);
            Timings::$playerCommandTimer->stopTiming();
            $event->setCancelled(true);
        }
    }
}