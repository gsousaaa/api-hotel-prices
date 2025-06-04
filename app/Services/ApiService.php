<?php
namespace App\Services;

use App\Clients\ApiInvertexto;
use App\Exceptions\BadRequestException;
use App\Repositories\PriceRepository;
use App\Repositories\RoomRepository;
use Carbon\Carbon;

class ApiService
{
    public function __construct(
        protected RoomRepository $roomRepository,
        protected PriceRepository $priceRepository,
        protected ApiInvertexto $apiInvertexto
    ) {
    }

    protected function isHoliday(string $date, array $holidays): bool
    {
        return collect($holidays)->contains('date', $date);
    }

    protected function isWeekend(string $date): bool
    {
        $formattedDate = Carbon::parse($date);
        return $formattedDate->isWeekend();
    }

    protected function calculatePrice(bool $isDayOff, int $ocupationRate, string $roomType, float $price)
    {
        $addictions = [];

        if ($isDayOff) {
            $addictions[] = 1.20;
        }

        $roomTypeAddictions = [
            'Suite' => 1.15,
            'Deluxe' => 1.10,
            'Standard' => 1.00
        ];

        $addictions[] = $roomTypeAddictions[$roomType];

        if ($ocupationRate > 80) {
            $addictions[] = 1.10;
        }

        if ($ocupationRate < 80) {
            $addictions[] = 0.95;
        }

        $finalPrice = $price;

        foreach ($addictions as $addiction) {
            $finalPrice *= $addiction;
        }

        return $finalPrice;
    }
    public function getCompanyRooms(int $companyId)
    {
        return $this->roomRepository->findCompanyRooms($companyId);
    }
    
    public function calculatePriceForecast(array $data)
    {
        $room = $this->roomRepository->findRoomById($data['room_id']);

        $actualDate = Carbon::today();
        $effectiveDate = $data['effective_date'];

        if (Carbon::parse($effectiveDate)->lessThanOrEqualTo($actualDate)) {
            throw new BadRequestException('Data de previsão inválida. Envie uma data maior que a atual');
        }

        if (!$room || $room->company_id !== $data['company_id']) {
            throw new BadRequestException('Quarto não encontrado!');
        }

        $lastPrice = $this->priceRepository->findLastPrice($room->id);

        if (!$lastPrice) {
            throw new BadRequestException('Nenhum preço foi encontrado!');
        }

        $year = Carbon::parse($effectiveDate)->year;
        $holidays = $this->apiInvertexto->findHolidays($year, $room->company->uf);
        $isDayOff = $this->isHoliday($effectiveDate, $holidays) || $this->isWeekend($effectiveDate);

        $reajustedPrice = $this->calculatePrice($isDayOff, $data['occupancyRate'], $room->type, $lastPrice->price);

        $this->priceRepository->createPrice([
            'room_id' => $data['room_id'],
            'price' => $reajustedPrice,
            'effective_date' => $effectiveDate
        ]);

        return ['price' => $reajustedPrice];
    }
}