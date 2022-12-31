<?php

class AccountModel
{
	public function getAllCharacter()
	{
		return DB::table('character')->select('name')->where('AccountID', Auth::user()->username)->get();
	}
	
	public function getCharacterDetailsByName($character)
	{
		return DB::table('character')->select('mlevel')->where('name', $character)->first();
	}
	
	public function getCharacterInfoByName($character)
	{
		return DB::table('character')
		->select(
			'AccountID', 
			'name', 
			DB::raw('class as chartype'),
			'mlevel', 
			'mlpoint',
			'class', 
			'clevel', 
			'leveluppoint', 
			'strength', 
			'dexterity', 
			'vitality', 
			'leadership', 
			'energy', 
			'money', 
			'winduels', 
			'loseduels'
		)
		->where('name', $character)->first();
	}
	
	public function getPKCountByCharname($charname)
	{
		return DB::table('C_PlayerKiller_Info')
			->select(DB::raw('top 1 count(victim) as victim, killer'))
			->where('killer', $charname)
			->groupBy('killer')
			->orderBy('victim', 'desc')
			->get();
	}
	public function getCoinsByUsername($username)
	{
		$coin = DB::table('T_InGameShop_Point')->where('AccountID', $username)->first();
		$coins15 = DB::connection('s15')->table('T_InGameShop_Point')->where('AccountID', $username)->first();

		$c = new StdClass;
		$c->AccountID = $username;

		if($coins15 == null)
		{
			$c->GoblinPoint15 = 0;
			$c->WCoin = 0;
			
		}else{
			$c->GoblinPoint15 = $coins15->GoblinPoint;
			$c->WCoin = $coins15->WCoin;
		}

		if($coin == null)
		{
			$c->WCoin = 0;
			$c->GoblinPoint = 0;
		}else{
			$c->WCoinP = $coin->WCoin;
			$c->GoblinPoint = $coin->GoblinPoint;
		}

		return $c;
	}
	
	public function checkAccountIfOnline($username)
	{
		return DB::table('memb_stat')->where('memb___id', $username)->first();
	}
	
	public function getMemberAccountByUsername($username)
	{	
		return DB::table('memb_info')->where('memb___id', $username)->first();
	}
	
	public function addJewels($username)
	{
		$bless = array(
			'AccountID' => $username,
			'AuthCode' =>0,
			'UniqueID1' => 673,
			'UniqueID2' => 499,
			'UniqueID3' => 578,
			'InventoryType' => 1,
		);
		
		$soul = array(
			'AccountID' => $username,
			'AuthCode' =>0,
			'UniqueID1' => 673,
			'UniqueID2' => 500,
			'UniqueID3' => 579,
			'InventoryType' => 1,
		);
		
		DB::table('T_InGameShop_Items')->insert($bless);
		DB::table('T_InGameShop_Items')->insert($soul);
	}
	
	public function addStartingSeals($username)
	{
		$seals = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 10,
					'UniqueID3' => 15,
					'InventoryType' => 1,
				);
				
				$sealshealing = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 38,
					'UniqueID3' => 63,
					'InventoryType' => 1,
				);
				
				$sealspet = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 61,
					'UniqueID3' => 102,
					'InventoryType' => 1,
				);
				
				$sealsring = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 59,
					'UniqueID3' => 98,
					'InventoryType' => 1,
				);
				
				$masterseals = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 51,
					'UniqueID3' => 81,
					'InventoryType' => 1,
				);
				
				DB::table('T_InGameShop_Items')->insert($seals);
				DB::table('T_InGameShop_Items')->insert($sealshealing);
				DB::table('T_InGameShop_Items')->insert($sealspet);
				DB::table('T_InGameShop_Items')->insert($sealsring);
				DB::table('T_InGameShop_Items')->insert($masterseals);
	}
	
	public function add1DaySeals($username)
	{
		$masterseals = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 38,
					'UniqueID3' => 62,
					'InventoryType' => 1,
				);
				
				$sealspet = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 61,
					'UniqueID3' => 101,
					'InventoryType' => 1,
				);
				
				$sealsring = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 59,
					'UniqueID3' => 97,
					'InventoryType' => 1,
				);
			
				
				DB::table('T_InGameShop_Items')->insert($masterseals);
				DB::table('T_InGameShop_Items')->insert($sealspet);
				DB::table('T_InGameShop_Items')->insert($sealsring);
			
	}
	
	public function add1daywealth($username)
	{
		$masterseals = array(
					'AccountID' => $username,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 51,
					'UniqueID3' => 80,
					'InventoryType' => 1,
				);
				
				DB::table('T_InGameShop_Items')->insert($masterseals);
			
	}
}