<?php

namespace Tanlotus\DuelPlugin;

use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Tanlotus\DuelPlugin\commands\DuelCommand;

class Main extends PluginBase{
    use SingletonTrait;

    public $configCache;

    public function onEnable(): void{
        $this::setInstance($this);
        $this->getLogger()->info("Duel plugin activated");

        $this->getServer()->getCommandMap()->registerAll("commands", [new DuelCommand()]);

        $this->configCache = $this->getConfig()->getAll();


    }
}