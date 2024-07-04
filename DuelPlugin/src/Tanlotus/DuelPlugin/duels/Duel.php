<?php

namespace Tanlotus\DuelPlugin\duels;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use pocketmine\world\World;
use Tanlotus\DuelPlugin\Main;

class Duel implements Listener {

    private Player $player1;
    private Player $player2;
    private $kit;
    private array $map;
    public function __construct(Player $player1, Player $player2, $kit, $map) {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->kit = $kit;
        $this->map = $map;



        foreach([$player1, $player2] as $player) {
            $player->sendPopup("3");
            $player->sendPopup("2");
            $player->sendPopup("1");

            $this->tpPlayer($this->player1, $this->player2);

            foreach([Main::getInstance()->configCache["duels-kits"][$this->kit]["armor"]["leggings"], Main::getInstance()->configCache["duels-kits"][$this->kit]["armor"]["helmet"], Main::getInstance()->configCache["duels-kits"][$this->kit]["armor"]["chesplate"], Main::getInstance()->configCache["duels-kits"][$this->kit]["armor"]["boots"]] as $item){
                $player->getArmorInventory()->addItem($item);
                if($item instanceof Item){
                    $item->addEnchantment(Main::getInstance()->configCache["duels-kits"][$this->kit]["armor-enchant"]);
                }

                $player->getInventory()->addItem(Main::getInstance()->configCache["duels-kits"][$this->kit]["item"]);
            }
        }
    }

    public function tpPlayer(Player $player1, Player $player2) {

        $cooPLayer1X = $this->map["players-spawn"]["1"]["x"];
        $cooPLayer1Y = $this->map["players-spawn"]["1"]["y"];
        $cooPLayer1Z = $this->map["players-spawn"]["1"]["z"];
        $cooPLayer2X = $this->map["players-spawn"]["1"]["x"];
        $cooPLayer2Y = $this->map["players-spawn"]["1"]["y"];
        $cooPLayer2Z = $this->map["players-spawn"]["1"]["z"];

        $this->copyDirectory("../worlds/$this->map", "./duel/");
        Main::getInstance()->getServer()->getWorldManager()->loadWorld("$this->map");

        $mapToTeleport = Main::getInstance()->getServer()->getWorldManager()->getWorldByName("$this->map");

        if($mapToTeleport instanceof World){
            $position1 = new Position($cooPLayer1X, $cooPLayer1Y, $cooPLayer1Z, $mapToTeleport);
            $position2 = new Position($cooPLayer2X, $cooPLayer2Y, $cooPLayer2Z, $mapToTeleport);

            $player1->teleport($position1);
            $player2->teleport($position2);
        }else{
            $player1->sendMessage(TextFormat::RED . "Une erreur est survenu lors de la création du duel.");
            $player2->sendMessage(TextFormat::RED . "Une erreur est survenu lors de la création du duel.");
        }
    }

    private function copyDirectory(string $source, string $destination): bool {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $directory = opendir($source);
        if ($directory === false) {
            return false;
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $srcFile = $source . DIRECTORY_SEPARATOR . $file;
            $destFile = $destination . DIRECTORY_SEPARATOR . $file;

            if (is_dir($srcFile)) {
                if (!$this->copyDirectory($srcFile, $destFile)) {
                    closedir($directory);
                    return false;
                }
            } else {
                if (!copy($srcFile, $destFile)) {
                    closedir($directory);
                    return false;
                }
            }
        }

        closedir($directory);
        return true;
    }


    public function onDamage(EntityDamageByEntityEvent $event) {
        $entity = $event->getEntity();

        if($entity instanceof Player) {
             $worldEntity = $entity->getWorld()->getFolderName();

             if($worldEntity == $this->map) {
                 $kb = Main::getInstance()->configCache["duels-kb"][$this->kit][1];
                 $verticalKbLimit = Main::getInstance()->configCache["duels-kb"][$this->kit][2];
                 $cooldown = Main::getInstance()->configCache["duels-kb"][$this->kit][3];

                 $event->setKnockBack($kb);
                 $event->setVerticalKnockBackLimit($verticalKbLimit);
                 $event->setAttackCooldown($cooldown);
             }
        }
    }
}