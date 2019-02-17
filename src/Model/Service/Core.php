<?php

namespace App\Model\Service;

use Cake\Core\Configure;
use Cake\I18n\Time;
use App\Model\Table\RatingsTable;
use Cake\ORM\TableRegistry;

class Core
{

    public static $experience = [
        '1' => 'Curious',
        '2' => 'Beginner',
        '3' => 'Intermediate',
        '4' => 'Expert'
    ];
    public static $investmentStyle = [
        '1' => 'Not sure',
        '2' => 'Longterm',
        '3' => 'Shorterm',
        '4' => 'Daytrader'
    ];
    public static $countryCurrently = [
        '1' => 'Jamaica',
        '2' => 'Barbados',
        '3' => 'USA',
        '4' => 'Trinidad'
    ];
    public static $investmentPreferences = [
        '1' => 'Stock',
        '2' => 'Mutual Fund',
        '3' => 'Forex'
    ];
    public static $orderType = [
        '1' => 'Market',
        '2' => 'Limit'
    ];
    public static $action = [
        '1' => 'Buy',
        '2' => 'Sell',
        '3' => 'Short'
    ];
    public static $action_admin = [
        'Buy' => 'Buy',
        'Sell' => 'Sell',
        'Hold' => 'Hold',
        'Suspended' => 'Suspended'
    ];
    public static $status = [
        '0' => 'New',
        '1' => 'Pending',
        '2' => 'Executed',
        '3' => 'Rejected',
        '4' => 'Canceled',
    ];
    public static $brokers = [
        '1' => 'TD Ameritrade',
        '2' => 'Interactive Brokers',
        '3' => 'Trade station',
        '4' => 'Just2 trade',
        '5' => 'Suretrader',
        '6' => 'Choice trade',
    ];
    public static $required_documents = [
        '1' => 'Passport (Visa)',
        '2' => 'bank statement or utility bill (Showing name and address)',
        '3' => 'W-8 BEN form'
    ];
    public static $market_to_trade = [
        '1' => 'US',
        '2' => 'Jamaican',
        '3' => 'Investment interest Stocks Forex'
    ];
    public static $us_residencyin = [
        '1' => 'Citizen',
        '2' => 'Permanent resident (green card holder)'
    ];
    public static $market = [
        'USD' => 'USA',
        'JMD' => 'Jamaican'
    ];

    public static $market_caps_filter_data = [
        '1_50000000'=>'1 to 50 million',
        '50000000_100000000'=>'50 to 100 million',
        '100000000_300000000'=>'100 million to 300 million',
        '300000000_400000000'=>'300 million to 600 million',
        '500000000_1000000000'=>'600 million million to 1B',
        '1000000000_5000000000'=>'1B to 5B',
        '5000000000_'=>'5B or more',
    ];


    public static $dividend_yield_filter_data = [
        '0_10'=>'0 to 10',
        '10_20'=>'10 to 20',
        '20_30'=>'20 to 30',
        '30_40'=>'30 to 40',
        '40_50'=>'40 to 50',
        '60_70'=>'60 to 70',
        '80_90'=>'80 or 90',
        '90_'=>'90 or more',
    ];

    public static $high_low_price_52_week_filter_data = [
        '0_1'=>'0 to 1',
        '1_2'=>'1 to 2',
        '2_3'=>'2 to 3',
        '3_4'=>'3 to 4',
        '4_5'=>'4 to 5',
        '6_7'=>'6 to 7',
        '8_9'=>'8 or 9',
        '9_'=>'9 or more',
    ];

    public static $ipo_date_filter_data = [
        1=>'Today',
        2=>'Yesterday',
        3=>'In the last week',
        4=>'In the last month',
        5=>'In the last quarter',
        6=>'In the last year',
        7=>'More than a year ago',
        8=>'More than 5 years ago',
        9=>'More than 10 years ago',
        10=>'More than 15 years ago',
        11=>'More than 20 years ago',
        12=>'More than 25 years ago',
    ];

