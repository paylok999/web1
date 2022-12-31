<?php

class Api extends BaseController 
{
	public function showAllOnline()
	{
		//return DB::table('memb_stat')->where('ConnectStat', 1)->get();
		return DB::select(DB::raw('select * from MEMB_STAT where ConnectTM between DATEADD(hh, -24, GETDATE()) and GETDATE()'));
		
	}
}
