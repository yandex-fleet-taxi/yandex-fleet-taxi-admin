<?php

namespace App\Http\Controllers;

use App\Lead;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverInterface;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverLicenseInterface;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use App\Http\Requests\Lead as LeadFormRequest;
use Illuminate\Validation\ValidationException;
use Likemusic\YandexFleetTaxi\FrontendData\ToYandexClientPostDataConverters\Converter\ToCreateDriver as ToCreateDriverPostDataConverter;
use Likemusic\YandexFleetTaxi\FrontendData\ToYandexClientPostDataConverters\Converter\ToCreateCar as ToCreateCarPostDataConverter;
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
     * @var ToCreateCarPostDataConverter
     */
    private $toCreateCarPostDataConverter;

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

    /**
     * @var array
     */
    private $defaultDriverPostData;

    /**
     * @var array
     */
    protected $defaultCarPostData;

    public function __construct(
        string $parkId,
        string $yandexLogin,
        string $yandexPassword,
        YandexClientInterface $yandexClient,
        ToCreateDriverPostDataConverter $toCreateDriverPostDataConverter,
        ToCreateCarPostDataConverter $toCreateCarPostDataConverter,
        array $defaultDriverPostData = [],
        array $defaultCarPostData = []
    ) {
        $this->parkId = $parkId;
        $this->yandexLogin = $yandexLogin;
        $this->yandexPassword = $yandexPassword;
        $this->yandexClient = $yandexClient;
        $this->toCreateDriverPostDataConverter = $toCreateDriverPostDataConverter;
        $this->toCreateCarPostDataConverter = $toCreateCarPostDataConverter;
        $this->defaultDriverPostData = $defaultDriverPostData;
        $this->defaultCarPostData = $defaultCarPostData ;
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
            $this->yandexFleetClientVisitHomepage();
            $parkId = $this->parkId;

            $defaultDriverPostData = $this->defaultDriverPostData;
            $driverId = $this->createDriverByFrontendData($parkId, $data, $defaultDriverPostData);
            $defaultCarPostData = $this->defaultCarPostData;

            $carId = $this->createCarByFrontendData($data, $defaultCarPostData);
            $this->bindCarToDriver($parkId, $driverId, $carId);

            $status = 'success';
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

    private function bindCarToDriver(string $parkId, string $driverId, string $carId)
    {
        $this->yandexClient->bindDriverWithCar($parkId, $driverId, $carId);
    }

    private function createCarByFrontendData(array $frontendData, array $defaultCarPostData)
    {
        $yandexClientPostData = $this->convertFrontendDataToYandexClientCreateCarPostData($frontendData, $defaultCarPostData);

        $createCarResponseData = $this->createCar($yandexClientPostData);

        return $createCarResponseData['data']['id'];
    }

    private function createCar($postData)
    {
        return $this->yandexClient->storeVehicles($postData);
    }

    private function convertFrontendDataToYandexClientCreateCarPostData(array $frontendData, array $defaultCarPostData)
    {
        return $this->toCreateCarPostDataConverter->convert($frontendData, $defaultCarPostData);
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

            case 'duplicate_phone':
                $message = 'Водитель с указанным номером рабочего телефона уже зарегистрирован.';
                $errors = [
                    DriverInterface::WORK_PHONE => [$message],
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

    private function yandexFleetClientVisitHomepage()
    {
        $this->yandexClient->getDashboardPageData();
    }


    private function yandexFleetClientLogin(string $login, string $password)
    {
        $this->yandexClient->login($login, $password);
    }

    private function getDriverIdByFrontendData(string $parkId, array $frontendData)
    {
        $driverLicence = $this->getDriverLicenseByFrontendData($frontendData);
        $driverWorkPhone = $this->getDriverWorkPhoneByFrontendData($frontendData);

        $driversByLicense = $this->getDriversByString($parkId, $driverLicence)['data']['driver_profiles'];
        $driversByWorkPhone = $this->getDriversByString($parkId, $driverWorkPhone)['data']['driver_profiles'];

        return $this->getDriverIdOrNullByFoundDrivers($driversByLicense, $driversByWorkPhone);
    }

    private function getDriverIdOrNullByFoundDrivers(array $driversByLicense, array $driversByWorkPhone)
    {
        //todo: refactor this method
        $licenseDriversCount = count($driversByLicense);

        if ($licenseDriversCount > 1) {
            throw new InvalidArgumentException('Для указанного ВУ найдено более одного водителя.');
        }

        $phoneDriversCount = count($driversByWorkPhone);

        if ($phoneDriversCount > 1) {
            throw new InvalidArgumentException('Для указанного номера телефона найдено более одного водителя.');
        }

        $licenseDriverId = $phoneDriverId = null;

        if ($licenseDriversCount) {
            $licenseDriver = current($driversByLicense);
            $licenseDriverId = $licenseDriver['driver']['id'];
        }

        if ($phoneDriversCount) {
            $phoneDriver = current($driversByWorkPhone);
            $phoneDriverId = $phoneDriver['driver']['id'];
        }

        $driverId = null;

        if ($licenseDriverId) {
            if (!$phoneDriverId) {
                $driverId = $licenseDriverId;
            } else {
                if($licenseDriverId === $phoneDriverId) {
                    $driverId = $licenseDriverId;
                } else {
                    throw new InvalidArgumentException('Для указанного ВУ и номера рабочего телефона найдены разные водители.');
                }
            }
        } elseif($phoneDriverId) {
            $driverId = $phoneDriverId;
        }

        return $driverId;
    }

    private function getDriversByString($parkId, string $search)
    {
        return $this->yandexClient->getDrivers($parkId, $search);
    }

    private function getDriverWorkPhoneByFrontendData(array $frontendData)
    {
        $driverPhones = $this->getDriverPhonesByFrontendData($frontendData);

        return current($driverPhones);
    }

    private function getDriverPhonesByFrontendData(array $frontendData)
    {
        return $this->toCreateDriverPostDataConverter->getDriverPhones($frontendData);
    }

    private function getDriverLicenseByFrontendData(array $frontendData)
    {
        return $this->toCreateDriverPostDataConverter->getDriverLicenceNumber($frontendData);
    }

    private function createDriverByFrontendData($parkId, array $frontendData, $defaultDriverPostData)
    {
        $yandexClientPostData = $this->convertFrontendDataToYandexClientCreateDriverPostData($frontendData, $defaultDriverPostData);

        return $this->createDriver($parkId, $yandexClientPostData);
    }

    private function createDriver($parkId, array $postData)
    {
        return $this->yandexClient->createDriver($parkId, $postData);
    }

    private function convertFrontendDataToYandexClientCreateDriverPostData(array $frontendData, array $defaultDriverPostData)
    {
        return $this->toCreateDriverPostDataConverter->convert($frontendData, $defaultDriverPostData);
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
