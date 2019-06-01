<?php

namespace EtcItem;

use Core\Core;
use Core\util\Util;
use HotbarSystemManager\HotbarSystemManager;
use pocketmine\command\{Command, CommandSender};
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use WarpManager\WarpManager;

class EtcItem extends PluginBase {

    private static $instance = null;
    //public $pre = "§l§9[ §f아이템 §9] §r§9";
    public $pre = "§e• ";
    public $data, $d;

    public static function getInstance() {
        return self::$instance;
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
        $this->warp = WarpManager::getInstance();
        $this->core = Core::getInstance();
        $this->i = new Util ($this->core);
        $this->HotbarSystem = HotbarSystemManager::getInstance();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        @mkdir($this->getDataFolder());
        $this->saveResource("EtcItem.yml");
        $this->edata = (new Config ($this->getDataFolder() . "EtcItem.yml", Config::YAML))->getAll();
        $this->data = new Config ($this->getDataFolder() . "data.yml", Config::YAML);
        $this->d = $this->data->getAll();
        $this->count = new Config ($this->getDataFolder() . "count.yml", Config::YAML);
        $this->cdata = $this->count->getAll();
    }

    public function onDisable() {
        $this->d = [];
        $this->save();
    }

    public function save() {
        $this->count->setAll($this->cdata);
        $this->count->save();
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, $args): bool {
        if ($cmd->getName() == "잡화") {
            if (!$sender->isOp())
                return false;
            if (!isset($args[0]) || !isset($args[1]) || !is_numeric($args[1]) || $args[1] <= 0)
                return false;
            $this->getEtcItem($sender, $args[0], $args[1]);
            return true;
        }
    }

    public function getEtcItem(Player $player, string $itemname, int $amount) {
        if (!isset($this->edata [$itemname])) return;
        $itemData = explode(":", $this->edata [$itemname]);
        if (isset($itemData [3])) {
            $arr = [];
            for ($i = 3; $i < count($itemData) - 1; $i++) {
                array_push($arr, "§r§f" . $itemData [$i]);
            }
        }
        $item = Item::get($itemData [1], $itemData [2], $amount);
        $item->clearNamedTag();
        $item->setCustomName("{$itemData [0]}{$itemname}");
        if (isset($itemData [3])) $item->setLore($arr);
        $player->getInventory()->addItem($item);
    }

    public function isEtcItem(string $itemname) {
        $customname = str_replace(["§0", "§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§a", "§b", "§c", "§d", "§e", "§f", "§l", "§o", "§r"], ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""], $itemname);
        if (isset ($this->edata [$customname])) return true;
        elseif (!isset ($this->edata [$customname])) return false;
    }

    public function getEtcItemName(Item $item) {
        $customname = str_replace(["§0", "§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§a", "§b", "§c", "§d", "§e", "§f", "§l", "§o", "§r"], ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""], $item->getCustomName());
        if (isset ($this->edata [$customname])) {
            return $customname;
        } elseif (!isset ($this->edata [$customname])) {
            return false;
        }
    }

    public function getPrize($name) {
        if (!isset ($this->edata [$name])) return;
        $itemData = explode(":", $this->edata [$name]);
        $i = count($itemData);
        return $itemData [$i - 1];
    }

    public function getExpBuff($name) {
        $a = 1;
        if (isset ($this->d [$name] ["경험치물약"])) $a = 2;
        return $a;
    }

    public function getATKBuff($name) {
        $a = 1;
        $b = 1;
        if (isset ($this->d [$name] ["힘의 샘물 I"])) $a = 11 / 10;
        if (isset ($this->d [$name] ["힘의 샘물 II"])) $b = 115 / 100;
        return $a * $b;
    }

    public function getDEFBuff($name) {
        $a = 1;
        $b = 1;
        if (isset ($this->d [$name] ["강화의 호신부 I"])) $a = 11 / 10;
        if (isset ($this->d [$name] ["강화의 호신부 II"])) $b = 115 / 100;
        return $a * $b;
    }

    public function getMDEFBuff($name) {
        $a = 1;
        $b = 1;
        if (isset ($this->d [$name] ["강화의 호신부 I"])) $a = 11 / 10;
        if (isset ($this->d [$name] ["강화의 호신부 II"])) $b = 115 / 100;
        return $a * $b;
    }

    public function Item(int $id, int $damage, int $amount, string $itemname, array $lore = null) {
        $item = Item::get($id, $damage, $amount);
        $item->setCustomName($itemname);
        if ($lore !== null) $item->setLore($lore);
        return $item;
    }

    public function removeItem(Player $player, Item $item, $count = 1) {
        $item_1 = Item::get($item->getId(), $item->getDamage(), $count);
        $item_1->setCustomName($item->getCustomName());
        $item_1->setLore($item->getLore());
        $player->getInventory()->removeItem($item_1);
    }

    public function getEtcItem_1(string $itemname, int $amount) {
        if (!isset($this->edata [$itemname])) return;
        $itemData = explode(":", $this->edata [$itemname]);
        if (isset($itemData [3])) {
            $arr = [];
            for ($i = 3; $i < count($itemData) - 1; $i++) {
                array_push($arr, "§r§f" . $itemData [$i]);
            }
        }
        $item = Item::get($itemData [1], $itemData [2], $amount);
        $item->clearNamedTag();
        $item->setCustomName("{$itemData [0]}{$itemname}");
        if (isset($itemData [3])) $item->setLore($arr);
        return $item;
    }
}
