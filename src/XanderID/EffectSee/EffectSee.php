<?php

namespace XanderID\EffectSee;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\VanillaEffects;

use XanderID\EffectSee\Commands\EffectSeeCommands;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class EffectSee extends PluginBase implements Listener{
    
    public const effectsData = [
		 "speed" => ["name" => "Speed", "image" => "textures/ui/speed_effect"],
	 	"slowness" => ["name" => "Slowness", "image" => "textures/ui/slowness_effect"],
		 "haste" => ["name" => "Haste", "image" => "textures/ui/haste_effect"],
		 "mining_fatigue" => ["name" => "Mining Fatigue", "image" => "textures/ui/mining_fatigue_effect"],
		 "strength" => ["name" => "Strength", "image" => "textures/ui/strength_effect"],
		 "instant_health" => ["name" => "Instant Health", "image" => null],
		 "instant_damage" => ["name" => "Instant Damage", "image" => null],
		 "jump_boost" => ["name" => "Jump Boost", "image" => "textures/ui/jump_boost_effect"],
		 "nausea" => ["name" => "Nausea", "image" => "textures/ui/nausea_effect"],
		 "regeneration" => ["name" => "Regeneration", "image" => "textures/ui/regeneration_effect"],
		 "resistance" => ["name" => "Resistance", "image" => "textures/ui/resistance_effect"],
		 "fire_resistance" => ["name" => "Fire Resistance", "image" => "textures/ui/fire_resistance_effect"],
		 "water_breathing" => ["name" => "Water Breathing", "image" => "textures/ui/water_breathing_effect"],
		 "invisibility" => ["name" => "Invisibility", "image" => "textures/ui/_effect"],
		 "blindness" => ["name" => "Blindness", "image" => "textures/ui/invisibility_effect"],
		 "night_vision" => ["name" => "Night Vision", "image" => "textures/ui/night_vision_effect"],
		 "hunger" => ["name" => "Hunger", "image" => "textures/ui/hunger_effect"],
		 "weakness" => ["name" => "Weakness", "image" => "textures/ui/weakness_effect"],
		 "poison" => ["name" => "Poison", "image" => "textures/ui/poison_effect"],
		 "wither" => ["name" => "Wither", "image" => "textures/ui/wither_effect"],
		 "health_boost" => ["name" => "Health Boost", "image" => "textures/ui/health_boost_effect"],
		 "absorption" => ["name" => "Absorption", "image" => "textures/ui/absorption_effect"],
		 "saturation" => ["name" => "Saturation", "image" => "textures/ui/saturation_effect"],
		 "levitation" => ["name" => "Levitation", "image" => "textures/ui/levitation_effect"],
		 "conduit_power" => ["name" => "Conduit Power", "image" => "textures/ui/conduit_power_effect"]
	];
	
	/** @var array $fronteffect */
	private $fronteffect = ["effectname" => [], "realname" => []];
    
    public function onEnable(): void{
    	$index = 0;
    	foreach(self::effectsData as $name => $data){
    		$this->fronteffect["effectname"][$index] = $data["name"];
    		$this->fronteffect["realname"][$index] = $name;
    		$index++;
    	}
        $this->getServer()->getCommandMap()->register("EffectSee", new EffectSeeCommands($this));
    }
    
    public static function getEffectNameByName(string $name): string{
    	if(!isset(self::effectsData[$name]["name"])){
    		return "";
    	}
		return self::effectsData[$name]["name"];
	}
	
	public static function getImageEffectByName(string $name): string{
		if(!isset(self::effectsData[$name]["image"])){
			return "";
		}
		return self::effectsData[$name]["image"];
	}
	
	public static function getEffectByName(string $name): Effect {
	        return match ($name) {
	            "absorption" => VanillaEffects::ABSORPTION(),
	            "blindness" => VanillaEffects::BLINDNESS(),
	            "conduit_power" => VanillaEffects::CONDUIT_POWER(),
	            "poison" => VanillaEffects::POISON(),
	            "fire_resistance" => VanillaEffects::FIRE_RESISTANCE(),
	            "haste" => VanillaEffects::HASTE(),
	            "health_boost" => VanillaEffects::HEALTH_BOOST(),
	            "hunger" => VanillaEffects::HUNGER(),
	            "instant_damage" => VanillaEffects::INSTANT_DAMAGE(),
	            "instant_health" => VanillaEffects::INSTANT_HEALTH(),
	            "invisibility" => VanillaEffects::INVISIBILITY(),
	            "jump_boost" => VanillaEffects::JUMP_BOOST(),
	            "levitation" => VanillaEffects::LEVITATION(),
	            "mining_fatigue" => VanillaEffects::MINING_FATIGUE(),
	            "nausea" => VanillaEffects::NAUSEA(),
	            "night_vision" => VanillaEffects::NIGHT_VISION(),
	            "regeneration" => VanillaEffects::REGENERATION(),
	            "resistance" => VanillaEffects::RESISTANCE(),
	            "saturation" => VanillaEffects::SATURATION(),
	            "slowness" => VanillaEffects::SLOWNESS(),
	            "speed" => VanillaEffects::SPEED(),
	            "strength" => VanillaEffects::STRENGTH(),
	            "water_breathing" => VanillaEffects::WATER_BREATHING(),
	            "weakness" => VanillaEffects::WEAKNESS(),
	            "wither" => VanillaEffects::WITHER(),
	            default => null,
	        };
	}
	
	public static function translateEffectName(string $name): string {
		$name = str_replace("potion.", "", $name);
	        return match ($name) {
	            "jump" => "jump_boost",
	            "confusion" => "nausea",
	            "heal" => "instant_health",
	            "harm" => "instant_damage",
	            "conduitPower" => "conduit_power",
	            "damageBoost" => "strength",
	            "digSlowDown" => "mining_fatigue",
	            "digSpeed" => "haste",
	            "fireResistance" => "fire_resistance",
	            "healthBoost" => "health_boost",
	            "moveSlowdown" => "slowness",
	            "moveSpeed" => "speed",
	            "nightVision" => "night_vision",
	            "waterBreathing" => "water_breathing",
	            default => $name,
	        };
	}
    
	public function seeEffects(Player $sender, Player $player): bool{
		$form = new SimpleForm(function (Player $sender, $data = null) use($player){
            if ($data === null) {
                return false;
            }
           if($data == "ADDEFFECT"){
           	$this->addEffects($sender, $player);
           	return true;
           }
           $effects = null;
           foreach($player->getEffects()->all() as $index => $effect){
           	$transtable = $effect->getType()->getName()->getText();
           	if($data == $transtable){
           		$effects = $effect;
           	}
           }
           $this->actionEffects($sender, $player, $effects);
           return true;
        });
        $form->setTitle($player->getName() . " Effects");
        // Text for if Target not have any effect
        if(count($player->getEffects()->all()) == 0){
        	$form->setContent("This player doesn't have any effect");
        	$form->addButton("Add Effects", 0, "textures/items/potion_bottle_drinkable", "ADDEFFECT");
        	$sender->sendForm($form);
        	return false;
        }
        $form->setContent("Select Effects for action:");
        $form->addButton("Add Effects", 0, "textures/items/potion_bottle_drinkable", "ADDEFFECT");
        foreach($player->getEffects()->all() as $index => $effect){
        	$transtable = $effect->getType()->getName()->getText();
        	$effectname = self::translateEffectName($transtable);
        	$form->addButton(self::getEffectNameByName($effectname), 0, self::getImageEffectByName($effectname), $transtable);
        }
        $sender->sendForm($form);
        return true;
	}
	
	public static function getTimeFromTick(int $tick): string{
		$tick = $tick / 20;
		$tick = floor($tick);
		return gmdate("i:s", $tick);
	}
	
	private function actionEffects(Player $sender, Player $player, EffectInstance $effectinstance): bool{
		$form = new SimpleForm(function (Player $sender, $data = null) use($player, $effectinstance){
            if ($data === null) {
                return false;
            }
            $effect = $effectinstance->getType();
           if($data == "TRASHEFFECT"){
           	if(!$player->getEffects()->has($effect)){
           		$sender->sendMessage("§ccan't throw effect");
           		return false;
           	}
           	$player->getEffects()->remove($effect);
           	$sender->sendMessage("§aEffect Successfully Trashed");
           }
           if($data == "EDITEFFECT"){
           	$this->editEffects($sender, $player, $effectinstance);
           }
           return true;
        });
        $form->setTitle("Action For " . $player->getName() . " Effects");
    	 // for information in content
		$nameeffect = self::translateEffectName($effectinstance->getType()->getName()->getText());
		$nameeffect = self::getEffectNameByName($nameeffect);
        $visibled = $effectinstance->isVisible() ? "yes":"no";
        $duration = self::getTimeFromTick($effectinstance->getDuration());
        $amplifier = $effectinstance->getAmplifier();
        $badeffect = $effectinstance->getType()->isBad() ? "yes":"no";
        $text = "Effect: " . $nameeffect;
        $text .= "\nDuration: " . $duration;
        $text .= "\nAmplifier: " . $amplifier;
        $text .= "\nVisibled: " . $visibled;
        $text .= "\nBad Effect: " . $badeffect;
        $text .= "\n\nSelect Action:";
        // end information content variable
        $form->setContent($text);
        $form->addButton("Trash Effect", 0, "textures/ui/icon_trash", "TRASHEFFECT");
        $form->addButton("Edit Effect", 0, "textures/ui/pencil_edit_icon", "EDITEFFECT");
        $sender->sendForm($form);
        return true;
	}
	
	private function editEffects(Player $sender, Player $player, EffectInstance $effectinstance): bool{
		$form = new CustomForm(function (Player $sender, $data = null) use($player, $effectinstance){
            if ($data === null) {
                return false;
            }
            // Check all Data
			if(!is_numeric($data[1])){
				$sender->sendMessage("§cEffect duration must be numeric");
				return false;
			}
			if(!is_numeric($data[2]) or $data[2] < 0 or $data[2] > 255){
				$sender->sendMessage("§cEffect Amplifier must be 0-255");
				return false;
			}
			// send effect to player
			$effectname = self::translateEffectName($effectinstance->getType()->getName()->getText());
			$effect = self::getEffectByName($effectname);
			$visible = $data[0];
			$duration = 20 * $data[1];
			$amplifier = $data[2];
			// First delete the old effect
			$player->getEffects()->remove($effectinstance->getType());
			// then add new effect
			$player->getEffects()->add(new EffectInstance($effect, $duration, $amplifier, $visible));
			$sender->sendMessage("§aEffects successfully Edited");
			return true;
        });
        $form->setTitle("Edit " . $player->getName() . " Effects");
        $form->addToggle("Visibled", $effectinstance->isVisible());
        $form->addInput("Set Duration:", "3 ( Seconds )");
        $form->addInput("Set Amplifier:", "0-255");
        $sender->sendForm($form);
        return true;
    }
	
	public function addEffects(Player $sender, Player $player): bool{
		$form = new CustomForm(function (Player $sender, $data = null) use($player){
            if ($data === null) {
                return false;
            }
            // Check all Data
			if(!is_numeric($data[2])){
				$sender->sendMessage("§cEffect duration must be numeric");
				return false;
			}
			if(!is_numeric($data[3]) or $data[3] < 0 or $data[3] > 255){
				$sender->sendMessage("§cEffect Amplifier must be 0-255");
				return false;
			}
			// add effect to player
			$effect = self::getEffectByName($this->fronteffect["realname"][$data[0]]);
			$visible = $data[1];
			$duration = 20 * $data[2];
			$amplifier = $data[3];
			$player->getEffects()->add(new EffectInstance($effect, $duration, $amplifier, $visible));
			$sender->sendMessage("§aEffects successfully added");
			return true;
        });
        $form->setTitle("Add " . $player->getName() . " Effects");
        $form->addDropdown("Select Effect:", $this->fronteffect["effectname"]);
        $form->addToggle("Visibled", true);
        $form->addInput("Set Duration:", "3 ( Seconds )");
        $form->addInput("Set Amplifier:", "0-255");
        $sender->sendForm($form);
        return true;
	}
}
