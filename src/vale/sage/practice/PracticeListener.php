<?php
namespace vale\sage\practice;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use vale\sage\practice\session\PracticeSession;
use vale\sage\practice\utils\IUtils;
use vale\sage\practice\utils\menus\invs\FreeForAllInventory;
use vale\sage\practice\utils\misc\HotbarUtils;
use vale\sage\practice\utils\misc\PlayerUtils;
use vale\sage\practice\utils\misc\ScoreboardUtils;

class PracticeListener implements Listener
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
     * @param PlayerLoginEvent $event
     */
    public function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $value = isset(Loader::getInstance()->practiceSessions[$player->getName()]);
        if (!$value) {
            Loader::getInstance()->practiceSessions[$player->getName()] = new PracticeSession($player);
        }
    }

	/**
	 * @param PlayerJoinEvent $event
	 */
    public function onConnect(PlayerJoinEvent $event): void
    {
		$event->setJoinMessage("");
        $player = $event->getPlayer();
        if ($session = Loader::getInstance()->practiceSessions[$player->getName()]) {
            if ($session instanceof PracticeSession) {
				if ($player->hasPlayedBefore()) {
					$session->load();
				}
			}
			if(!$player->hasPlayedBefore()){
				$session->register();
			}
			PlayerUtils::reset($player);
			HotbarUtils::sendHotBar($player);
			Loader::getInstance()->getChangeLogs()->getMessage($player);
			$world = Loader::getInstance()->getServer()->getWorldManager()->getWorldByName(IUtils::DEFAULT_LOBBY)->getFolderName();
			$player->teleport($this->plugin->getServer()->getWorldManager()->getWorldByName($world)->getSafeSpawn());
		}
    }

	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onDisconnect(PlayerQuitEvent $event): void
	{
		$event->setQuitMessage("");
		$player = $event->getPlayer();
		if ($session = Loader::getInstance()->practiceSessions[$player->getName()]) {
			if ($session instanceof PracticeSession) {
				$session->save();
			}
		}
	}
}