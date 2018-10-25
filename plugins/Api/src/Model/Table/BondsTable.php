<?php

namespace Api\Model\Table;

use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * BondsTable Model
 *
 * @property \App\Model\Table\BondsTable|\Cake\ORM\Association\HasMany $Bonds
 *
 * @method \App\Model\Entity\Country get($primaryKey, $options = [])
 * @method \App\Model\Entity\Country newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Country[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Country|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Country patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Country[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Country findOrCreate($search, callable $callback = null, $options = [])
 *
 */
class BondsTable extends Table
{

    const BOND_EXCHANGE = 7;
    const JMD = 'JMD';
    const USD = 'USD';
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable(false);
    }

    /**
     * getBonds method It will connect to the cbonds api and return the bonds based in the get_emissions URL
     * 
     * @todo Create a shell to get this data and save in a table to get this records more fast.
     * @return array
     */
    public function getBonds() {
        $http = $this->__clientClass();
        $response = $http->get('https://ws.cbonds.info/services/json/get_emissions/', [
            'lang' => 'eng',
            'cache_all_revalidate' => 0,
            'nocache_all' => 0,
            'login' => Configure::read('cbonds.username'),
            'password' => Configure::read('cbonds.password')
        ]);

        $result = $response->body();

        return json_decode($result, true);
    }

    /**
     * getBondsBondeValue method It will connect to the bondevalue api and return the bonds
     * 
     * @todo Create a shell to get this data and save in a table to get this records more fast.
     * @return array
     */
    public function getBondsBondeValue()
    {
        $http = $this->__clientClass();

        $token = Configure::read('bondevalue.token');
        $data = "{\"data\":{\"userToken\":\"" . $token . "\"}}";
        $encrypted = openssl_encrypt($data, 'AES-128-ECB', $token);
        
        $response = $http->post('https://bondevalue.com/app/bondsDataDailyChange', [
            'requestData' => $encrypted,
            'userToken' => $token,
            'requestSource' => 'API'
        ]);
            
        $result = $response->body();
        $result = json_decode($result, true);
        $response = [];
        
        $response = Hash::get($result, 'response.Result');

        return $response;
    }


    /**
     * getHistorical method It will get all historical from a isin code
     * 
     * @todo Create a shell to get this data and save in a table to get this records more fast.
     * @param string $isinCode ISIN Code
     * @param string $userId User ID
     * @param string $currentLanguage Current Language
     * @return array
     */
    public function getHistorical($isinCode, $userId, $currentLanguage)
    {
        $token = Configure::read('bondevalue.token');

        $data = "{\"data\":{\"userToken\":\"" . $token . "\"}}";

        $encrypted = openssl_encrypt($data, 'AES-128-ECB', $token);

        $http = $this->__clientClass();
        $response = $http->post('https://bondevalue.com/app/bondsDataDailyChange', [
            'requestData' => $encrypted,
            'userToken' => $token,
            'requestSource' => 'API'
        ]);

        $result = $response->body();
        $result = json_decode($result, true);
        $bonds = [];
        if (isset($result['response']['Result'])) {
            $bonds = $result['response']['Result'];
        }

        $bond = [];
        foreach ($bonds as $loopBond) {
            if ($loopBond['ISINCode'] == $isinCode) {
                $bond = $loopBond;
                break;
            }
        }
        $bond = [
            'Country' => $bond['country'] . ' / ' . $bond['countryName'],
            'Callable' => $bond['callable'],
            'Issuer Name 1' => $bond['issuerNameInBoldLetters'],
            'Issuer Name 2' => $bond['issuerNameInNormalLetters'],
            'ISIN Code' => $bond['ISINCode'],
            'Price' => $bond['bondPrice'],
            'Bond Yield' => $bond['bondYield'],
            'Yield Change' => $bond['yieldChange'],
            'Yield Change (BP Unit)' => $bond['YieldChangeInBpUnit'],
            'Yield Change (percentage)' => $bond['yieldChangeInPercentage'],
            'Rating' => $bond['rating'],
            'Current Date' => $bond['currentDate'],
            'Previous Date' => $bond['previousDate'],
            'Bond Maturity Date' => $bond['bondMaturityDate'],
            'Maturity Years Remaining' => $bond['maturityYrsRemain'],
            'Perpetual?' => $bond['perpetual'],
            'Country of Risk' => $bond['countryOfRisk'] . ' / ' . $bond['countryOfRiskName'],
            'Currency' => $bond['currency'] . ' / ' . $bond['currencyName'],
            'Price Change' => $bond['priceChange'],
            'Price Change (percentage)' => $bond['priceChangeInPercentage'],
            'Bond Coupon' => $bond['bondCoupon'],
            'Bond Amount Out' => $bond['bondAmountOut']
        ];

        $price = $bond['Price'];
        $Messages = TableRegistry::get('Api.Messages');
        $News = TableRegistry::get('Api.News');
        $name = $bond['Issuer Name 1'];
        $news_data = $News->getNews($currentLanguage);
        $news = $News->formatBondNews($news_data);
        $messages = $Messages->find()
            ->where(['BINARY (message) LIKE' => '%' . $isinCode . '%'])
            ->where(['Messages.comment_id IS' => NULL])
            ->contain('AppUsers')
            ->contain(['Ratings'])
            ->contain(['ScreenshotMessage'])
            ->order(['Messages.created' => 'desc'])
            ->toArray();

        return [
            'isinCode' => $isinCode,
            'messages' => $messages,
            'bond' => $bond,
            'news' => $news,
            'name' => $name,
            'price' => $price
        ];
    }
    public function getBondInfo($isinCode, $userId, $currentLanguage)
    {
        $token = Configure::read('bondevalue.token');

        $data = "{\"data\":{\"userToken\":\"" . $token . "\"}}";

        $encrypted = openssl_encrypt($data, 'AES-128-ECB', $token);

        $http = $this->__clientClass();
        $response = $http->post('https://bondevalue.com/app/bondsDataDailyChange', [
            'requestData' => $encrypted,
            'userToken' => $token,
            'requestSource' => 'API'
        ]);

        $result = $response->body();
        $result = json_decode($result, true);
        $bonds = [];
        if (isset($result['response']['Result'])) {
            $bonds = $result['response']['Result'];
        }

        $bond = [];
        foreach ($bonds as $loopBond) {
            if ($loopBond['ISINCode'] == $isinCode) {
                $bond = $loopBond;
                break;
            }
        }
        if($bond){
            $bond = [
                'country/countryName' => $bond['country'] . ' / ' . $bond['countryName'],
                'callable' => $bond['callable'],
                'issuerNameInBoldLetters' => $bond['issuerNameInBoldLetters'],
                'issuerNameInNormalLetters' => $bond['issuerNameInNormalLetters'],
                'ISINCode' => $bond['ISINCode'],
                'bondPrice' => $bond['bondPrice'],
                'bondYield' => $bond['bondYield'],
                'yieldChange' => $bond['yieldChange'],
                'YieldChangeInBpUnit' => $bond['YieldChangeInBpUnit'],
                'yieldChangeInPercentage' => $bond['yieldChangeInPercentage'],
                'rating' => $bond['rating'],
                'currentDate' => $bond['currentDate'],
                'previousDate' => $bond['previousDate'],
                'bondMaturityDate' => $bond['bondMaturityDate'],
                'maturityYrsRemain' => $bond['maturityYrsRemain'],
                'perpetual' => $bond['perpetual'],
                'countryOfRisk/countryOfRiskName' => $bond['countryOfRisk'] . ' / ' . $bond['countryOfRiskName'],
                'currency/currencyName' => $bond['currency'] . ' / ' . $bond['currencyName'],
                'priceChange' => $bond['priceChange'],
                'priceChangeInPercentage' => $bond['priceChangeInPercentage'],
                'bondCoupon' => $bond['bondCoupon'],
                'bondAmountOut' => $bond['bondAmountOut'],
                'isCorporate' => false
            ];
        }else{
            $bonds = $this->getCorporateBonds2($currentLanguage,$isinCode);
            $bond = [
                'country/countryName' => null,
                'callable' => null,
                'issuerNameInBoldLetters' => $bonds['name'],
                'issuerNameInNormalLetters' => null,
                'ISINCode' => $bonds['symbol'],
                'bondPrice' => 0,
                'bondYield' => null,
                'yieldChange' => null,
                'YieldChangeInBpUnit' => null,
                'yieldChangeInPercentage' =>null,
                'rating' => null,
                'currentDate' => date("Y-M-d H:i:s"),
                'previousDate' => null,
                'bondMaturityDate' => null,
                'maturityYrsRemain' => null,
                'perpetual' => null,
                'countryOfRisk/countryOfRiskName' => null,
                'currency/currencyName' => $bonds['exchange']['country']['market'],
                'priceChange' => null,
                'priceChangeInPercentage' => null,
                'bondCoupon' => null,
                'bondAmountOut' =>null,
                'isCorporate' => true
            ];
        }
        return $bond;
    }

    /**
     * getCorporateBonds method it will filter all bond companies
     *
     * @param string $currentLanguage Current Language
     * @return \Cake\ORM\Query
     */
    public function getCorporateBonds($currentLanguage)
    {
        $Companies = TableRegistry::get('Api.Companies');

        $companies = $Companies->find()
            ->contain(['Exchanges' => function ($q) use ($currentLanguage) {
                return $q
                    ->contain(['Countries' => function ($q) use ($currentLanguage) {
                        return $q
                            ->where(['Countries.market' => $currentLanguage]);
                    }]);
            }])
            ->where(['Companies.exchange_id' => self::BOND_EXCHANGE])
            ->toArray();

        $Stocks = TableRegistry::get('Api.Stocks');


        foreach ($companies as $key => $company) {
            if ($currentLanguage == self::JMD) {
                $stock = $Stocks->getStockInformationLocal($company->symbol, $company->id);
            } else {
                $stock = $Stocks->getStockInformation($company->symbol);
            }

            $company->stocks = $stock;
            $companies[$key] = $company;
        }
        return $companies;
    }
    public function getCorporateBonds2($currentLanguage,$isinCode)
    {
        $Companies = TableRegistry::get('Api.Companies');

        $companies = $Companies->find()
            // ->contain(['Exchanges' => function ($q) use ($currentLanguage) {
            //     return $q
            //         ->contain(['Countries' => function ($q) use ($currentLanguage) {
            //             return $q
            //                 ->where(['Countries.market' => $currentLanguage]);
            //         }]);
            // }])
            ->where(['Companies.symbol' => $isinCode,'Companies.exchange_id' => self::BOND_EXCHANGE])
            ->first();

        $Stocks = TableRegistry::get('Api.Stocks');


        foreach ($companies as $key => $company) {
            if ($currentLanguage == self::JMD) {
                $stock = $Stocks->getStockInformationLocal($company->symbol, $company->id);
            } else {
                $stock = $Stocks->getStockInformation($company->symbol);
            }

            $company->stocks = $stock;
            $companies[$key] = $company;
        }
        return $companies;
    }



    /**
     * __clientClass method Returning a client instance
     *
     * @return Cake\Http\Client
     */
    private function __clientClass()
    {
        return new Client();
    }
}
