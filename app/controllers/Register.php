<?php 

class Register extends BaseController
{
	public function index()
	{
		return View::make('register');
		
	}
	
	public function addUser()
	{
		
		
		$postdata = Input::get();
		
		$validator = Validator::make(
			$postdata,
			array(
				'username' => 'required|min:6|max:10',
				'password' => 'required|min:6|max:10',
				'email' => 'required|email|max:50',
				//'secreta' => 'required||min:4|max:50',
			)
		);
		
		if($validator->fails()){
			$this->api->callback = 'Something went wrong.';
			$this->api->validator = $validator->messages();
			return json_encode($this->api);
			
		}else{
			$checkuser = DB::table('memb_info')
			->where('memb___id', $postdata['username'])
			->orwhere('mail_addr', $postdata['email'])
			->get();
			
			$checkusers1 = DB::connection('s1')->table('memb_info')
			->where('memb___id', $postdata['username'])
			->orwhere('mail_addr', $postdata['email'])
			->get();
			
			$userinput = array(
				'memb___id' => Input::get('username'),
				'memb_name' => Input::get('username'),
				'memb__pwd' => Input::get('password'),
				'mail_addr' => Input::get('email'),
				'fpas_ques' => Input::get('secretq'),
				'fpas_answ' => Input::get('secreta'),
				'sno__numb' => '1111111111111',
				'bloc_code' => 0,
				'ctl1_code' => 0,
			);
			
			$s1Message = '';
			if(empty($checkusers1)){
				DB::connection('s1')->table('memb_info')->insert($userinput);
				$s1Message = 'Season 1 Account Created, but ';
			}
			
			if(!empty($checkuser)){
				$this->api->callback = $s1Message. 'Username or Emaill address exist. Please Try again';
				return json_encode($this->api);
			}else{
				
				
				//DB::table('T_InGameShop_Point')->insert(array('AccountID' => Input::get('username'), 'WCoinC' => '200000000', 'WCoinP' => '200000000', 'GoblinPoint' => '200000000'));
				
				//DB::table('memb_info')->insert($userinput);
				DB::connection('s15')->table('memb_info')->insert($userinput);
				DB::connection('s17')->table('memb_info')->insert($userinput);
				//DB::connection('s1')->table('memb_info')->insert($userinput);
				//DB::connection('s15')->table('T_InGameShop_Point')->insert(array('AccountID' => Input::get('username'), 'WCoin' => '200000000', 'GoblinPoint' => '200000000'));
				
				$seals = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 9,
					'UniqueID3' => 11,
					'InventoryType' => 1,
				);
				
				$sealshealing = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 38,
					'UniqueID3' => 63,
					'InventoryType' => 1,
				);
				
				$sealspet = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 60,
					'UniqueID3' => 100,
					'InventoryType' => 1,
				);
				
