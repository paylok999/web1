<?php

class Character extends BaseController
{
	public $limit = 20;
	
	public function getTop($order = 'mlevel')
	{
		$orderlimit = array('mlevel', 'pkcount', 'winduels', 's15');
		if(!in_array($order, $orderlimit))
			die();

			if($order == 's15'){
				$rankings =  DB::connection('s15')->table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, mlExperience, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->distinct()
				->leftJoin('ConnectionHistory', 'character.AccountID', '=', 'ConnectionHistory.AccountID')
				->leftJoin('MEMB_INFO', 'character.AccountID', '=', 'MEMB_INFO.memb___id')
				//->leftJoin('T_InGameShop_Point', 'character.AccountID', '=', 'T_InGameShop_Point.AccountID')
				->where('ctlcode', 0)
				//->where('mdate','>', '2020-1-10 20:00:00')
				->where('ConnectionHistory.HWID', '!=', 'N/A')
				//->where('character.Name', '!=', 'Aldos')
				//->where('character.Name', '!=', 'JUDAS')
				//->where('MEMB_INFO.memb_guid', '>', '2565')
				->orderby('total_level', 'desc')
				->orderby('mlExperience', 'desc')
				->limit(200)
				->get();
			}else{
				$rankings =  DB::connection('s15')->table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, mlExperience, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->distinct()
				->leftJoin('ConnectionHistory', 'character.AccountID', '=', 'ConnectionHistory.AccountID')
				->leftJoin('MEMB_INFO', 'character.AccountID', '=', 'MEMB_INFO.memb___id')
				//->leftJoin('T_InGameShop_Point', 'character.AccountID', '=', 'T_InGameShop_Point.AccountID')
				->where('ctlcode', 0)
				//->where('mdate','>', '2020-1-10 20:00:00')
				->where('ConnectionHistory.HWID', '!=', 'N/A')
				//->where('character.Name', '!=', 'Aldos')
				//->where('character.Name', '!=', 'JUDAS')
				//->where('MEMB_INFO.memb_guid', '>', '2565')
				->orderby('total_level', 'desc')
				->orderby('mlExperience', 'desc')
				->limit(200)
				->get();
			}
			

			$filteredRankings = array();
			$newRankings = array();
			$filteredByNameRankings = array();
			$finalRankings = array();
			
			foreach($rankings as $ranking){
			
				
				
				$filteredRankings[$ranking->HWID][] = $ranking;
				$allnames = array();
				//if($ranking->mlevel == 800){
					$filteredRankings[$ranking->HWID][0]->names[] = array('name' => $ranking->name, 'level' => $ranking->total_level);
					
					
					
				//}
			}
			
	
			
			
			foreach($filteredRankings as $key => $filteredRanking){
				
				$newRankings[] = $filteredRanking[0];
			}
			
				
			
			
			foreach($newRankings as $newRanking){
				$filteredByNameRankings[$newRanking->name][] = $newRanking;
			}
			
			$excludedNames = [];
			foreach($filteredByNameRankings as $k => $filteredByNameRanking){
				foreach($filteredByNameRanking[0]->names as $names) {
					if($k != $names['name']) {
						$excludedNames[] = $names['name'];
					}
				}
				if(!in_array($k, $excludedNames)) {
					$finalRankings[] = $filteredByNameRanking[0];
				}
				
				
				
				
			}
			
				//echo '<pre>';
				//var_dump($finalRankings);
				//echo '</pre>';die();
			//echo '<pre>';
			//dd($finalRankings);
			//echo '</pre>';
			if($order == 's15'){
		
				
				//foreach($finalRankings as $k => $jewelPrize){
					//if($jewelPrize->total_level == 1200){
						//echo $k . ' '.$jewelPrize->memb___id .'<br>';
						//$this->account->addJewels($jewelPrize->memb___id);
						//$this->account->addJewels($jewelPrize->memb___id);
						//$this->account->addJewels($jewelPrize->memb___id);
					//}
					
				//}
				//die();

				//return $finalRankings;
				return array_slice($finalRankings,0,100);
			}else{
				//return $finalRankings;
				return array_slice($finalRankings,0,100);
				
			}
			
	}
	
