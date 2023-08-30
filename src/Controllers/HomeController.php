<?php

namespace App\Controllers;

use App\Helpers\TwigHelper;
use App\Request\Request;
use App\Services\SalesService;
use Exception;

class HomeController
{
    /** @var SalesService $salesService */
    private $salesService;

    /** @var TwigHelper $twigService */
    private $twigService;

    /** @var Request $request */
    private $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->salesService = new SalesService();
        $this->twigService = new TwigHelper();
    }

    public function index(): void
    {
        try {
            $data = $this->salesService->getSales();
            $response = $this->twigService->render(
                'index.twig',
                'src/Views/sales',
                ['salesList' => $data]
            );
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        echo $response;
    }

    public function filterSales(): void
    {
        try {

            $data = $this->salesService->getSales($this->request->getPost());
            $response = $this->twigService->render(
                'list.twig',
                'src/Views/sales',
                ['salesList' => $data]
            );
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        echo $response;
    }

    public function uploadSalesData(): void
    {
        try {
            $response = $this->salesService->upload($this->request);
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        echo $response;
    }


}