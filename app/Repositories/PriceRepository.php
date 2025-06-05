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
        ->whereDate('effective_date', '<=', now()->toDateString())
        ->orderByDesc('effective_date')
        ->first();
}

    public function findPriceByDate(int $roomId, string $effectiveDate) {
        return Price::where('room_id', $roomId)
        ->where('effective_date', $effectiveDate)
        ->first();
    }

}