	public function getTop2($order = 'mlevel')
	{
		$orderlimit = array('mlevel', 'pkcount', 'winduels', 's15');
		if(!in_array($order, $orderlimit))
			die();

			if($order == 's15'){
				$rankings =  DB::connection('s15')->table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, mlExperience, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->distinct()
				->leftJoin('ConnectionHistory', 'character.AccountID', '=', 'ConnectionHistory.AccountID')
				->leftJoin('MEMB_INFO', 'character.AccountID', '=', 'MEMB_INFO.memb___id')
				->leftJoin('IGC_Gens', 'character.name', '=', 'IGC_Gens.name')
				//->leftJoin('T_InGameShop_Point', 'character.AccountID', '=', 'T_InGameShop_Point.AccountID')
				->where('ctlcode', 0)
				//->where('mdate','>', '2020-1-10 20:00:00')
				->where('ConnectionHistory.HWID', '!=', 'N/A')
				->where('IGC_Gens.Influence', '!=', '1')
				//->where('character.Name', '!=', 'Aldos')
				//->where('character.Name', '!=', 'JUDAS')
				//->where('MEMB_INFO.memb_guid', '>', '2565')
				->orderby('total_level', 'desc')
				->orderby('mlExperience', 'desc')
				->limit(200)
				->get();
			}else{
				$rankings =  DB::connection('s15')->table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, character.name,mlevel, mlExperience, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID, IGC_Gens.Name as gensname'))
				->distinct()
				->leftJoin('ConnectionHistory', 'character.AccountID', '=', 'ConnectionHistory.AccountID')
				->leftJoin('MEMB_INFO', 'character.AccountID', '=', 'MEMB_INFO.memb___id')
				//->leftJoin('T_InGameShop_Point', 'character.AccountID', '=', 'T_InGameShop_Point.AccountID')
				->leftJoin('IGC_Gens', 'character.name', '=', 'IGC_Gens.Name')
				->where('ctlcode', 0)
				//->where('mdate','>', '2020-1-10 20:00:00')
				->where('ConnectionHistory.HWID', '!=', 'N/A')
				->where('IGC_Gens.Influence', '=', '1')
				->where('IGC_Gens.name', '!=', 'Makisig')
				->where('IGC_Gens.name', '!=', 'Aragorn')
				//->where('character.Name', '!=', 'Aldos')
				//->where('character.Name', '!=', 'JUDAS')
				//->where('MEMB_INFO.memb_guid', '>', '2565')
				->orderby('total_level', 'desc')
				->orderby('mlExperience', 'desc')
				->limit(200)
				->get();
			}
			

			$filteredRankings = array();
			$newRankings = array();
			$filteredByNameRankings = array();
			$finalRankings = array();
			
			foreach($rankings as $ranking){
			
				
				
				$filteredRankings[$ranking->HWID][] = $ranking;
				$allnames = array();
				//if($ranking->mlevel == 800){
					$filteredRankings[$ranking->HWID][0]->names[] = array('name' => $ranking->name, 'level' => $ranking->total_level);
					
					
					
				//}
			}
			
	
			
			
			foreach($filteredRankings as $key => $filteredRanking){
				
				$newRankings[] = $filteredRanking[0];
			}
			
				
			
			
			foreach($newRankings as $newRanking){
				$filteredByNameRankings[$newRanking->name][] = $newRanking;
			}
			
			$excludedNames = [];
			foreach($filteredByNameRankings as $k => $filteredByNameRanking){
				foreach($filteredByNameRanking[0]->names as $names) {
					if($k != $names['name']) {
						$excludedNames[] = $names['name'];
					}
				}
				if(!in_array($k, $excludedNames)) {
					$finalRankings[] = $filteredByNameRanking[0];
				}
				
				
				
				
			}
			
				//echo '<pre>';
				//var_dump($finalRankings);
				//echo '</pre>';die();
			//echo '<pre>';
			//dd($finalRankings);
			//echo '</pre>';
			if($order == 's15'){
		
				
				//foreach($finalRankings as $k => $jewelPrize){
					//if($jewelPrize->total_level == 1200){
						//echo $k . ' '.$jewelPrize->memb___id .'<br>';
						//$this->account->addJewels($jewelPrize->memb___id);
						//$this->account->addJewels($jewelPrize->memb___id);
						//$this->account->addJewels($jewelPrize->memb___id);
					//}
					
				//}
				//die();
				
				//return $finalRankings;
				//return array_slice($finalRankings,0,100);
			}else{
				//return $finalRankings;
				//return array_slice($finalRankings,0,100);
				
			}
			//dd($finalRankings);
			foreach($finalRankings as $finalRanking) {
				//dd($finalRanking->names[0]['name']);
				echo $finalRanking->names[0]['name'] . ' - ' .$finalRanking->names[0]['level'] . '<br>';
					
				}
			
	}
	
