<?php

namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

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

        $response['items'] = Hash::get($result, 'response.Result');

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

        $country = '';
        if (Hash::get($bond, 'country') && Hash::get($bond, 'countryName')) {
            $country = Hash::get($bond, 'country') . ' / ' . Hash::get($bond, 'countryName');
        }

        $countryOfRisk = '';
        if (Hash::get($bond, 'countryOfRisk') && Hash::get($bond, 'countryOfRiskName')) {
            $countryOfRisk = Hash::get($bond, 'countryOfRisk') . ' / ' . Hash::get($bond, 'countryOfRiskName');
        }

        $currency = '';
        if (Hash::get($bond, 'currency') && Hash::get($bond, 'currencyName')) {
            $currency = Hash::get($bond, 'currency') . ' / ' . Hash::get($bond, 'currencyName');
        }

        $bond = [
            'Country' => $country,
            'Callable' => Hash::get($bond, 'callable'),
            'Issuer Name 1' => Hash::get($bond, 'issuerNameInBoldLetters'),
            'Issuer Name 2' => Hash::get($bond, 'issuerNameInNormalLetters'),
            'ISIN Code' => Hash::get($bond, 'ISINCode'),
            'Price' => Hash::get($bond, 'bondPrice'),
            'Bond Yield' => Hash::get($bond, 'bondYield'),
            'Yield Change' => Hash::get($bond, 'yieldChange'),
            'Yield Change (BP Unit)' => Hash::get($bond, 'YieldChangeInBpUnit'),
            'Yield Change (percentage)' => Hash::get($bond, 'yieldChangeInPercentage'),
            'Rating' => Hash::get($bond, 'rating'),
            'Current Date' => Hash::get($bond, 'currentDate'),
            'Previous Date' => Hash::get($bond, 'previousDate'),
            'Bond Maturity Date' => Hash::get($bond, 'bondMaturityDate'),
            'Maturity Years Remaining' => Hash::get($bond, 'maturityYrsRemain'),
            'Perpetual?' => Hash::get($bond, 'perpetual'),
            'Country of Risk' => $countryOfRisk,
            'Currency' => $currency,
            'Price Change' => Hash::get($bond, 'priceChange'),
            'Price Change (percentage)' => Hash::get($bond, 'priceChangeInPercentage'),
            'Bond Coupon' => Hash::get($bond, 'bondCoupon'),
            'Bond Amount Out' => Hash::get($bond, 'bondAmountOut')
        ];

        $price = Hash::get($bond, 'Price');
        $Messages = TableRegistry::get('Messages');
        $News = TableRegistry::get('News');
        $name = Hash::get($bond, 'Issuer Name 1');
        $news = $News->getNews($currentLanguage);
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

    /**
     * getCorporateBonds method it will filter all bond companies
     *
     * @param string $currentLanguage Current Language
     * @return \Cake\ORM\Query
     */
    public function getCorporateBonds($currentLanguage)
    {
        $Companies = TableRegistry::get('Companies');

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

        $Stocks = TableRegistry::get('Stocks');

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
