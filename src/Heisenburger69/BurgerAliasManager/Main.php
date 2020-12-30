<?php

declare(strict_types=1);

namespace Heisenburger69\BurgerAliasManager;

use Heisenburger69\BurgerAliasManager\commands\AliasCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    /**
     * @var Config
     */
    private $aliasData;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->aliasData = new Config($this->getDataFolder() . "aliases.json", Config::JSON);
        $this->getServer()->getCommandMap()->register("burgeraliasmanager", new AliasCommand($this, "alias", "Manage your personal command aliases!"));
    }

    public function savePlayerAliasData(string $playerName, array $aliasData): void
    {
        $this->aliasData->set($playerName, $aliasData);
    }

    public function getPlayerAliasData(string $playerName): array
    {
        return $this->aliasData->get($playerName);
    }
}
