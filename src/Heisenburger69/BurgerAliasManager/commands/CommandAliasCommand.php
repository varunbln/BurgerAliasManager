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
        if (!$sender instanceof Player) {
            $sender->sendMessage(self::PREFIX . C::RED . "This command must be used in-game!");
            return;
        }
        if (!$sender->hasPermission("burgeraliasmanager.use")) {
            $sender->sendMessage(self::PREFIX . C::RED . "You do not have permission to use this command!");
            return;
        }
        if (($session = SessionManager::getSessionByPlayer($sender)) === null) {
            $sender->sendMessage(self::PREFIX . C::DARK_RED . "Session not initialized. Please report to an admin.");
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage(C::RED . "Do /commandalias <add/remove/list>");
            return;
        }
        if($args[0] === "list") {
            $page = 1;
            $maxPages = $session->getMaxPages();
            if(isset($args[1])) {
                if(is_numeric($args[1]) && (int)$args[1] > 0) {
                    $page = (int)$args[1];
                }
            }
            $message = C::GREEN . "Showing created command aliases " . C::AQUA . "Page ($page/$maxPages):\n";
            $aliases = $session->getAliasList($page - 1);
            $i = 0;
            foreach ($aliases as $cmdAlias => $cmd) {
                $i++;
                $message .= C::AQUA . $i . ". " . C::YELLOW . "/$cmdAlias" . C::AQUA . " => " . C::YELLOW . "/$cmd" . "\n";
            }
            $sender->sendMessage(self::PREFIX . $message);
            return;
        }
        if($args[0] === "add") {
            if(!isset($args[1]) || !isset($args[2])) {
                $sender->sendMessage(C::RED . "Do /commandalias add <alias> <command>");
                return;
            }
            unset($args[0]);
            if ($session->getAliasCount() >= $this->plugin->getConfig()->get("max-aliases") && !$sender->hasPermission("burgeraliasmanager.op")) {
                $sender->sendMessage(C::RED . "You have already created the max amount of command aliases allowed on this server!");
                return;
            }
            $alias = array_shift($args);
            if($this->plugin->getServer()->getCommandMap()->getCommand((string)$alias) !== null) {
                $sender->sendMessage(C::RED . "You cannot create an alias that is the same as an existing command!");
                return;
            }
            $command = implode(" ", $args);
            if ($session->addAlias($command, $alias)) {
                $sender->sendMessage(C::GREEN . "Successfully registered a Command Alias called " . C::AQUA . "/$alias" . C::GREEN . " for " . C::AQUA . "/$command");
            } else {
                $sender->sendMessage(C::RED . "You already have this alias registered!");
            }
            return;
        }
        if($args[0] === "remove") {
            if(!isset($args[1])) {
                $sender->sendMessage(C::RED . "Do /commandalias remove <alias>");
                return;
            }
            $alias = $args[1];
            if ($session->removeAlias($alias)) {
                $sender->sendMessage(C::GREEN . "Successfully removed the Command Alias " . C::AQUA . "/$alias");
            } else {
                $sender->sendMessage(C::RED . "You do not have an alias by this name registered!");
            }
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}