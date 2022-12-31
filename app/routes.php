<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});
/*server feature*/
Route::get('/addonlinecoins', 'Character@giveAllOnlineCoins');
Route::get('/monthlyonline', 'Character@getMonthlyOnline');
Route::get('/addonlinedemon', 'Character@giveAllDemons');
Route::get('/addblesssoul', 'Character@giveBlessSoul');
Route::get('/addblesssoul/user/{username}', 'Character@giveBlessSoulUser');
//Route::get('/addcoin/{username}/{coin}', 'Character@sendUserCoin');

/*API new routers*/
Route::get('/api/allonline', 'Api@showAllOnline');
Route::get('/betawinner', 'Character@betaWinner');

Route::post('/api/register', 'Register@addUser');
Route::get('/api/showuser', 'Authenticate@login');
Route::get('/api/character/rankings/{order?}', 'Character@getTop');
Route::get('/api/character/rankings2/{order?}', 'Character@getTop2');
Route::get('/api/character/rankingsprizes/{order?}', 'Character@getTopPrizes');
Route::get('/api/character/goblinpoints', 'Character@getGoblinPoints');
Route::get('/api/character/rankings2015/{order?}', 'Character@get2015TopPlayer');
Route::get('/api/character/classraking/{class?}', 'Character@getClassRanking');
Route::get('/api/character/bloodcastle', 'Character@getBloodCastleRankings');
Route::get('/api/character/chaoscastle', 'Character@chaosCastleRankings');
Route::get('/api/character/devilsquare', 'Character@getDevilSquareRankings');
Route::get('/api/character/gens', 'Character@getGensRankings');
Route::get('/resetenhance', 'Character@resetEnhanceMl');
Route::get('/importchar', 'Character@importCharacter');

Route::get('/api/account/byid/{id}', 'Authenticate@showById');
Route::get('/api/account/byusername/{id}', 'Authenticate@showByCredentials');

/* - Member details - */
Route::get('/api/user/allcharacter/{username}', 'Account@showAllCharacter');
Route::get('/api/user/characterinfo/{name}', 'Account@getCharacterDetailsInfo');
Route::get('/api/user/characterpk/{name}', 'Account@getCharacterDetailsPk');
Route::get('/api/user/coininfo/{username}', 'Account@getCoinTransferForm');
Route::get('/api/user/vip/{username}', 'Account@checkVIPStatus');
Route::post('/api/user/enrollvip', 'Account@enrollVip');
Route::post('/api/user/changepassword/{username}', 'Account@changePassword');
Route::post('/api/user/cointransfer', 'Account@transferCoin');
Route::post('/api/user/cointransferp', 'Account@transferCoinP');
Route::post('/api/user/msreset', 'Account@resetMSReset');
Route::post('/api/user/statreset', 'Account@resetStats');
Route::post('/api/user/unstock', 'Account@unstockCharacter');
Route::post('/api/user/rename', 'Account@renameChar');
Route::post('/api/minuscoin', 'Account@minusCoin');

Route::post('/api/gremory', 'Account@insertIntoGremory');

/* - Shop - */
Route::get('/api/shop/allitems', 'Shop@showItems');
Route::post('/api/shop/additem', 'Shop@addCart');
Route::get('/api/shop/allitemsbyusername/{username}', 'Shop@getAllItemsByUsername');
Route::delete('/api/shop/delete/{id}', 'Shop@deleteItem');
/* - API END - */

Route::get('/', 'Home@showIndex');
Route::get('register', 'Home@showIndex');
Route::post('register', 'Register@addUser');
Route::get('pluscoin', 'Register@addCoin');
Route::get('user/unique', 'Register@getAllUsersUniqueIp');
//Route::get('oldlogin', 'Register@changeOldPassword');
Route::get('addseals/{username}', 'Register@addseals');
Route::get('addseal/1day', 'Register@add1DaySetOfSeals');
Route::post('authenticate', 'Authenticate@login');
Route::get('logout', 'Authenticate@logout');
Route::get('character/rankings/{order?}', 'Character@getTop');
Route::get('character/rankings2015/{order?}', 'Character@get2015TopPlayer');

/*authenticated user */
Route::group(array('before' => 'auth'), function()
{
	Route::post('account/changepassword', 'Account@changePassword');
	Route::get('account/character/{charname}', 'Account@getCharacterDetails');
	Route::get('account/characters', 'Account@getAllCharacter');
	Route::get('account/coins', 'Account@getCoinTransferForm');
	Route::post('account/coins', 'Account@transferCoin');
	Route::post('/account/msreset', 'Account@resetMSReset');
	Route::post('/account/statreset', 'Account@resetStats');
	Route::post('/account/unstock', 'Account@unstockCharacter');
	
	/*shopping*/
	Route::get('/shop', 'Shop@show');
	Route::post('/shop', 'Shop@addCart');
	Route::get('/shop/checkout', 'Shop@getAllItemsByUsername');
	Route::delete('/shop/delete', 'Shop@deleteItem');
	/*response*/
	Route::get('/shop/checkout/cancel', 'Shop@cancel');
	Route::get('/shop/checkout/complete', 'Shop@complete');
});


App::missing(function($exception)
{
	return Redirect::to('/');
	
});