<?php
/**
 * User: sacheen
 * Date: 2018/04/10
 * Time: 3:43 PM
 */

namespace SD\Root;

use GuzzleHttp\Client;

class RootInsurance
{

    /**
     * @var Client
     */
    private $client;

    public function __construct($endpoint, $apiKey, $debug = false)
    {
        $this->client = new Client([
                'base_uri' => $endpoint,
                'auth' => [$apiKey, null, 'basic'],
                'headers' => ['Content-Type' => 'application/json'],
                'debug' => $debug
            ]
        );
    }

    public static $coverPeriods = [
        '1_year' => '1 Year',
        '2_years' => '2 Years',
        '5_years' => '5 Years',
        '10_years' => '10 Years',
        '15_years' => '15 Years',
        '20_years' => '20 Years',
        'whole_life' => 'Life time'
    ];

    public static $educationStatus = [
        'grade_12_no_matric' => 'No Matric',
        'grade_12_matric' => 'Matric',
        'diploma_or_btech' => 'Diploma or Btech',
        'undergraduate_degree' => 'Under Grad Degree',
        'professional_degree' => 'Professional Degree'
    ];


    /**
     * @param $modelName
     * @return string
     */
    public function generateGadgetQuote($modelName)
    {
        $body = json_encode(['type' => 'root_gadgets', 'model_name' => $modelName]);
        $response = $this->getClient()->request('POST', '/v1/insurance/quotes', ['body' => $body]);
        return $response->getBody()->getContents();
    }

    /**
     * @param $coverAmount
     * @param $hasSpouse
     * @param $numberOfChildren
     * @param $extendedFamilyAges
     * @return string
     */
    public function generateFuneralQuote($coverAmount, $hasSpouse, $numberOfChildren, $extendedFamilyAges = [])
    {
        $body = json_encode([
                'type' => 'root_funeral',
                'cover_amount' => $coverAmount,
                'has_spouse' => $hasSpouse,
                'number_of_children' => $numberOfChildren,
                'extended_family_ages' => $extendedFamilyAges]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/quotes', ['body' => $body]);
        return $response->getBody()->getContents();
    }

    /**
     * @param $coverAmount
     * @param $coverPeriod
     * @param $basicIncomePerMonth
     * @param $educationStatus
     * @param $smoker
     * @param $gender
     * @param $age
     * @return string
     */
    public function generateTermQuote($coverAmount, $coverPeriod, $basicIncomePerMonth, $educationStatus, $smoker, $gender, $age)
    {
        $body = json_encode([
                'type' => 'root_term',
                'cover_amount' => $coverAmount,
                'cover_period' => $coverPeriod,
                'basic_income_per_month' => $basicIncomePerMonth,
                'education_status' => $educationStatus,
                'smoker' => $smoker,
                'gender' => $gender,
                'age' => $age]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/quotes', ['body' => $body]);
        return $response->getBody()->getContents();
    }

    /**
     * @param $idNumber
     * @param string $IdType
     * @param $idCountry
     * @param $firstName
     * @param $lastName
     * @param null $dateOfBirth
     * @param null $email
     * @param null $cellphone
     * @param array $appData
     * @return string
     */
    public function createPolicyHolder($idNumber, $IdType = 'id', $idCountry, $firstName, $lastName, $dateOfBirth = null, $email = null, $cellphone = null, $appData = [])
    {
        $body = json_encode([
                'id' => [
                    'type' => $IdType,
                    'number' => $idNumber,
                    'country' => $idCountry
                ],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $dateOfBirth,
                'email' => $email,
                'cellphone' => $cellphone,
                'app_data' => $appData
            ]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/policyholders', ['body' => $body]);
        return $response->getBody()->getContents();
    }

    /**
     * @param $serialNumber
     * @param $quotePackageId
     * @param $policyHolder
     * @param $monthlyPremium
     * @return string
     */
    public function createGadgetApplication($serialNumber, $quotePackageId, $policyHolder, $monthlyPremium)
    {
        $body = json_encode([
                'serial_number' => $serialNumber,
                'quote_package_id' => $quotePackageId,
                'policyholder_id' => $policyHolder,
                'monthly_premium' => $monthlyPremium]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/applications', ['body' => $body]);
        return $response->getBody()->getContents();

    }

    /**
     * @param $quotePackageId
     * @param $policyHolder
     * @param $monthlyPremium
     * @param null $spouseId
     * @param array $childrenIds
     * @param array $extendedFamliyIds
     * @return string
     */
    public function createFuneralApplication($quotePackageId, $policyHolder, $monthlyPremium, $spouseId = null, $childrenIds = [], $extendedFamliyIds = [])
    {
        $body = json_encode([
                'spouse_id' => $spouseId,
                'childrenIds' => $childrenIds,
                'extended_family_ids' => $extendedFamliyIds,
                'quote_package_id' => $quotePackageId,
                'policyholder_id' => $policyHolder,
                'monthly_premium' => $monthlyPremium]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/applications', ['body' => $body]);
        return $response->getBody()->getContents();

    }

    /**
     * @param $quotePackageId
     * @param $policyHolder
     * @param $monthlyPremium
     * @return string
     */
    public function createTermApplication($quotePackageId, $policyHolder, $monthlyPremium)
    {
        $body = json_encode([
                'quote_package_id' => $quotePackageId,
                'policyholder_id' => $policyHolder,
                'monthly_premium' => $monthlyPremium]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/applications', ['body' => $body]);
        return $response->getBody()->getContents();

    }


    /**
     * @param $applicationId
     * @param array $appData
     * @return string
     */
    public function issuePolicy($applicationId, $appData = [])
    {
        $body = json_encode([
                'application_id' => $applicationId,
                'app_data' => $appData]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/policies', ['body' => $body]);
        return $response->getBody()->getContents();

    }

    /**
     * @param $policyId
     * @param int $version
     * @return string
     */
    public function policyReceipt($policyId, $version = 1)
    {
        $body = json_encode([
                'policy_id' => $policyId,
                'version' => $version]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/policies/' . $policyId, ['body' => $body]);
        return $response->getBody()->getContents();
    }

    /**
     * @param $policyId
     * @param $firstName
     * @param $lastName
     * @param $idNumber
     * @param string $idType
     * @param $idCountry
     * @param $percentage
     * @param $cellphone
     * @return string
     */
    public function addBeneficiaries($policyId, $firstName, $lastName, $idNumber, $idType = 'id', $idCountry, $percentage, $cellphone)
    {
        $body = json_encode([
                'policy_id' => $policyId,
                'id' => [
                    'type' => $idType,
                    'number' => $idNumber,
                    'country' => $idCountry
                ],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'percentage' => $percentage,
                'cellphone' => $cellphone
            ]
        );
        $response = $this->getClient()->request('POST', '/v1/insurance/policies/' . $policyId, ['body' => $body]);
        return $response->getBody()->getContents();

    }


    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }


}