	public function getTopPrizes($order = 'mlevel')
	{
		$orderlimit = array('mlevel', 'pkcount', 'winduels', 's15');
		if(!in_array($order, $orderlimit))
			die();

			if($order == 's15'){
				$rankings =  DB::connection('s15')->table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw('GoblinPoint, MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->distinct()
				->leftJoin('ConnectionHistory', 'character.AccountID', '=', 'ConnectionHistory.AccountID')
				->leftJoin('MEMB_INFO', 'character.AccountID', '=', 'MEMB_INFO.memb___id')
				->leftJoin('T_InGameShop_Point', 'character.AccountID', '=', 'T_InGameShop_Point.AccountID')
				->where('ctlcode', 0)
				//->where('mdate','>', '2020-1-10 20:00:00')
				->where('ConnectionHistory.HWID', '!=', 'N/A')
				->where('character.Name', '!=', 'Aldos')
				->where('MEMB_INFO.memb_guid', '>', '859')
				->orderby('total_level', 'desc')
				->get();
			}else{
				$rankings =  DB::table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw(' GoblinPoint, MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->distinct()
				->leftJoin('ConnectionHistory', 'character.AccountID', '=', 'ConnectionHistory.AccountID')
				->leftJoin('MEMB_INFO', 'character.AccountID', '=', 'MEMB_INFO.memb___id')
				->leftJoin('T_InGameShop_Point', 'character.AccountID', '=', 'T_InGameShop_Point.AccountID')
				->where('ctlcode', 0)
				//->where('mdate','>', '2020-1-10 20:00:00')
				->where('ConnectionHistory.HWID', '!=', 'N/A')
				->where('MEMB_INFO.memb_guid', '>', '859')
				->orderby('GoblinPoint', 'desc')
				->orderby('total_level', 'desc')
				->get();
			}
			
			
			//return  $rankings;
			
			
			foreach($rankings as $ranking){
			
				
				
				$filteredRankings[$ranking->HWID][] = $ranking;
				$allnames = array();
				//if($ranking->mlevel == 800){
					$filteredRankings[$ranking->HWID][0]->names[] = array('name' => $ranking->name, 'level' => $ranking->total_level);
					
					
					
				//}
			}
			
			
			
			foreach($filteredRankings as $key => $filteredRanking){
				
				$newRankings[] = $filteredRanking[0];
			}
			
			
			
				
			
			
			foreach($newRankings as $newRanking){
				$filteredByNameRankings[$newRanking->name][] = $newRanking;
			}
			

			
			
			foreach($filteredByNameRankings as $filteredByNameRanking){
				foreach($filteredByNameRanking as $k => $v){
					$finalRankings[] = $v;
				}
				
			}
			
				echo '<pre>';
				var_dump($finalRankings);
				echo '</pre>';die();
			if($order == 's15'){
		
				
				foreach($finalRankings as $k => $jewelPrize){
					
					//if($jewelPrize->total_level == 1200){
						//if($jewelPrize->memb___id == 'pmuevent1'){
							
							echo $k . ' '.$jewelPrize->memb___id .' ';
							foreach($jewelPrize->names as $name){
								echo $name['name'] . ', ';
							}
							echo '<br>';
							
						//$this->account->addJewels($jewelPrize->memb___id);
						//$this->account->addJewels($jewelPrize->memb___id);
						//$this->account->addJewels($jewelPrize->memb___id);
							
						//}
						
					//}
					
				}
				die();
				return $finalRankings;
			}else{
				return array_slice($finalRankings,0,20);
				
			}
			
	}
	
	public function getDiedTimes($victim)
	{
		return DB::table('C_PlayerKiller_Info')->where('victim', $victim)->count();
		
	}
	
	public function getKillTimes($killer)
	{
		return DB::table('C_PlayerKiller_Info')->where('killer', $killer)->count();
		
	}
	
	public function getPkPenalty($killer)
	{
		$victims = DB::table('C_PlayerKiller_Info')
		->where('killer', $killer)
		->get();
		
		foreach($victims as $key => $victim){
			//var_dump($victim);
			//$id = $data['id'];
			$pktotal[$victim->Victim] = $victim;
			//var_dump($victim);
			//$charinfo = $this->account->getCharacterInfoByName($victim->Victim);
			//var_dump($charinfo);
			
		}
		//var_dump($pktotal);
		//return $penalties;
	}
	
	public function getBloodCastleRankings()
	{
		return DB::connection('sqlsrv_rankings')->table('EVENT_INFO_BC_5TH')
			->select(DB::raw('top 20 SUM (Point*PlayCount) as totalpoints, CharacterName'))
			->groupBy('CharacterName')
			->orderBy('totalpoints', 'desc')
			->get();
			return array();
		
	}
	
	public function getDevilSquareRankings()
	{
		return DB::connection('sqlsrv_rankings')->table('EVENT_INFO')
			->where('Square', 6)
			->orderBy('Point', 'desc')
			->get();
	}
	
	public function getGensRankings()
	{
		return DB::connection('s15')->table('IGC_Gens')

		//	->select(DB::raw('top 20 SUM (Point*PlayCount) as totalpoints, CharacterName'))
			//->groupBy('CharacterName')
			->orderBy('Points', 'desc')
			->limit(20)
			//->remember(10)
			->get();
		
	}
	
	public function get2015TopPlayer($order)
	{
		if($order == '2015top'){
			return DB::table('character')
			->select(DB::raw('TOP 20 name,mlevel, clevel'))
			->where('ctlcode', 0)
			->where('name', '!=', 'DL[Mamen]')
			->where('mdate','>=', '2015')
			->orderBy('clevel', 'desc')
			->orderBy('mlevel', 'desc')
			//->remember(10)
			->get();

		}
	}
	
	public function giveBlessSoulUser($username)
	{
		$checkUser = DB::table('MEMB_INFO')->where('memb___id', $username)->first();

		if(isset($checkUser->memb___id)){
			echo  $checkUser->memb___id . '<br>';
			$this->account->addJewels($checkUser->memb___id);
			$this->account->addJewels($checkUser->memb___id);
			$this->sendUserCoin($checkUser->memb___id, 1000);
		}
		

	}
	
	public function giveBlessSoul()
	{
		$allonline = DB::table('MEMB_INFO')->get();
		
		foreach($allonline as $k => $v){
			
			
			//if($v->memb___id == 'admin'){
				
				$currentcoins = $this->account->getCoinsByUsername($v->memb___id);
				
				$gptowc = 0;
				if($currentcoins->GoblinPoint > 10) {
					$gptowc = round($currentcoins->GoblinPoint / 10);
					$newc = $currentcoins->WCoin+$gptowc;
					echo $k.' currentWC: '. $currentcoins->WCoin . ' - ' .$v->memb___id . ' - GP - ' .$gptowc.  ' new wc  ' . $newc  .'<br>' ;
					//$this->sendUserCoin($v->memb___id, $gptowc);
					
					
					
				}
				
				
				
				//$this->account->addJewels($v->memb___id);
				//
			//}
			//$this->account->addStartingSeals($v->memb___id);
			
			
	
		}
	}
	
	public function giveAllOnlineCoins()
	{
		$allonline = DB::table('MEMB_STAT')->where('ConnectStat', 1)->get();
		
		foreach($allonline as $k => $v){
			
			
			//if($v->memb___id == 'admin'){
				echo $k.' '. $v->memb___id . '<br>';
				//$this->sendUserCoin($v->memb___id, 1000);
			//}
			//$this->account->addStartingSeals($v->memb___id);
			
			
	
		}
	}
	
	public function giveAllDemons()
	{
		$allonline = DB::table('MEMB_INFO')
		->get();
		
		$lvl801Char = DB::table('Character')->select('name', 'AccountID')
		->where('mLevel', '>', 401)
		->get();
		foreach($lvl801Char as $v){
			$username[] =  $v->AccountID;
		}
		$filteredUsername = array_values(array_unique($username));
		//dd($filteredUsername);
		//dd('123');
		
		foreach($filteredUsername as $v){
			
			//if( $v == 'admin') {
				$item1 = array(
					'AccountID' => $v,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 1114,
					'UniqueID3' => 1653,
					'InventoryType' => 1,
				);
				
				$item2 = array(
					'AccountID' => $v,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 1107,
					'UniqueID3' => 1639,
					'InventoryType' => 1,
				);
				
				$item3 = array(
					'AccountID' => $v,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 1108,
					'UniqueID3' => 1641,
					'InventoryType' => 1,
				);
				
				$item4 = array(
					'AccountID' => $v,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 1109,
					'UniqueID3' => 1643,
					'InventoryType' => 1,
				);
				
				$item5 = array(
					'AccountID' => $v,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 1106,
					'UniqueID3' => 1637,
					'InventoryType' => 1,
				);
				
				$item6 = array(
					'AccountID' => $v,
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 1113,
					'UniqueID3' => 1651,
					'InventoryType' => 1,
				);



				
				//DB::table('T_InGameShop_Items')->insert($item1);
				//DB::table('T_InGameShop_Items')->insert($item2);
				//DB::table('T_InGameShop_Items')->insert($item3);
				//DB::table('T_InGameShop_Items')->insert($item4);
				//DB::table('T_InGameShop_Items')->insert($item5);
				//DB::table('T_InGameShop_Items')->insert($item6);

				echo $v . '<br>';
			//}
		}
	}
	
	
	public function sendUserCoin($username, $value)
	{
		$currentcoins = $this->account->getCoinsByUsername($username);
		if($currentcoins !== NULL){
			DB::table('T_InGameShop_Point')->where('AccountID', $username)->update(array('WCoin' => $currentcoins->WCoin + $value));
		}
		$addedcoins = $this->account->getCoinsByUsername($username);
		echo 'Add coins complete. Current user coins: '.$username . ' - '. $addedcoins->WCoin;
	}
	
	public function resetEnhanceMl()
	{
		ini_set('MAX_EXECUTION_TIME', 1000);
		$characters = DB::table('Character')->select('Name', 'mlevel')
		
		->where('mlevel', '>=', 400)

		
		->get();
		echo count($characters);
		foreach($characters as $character)
		{
			$enhancePoints = $character->mlevel - 400; 
			
			echo $character->Name . ' mlevel: '. $character->mlevel . ' add to enhance'. $enhancePoints .'<br>';
			
			DB::table('Character')
            ->where('name', $character->Name)
            ->update(['i4thSkillPoint' => $enhancePoints, 'mlNextExp' => 0]);
		}
	}
	
	public function importCharacter()
	{
		$members = DB::table('MEMB_INFO')->get();
		
		//import memb___id
		foreach($members as $member)
		{
			$checkuser = DB::connection('s13p2')->table('MEMB_INFO')->where('memb___id', $member->memb___id)->count();
			
			if($checkuser == 0){
				/*$import = DB::connection('s13p2')->table('MEMB_INFO')
				->insert(array(
				'memb___id' => $member->memb___id, 
				'memb__pwd' => $member->memb__pwd, 
				'memb_name' => $member->memb_name,
				'sno__numb' => $member->sno__numb,
				'bloc_code' => $member->bloc_code,
				'ctl1_code' => $member->ctl1_code,
				));*/
			}

		}
		
		$characters  = DB::table('Character')->get();
		
		foreach($characters as $character)
		{
			$checkchar = DB::connection('s13p2')->table('Character')->where('name', $character->name)->count();
			
			if($checkuser == 0){
				$importChar = DB::connection('s13p2')->table('Character')
				->insert((array) $character);
			}
		}
		
		/*DB::table('users')->insert(
    array('email' => 'john@example.com', 'votes' => 0));
	}*/
	}
	
	public function betaWinner(){
		$beta = DB::connection('beta')->table('Character')
		->select('Character.AccountID', DB::raw('count(HWID) as hwids'))
		->leftJoin('ConnectionHistory', 'ConnectionHistory.AccountID', '=', 'Character.AccountID')
		->where('mlevel', '>=', '400')
		->groupBy('Character.AccountID')
		->get();
		
		foreach($beta as $k => $v){

			$betaChar = DB::connection('beta')->table('MEMB_INFO')->where('memb___id', $v->AccountID)->first();
			
			$officialChar = DB::table('MEMB_INFO')->where('memb___id', $betaChar->memb___id)->count();
			echo 'inserting - ' . $betaChar->memb___id . '<br>';
			if($officialChar == 0) {
				
				DB::table('MEMB_INFO')->insert(array('memb___id' => $betaChar->memb___id, 'memb__pwd' => $betaChar->memb__pwd, 'memb_name' => $betaChar->memb___id, 'sno__numb' => $betaChar->sno__numb, 'mail_addr' => $betaChar->mail_addr, 'fpas_ques' => $betaChar->fpas_ques, 'fpas_answ' => $betaChar->fpas_answ, 'bloc_code' =>  $betaChar->bloc_code, 'ctl1_code' => $betaChar->ctl1_code));
				DB::table('T_InGameShop_Point')->insert(array('AccountID' => $betaChar->memb___id, 'WCoin' => 100));
				DB::table('warehouse')->insert(array('AccountID' => $betaChar->memb___id, 'Money' => '100000000'));
				echo 'saved<br>';
			}
		}
		
	}
	
	public function chaosCastleRankings()
	{
		return DB::connection('sqlsrv_rankings')->table('EVENT_INFO_CC')
			->orderBy('Wins', 'desc')
			->limit(20)
			->get();
	}
	
	public function getClassRanking($class)
	{
		return $rankings =  DB::connection('s15')->table('character')
				//->select(DB::raw('MEMB_INFO.memb___id, character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level, ConnectionHistory.HWID'))
				->select(DB::raw(' character.AccountID, name,mlevel, winduels, loseduels, pkcount, (mlevel+clevel) AS total_level'))
				->where('ctlcode', 0)	->where('id', '>', '15127')
							->where('class', '135')
				//->where('MEMB_INFO.memb_guid', '>', '859')
				->orderby('total_level', 'desc')
				->get();
				
	}
	
	public function getMonthlyOnline(){
		$online =  DB::connection('s15')->table('ConnectionHistory')->where('Date', '>=', '2021-12-01')->get();
		
		$uniqueUser = [];
		foreach($online as $ol) {
			if($ol->HWID != 'N/A') {
			$uniqueUser[$ol->HWID][] = $ol->AccountID;
			}
		}
		
		foreach($uniqueUser as $k => $v) {
	
			$charname = DB::connection('s15')->table('character')->where('AccountID', $v[0])->orderBy('mlevel', 'desc')->pluck('name');
			echo $charname.'<br>';
			
		}
		echo '<pre>';
		//dd($charname);
	}

}
