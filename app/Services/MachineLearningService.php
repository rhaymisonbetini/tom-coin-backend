<?php

namespace App\Services;

use Carbon\Carbon;
use Phpml\Regression\LeastSquares;

class MachineLearningService
{

    public function predictTomCoinCotation($tomCoinHistory): mixed
    {
        $regression = new LeastSquares();

        $fomatedDate = $this->dateToMilliseconds($tomCoinHistory);
        $fiveDays = $this->addFiveDaysInPrediction();
        $samples = $fomatedDate[0];
        $targets = $fomatedDate[1];
        $regression->train($samples, $targets);
        $result = $regression->predict($fiveDays[0]);
        return $result;
    }

    public function dateToMilliseconds($tomCoinHistory): array
    {

        (array) $tomCoinAmostral = [];
        (array) $tomCoinLabels   = [];

        foreach ($tomCoinHistory as $history) {
            array_push($tomCoinAmostral, $this->toTime($history->created_at));
            array_push($tomCoinLabels, $history->cash);
        }

        return [
            $tomCoinAmostral,
            $tomCoinLabels
        ];
    }

    public function addFiveDaysInPrediction(): array
    {
        (array) $fiveDaysLater = [];
        (array) $days = [];
        $actualDay = date("Y-m-d H:i:s");
        for ($i = 0; $i < 15; $i++) {
            array_push($fiveDaysLater, $this->toTime(date("Y-m-d", strtotime($actualDay . "+" . $i . " days"))));
            array_push($days, date("d-m-Y", strtotime($actualDay . "+" . $i . " days")));
        }
        return [
            $fiveDaysLater,
            $days
        ];
    }

    public function toTime($date): array
    {
        $date = explode("-", explode(' ', $date)[0]);
        return [
            $date[2],
        ];
    }
}
