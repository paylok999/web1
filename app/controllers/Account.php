<?php

class Account extends BaseController
{
	public $msstatreset = 0;
	public $statreset = 10000;
	
	public function showAllCharacter($username)
	{
		return DB::table('character')
		->select(DB::raw('name, (mlevel+clevel) AS total_level'))
		->where('AccountID', $username)
		->orderBy('total_level', 'desc')
		->get();
	}
	
	public function changePassword($username)
	{
		$postdata = Input::get();
		
		$validator = Validator::make(
			$postdata,
			array(
				'oldpassword' => 'required|min:6|max:10',
				'newpassword' => 'required|min:6|max:10',
				'rnewpassword' => 'required|min:6|max:10',
			)
		);
		
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline';
			$this->api->validator = $validator->messages();
				return json_encode($this->api);
			
		}else{
			$userdetail = $this->getUserDetail($username);
			if($postdata['oldpassword'] != $userdetail->memb__pwd){
				$this->api->callback = 'Your input current password did not match on whats in our system. Please try again.';
				return json_encode($this->api);
			}else if($postdata['newpassword'] != $postdata['rnewpassword']){
				$this->api->callback = 'New password and Repeat new password did not match. Please Try again.';
				return json_encode($this->api);
			}else{
				
				DB::table('memb_info')
				->where('memb___id', $username)
				->update(array('memb__pwd' => ($postdata['newpassword'])));

				DB::connection('s15')->table('memb_info')
				->where('memb___id', $username)
				->update(array('memb__pwd' => ($postdata['newpassword'])));

				$this->api->callback = 1;
				return json_encode($this->api);
			}
		}
	}
	
	public function getUserDetail($username)
	{
		return DB::table('memb_info')->where('memb___id', $username)->first();
	}
	
	/* load transfer coin modules*/
	public function getCoinTransferForm($username)
	{
		return json_encode($this->account->getCoinsByUsername($username));
	}
	
		/* load transfer coin modules*/
	public function checkVIPStatus($username)
	{
		$vip =  DB::table('T_VIPList')->where('AccountID', $username)->first();
		
		if($vip == null){
			$c = new StdClass;
			$c->AccountID = $username;
			$c->Type = 0;
			$c->notfound = 1;
			return json_encode($c);
		}
		return json_encode($vip);
	}
	
	public function enrollVip()
	{
		/*$this->api->callback = 'This feature is disabled until further notice'; //. $validator->messages()
		return json_encode($this->api);
		die();*/
		$postdata = Input::get();
		$vip_prices = array(1 => 100, 3 => 250, 7 => 600, 30 => 2500);

		if(isset($vip_prices[$postdata['days']]))
		{
			$vip_charge = $vip_prices[$postdata['days']];
		}else{
			$this->api->callback = 'Invalid Price. Please Try again.';
			return json_encode($this->api);
		}

		$current_coin = $this->account->getCoinsByUsername($postdata['username']);
		
		if(isset($current_coin->WCoin))
		{
			if($current_coin->WCoin < $vip_charge){
				$this->api->callback = 'Insuficient Wcoin Fund. Load Wcoin First.';
				return json_encode($this->api);
			}else{
				$currentVip = json_decode($this->checkVIPStatus($postdata['username']));

				if($currentVip->Type == 0)
				{
					//echo 'no existing vip<br>';
					
					$subscription_date = new DateTime('+'.$postdata['days'].' day');
					
					if(!isset($currentVip->notfound))
					{
						DB::table('T_VIPList')
						->where('AccountID', $postdata['username'])
						->delete();
					}

					DB::table('T_VIPList')
							->insert(array('Date' => $subscription_date->format('Y-m-d H:i:s a'), 'Type' => 1, 'AccountID' => $postdata['username']));
							
					DB::table('T_InGameShop_Point')
							->where('AccountID', $postdata['username'])
							->update(array('WCoin' => $current_coin->WCoin - $vip_charge));
							
					$this->api->callback = 1;
					return json_encode($this->api);
				}else{

					if(strtotime($currentVip->Date) < strtotime(date('Y-m-d H:i:s')))
					{
						echo 'expired vip. delete and insert new VIP';
						$subscription_date = new DateTime(date('Y-m-d H:i:s'));
						
						$new_subscription_date = $subscription_date->modify('+'.$postdata['days'].' day');
						DB::table('T_VIPList')->where('AccountID', $currentVip->AccountID)->delete();
						DB::table('T_VIPList')
							->insert(array('Date' => $new_subscription_date->format('Y-m-d H:i:s a'), 'Type' => 1, 'AccountID' => $postdata['username']));
							
						DB::table('T_InGameShop_Point')
								->where('AccountID', $postdata['username'])
								->update(array('WCoin' => $current_coin->WCoin - $vip_charge));
						$this->api->callback = 1;
						return json_encode($this->api);
							
					}else{
						$subscription_date = new DateTime($currentVip->Date);
						$new_subscription_date = $subscription_date->modify('+'.$postdata['days'].' day');
						
						DB::table('T_VIPList')
								->where('AccountID', $postdata['username'])
								->update(array('Date' => $new_subscription_date->format('Y-m-d H:i:s a'), 'Type' => 1));
								
						DB::table('T_InGameShop_Point')
								->where('AccountID', $postdata['username'])
								->update(array('WCoin' => $current_coin->WCoin - $vip_charge));
						$this->api->callback = 1;
						return json_encode($this->api);
					}

					
				}
			}
		}else{
			$this->api->callback = 'No Wcoins Found.';
			return json_encode($this->api);
		}
		$this->api->callback = 'test';
		return json_encode($this->api);
	}
	
	/*POST transfercoin*/
	public function transferCoin()
	{
		$merchant = array('rayjohn91', 'jae611', 'admin', 'pmutestbk', 'keekyow');
		
		
		$postdata = Input::get();
		
		$validator = Validator::make(
			$postdata,
			array(
				'username' => 'required|min:5|max:10',
				'receiverusername' => 'required|min:5|max:10',
				'amount' => 'required|integer|min:1',
				'userpassword' => 'required|min:6|max:10',
			)
		);
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline. reason:';
			$this->api->validator = $validator->messages();
			return json_encode($this->api);
			
		}else{
			$checkifonline = $this->account->checkAccountIfOnline($postdata['username']);
			$checkifonlinereceiver = $this->account->checkAccountIfOnline($postdata['receiverusername']);
			
			if(!in_array($postdata['username'], $merchant))
			{
				$this->api->callback = 'This Feature is disabled.';
				return json_encode($this->api);
			}

			if($postdata['username'] == null){
				$this->api->callback = 'Receiver username not found. Please try again';
				return json_encode($this->api);
			}else if($postdata['username'] == $postdata['receiverusername']){
				$this->api->callback = 'You cant send to yourself.';
				return json_encode($this->api);
			}else if($checkifonline->ConnectStat == 1){
				$this->api->callback = 'Your account is online. Please logout first in game.';
				return json_encode($this->api);
			}else if($checkifonlinereceiver->ConnectStat == 1){
				$this->api->callback = 'Receiver account is online. Please logout first in game.';
				return json_encode($this->api);
			}
			$checkuser = $this->account->getMemberAccountByUsername($postdata['receiverusername']);
			if($checkuser == NULL){
				$this->api->callback = 'Username not found. Please try again.';
				return json_encode($this->api);
			}else{
				$currentcoins = $this->account->getCoinsByUsername($postdata['username']);
				if($postdata['amount'] > $currentcoins->WCoin){
					$this->api->callback = 'You do not have this amount of coin. Please try again.';
					return json_encode($this->api);
				}else{
					$checksenderuser = $this->account->getMemberAccountByUsername($postdata['username']);
					//var_dump($checksenderuser->memb__pwd);
					if($postdata['userpassword'] != $checksenderuser->memb__pwd){
						$this->api->callback = 'You have entered invalid password. Please try again.';
						return json_encode($this->api);
					}else{
						$sendercoin = $this->account->getCoinsByUsername($postdata['username']);
						$receivercoin = $this->account->getCoinsByUsername($postdata['receiverusername']);
						/*minus sender*/
						DB::table('T_InGameShop_Point')
						->where('AccountID', $sendercoin->AccountID)
						->update(array('WCoin' => $sendercoin->WCoin - $postdata['amount']));
						/*add receiver*/
						DB::table('T_InGameShop_Point')
						->where('AccountID', $receivercoin->AccountID)
						->update(array('WCoin' => $receivercoin->WCoin + $postdata['amount']));
						$this->api->callback = 1;
						return json_encode($this->api);
					}
				}
				
			}
		}
		//var_dump(Input::all());
		//$this->account->getMemberAccountByUsername()
		//return 1;
	}
	
	public function resetMSReset()
	{
		$charname = Input::get();
		$validator = Validator::make(
			$charname,
			array(
				'username' => 'required|min:4|max:10',
				'charname' => 'required|min:4|max:10',
			)
		);
		$this->api->callback = 'This feature is currently disabled';
		return json_encode($this->api);
		//exit();
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline. reason: '. $validator->messages();
			return json_encode($this->api);
			
		}else{
			$charinfo = $this->account->getCharacterDetailsByName($charname['charname']);
			
			$checkifonline = $this->account->checkAccountIfOnline($charname['username']);

			if(isset($checkifonline->ConnectStat)){
				if($checkifonline->ConnectStat == 1){
					$this->api->callback = 'Your account is online. Please logout first in game.';
					return json_encode($this->api);
				}else if($charinfo == NULL){
					$this->api->callback = 'Character not found. Please try again.';
					return json_encode($this->api);
				}else if($charinfo->mlevel <= 0){
					$this->api->callback = 'Not enough level for master skill. Please choose different character';
					return json_encode($this->api);
					
				}else{
					$currentcoins = $this->account->getCoinsByUsername($charname['username']);
					if($currentcoins->WCoin < $this->msstatreset){
						$this->api->callback = 'You do not have this amount of coin. Please try again.';
						return json_encode($this->api);
					}else{
						//minus coin
						$usercoin = $this->account->getCoinsByUsername($charname['username']);
						//DB::table('T_InGameShop_Point')
						//->where('AccountID', $usercoin->AccountID)
						//->update(array('WCoin' => $usercoin->WCoin - $this->msstatreset));
						
						$majesticlevel = 0;
						$masterlevel = $charinfo->mlevel;
						if($charinfo->mlevel > 400) {
							$majesticlevel = $charinfo->mlevel - 400;
							$masterlevel = 400;
						}
						//echo $masterlevel;
						//echo '<br>';
						//dd($majesticlevel);
						//reset msstat
						DB::table('character')
						->where('name', $charname['charname'])
						->update(array('MagicList' => DB::raw('Convert(varbinary(60),NULL)'), 'mlPoint' => $masterlevel, 'i4thSkillPoint' => $majesticlevel, 'AddStrength' => 0, 'AddDexterity' => 0, 'AddVitality' => 0, 'AddEnergy' => 0));
						
						$this->api->callback = 1;
						return json_encode($this->api);
					}
				}
			}else{
				$this->api->callback = 'Login Your Character First for initial Login. then logout';
				return json_encode($this->api);
			}
		}
	}
	/*character stat reset*/
	public function resetStats()
	{
		$charname = Input::get();
		$validator = Validator::make(
			$charname,
			array(
				'username' => 'required|min:4|max:10',
				'charname' => 'required|min:4|max:10',
			)
		);
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline. reason: '. $validator->messages();
			return json_encode($this->api);
			
		}else{
			$charinfo = $this->account->getCharacterInfoByName($charname['charname']);
			
			$checkifonline = $this->account->checkAccountIfOnline($charname['username']);
			if($checkifonline->ConnectStat == 1){
				$this->api->callback = 'Your account is online. Please logout first in game.';
				return json_encode($this->api);
			}else if($charinfo == NULL){
				$this->api->callback = 'Character not found. Please try again.';
				return json_encode($this->api);
			}else if($charinfo->clevel <= 150){
				return 'Not enough level for reset. Atleast level 150 is allowed. Please choose different character';
				
			}else{
				$currentcoins = $this->account->getCoinsByUsername($charname['username']);
				if($currentcoins->WCoinC < $this->statreset){
					$this->api->callback = 'You do not have this amount of coin. Please try again.';
					return json_encode($this->api);
				}else{
					//minus coin
					$usercoin = $this->account->getCoinsByUsername($charname['username']);
					DB::table('T_InGameShop_Point')
					->where('AccountID', $usercoin->AccountID)
					->update(array('WCoinC' => $usercoin->WCoinC - $this->statreset));
					
					$totalstats = ($charinfo->strength + $charinfo->dexterity + $charinfo->vitality + $charinfo->energy + $charinfo->leadership + $charinfo->leveluppoint) - 100;
					
					//reset stats
					DB::table('character')
					->where('name', $charname['charname'])
					->update(array('strength' => 25, 'dexterity' => 25, 'vitality' => 25, 'energy' => 25 , 'leveluppoint' => $totalstats));
					
					$this->api->callback = 1;
					return json_encode($this->api);
				}
			}
		}
	}
	/*show character details*/
	public function getCharacterDetailsInfo($charname)
	{
		if($this->account->getCharacterInfoByName($charname)){
			return json_encode($this->account->getCharacterInfoByName($charname));
		}else{
			$this->api->err = 1;
			return json_encode($this->api);
		}
	}
	
	public function getCharacterDetailsPk($charname)
	{
		return $this->account->getPKCountByCharname($charname);
	}
	
	public function unstockCharacter()
	{
		$charname = Input::get();
		$validator = Validator::make(
			$charname,
			array(
				'charname' => 'required|min:4|max:10',
			)
		);
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline. reason: '. $validator->messages();
			return json_encode($this->api);
			
		}else{
			$checkifonline = $this->account->checkAccountIfOnline($charname['username']);
			if($checkifonline->ConnectStat == 1){
				$this->api->callback = 'Your account is online. Please logout first in game.';
				return json_encode($this->api);
			}else{
				DB::table('character')->where('name', $charname['charname'])->update(array('mapnumber' => 0, 'MapPosX' => 140, 'MapPosY' => 127));
				$this->api->callback = 1;
				return json_encode($this->api);
			}
		}
		
	}
	
	public function renameChar()
	{
		$this->api->callback = 'This feature is disabled until further notice'; //. $validator->messages()
		return json_encode($this->api);
		die();
		$charname = Input::get();
		$rename_charge = 2500;
		
		$validator = Validator::make(
			$charname,
			array(
				'current_name' => 'required|min:4|max:10',
				'new_name' => 'required|min:4|max:10',
			)
		);
		
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline.'; //. $validator->messages()
			return json_encode($this->api);
			
		}else{
			$check_newname = DB::table('character')->select(DB::raw('name'))->where('name', $charname['new_name'])->first();
			if(isset($check_newname->name))
			{
				$this->api->callback = "The name ".$charname['new_name']." already exist. Please try again with new name";
				return json_encode($this->api);
			}else{
				
				
				$current_coin = $this->account->getCoinsByUsername($charname['username']);
				//dd($current_coin);
				
				if(isset($current_coin->WCoin))
				{
					if($current_coin->WCoin < $rename_charge){
						$this->api->callback = 'Insuficient Wcoin Fund. Load Wcoin First.';
						return json_encode($this->api);
					}else{
						
						$checkifonline = $this->account->checkAccountIfOnline($charname['username']);
						if($checkifonline->ConnectStat == 1){
							$this->api->callback = 'Your account is online. Please logout first in game.';
							return json_encode($this->api);
						}else{
							$check_penta = DB::table('IGC_PentagramInfo')->select(DB::raw('CONVERT(varchar(255), PentagramInfo, 1) AS pentagram'))->where('name', $charname['current_name'])->first();
							$nopenta = '0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF';

							if($check_penta == NULL)
							{
								$check_penta = new StdClass;
								$check_penta->pentagram = $nopenta;
							}
	
							if($check_penta->pentagram == $nopenta){
								
								$accountChar_obj = DB::table('AccountCharacter')->select(DB::raw('GameID1, GameID2, GameID3, GameID4, GameID5, GameID6, GameID7, GameID8'))->where('Id', $charname['username'])->first();
								$accountChar_arr = (array) $accountChar_obj;
								
								foreach($accountChar_arr as $key => $value)
								{
									if(is_null($value) || $value == '')
									unset($accountChar_arr[$key]);
								}
								$accountChar = array_flip($accountChar_arr);
								
								DB::table('character')->where('name', $charname['current_name'])->update(array('name' => $charname['new_name']));
								DB::table('AccountCharacter')->where($accountChar[$charname['current_name']], $charname['current_name'])->update(array($accountChar[$charname['current_name']] => $charname['new_name']));
				
								DB::table('T_InGameShop_Point')
								->where('AccountID', $charname['username'])
								->update(array('WCoin' => $current_coin->WCoin - $rename_charge));
								
								$this->api->callback = 1;
								return json_encode($this->api);
							
							}else{
								$this->api->callback = 'Please remove your pentagram from your inventory, store and put it on your vault first';
								return json_encode($this->api);
							}
						}
						
					}
				}else{
					$this->api->callback = "No Wcoins found.";
					return json_encode($this->api);
				}
			}
			//dd($check_newname);
			$charname['current_name'];
			$charname['new_name'];
		}
		
		
	}
	
	public function minusCoin()
	{
		$postdata = Input::get();
		
		$validator = Validator::make(
			$postdata,
			array(
				'username' => 'required|min:5|max:10',
			)
		);
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline. reason:';
			$this->api->validator = $validator->messages();
			return json_encode($this->api);
			
		}else{
			$current_coin = $this->account->getCoinsByUsername($postdata['username']);

			if($current_coin->WCoin >= 500)
			{
				$checkifonline = $this->account->checkAccountIfOnline($postdata['username']);
				if($checkifonline->ConnectStat == 1){
					$this->api->callback = 'Your account is online. Please logout first in game.';
					return json_encode($this->api);
				}else{
					DB::table('T_InGameShop_Point')
					->where('AccountID', $postdata['username'])
					->update(array('WCoin' => $current_coin->WCoin - 500));
					$this->api->callback = 1;
					return json_encode($this->api);
				}
			}else{
				$this->api->callback = "Insuficient Wcoins.";
				return json_encode($this->api);
			}
		}
	}
	
	public function insertIntoGremory()
	{
		date_default_timezone_set('Asia/Manila');
		$postdata = Input::get();
		
		$validator = Validator::make(
			$postdata,
			array(
				'username' => 'required|min:5|max:10',
				'itemcode' => 'required|min:4|max:32',
			)
		);
		
		if($validator->fails()){
			$this->api->callback = 'Something went wrong. Please Check your inputs. Follow the guideline. reason:';
			$this->api->validator = $validator->messages();
			return json_encode($this->api);
			
		}else{
			$checkifonline = $this->account->checkAccountIfOnline($postdata['username']);
			if(!isset($checkifonline->ConnectStat)) {
				$this->api->callback = 'No account found or if your username is newly registered, please login and logout first';
				return json_encode($this->api);
			}
			if($checkifonline->ConnectStat == 1){
				$this->api->callback = 'Your account is online. Please logout first in game.';
				return json_encode($this->api);
			}else{
				
				$itemcode = explode(',',$postdata['itemcode']);
				$currentDate = date('Y-m-d H:i:s');
				$expiredDate = date('Y-m-d H:i:s', strtotime($currentDate . ' +1 day'));
				
				$serial = DB::table('IGC_GremoryCase')
				->orderBy('Serial', 'desc')->first();
				
				if(!isset($serial->Serial)) {
					$this->api->callback = 'Invalid Serial';
					return json_encode($this->api);
				}
				
				$insertGremory = array(
					'AccountID' => $postdata['username'],
					'Name' => '',
					'GCType' => 1,
					'GiveType' => 15,
					'ItemType' => $itemcode[0],
					'ItemIndex' => $itemcode[1],
					'Level' => 0,
					'ItemDur' => $itemcode[2],
					'Skill' => 0,
					'Luck' => 0,
					'Opt' => 0,
					'SetOpt' => 0,
					'NewOpt' => 0,
					'BonusSocketOpt' => 0,
					'SocketOpt1' => 255,
					'SocketOpt2' => 255,
					'SocketOpt3' => 255,
					'SocketOpt4' => 255,
					'SocketOpt5' => 255,
					'UsedInfo' => 0,
					'Serial' => $serial->Serial+1,
					'RecvDate' => $currentDate,
					'ReceiptDate' => $currentDate,
					'RecvExpireDate' => $expiredDate,
					//'ItemExpireDate' => $expiredDate,
					'ItemExpireDate' => '1970-01-01 00:00:00',
					'RecvDateConvert' => strtotime($currentDate),
					'RecvExpireDateConvert' => strtotime($expiredDate),
					//'ItemExpireDateConvert' => strtotime($expiredDate),
					'ItemExpireDateConvert' => 0,
					'ItemOptionEx' => 0,
					'ItemSerial' => 0,
					'OptSlot1' => 255,
					'OptSlot2' => 255,
					'OptSlot3' => 255,
					'ItemJOHOption' => 0,
				);
				//echo '<pre>';
				//dd($insertGremory);
				
				DB::table('IGC_GremoryCase')
				->insert($insertGremory);
	
				
				$this->api->callback = 1;
				return json_encode($this->api);
			}
		}
		
		
		
	}
}