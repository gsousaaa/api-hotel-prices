<?php

namespace App\Repositories;

use App\Models\Price;

class PriceRepository
{
    public function createPrice(array $data)
    {
        return Price::create($data);
    }

    public function findLastPrice(int $roomId)
    {
        return Price::where('room_id', $roomId)
            ->orderByDesc('effective_date')
            ->first();
    }

}