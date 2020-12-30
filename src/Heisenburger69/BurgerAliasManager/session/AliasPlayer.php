<?php

namespace Heisenburger69\BurgerAliasManager\session;

use pocketmine\command\Command;
use pocketmine\Player;

class AliasPlayer
{
    /**
     * @var Player
     */
    private $player;
    /**
     * @var array
     */
    private $aliases;

    public function __construct(Player $player, array $aliases)
    {
        $this->player = $player;
        $this->aliases = $aliases;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return array
     */
    public function getAllAlias(): array
    {
        return $this->aliases;
    }

    /**
     * Creates a new command alias for the player.
     * Returns false is the alias is already in use
     *
     * @param string $commandLine
     * @param string $alias
     * @return bool
     */
    public function addAlias(string $commandLine, string $alias): bool
    {
        if(isset($this->aliases[$alias])) return false;
        $this->aliases[$alias] = $commandLine;
        return true;
    }

    /**
     * Removes a Command Alias from the player.
     * Returns true if alias exists and false if it doesn't.
     *
     * @param string $alias
     * @return bool
     */
    public function removeAlias(string $alias): bool
    {
        if(!isset($this->aliases[$alias])) return false;
        unset($this->aliases[$alias]);
        return true;
    }

    /**
     * Lists command aliases by page
     * Used to make viewing command aliases simpler in-game
     *
     * @param int $page
     * @param int $countPerPage
     * @return array
     */
    public function getAliasList(int $page = 0, int $countPerPage = 10): array
    {
        return array_slice($this->aliases, $page * $countPerPage, 10);
    }
}