<?php

namespace App\Http\Controllers;

use App\Lead;
use Exception;
use Illuminate\Http\Request;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use App\Http\Requests\Lead as LeadFormRequest;
use Illuminate\Validation\ValidationException;
use Likemusic\YandexFleetTaxi\FrontendData\ToYandexClientPostDataConverters\Converter\ToCreateDriver as ToCreateDriverPostDataConverter;
use Likemusic\YandexFleetTaxiClient\Exception as YandexClientException;
use Psr\Http\Message\RequestInterface;

class ApiController extends Controller
{
    /**
     * @var ToCreateDriverPostDataConverter
     */
    private $toCreateDriverPostDataConverter;

    /**
     * @var YandexClientInterface
     */
    private $yandexClient;

    /**
     * @var string
     */
    private $parkId;

    /**
     * @var string
     */
    private $yandexLogin;

    /**
     * @var string
     */
    private $yandexPassword;

    public function __construct(
        string $parkId,
        string $yandexLogin,
        string $yandexPassword,
        YandexClientInterface $yandexClient,
        ToCreateDriverPostDataConverter $toCreateDriverPostDataConverter
    ) {
        $this->parkId = $parkId;
        $this->yandexLogin = $yandexLogin;
        $this->yandexPassword = $yandexPassword;
        $this->yandexClient = $yandexClient;
        $this->toCreateDriverPostDataConverter = $toCreateDriverPostDataConverter;
    }

    public function addLead(LeadFormRequest $request)
    {
        $status = 'error';

        $ret = [];

        try {

            $data = $request->validated();

            $yandexLogin = $this->yandexLogin;
            $yandexPassword = $this->yandexPassword;
            $this->yandexFleetClientLogin($yandexLogin, $yandexPassword);
            $parkId = $this->parkId;

            $driverId = $this->getOrCreateDriverByFrontendData($parkId, $data);
//            $carId = $this->getOrCreateCarByFrontendData($data);
//            $this->bindCarToDriver($driverId, $carId);
            $status = 'success';
            $ret = ['status' => 'success'];
        }
        catch (Exception $exception) {
            $this->logException($exception, $request);

            //list($errorFieldName, $errorMessage) = $this->getErrorFieldNameAndMessageByException($exception);
            $ret['errors']['common'][] =  'Во время обработки формы произошла ошибка. Попробуйте повторить отправку формы еще раз немного позже.';
        }

        $ret['status'] = $status;

        return $this->createJsonResponse($ret);
    }

    private function logException(Exception $exception, LeadFormRequest $request)
    {
        //todo
    }


    private function yandexFleetClientLogin(string $login, string $password)
    {
        $this->yandexClient->login($login, $password);
    }

    private function getOrCreateDriverByFrontendData($parkId, array $frontendData)
    {
        if ($driver = $this->getDriverByFrontendData($frontendData)) {
            return $driver['id'];
        };

        return $this->createDriverByFrontendData($parkId, $frontendData);
    }

    private function getDriverByFrontendData(array $frontendData)
    {
        return null;//todo
    }

    private function createDriverByFrontendData($parkId, array $frontendData)
    {
        $yandexClientPostData = $this->convertFrontendDataToYandexClientCreateDriverPostData($frontendData);

        return $this->createDriver($parkId, $yandexClientPostData);
    }

    private function createDriver($parkId, array $postData)
    {
        return $this->yandexClient->createDriver($parkId, $postData);
    }

    private function convertFrontendDataToYandexClientCreateDriverPostData(array $frontendData)
    {
        return $this->toCreateDriverPostDataConverter->convert($frontendData);
    }

    private function getErrorFieldNameAndMessageByException(Exception $exception)
    {
        return ['common', $exception->getMessage()];//todo
    }

    private function createJsonResponse($data)
    {
        return response()->json($data);
    }
}
