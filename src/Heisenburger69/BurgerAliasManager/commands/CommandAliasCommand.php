<?php

namespace Heisenburger69\BurgerAliasManager\commands;

use Heisenburger69\BurgerAliasManager\Main;
use Heisenburger69\BurgerAliasManager\session\SessionManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat as C;

class CommandAliasCommand extends Command implements PluginIdentifiableCommand
{

    const PREFIX = C::AQUA . C::BOLD . "Burger" . C::LIGHT_PURPLE . "AliasManager>" . C::RESET;
    /**
     * @var Main
     */
    private $plugin;

    public function __construct(Main $plugin, string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player) {
            $sender->sendMessage(self::PREFIX . C::RED . "This command must be used in-game!");
            return;
        }
        if(!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage(self::PREFIX. C::RED . "Do /commandalias <alias> <command>");
            return;
        }
        if(($session = SessionManager::getSessionByPlayer($sender)) === null) {
            $sender->sendMessage(self::PREFIX.C::DARK_RED . "Session not initialized. Please report to an admin.");
            return;
        }
        $alias = array_shift($args);
        $command = implode(" ", $args);
        if($session->addAlias($command, $alias)) {
            $sender->sendMessage(self::PREFIX . C::GREEN . "Successfully registered a Command Alias called " . C::AQUA . "/$alias" . C::GREEN . " for " . C::AQUA . "/$command");
        } else {
            $sender->sendMessage(self::PREFIX . C::RED . "You already have this alias registered!");
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}