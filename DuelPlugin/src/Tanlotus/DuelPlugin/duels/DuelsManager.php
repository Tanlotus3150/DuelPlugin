<?php

namespace Tanlotus\DuelPlugin\duels;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Tanlotus\DuelPlugin\Main;

class DuelsManager {

    public array $playerInDuel;
    private array $duelRequests;

    public function sendDuelRequest(string $senderDuel, string $receiverPlayer, string $kit): void{
        if (!isset($this->duelRequests[$receiverPlayer])) {
            $this->duelRequests[$receiverPlayer] = [];
        }
        $this->duelRequests[$receiverPlayer][$senderDuel] = $kit;
    }
    public function hasSentDuelRequest(string $senderDuel, string $receiverPlayer): bool {
        if (isset($this->duelRequests[$receiverPlayer]) && in_array($senderDuel, $this->duelRequests[$receiverPlayer])) {
            return true;
        } else {
            return false;
        }
    }

    public function removeDuelRequest(string $senderDuelPlayer, string $reiceverPlayer): void{
        if (isset($this->duelRequests[$reiceverPlayer]) && array_key_exists($senderDuelPlayer, $this->duelRequests[$reiceverPlayer])) {
            unset($this->duelRequests[$reiceverPlayer][$senderDuelPlayer]);

            if (empty($this->duelRequests[$reiceverPlayer])) {
                unset($this->duelRequests[$reiceverPlayer]);
            }
        }
    }

    public function getKit(string $senderDuelPlayer, string $reiceverPlayer): string {
        if ($this->hasSentDuelRequest($senderDuelPlayer, $reiceverPlayer)) {
            return $this->duelRequests[$reiceverPlayer][$senderDuelPlayer];
        }
    }


    public function getAcceptDuel(string $reiceverName, string $senderDuelName): void {
        $reicever = Main::getInstance()->getServer()->getPlayerExact($reiceverName);
        $senderDuel = Main::getInstance()->getServer()->getPlayerExact($senderDuelName);

        $kit = $this->getKit($senderDuelName, $reiceverName);

        if($this->hasSentDuelRequest($senderDuelName, $reiceverName) !== false){
            if($reicever == null || $senderDuel == null){
                if(in_array($reiceverName, $this->playerInDuel) !== false || in_array($senderDuelName, $this->playerInDuel) !== false){
                    array_push($this->playerInDuel, $reiceverName, $senderDuelName);
                    $this->removeDuelRequest($senderDuelName, $reiceverName);
                    new Duel($reicever, $senderDuel, $this->getChoiceKit($kit), $this->getRandomMap());
                }else{
                    $reicever->sendMessage(TextFormat::RED . "One of the two players is already in a duel.");
                }
            }else{
                $reicever->sendMessage(TextFormat::RED . "One of the two players is not online");
            }
        }else{
            $reicever->sendMessage(TextFormat::RED . "This player has not sent you a duel invitation.");
        }
    }


    public function getRandomMap(){
        $maps = Main::getInstance()->configCache["duels-maps"];
        return $maps[array_rand($maps)];
    }

    public function getChoiceKit(string $kit){
        $kitChoice = Main::getInstance()->configCache["duels-kits"];
        return $kitChoice[$kit];
    }
}