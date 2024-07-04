<?php

namespace Tanlotus\DuelPlugin\forms;

use pocketmine\player\Player;
use Tanlotus\DuelPlugin\libs\Vecnavium\FormsUI\SimpleForm;

class DuelForm{
    public static function DuelForm(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if($data === null){
                return true;
            }

            switch ($data){
                case 0:
                    CreateDuelForm::CreateDuelForm($player);
                    break;
                case 1:
                    AcceptDuelForm::AcceptDuelForm($player);
                    break;
            }
        });

        $form->setTitle("DUEL");
        $form->addButton("Create Duel");//data = 0
        $form->addButton("Accept Duel");//data = 1

        $player->sendForm($form);
    }
}