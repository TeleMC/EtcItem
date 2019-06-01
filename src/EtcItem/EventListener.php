<?php

namespace EtcItem;

use Core\Core;
use Core\util\Util;
use EtcItem\Task\RecoveryTask;
use pocketmine\entity\Attribute;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class EventListener implements Listener {

    public function __construct(EtcItem $plugin) {
        $this->plugin = $plugin;
        $this->core = Core::getInstance();
        $this->i = new Util ($this->core);
    }

    public function onJoin(PlayerJoinEvent $e) {
        $player = $e->getPlayer();
        if (!isset ($this->plugin->cdata [$player->getName()])) {
            $this->plugin->cdata [$player->getName()] ["신속의 깃털 I"] = 0;
            $this->plugin->cdata [$player->getName()] ["신속의 깃털 II"] = 0;
            $this->plugin->cdata [$player->getName()] ["힘의 샘물 I"] = 0;
            $this->plugin->cdata [$player->getName()] ["힘의 샘물 II"] = 0;
            $this->plugin->cdata [$player->getName()] ["강화의 호신부 I"] = 0;
            $this->plugin->cdata [$player->getName()] ["강화의 호신부 II"] = 0;
        }
    }

    public function onQuit(PlayerQuitEvent $ev) {
        $player = $ev->getPlayer();
        unset ($this->plugin->d [$player->getName()] ["신속의 깃털 I"]);
        unset ($this->plugin->d [$player->getName()] ["신속의 깃털 II"]);
    }

    public function onDeath(PlayerDeathEvent $ev) {
        $player = $ev->getPlayer();
        unset ($this->plugin->d [$player->getName()] ["신속의 깃털 I"]);
        unset ($this->plugin->d [$player->getName()] ["신속의 깃털 II"]);
    }

    public function onHand(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $item = $event->getItem();
        // 체력 회복 물약
        if ($item->getId() == 262 && $item->getDamage() == 8 && $item->getCustomName() == "§r§c회복 물약 I") {
            $player->sendMessage($this->plugin->pre . "HP (을)를 100 만큼 회복하였습니다!");
            $player->heal(new EntityRegainHealthEvent($player, 100, 3));
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 9 && $item->getCustomName() == "§r§c회복 물약 II") {
            $player->sendMessage($this->plugin->pre . "HP (을)를 500 만큼 회복하였습니다!");
            $player->heal(new EntityRegainHealthEvent($player, 500, 3));
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 6 && $item->getCustomName() == "§r§c회복 물약 III") {
            $player->sendMessage($this->plugin->pre . "HP (을)를 1000 만큼 회복하였습니다!");
            $player->heal(new EntityRegainHealthEvent($player, 1000, 3));
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 7 && $item->getCustomName() == "§r§c회복 물약 IV") {
            $player->sendMessage($this->plugin->pre . "HP (을)를 2500 만큼 회복하였습니다!");
            $player->heal(new EntityRegainHealthEvent($player, 2500, 3));
            $this->plugin->removeItem($player, $item, 1);
            return;
        }

        // 마나 회복 물약
        if ($item->getId() == 262 && $item->getDamage() == 10 && $item->getCustomName() == "§r§b마력 회복제 I") {
            $player->sendMessage($this->plugin->pre . "MP (을)를 50 만큼 회복하였습니다!");
            $this->i->addMp($player->getName(), 50);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 11 && $item->getCustomName() == "§r§b마력 회복제 II") {
            $player->sendMessage($this->plugin->pre . "MP (을)를 150 만큼 회복하였습니다!");
            $this->i->addMp($player->getName(), 150);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 12 && $item->getCustomName() == "§r§b마력 회복제 III") {
            $player->sendMessage($this->plugin->pre . "MP (을)를 500 만큼 회복하였습니다!");
            $this->i->addMp($player->getName(), 500);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 12 && $item->getCustomName() == "§r§b마력 회복제 IV") {
            $player->sendMessage($this->plugin->pre . "MP (을)를 1500 만큼 회복하였습니다!");
            $this->i->addMp($player->getName(), 1500);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 24 && $item->getCustomName() == "§r§d§l공간이동 주문서 - 켄텀브리엄") {
            if (!$this->plugin->warp->Warp($player, "켄텀브리엄")) {
                $player->sendMessage($this->plugin->HotbarSystem->pre . " 운영 시스템에 의해 워프할 수 없습니다.");
                return;
            } elseif ($this->plugin->warp->Warp($player, "켄텀브리엄")) {
                $player->sendMessage($this->plugin->pre . "켄텀브리엄 (으)로 워프하였습니다.");
                $this->plugin->removeItem($player, $item, 1);
            }
        }
        if ($item->getId() == 262 && $item->getDamage() == 22 && $item->getCustomName() == "§r§l§f신속의 깃털 I") {
            if (isset ($this->plugin->d [$player->getName()] ["신속의 깃털 I"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["신속의 깃털 I"] = "on";
            $this->plugin->cdata [$player->getName()] ["신속의 깃털 I"]++;
            $this->plugin->save();
            $attribute = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->getValue();
            $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->setValue($attribute * 115 / 100);
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player, $this->plugin->cdata [$player->getName()] ["신속의 깃털 I"]) extends Task {
                        public function __construct(EtcItem $plugin, Player $player, int $count) {
                            $this->plugin = $plugin;
                            $this->player = $player;
                            $this->count = $count;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["신속의 깃털 I"])) return;
                            if ($this->count !== $this->plugin->cdata [$this->player->getName()] ["신속의 깃털 I"]) return;
                            if ($this->player->isOnline()) {
                                $attribute = $this->player->getAttributeMap()->getAttribute( /*Attribute::MOVEMENT_SPEED*/ 5)->getValue();
                                $this->player->getAttributeMap()->getAttribute( /*Attribute::MOVEMENT_SPEED*/ 5)->setValue($attribute * 100 / 115);
                                unset ($this->plugin->d [$this->player->getName()] ["신속의 깃털 I"]);
                                $this->player->sendMessage($this->plugin->pre . "신속의 깃털 I 의 효능이 끝났습니다.");
                            } else {
                                unset ($this->plugin->d [$this->player->getName()] ["신속의 깃털 I"]);
                                return;
                            }
                        }
                    }, 400);
            $player->sendMessage($this->plugin->pre . "신속의 깃털 I (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            return;
        }

        if ($item->getId() == 262 && $item->getDamage() == 22 && $item->getCustomName() == "§r§l§f신속의 깃털 II") {
            if (isset ($this->plugin->d [$player->getName()] ["신속의 깃털 II"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["신속의 깃털 II"] = "on";
            $this->plugin->cdata [$player->getName()] ["신속의 깃털 II"]++;
            $this->plugin->save();
            $attribute = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->getValue();
            $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->setValue($attribute * 13 / 10);
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player, $this->plugin->cdata [$player->getName()] ["신속의 깃털 II"]) extends Task {
                        public function __construct(EtcItem $plugin, Player $player, int $count) {
                            $this->plugin = $plugin;
                            $this->player = $player;
                            $this->count = $count;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["신속의 깃털 II"])) return;
                            if ($this->count !== $this->plugin->cdata [$this->player->getName()] ["신속의 깃털 II"]) return;
                            if ($this->player->isOnline()) {
                                $attribute = $this->player->getAttributeMap()->getAttribute(5)->getValue();
                                $this->player->getAttributeMap()->getAttribute(5)->setValue($attribute * 10 / 13);
                                unset ($this->plugin->d [$this->player->getName()] ["신속의 깃털 II"]);
                                $this->player->sendMessage($this->plugin->pre . "신속의 깃털 II 의 효능이 끝났습니다.");
                            } else {
                                unset ($this->plugin->d [$this->player->getName()] ["신속의 깃털 II"]);
                                return;
                            }
                        }
                    }, 500);
            $player->sendMessage($this->plugin->pre . "신속의 깃털 II (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            return;
        }

        if ($item->getId() == 262 && $item->getDamage() == 30 && $item->getCustomName() == "§r§l§a경험치 물약") {
            // 대략 한시간
            if (isset ($this->plugin->d [$player->getName()] ["경험치포션"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $player->sendMessage($this->plugin->pre . "경험치 포션 (을)를 사용하였습니다!");
            $this->plugin->d [$name] ["경험치포션"] = "on";
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player) extends Task {
                        public function __construct(EtcItem $plugin, Player $player) {
                            $this->player = $player;
                            $this->plugin = $plugin;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["경험치포션"])) return;
                            unset ($this->plugin->d [$this->player->getName()] ["경험치포션"]);
                            if ($this->player->isOnline()) {
                                $this->player->sendMessage($this->plugin->pre . "경험치 포션의 효능이 끝났습니다.");
                            }
                        }
                    }, 72000);
            $this->plugin->save();
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 8 && $item->getCustomName() == "§r§l§c재생의 물약 I") {
            if (isset ($this->plugin->d [$player->getName()] ["재생 포션"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["재생 포션"] = "on";
            $player->sendMessage($this->plugin->pre . "재생의 물약 I (을)를 사용하였습니다!");
            $this->plugin->getScheduler()->scheduleRepeatingTask(new RecoveryTask ($player, $this->plugin, 140, 20, "재생의 물약 I"), 20);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 8 && $item->getCustomName() == "§r§l§c재생의 물약 II") {
            if (isset ($this->plugin->d [$player->getName()] ["재생 포션"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["재생 포션"] = "on";
            $player->sendMessage($this->plugin->pre . "재생의 물약 II (을)를 사용하였습니다!");
            $this->plugin->getScheduler()->scheduleRepeatingTask(new RecoveryTask ($player, $this->plugin, 200, 55, "재생의 물약 II"), 20);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 8 && $item->getCustomName() == "§r§l§c재생의 물약 III") {
            if (isset ($this->plugin->d [$player->getName()] ["재생 포션"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["재생 포션"] = "on";
            $player->sendMessage($this->plugin->pre . "재생의 물약 III (을)를 사용하였습니다!");
            $this->plugin->getScheduler()->scheduleRepeatingTask(new RecoveryTask ($player, $this->plugin, 100, 240, "재생의 물약 III"), 20);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 8 && $item->getCustomName() == "§r§l§c재생의 물약 IV") {
            if (isset ($this->plugin->d [$player->getName()] ["재생 포션"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["재생 포션"] = "on";
            $player->sendMessage($this->plugin->pre . "재생의 물약 IV (을)를 사용하였습니다!");
            $this->plugin->getScheduler()->scheduleRepeatingTask(new RecoveryTask ($player, $this->plugin, 250, 240, "재생의 물약 IV"), 20);
            $this->plugin->removeItem($player, $item, 1);
            return;
        }
        if ($item->getId() == 262 && $item->getDamage() == 18 && $item->getCustomName() == "§r§l§c힘의 샘물 I") {
            if (isset ($this->plugin->d [$player->getName()] ["힘의 샘물 I"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["힘의 샘물 I"] = "on";
            $this->plugin->cdata [$player->getName()] ["힘의 샘물 I"]++;
            $this->plugin->save();
            $player->sendMessage($this->plugin->pre . "힘의 샘물 I (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player, $this->plugin->cdata [$player->getName()] ["힘의 샘물 I"]) extends Task {
                        public function __construct(EtcItem $plugin, Player $player, int $count) {
                            $this->plugin = $plugin;
                            $this->player = $player;
                            $this->count = $count;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["힘의 샘물 I"])) return;
                            if ($this->count !== $this->plugin->cdata [$this->player->getName()] ["힘의 샘물 I"]) return;
                            unset ($this->plugin->d [$this->player->getName()] ["힘의 샘물 I"]);
                            if ($this->player->isOnline()) {
                                $this->player->sendMessage($this->plugin->pre . "힘의 샘물 I의 효능이 끝났습니다.");
                            }
                        }
                    }, 1200);
        }
        if ($item->getId() == 262 && $item->getDamage() == 18 && $item->getCustomName() == "§r§l§c힘의 샘물 II") {
            if (isset ($this->plugin->d [$player->getName()] ["힘의 샘물 II"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["힘의 샘물 II"] = "on";
            $this->plugin->cdata [$player->getName()] ["힘의 샘물 II"]++;
            $this->plugin->save();
            $player->sendMessage($this->plugin->pre . "힘의 샘물 II (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player, $this->plugin->cdata [$player->getName()] ["힘의 샘물 II"]) extends Task {
                        public function __construct(EtcItem $plugin, Player $player, int $count) {
                            $this->plugin = $plugin;
                            $this->player = $player;
                            $this->count = $count;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["힘의 샘물 II"])) return;
                            if ($this->count !== $this->plugin->cdata [$this->player->getName()] ["힘의 샘물 II"]) return;
                            unset ($this->plugin->d [$this->player->getName()] ["힘의 샘물 II"]);
                            if ($this->player->isOnline()) {
                                $this->player->sendMessage($this->plugin->pre . "힘의 샘물 II 의 효능이 끝났습니다.");
                            }
                        }
                    }, 1800);
        }
        if ($item->getId() == 262 && $item->getDamage() == 13 && $item->getCustomName() == "§r§l§b강화의 호신부 I") {
            if (isset ($this->plugin->d [$player->getName()] ["강화의 호신부 I"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["강화의 호신부 I"] = "on";
            $this->plugin->cdata [$player->getName()] ["강화의 호신부 I"]++;
            $this->plugin->save();
            $player->sendMessage($this->plugin->pre . "강화의 호신부 I (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player, $this->plugin->cdata [$player->getName()] ["강화의 호신부 I"]) extends Task {
                        public function __construct(EtcItem $plugin, Player $player, int $count) {
                            $this->plugin = $plugin;
                            $this->player = $player;
                            $this->count = $count;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["강화의 호신부 I"])) return;
                            if ($this->count !== $this->plugin->cdata [$this->player->getName()] ["강화의 호신부 I"]) return;
                            unset ($this->plugin->d [$this->player->getName()] ["강화의 호신부 I"]);
                            if ($this->player->isOnline()) {
                                $this->player->sendMessage($this->plugin->pre . "강화의 호신부 I 의 효능이 끝났습니다.");
                            }
                        }
                    }, 1200);
        }
        if ($item->getId() == 262 && $item->getDamage() == 13 && $item->getCustomName() == "§r§l§b강화의 호신부 II") {
            if (isset ($this->plugin->d [$player->getName()] ["강화의 호신부 II"])) {
                $player->sendMessage($this->plugin->pre . "이미 사용중입니다.");
                return;
            }
            $this->plugin->d [$player->getName()] ["강화의 호신부 II"] = "on";
            $this->plugin->cdata [$player->getName()] ["강화의 호신부 II"]++;
            $this->plugin->save();
            $player->sendMessage($this->plugin->pre . "강화의 호신부 II (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            $this->plugin->getScheduler()->scheduleDelayedTask(
                    new class ($this->plugin, $player, $this->plugin->cdata [$player->getName()] ["강화의 호신부 II"]) extends Task {
                        public function __construct(EtcItem $plugin, Player $player, int $count) {
                            $this->plugin = $plugin;
                            $this->player = $player;
                            $this->count = $count;
                        }

                        public function onRun($currentTick) {
                            if (!isset ($this->plugin->d [$this->player->getName()] ["강화의 호신부 II"])) return;
                            if ($this->count !== $this->plugin->cdata [$this->player->getName()] ["강화의 호신부 II"]) return;
                            unset ($this->plugin->d [$this->player->getName()] ["강화의 호신부 II"]);
                            if ($this->player->isOnline()) {
                                $this->player->sendMessage($this->plugin->pre . "강화의 호신부 II 의 효능이 끝났습니다.");
                            }
                        }
                    }, 1800);
        }
        if ($item->getId() == 262 && $item->getDamage() == 20 && $item->getCustomName() == "§r§l§e만병통치약") {
            $player->sendMessage($this->plugin->pre . "만병통치약 (을)를 사용하였습니다!");
            $this->plugin->removeItem($player, $item, 1);
            if (isset ($this->plugin->d [$player->getName()] ["신속의 깃털 I"])) {
                $attribute = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->getValue();
                $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->setValue($attribute * 100 / 115);
                unset ($this->plugin->d [$player->getName()] ["신속의 깃털 I"]);
            }
            if (isset ($this->plugin->d [$player->getName()] ["신속의 깃털 II"])) {
                $attribute = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->getValue();
                $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->setValue($attribute * 10 / 13);
                unset ($this->plugin->d [$player->getName()] ["신속의 깃털 II"]);
            }
            unset ($this->plugin->d [$player->getName()] ["힘의 샘물 I"]);
            unset ($this->plugin->d [$player->getName()] ["힘의 샘물 II"]);
            unset ($this->plugin->d [$player->getName()] ["강화의 호신부 I"]);
            unset ($this->plugin->d [$player->getName()] ["강화의 호신부 II"]);
        }
    }
}
