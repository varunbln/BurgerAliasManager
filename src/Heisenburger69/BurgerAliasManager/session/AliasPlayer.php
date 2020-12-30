<?php

namespace Heisenburger69\BurgerAliasManager\session;

use Heisenburger69\BurgerAliasManager\utils\Utils;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->player->getName();
    }

    /**
     * Returns all the command aliases created by the user.
     *
     * @return array
     */
    public function getAllAliases(): array
    {
        return $this->aliases;
    }

    /**
     * Returns the number of command aliases created by the user.
     *
     * @return int
     */
    public function getAliasCount(): int
    {
        return count($this->aliases);
    }

    /**
     * Gets the actual command set by the user for the given alias
     * Returns null if alias wasn't set to a command.
     *
     * @param string $alias
     * @return string|null
     */
    public function getCommandByAlias(string $alias): ?string
    {
        if(isset($this->aliases[$alias])) return $this->aliases[$alias];
        return null;
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
        Utils::refreshAvailableCommands($this);
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
        Utils::refreshAvailableCommands($this);
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

    /**
     * Returns the max number of pages that can be populated based on the
     * command aliases created by the player
     *
     * @return int
     */
    public function getMaxPages(): int
    {
        return (floor(count($this->aliases)/ 10) + 1);
    }
}