				$sealsring = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 59,
					'UniqueID3' => 98,
					'InventoryType' => 1,
				);
				
				$masterseals = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 50,
					'UniqueID3' => 78,
					'InventoryType' => 1,
				);
				
				$smallwings_disaster = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 53,
					'UniqueID3' => 86,
					'InventoryType' => 1,
				);
				$smallwings_fairy = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 54,
					'UniqueID3' => 88,
					'InventoryType' => 1,
				);
				$smallwings_heaven = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 55,
					'UniqueID3' => 90,
					'InventoryType' => 1,
				);
				$smallwings_satan = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 56,
					'UniqueID3' => 92,
					'InventoryType' => 1,
				);
				$smallwings_cloak = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 283,
					'UniqueID3' => 352,
					'InventoryType' => 1,
				);
				
				$smallwings_cape = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 392,
					'UniqueID3' => 461,
					'InventoryType' => 1,
				);
				
				$smallwings_limit = array(
					'AccountID' => Input::get('username'),
					'AuthCode' =>0,
					'UniqueID1' => 673,
					'UniqueID2' => 393,
					'UniqueID3' => 462,
					'InventoryType' => 1,
				);
				
				$vault = array(
					'AccountID' => Input::get('username'),
					'Money' =>'5000000'
				);
				
				$accountCharacter = array(
					'Id' => Input::get('username'),
					'WarehouseExpansion' => 1
				);
				
				//DB::table('T_InGameShop_Items')->insert($seals);
			//	DB::table('T_InGameShop_Items')->insert($sealshealing);
			//	DB::table('T_InGameShop_Items')->insert($sealspet);
			//	DB::table('T_InGameShop_Items')->insert($sealsring);
			//	DB::table('T_InGameShop_Items')->insert($masterseals);
				//DB::table('T_InGameShop_Items')->insert($smallwings_disaster);
				//DB::table('T_InGameShop_Items')->insert($smallwings_fairy);
				//DB::table('T_InGameShop_Items')->insert($smallwings_heaven);
				//DB::table('T_InGameShop_Items')->insert($smallwings_satan);
				//DB::table('T_InGameShop_Items')->insert($smallwings_cloak);
				//DB::table('T_InGameShop_Items')->insert($smallwings_cape);
				//DB::table('T_InGameShop_Items')->insert($smallwings_limit);
				
				//DB::table('AccountCharacter')->insert($accountCharacter);
				
				
				//DB::table('Warehouse')->insert($vault);
				$this->api->callback = 1;
				$this->api->success = 1;
				return json_encode($this->api);
			}
		}
		
	}
	
	public function showAllCharacter($username)
	{
		$char =  DB::table('character')
		->select(DB::raw('name, (mlevel+clevel) AS total_level'))
		->where('AccountID', $username)
		->orderBy('total_level', 'desc')
		->first();
		
		return $char->name;
	}
	
	public function getAllUsersUniqueIp()
	{
		$checkonline = DB::table('memb_stat')
		->orWhere('ConnectTM', '>=','2018-12-31 20:00:00')
		->orWhere('DisConnectTM', '>=','2018-12-31 20:00:00')

		->get();
		
		
		$users = array();
		foreach($checkonline as $online){
				if(isset($users[$online->IP])){
					if($users[$online->IP] == $online->IP)
					{
						//$
					}
				}
				$users[$online->IP]['usernames'][] = $this->showAllCharacter($online->memb___id);//array('usernames' => );
				//echo $online->memb___id.'<br>';
			}
			echo '<pre>';
		//var_dump($users);
		echo '</pre>';
	//	die();
	echo count($users).'<br>';
		foreach($users as $user)
		{
			echo implode(', ', $user['usernames']) . '<br>';
		}
	}
	public function addCoin(){
		$checkonline = DB::table('memb_stat')->where('ConnectStat', 1)->where('ServerName', 'Server1')->get();

		echo count($checkonline).'<br>';
		//var_dump($checkonline);
		foreach($checkonline as $online){
			$onlinecoin = DB::table('T_InGameShop_Point')->where('AccountID', $online->memb___id)->get();
			//echo count($onlinecoin);
			foreach ($onlinecoin as $coin){
				echo $coin->AccountID.' | '.$coin->WCoin.'<br>';
				//echo $coin->AccountID.'<br>';
				//var_dump($coin);
				//if($coin->AccountID == 'admin'){
				//DB::table('T_InGameShop_Point')->where('AccountID', $coin->AccountID)->update(array('Wcoin' => $coin->WCoin+1000));
				//}
			}
			//echo $onlinecoin->wcoinc.'<br>';
			
			
		}
		
	}
	
	public function changeOldPassword()
	{
		$oldlogins = DB::table('memb_stat')->whereRaw("ConnectTM <= '2014-12-28'")->get();
		//var_dump(count($oldlogins));die();
		foreach($oldlogins as $oldlogin){
			
			//echo $oldlogin->memb___id.'<br>';
			$accounts = DB::table('memb_info')->select('memb___id', 'memb__pwd')->where('memb___id', $oldlogin->memb___id)->get();
			foreach($accounts as $account){
				//echo $account->memb___id.' '. $account->memb__pwd.'<br>';
				/*DB::table('memb_info')
				->where('memb___id', $account->memb___id)
				->update(array('memb__pwd' => 'pig'.$account->memb__pwd.'sisiw'));*/
			}
		}
		
	}
	
	public function addseals($username)
	{
		$account = new AccountModel;
		return $account->addStartingSeals($username);
	}
	
	public function add1DaySetOfSeals()
	{

		$account = new AccountModel;
		//$account->add1DaySeals('admin01');
		//$checkonline = DB::table('memb_stat')->where('ConnectStat', 1)->get();
		$checkonline = DB::table('memb_info')->get();
		//$account->add1daywealth('wizmeluck');
		foreach($checkonline as $online){
			echo $online->memb___id.'<br>';
			$account->add1daywealth($online->memb___id);
		}
	}
}