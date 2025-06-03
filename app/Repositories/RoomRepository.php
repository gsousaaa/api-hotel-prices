<?php

namespace App\Repositories;

use DB;

class RoomRepository
{
    public function findCompanyRooms(int $companyId)
    {
        return DB::table('rooms as r')
            ->join('prices as p', 'p.room_id', '=', 'r.id')
            ->where('r.company_id', $companyId)
            ->select('r.*', 'p.*')
            ->get();
    }


}