    public static $price_filter_data = [
        "_1"=>"Under $1",
        "_2"=>"Under $2",
        "_3"=>"Under $3",
        "_4"=>"Under $4",
        "_5"=>"Under $5",
        "_7"=>"Under $7",
        "_10"=>"Under $10",
        "_15"=>"Under $15",
        "_20"=>"Under $20",
        "_30"=>"Under $30",
        "_40"=>"Under $40",
        "_50"=>"Under $50",
        "1_"=>"Over $1",
        "2_"=>"Over $2",
        "3_"=>"Over $3",
        "4_"=>"Over $4",
        "5_"=>"Over $5",
        "7_"=>"Over $7",
        "10_"=>"Over $10",
        "15_"=>"Over $15",
        "20_"=>"Over $20",
        "30_"=>"Over $30",
        "40_"=>"Over $40",
        "50_"=>"Over $50",
        "60_"=>"Over $60",
        "70_"=>"Over $70",
        "80_"=>"Over $80",
        "90_"=>"Over $90",
        "100_"=>"Over $100",
        "1_5"=>"$1 to $5",
        "1_10"=>"$1 to $10",
        "1_20"=>"$1 to $20",
        "5_10"=>"$5 to $10",
        "5_20"=>"$5 to $20",
        "5_50"=>"$5 to $50",
        "10_20"=>"$10 to $20",
        "10_50"=>"$10 to $50",
        "20_50"=>"$20 to $50",
        "50_100"=>"$50 to $100",
    ];

    public static $current_volume_filter_data = [
        "_50000"=>"Under 50K",
        "_100000"=>"Under 100K",
        "_500000"=>"Under 500K",
        "_750000"=>"Under 750K",
        "_1000000"=>"Under 1M",
        "0_"=>"Over 0",
        "50000_"=>"Over 50K",
        "100000_"=>"Over 100K",
        "200000_"=>"Over 200K",
        "300000_"=>"Over 300K",
        "400000_"=>"Over 400K",
        "500000_"=>"Over 500K",
        "750000_"=>"Over 750K",
        "1000000_"=>"Over 1M",
        "2000000_"=>"Over 2M",
        "5000000_"=>"Over 5M",
        "10000000_"=>"Over 10M",
        "2000000_"=>"Over 20M",
    ];

    public static $change_filter_data = [
        "1_"=>"Up 1%",
        "2_"=>"Up 2%",
        "3_"=>"Up 3%",
        "4_"=>"Up 4%",
        "5_"=>"Up 5%",
        "6_"=>"Up 6%",
        "7_"=>"Up 7%",
        "8_"=>"Up 8%",
        "9_"=>"Up 9%",
        "10_"=>"Up 10%",
        "15_"=>"Up 15%",
        "20_"=>"Up 20%",
        "_1"=>"Down 1%",
        "_2"=>"Down 2%",
        "_3"=>"Down 3%",
        "_4"=>"Down 4%",
        "_5"=>"Down 5%",
        "_6"=>"Down 6%",
        "_7"=>"Down 7%",
        "_8"=>"Down 8%",
        "_9"=>"Down 9%",
        "_10"=>"Down 10%",
        "_15"=>"Down 15%",
        "_20"=>"Down 20%",
    ];


    public static function getImagePath($path)
    {
        $fullpath = Configure::read('News.image.fullpath') . $path;
        if (file_exists($fullpath)) {
            return Configure::read('News.image.path') . $path;
        }

        return $path;
    }

    public static function getPartnerImagePath($path)
    {
        $fullpath = Configure::read('Partners.image.fullpath') . $path;
        if (file_exists($fullpath)) {
            return Configure::read('Partners.image.path') . $path;
        }

        return $path;
    }

    public static function getPostComment($id)
    {
        $fullpath = Configure::read('Partners.image.fullpath') . $path;
        if (file_exists($fullpath)) {
            return Configure::read('Partners.image.path') . $path;
        }

        return $path;
    }

    public static function modifyDate($param)
    {
        $dateNow = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");

        $date = new Time($dateNow);
        $result = (new Time($date->modify($param), 'America/New_York'))->format("Y-m-d H:i:s");
        return $result;
    }

    public static function getUserRating($user_id)
    {

        $rating = 0;

        if (!is_null($user_id)) {
            $Messages = TableRegistry::get('Messages');
            $messages = $Messages->find('all')
                    ->where(['user_id' => $user_id])
                    ->order(['Messages.modified DESC'])
                    ->contain(['AppUsers'])
                    ->toArray();
            $count = 0;
            $averageStatistically = 0;
            foreach ($messages as $message) {
                $averageStatistically += RatingsTable::getAverageRanking($message['id']);
                $count++;
            }
            if ($count) {
                $rating = (int) ($averageStatistically / $count);
            }
        }

        return $rating;
    }

}
