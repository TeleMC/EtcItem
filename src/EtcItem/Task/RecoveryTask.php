<?php

namespace EtcItem\Task;

use EtcItem\EtcItem;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class RecoveryTask extends Task {
    private $player;

    public function __construct(Player $player, EtcItem $owner, int $heal, int $count, string $type) {
        $this->plugin = $owner;
        $this->player = $player;
        $this->count = $count;
        $this->heal = $heal / $count;
        $this->type = $type;
    }

    public function onRun($currentTick) {
        $player = $this->player;
        if ($this->count > 0) {
            if ($this->player->isOnline()) {
                $this->plugin->save();
                $player->heal(new EntityRegainHealthEvent($player, $this->heal, 3));
                $this->count--;
                return;
            } else {
                $this->count--;
                $this->plugin->i->addHp($player->getName(), $this->heal);
            }
        } else {
            $this->plugin->getScheduler()->cancelTask($this->getTaskId());
            unset ($this->plugin->d [$this->player->getName()] ["재생 포션"]);
            if ($this->player->isOnline()) {
                $player->sendMessage($this->plugin->pre . $this->type . " 의 효능이 끝났습니다.");
            } else {
                return;
            }
        }
    }
}
