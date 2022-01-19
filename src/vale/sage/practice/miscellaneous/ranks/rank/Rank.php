<?php
namespace vale\sage\practice\miscellaneous\ranks\rank;
class Rank{

	public function __construct(
		public string $rankName = "",
		public ?int $rankID = null,
		public array  $permissions = [],
		public $rankFormat = "",
		public $chatFormat = "",
	){

	}

	/**
	 * @return string
	 */
	public function getChatFormat(): string
	{
		return $this->chatFormat;
	}

	/**
	 * @param string $rankFormat
	 */
	public function setRankFormat(string $rankFormat): void
	{
		$this->rankFormat = $rankFormat;
	}

	/**
	 * @return string
	 */
	public function getRankFormat(): string
	{
		return $this->rankFormat;
	}

	/**
	 * @return array
	 */
	public function getPermissions(): array
	{
		return $this->permissions;
	}

	/**
	 * @return int|null
	 */
	public function getRankID(): ?int
	{
		return $this->rankID;
	}

	/**
	 * @return string
	 */
	public function getRankName(): string
	{
		return $this->rankName;
	}

	/**
	 * @param string $chatFormat
	 */
	public function setChatFormat(string $chatFormat): void
	{
		$this->chatFormat = $chatFormat;
	}

	/**
	 * @param array $permissions
	 */
	public function setPermissions(array $permissions): void
	{
		$this->permissions = $permissions;
	}

	/**
	 * @param int|null $rankID
	 */
	public function setRankID(?int $rankID): void
	{
		$this->rankID = $rankID;
	}

	/**
	 * @param string $rankName
	 */
	public function setRankName(string $rankName): void
	{
		$this->rankName = $rankName;
	}
}