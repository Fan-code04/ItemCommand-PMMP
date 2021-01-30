<?php

namespace Fan;


use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;

class Main extends PluginBase implements Listener{ 

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);    
        $this->getLogger()->info("Activez! by Fan"); 

        @mkdir($this->getDataFolder());
        $this->saveResource("configs.yml");
        $this->cfgs = new Config($this->getDataFolder() . "configs.yml", Config::YAML);
    }

    public function onDisable() {
$this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getLogger()->info("Désactivé!");
    }

    public function onInteract(PlayerInteractEvent $event){
         
        $player = $event->getPlayer();
        $playername = $event->getPlayer()->getName();
        $monitems = $event->getItem();
        $myconfigs = $this->cfgs->getAll();
        $mydatas = $this->getInConfig($monitems->getId(),$monitems->getDamage());

        if (!is_null($this->getinConfig($monitems->getId(),$monitems->getDamage()))){

            $id = Item::fromString($mydatas);

            if ($monitems->getId() === $id->getId() and ($id->getDamage() == 0 or $monitems->getDamage() == $id->getDamage())) {

                $ici = $myconfigs[$mydatas];

                        $player->getInventory()->removeItem(Item::get($id->getId(),$id->getDamage(),1));

                        $player->sendMessage($ici["message"]);
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", $playername, $ici["commands"]));
            }
        }
    }
 
    public function getInConfig(int $id, int $damage){
        $configs = $this->cfgs->getAll();
        $ids = array_keys($configs);

        if (in_array("$id",$ids)) {

            return "$id";

        } else if (in_array("$id:$damage",$ids)) {

            return "$id:$damage";

        }

        return null;
    }

}
