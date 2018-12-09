<?php

namespace NxtUserHD;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class WorldSchutz extends PluginBase implements Listener{

public $prefix = "§8[§bWorldSchutz§8]§r ";

public function onEnable(){
@mkdir($this->getDataFolder());
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
if(empty($config->get("Protected"))){
$config->set("Protected", array());
$config->set("NoPvP", array());
$config->save();
}
$this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->getLogger()->info($this->prefix."§awurde aktiviert");
}
public function onBreak(BlockBreakEvent $event)
  {
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
$player = $event->getPlayer();
if(in_array($player->getlevel()->getName(), $config->get("Protected"))){
$event->setCancelled(true);
}
}
public function onPlace(BlockPlaceEvent $event)
  {
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
$player = $event->getPlayer();
if(in_array($player->getlevel()->getName(), $config->get("Protected"))){
$event->setCancelled(true);
}
}
public function onDamage(EntityDamageEvent $event)
  {
$player = $event->getEntity();
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
if(in_array($player->getlevel()->getName(), $config->get("NoPvP"))){
$event->setCancelled(true);
}
}
public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
if(strtolower($command->getName()) == "ws"){
if($sender instanceof Player){
if($sender->hasPermission("ws.owner")){
if(empty($args[0])){
$sender->sendMessage($this->prefix."/ws <lock | nopvp> <true | false>");
}else{
if($args[0] == "lock"){
if(empty($args[1])){
$sender->sendMessage($this->prefix."/ws <lock | nopvp> <true | false>");
}else{
if($args[1] == "true"){
$protected = $config->get("Protected");
$protected[] = $sender->getlevel()->getName();
$config->set("Protected", $protected);
$config->save();
$sender->sendMessage($this->prefix."§aDie Welt ".$sender->getlevel()->getName()." wurde Protected");
}else{
$protected = $config->get("Protected");
if(in_array($sender->getlevel()->getName(), $protected)){
unset($protected[array_search($sender->getlevel()->getName(), $protected)]);
$config->set("Protected", $protected);
$config->save();
$sender->sendMessage($this->prefix."§aDie Welt ".$sender->getlevel()->getName()." wurde nicht mehr Protected");
}else{
$sender->sendMessage($this->prefix."§cDiese Welt ist noch nicht Protected");
}
}
}
}
if($args[0] == "nopvp"){
if(empty($args[1])){
$sender->sendMessage($this->prefix."/ws nopvp <true | false>");
}else{
if($args[1] == "true"){
$nopvp = $config->get("NoPvP");
$nopvp[] = $sender->getlevel()->getName();
$config->set("NoPvP", $nopvp);
$config->save();
$sender->sendMessage($this->prefix."§aDie Welt ".$sender->getlevel()->getName()." wurde auf NoPvP gesetzt");
}else{
$nopvp = $config->get("NoPvP");
if(in_array($sender->getlevel()->getName(), $nopvp)){
unset($nopvp[array_search($sender->getlevel()->getName(), $nopvp)]);
$config->set("NoPvP", $nopvp);
$config->save();
$sender->sendMessage($this->prefix."§aDie Welt ".$sender->getlevel()->getName()." wurde nicht mehr auf NoPvP gesetzt");
}else{
$sender->sendMessage($this->prefix."§cDiese Welt ist noch nicht auf NoPvP gesetzt");
}
}
}
}
}
}
}
}
return true;
}
}
?>
