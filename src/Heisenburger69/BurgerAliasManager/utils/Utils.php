<?php

namespace Heisenburger69\BurgerAliasManager\utils;

use Heisenburger69\BurgerAliasManager\session\AliasPlayer;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\Server;

class Utils
{
    public static function refreshAvailableCommands(AliasPlayer $session): void
    {
        $pk = new AvailableCommandsPacket();
        foreach (Server::getInstance()->getCommandMap()->getCommands() as $name => $command) {
            if (isset($pk->commandData[$command->getName()]) or $command->getName() === "help" or !$command->testPermissionSilent($session->getPlayer())) {
                continue;
            }

            $data = new CommandData();
            $data->commandName = strtolower($command->getName());
            $data->commandDescription = Server::getInstance()->getLanguage()->translateString($command->getDescription());
            $data->flags = 0;
            $data->permission = 0;

            $parameter = new CommandParameter();
            $parameter->paramName = "args";
            $parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
            $parameter->isOptional = true;
            $data->overloads[0][0] = $parameter;

            $aliases = $command->getAliases();
            if (count($aliases) > 0) {
                if (!in_array($data->commandName, $aliases, true)) {
                    $aliases[] = $data->commandName;
                }
                $data->aliases = new CommandEnum();
                $data->aliases->enumName = ucfirst($command->getName()) . "Aliases";
                $data->aliases->enumValues = array_values($aliases);
            }

            $pk->commandData[$command->getName()] = $data;
        }
        foreach ($session->getAllAliases() as $alias => $commandLine) {
            if (isset($pk->commandData[$alias])) {
                continue;
            }

            $data = new CommandData();
            $data->commandName = strtolower($alias);
            $data->commandDescription = "";
            $data->flags = 0;
            $data->permission = 0;

            $parameter = new CommandParameter();
            $parameter->paramName = "args";
            $parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
            $parameter->isOptional = true;
            $data->overloads[0][0] = $parameter;

            $pk->commandData[$alias] = $data;
        }

        $session->getPlayer()->dataPacket($pk);
    }
}