<?php

namespace Tanlotus\DuelPlugin\forms;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Tanlotus\DuelPlugin\duels\DuelsManager;
use Tanlotus\DuelPlugin\libs\Vecnavium\FormsUI\CustomForm;
use Tanlotus\DuelPlugin\Main;

class AcceptDuelForm{
    public static function AcceptDuelForm(Player $player){
        $form = new CustomForm(function (Player $player, ?array $data = null){
            if($data === null){
                return true;
            }

            switch ($data){
                case 0:

                    break;
            }

            $senderDuel = $data[0];

            $senderDuelPLayer = Main::getInstance()->getServer()->getPlayerExact($senderDuel);
            if($senderDuelPLayer !== null){
                if(DuelsManager::class->hasSentDuelRequest($senderDuelPLayer->getName(), $player->getName()) === true){
                    DuelsManager::class->getAcceptDuel($player->getName(), $senderDuel);
                }else{
                    $player->sendMessage(TextFormat::RED . "This player hasn't sent you any duel invitations.");
                }
            }else{
                $player->sendMessage(TextFormat::RED . "The player isn't online");
            }
        });

        $form->setTitle("ACCEPT DUEL");
        $form->addInput("Player Name", "pseudo");

        $player->sendForm($form);
    }
}