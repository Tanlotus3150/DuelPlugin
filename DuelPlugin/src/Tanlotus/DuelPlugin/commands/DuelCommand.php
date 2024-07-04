<?php

namespace Tanlotus\DuelPlugin\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Tanlotus\DuelPlugin\duels\DuelsManager;
use Tanlotus\DuelPlugin\forms\AcceptDuelForm;
use Tanlotus\DuelPlugin\forms\CreateDuelForm;
use Tanlotus\DuelPlugin\forms\DuelForm;
use Tanlotus\DuelPlugin\Main;

class DuelCommand extends Command{
    public function __construct(){
        parent::__construct("duel", "Duel Command", "/duel", ["d"]);
        $this->setPermission("duel.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$this->testPermission($sender)) {
            return false;
        }

        if ($sender instanceof Player) {
            if(!isset($args[0])){
                DuelForm::DuelForm($sender);
            }

            if($args[0] == "create"){
                if(isset($args[1])){
                    $player2 = Main::getInstance()->getServer()->getPlayerExact($args[1]);
                    $senderName = $sender->getName();
                    if($player2 !== null){
                        if(isset($args[2])){
                            DuelsManager::class->sendDuelRequest($sender->getName(), $player2->getName(), $args[2]);
                            $player2->sendMessage(TextFormat::GREEN . "$senderName send you a duel request !!!");
                        }else{
                            $sender->sendMessage(TextFormat::RED . "You don't choose a kit !");
                        }
                    }else{
                        $sender->sendMessage(TextFormat::RED . "$args[1] is not online or is not a player.");
                    }
                }else{
                    CreateDuelForm::CreateDuelForm($sender);
                }
            }

            if($args[0] == "accept"){
                if(isset($args[1])){
                    $senderDuel = $args[1];

                    if(DuelsManager::class->hasSentDuelRequest($senderDuel, $sender->getName()) === true){
                        DuelsManager::class->getAcceptDuel($sender->getName(), $senderDuel);
                    }else{
                        $sender->sendMessage(TextFormat::RED . "This player doesn't send you a duel request !");
                    }
                }else{
                    AcceptDuelForm::AcceptDuelForm($sender);
                }
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "You're not a player. Please Use this command in-game.");
        }
    }
}