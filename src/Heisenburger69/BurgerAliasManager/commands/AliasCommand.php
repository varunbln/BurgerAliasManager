<?php

namespace Heisenburger69\BurgerAliasManager\commands;

use Heisenburger69\BurgerAliasManager\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class AliasCommand extends Command implements PluginIdentifiableCommand
{

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

    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}