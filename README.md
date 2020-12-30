# BurgerAliasManager
 Allow players to register their own custom command aliases!

## Description
With this plugin players can create their own command shortcuts or aliases.
For example, if a player frequently runs a command called /sell all, and the
player wanted to shorten it to /sa, he could do so for himself. A different player
could keep his shortcut as /s instead of /sa. In this way, it allows multiple
players to create their own command shortcuts thereby enhancing the overall
gameplay experience from a QOL point of view.

## Features
- Per-Player command aliases so each player can have different command shortcuts.
- Supports command arguments, so /tp 100 100 100 can be shortened to something like /tp100.
- Players can create their own command aliases however they want.
- All the aliases can be easily viewed using /commandalias list.
- Pagination in the command alias list for easier viewing.
- Command aliases can be easily removed if the player wants to.
- Server admins can set a maximum number of aliases each player can create.
- Built-in safety checks to prevent players from registering existing commands as aliases.

## Commands
- /commandalias list {page}: View all the command aliases you created
- /commandalias create {alias} {command}: Create a command alias called {alias} for the {command} Command
- /commandalias remove {alias}: Remove the command alias called {alias}

## Permissions
- burgeraliasmanager.use: Allows a player to register their own command aliases. Given to all players by default.
- burgeraliasmanager.op: Allows a player to bypass the max alias limit. Given only to OP's by default.

## Future Updates:
- [ ] Support for alias descriptions and argument autofilling(Someone pls PR this sounds like a pain to implement)  
- [ ] Maybe support setting server-wide command alia to make this a more "All in One" plugin  
Feel free to open an issue and suggest more :D
