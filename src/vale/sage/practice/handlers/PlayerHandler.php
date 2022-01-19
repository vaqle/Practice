<?php
namespace vale\sage\practice\handlers;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\GameMode;
use vale\sage\practice\Loader;
use vale\sage\practice\utils\IUtils;

class PlayerHandler implements Listener
{
	/**
	 * @param Loader $plugin
	 */
	public function __construct(
		public Loader $plugin
	)
	{
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param PlayerExhaustEvent $event
	 */
	public function onExhaust(PlayerExhaustEvent $event): void
	{
		$player = $event->getPlayer();
		$world = !($player->getWorld()->getFolderName() === IUtils::DEFAULT_LOBBY);
		if(!$world){
			$event->setAmount(20);
			$event->cancel();
		}
	}

	/**
	 * @param InventoryTransactionEvent $event
	 */
	public function onSlotChange(InventoryTransactionEvent $event): void{
		$player = $event->getTransaction()->getSource();
		 if($player->getWorld()->getFolderName() === IUtils::DEFAULT_LOBBY && $player->getGamemode() !== GameMode::CREATIVE()) $event->cancel();
	}
}