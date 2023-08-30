<?php

namespace App\Controllers;

use App\Helpers\Twig;
use App\Request\Request;
use App\Services\SalesService;
use Exception;

class HomeController
{
    /** @var SalesService $salesService */
    private $salesService;


    /** @var Request $request */
    private $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->salesService = new SalesService();
    }

    /** return type not used due to routes and ajax */
    public function index(): void
    {
        try {
            $data = $this->salesService->getSales();
            $response = Twig::render(
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
            $response = Twig::render(
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