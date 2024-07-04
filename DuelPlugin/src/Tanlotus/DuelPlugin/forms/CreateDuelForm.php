<?php

namespace Tanlotus\DuelPlugin\forms;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Tanlotus\DuelPlugin\duels\DuelsManager;
use Tanlotus\DuelPlugin\libs\Vecnavium\FormsUI\CustomForm;
use Tanlotus\DuelPlugin\libs\Vecnavium\FormsUI\SimpleForm;
use Tanlotus\DuelPlugin\Main;


class CreateDuelForm{
    public static function CreateDuelForm(Player $player){
        $form = new CustomForm(function (Player $player, ?array $data = null){
            if($data === null){
                return true;
            }

            switch ($data){
                case 0:
                    break;
                case 1:

                    break;
            }
            $player2Duel = $data[0];
            $choiceKit = $data[1];

            $player2 = Main::getInstance()->getServer()->getPlayerExact($player2Duel);
            if($player2 !== null){
                DuelsManager::class->sendDuelRequest($player->getName(), $player2Duel, $choiceKit);
                $player->sendMessage(TextFormat::GREEN . "A request of duel is send at $player2.");
                $player2->sendMessage(TextFormat::GREEN . "$player send you a duel request !!!");
            }else{
                $player->sendMessage(TextFormat::RED . "The player isn't online.");
            }
        });

        $form->setTitle("CREATE DUEL");
        $form->addInput("Player Name", "pseudo");
        $form->addDropdown("Choose Kit", array_keys(Main::getInstance()->configCache["duels-kits"]));

        $player->sendForm($form);
    }
}