<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TomCoinHistoryRepository;
use App\Services\MachineLearningService;

class TomCoinHistoryController extends Controller
{
    private $tomCoinHistoryRepository;
    private $machineLearningService;

    public function __construct(
        TomCoinHistoryRepository $tomCoinHistoryRepository,
        MachineLearningService $machineLearningService
    ) {
        $this->tomCoinHistoryRepository = $tomCoinHistoryRepository;
        $this->machineLearningService = $machineLearningService;
    }

    /**
     * return response.
     *
     * @return \Illuminate\Http\Response
     */
    public function MachineTomPredict()
    {

        try {

            $tomCoinHistory = $this->tomCoinHistoryRepository->getTomCoinCotationHistory();
            $predictions = $this->machineLearningService->predictTomCoinCotation($tomCoinHistory);
            return response()->json($predictions, 200);
        } catch (\Exception $e) {
            \Log::alert($e);
            return response()->json($e, 400);
        }
    }
}
