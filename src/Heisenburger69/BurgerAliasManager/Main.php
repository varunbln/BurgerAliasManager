<?php

declare(strict_types=1);

namespace Heisenburger69\BurgerAliasManager;

use Heisenburger69\BurgerAliasManager\commands\CommandAliasCommand;
use Heisenburger69\BurgerAliasManager\session\SessionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    /**
     * @var Main
     */
    private static $instance;
    /**
     * @var Config
     */
    private $aliasData;

    /**
     * @return Main
     */
    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public function onEnable()
    {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->aliasData = new Config($this->getDataFolder() . "aliases.json", Config::JSON);
        $this->getServer()->getCommandMap()->register("burgeraliasmanager", new CommandAliasCommand($this, "commandalias", "Manage your personal command aliases!", null, ["cmdalias"]));
    }

    public function savePlayerAliasData(string $playerName, array $aliasData): void
    {
        $this->aliasData->set($playerName, $aliasData);
    }

    public function getPlayerAliasData(string $playerName): array
    {
        return $this->aliasData->get($playerName, []);
    }

    public function onDisable()
    {
        SessionManager::endAllSessions();
        $this->aliasData->save();
    }
}
