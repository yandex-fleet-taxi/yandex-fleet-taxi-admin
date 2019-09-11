<?php

namespace App\Http\Controllers;

use App\Lead;
use Exception;
use Illuminate\Http\Request;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverLicenseInterface;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use App\Http\Requests\Lead as LeadFormRequest;
use Illuminate\Validation\ValidationException;
use Likemusic\YandexFleetTaxi\FrontendData\ToYandexClientPostDataConverters\Converter\ToCreateDriver as ToCreateDriverPostDataConverter;
use Likemusic\YandexFleetTaxiClient\Exception as YandexClientException;
use Likemusic\YandexFleetTaxiClient\HttpJsonResponseException;
use Likemusic\YandexFleetTaxiClient\HttpResponseException;
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
    )
    {
        $this->parkId = $parkId;
        $this->yandexLogin = $yandexLogin;
        $this->yandexPassword = $yandexPassword;
        $this->yandexClient = $yandexClient;
        $this->toCreateDriverPostDataConverter = $toCreateDriverPostDataConverter;
    }

    public function addLead(LeadFormRequest $request)
    {
        $status = 'error';
        $errors = null;
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
        } catch (HttpJsonResponseException $exception) {
            $this->logHttpJsonResponseException($exception);
            $errors = $this->getErrorsByHttpJsonResponseException($exception);
        } catch (HttpResponseException $exception) {
            $this->logHttpResponseException($exception);
            $errors = $this->getErrorsByHttpResponseException($exception);
        } catch (Exception $exception) {
            $this->logException($exception, $request);
            $errors = $this->getErrorsByException($exception);
        }

        if ($errors) {
            $ret['errors'] = $errors;
        }

        $ret['status'] = $status;

        return $this->createJsonResponse($ret, $errors);
    }

    protected function getErrorsByException(Exception $exception)
    {
        return [
            'common' => [$exception->getMessage()],
        ];//todo
    }

    private function getErrorsByHttpResponseException(HttpResponseException $exception)
    {
        return [];//todo
    }

    private function getErrorsByHttpJsonResponseException(HttpJsonResponseException $exception)
    {
        if ($this->isRequestValidationErrorException($exception)) {
            return $this->getRequestValidationErrorResponseErrors($exception);
        }

        $jsonCode = $exception->getJsonCode();

        switch ($jsonCode) {
            case 'duplicate_driver_license':
                $message = 'Водитель с указанным ВУ уже зарегистрирован.';
                $errors = [
                    DriverLicenseInterface::SERIES => [$message],
                    DriverLicenseInterface::NUMBER => [$message],
                ];
                break;
            default:
                $errors = [
                    'common' => [
                        'Во время обработки запроса произошла ошибка. Попробуйте повторить отправку формы немного позже.'
                    ]
                ];
        }

        return $errors;
    }

    private function getRequestValidationErrorResponseErrors(HttpJsonResponseException $exception)
    {
        return [
            'common' => [
                'Во время обработки запроса произошла ошибка. Попробуйте повторить отправку формы немного позже. Если ошибка повторяется, сообщите об это владельцу сайта.'
            ]
        ];
    }

    private function isRequestValidationErrorException(HttpJsonResponseException $exception)
    {
        return $exception->getCode() === 400
            && $exception->getJsonCode() === 'REQUEST_VALIDATION_ERROR'
            && $exception->getJsonMessage() === 'Some parameters are invalid';
    }

    private function logHttpResponseException(HttpResponseException $exception)
    {
        //todo
    }

    private function logHttpJsonResponseException(HttpJsonResponseException $exception)
    {
        //todo
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

    private function createJsonResponse($data, $error = false)
    {
        $status = !$error ? 200 : 422;

        return response()->json($data, $status);
    }
}
