<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function findCompanyRooms(int $companyId)
    {
        return Room::with('prices')
            ->where('company_id', $companyId)
            ->get();
    }

    public function findRoomById(int $roomId) {
        return Room::where('id', $roomId)->first();
    }

}