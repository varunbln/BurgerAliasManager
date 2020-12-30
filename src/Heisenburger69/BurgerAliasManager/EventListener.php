<?php

namespace Heisenburger69\BurgerAliasManager;

use pocketmine\event\Listener;

class EventListener implements Listener
{
    /**
     * @var Main
     */
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
}