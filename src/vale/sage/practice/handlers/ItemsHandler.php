<?php
namespace vale\sage\practice\handlers;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use vale\sage\practice\duels\kit\DuelKit;
use vale\sage\practice\duels\kit\types\TestKit;
use vale\sage\practice\duels\task\DuelTask;
use vale\sage\practice\Loader;
use vale\sage\practice\session\PracticeSession;
use vale\sage\practice\utils\menus\forms\FreeForAllForm;
use vale\sage\practice\utils\menus\invs\FreeForAllInventory;
use vale\sage\practice\utils\misc\HotbarUtils;
use vale\sage\practice\utils\misc\PlayerUtils;

class ItemsHandler implements Listener
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

	public function onClick(PlayerItemUseEvent $event): void
	{
		$player = $event->getPlayer();
		$item = $event->getItem();
		$tag = $item->getNamedTag();
		$session = Loader::getInstance()->practiceSessions[$player->getName()];
		if (!$session instanceof PracticeSession) {
			return;
		}
		if ($tag->getString(HotbarUtils::TAG_FFA, "") !== "") {
			PlayerUtils::playSound($player, "bubble.pop",2);
			Loader::getInstance()->getDuelsManager()->createDuel($player,$player,"lol");
			$matched = Loader::getInstance()->getDuelsManager()->getMatchFromId($session->getMatchId());
			$player->sendMessage("CREATED DUEL");
			$player->sendMessage("FIGHTING: {$matched->getOpponet()->getName()}");
			$player->sendMessage("MATCH ID: {$matched->getMatchId()}");
		}

		if ($tag->getString(HotbarUtils::TAG_DUELS_RANKED, "") !== "") {
			$player->sendMessage("STARTED DUEL");
			PlayerUtils::playSound($player, "bubble.pop",2);
			$matched = Loader::getInstance()->getDuelsManager()->getMatchFromId($session->getMatchId());
			Loader::getInstance()->getDuelsManager()->startDuel($matched);
		}
	}
}