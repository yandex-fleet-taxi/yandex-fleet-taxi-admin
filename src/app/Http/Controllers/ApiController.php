<?php

namespace App\Http\Controllers;

use App\Lead;
use Exception;
use Illuminate\Http\Request;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\CreateDriver\DriverProfileInterface;

class ApiController extends Controller
{
    public function addLead(Request $request)
    {
//        try {
            $this->validateRequest($request);

//            $data = $request->all();
//            $this->validateData($data);

//            $driverId = $this->getOrCreateDriverByFrontendData($data);
//            $carId = $this->getOrCreateCarByFrontendData($data);
//            $this->bindCarToDriver($driverId, $carId);

            $ret = ['status' => 'success'];
//        } catch (Exception $exception) {
//            list($errorFieldName, $errorMessage) = $this->getErrorFieldNameAndMessageByException($exception);

//            $ret = [
//                'status' => 'error',
//                'errors' => [
//                    $errorFieldName => $errorMessage,
//                ]
//            ];
//        }

        return $this->createJsonResponse($ret);
    }

    private function getErrorFieldNameAndMessageByException(Exception $exception)
    {
        return ['test' => 'test'];//todo
    }

    private function createJsonResponse($data)
    {
        return response()->json($data);
    }

    private function validateRequest(Request $request)
    {
        $validator = [
            DriverProfileInterface::FIRST_NAME => 'required',
            DriverProfileInterface::LAST_NAME => 'required',
            DriverProfileInterface::MIDDLE_NAME => 'required',
        ];

        $request->validate($validator);
    }

    private function validateData(array $data)
    {

    }
}
