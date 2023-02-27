<?php

namespace XanderID\EffectSee\Commands;

use XanderID\EffectSee\EffectSee;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

class EffectSeeCommands extends Command implements PluginOwned {

	/** @var EffectSee $plugin */
    private $plugin;

    /**
     * EffectSeeCommands constructor.
     * @param EffectSee $plugin
     */
    public function __construct(EffectSee $plugin) {
		$this->plugin = $plugin;
		parent::__construct("effectsee", "See Player Effects", "/effsee", ["effsee"]);
        $this->setPermission("effectsee.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
    	if(!$sender instanceof Player){
    		$sender->sendMessage("Use Commands in Game");
    		return false;
    	}
    	if (!$this->testPermission($sender)) return false;
    	// if args 0 is not entered, the player will see his effect automatically
    	$target = $sender;
    
    	if(isset($args[0])){
    		$getp = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($args[0]);
    		if($getp !== null){
    			$target = $getp;
    		}
    	}
    	$this->getOwningPlugin()->seeEffects($sender, $target);
        return true;
	}
	
	public function getOwningPlugin(): EffectSee{
        return $this->plugin;
    }
}
