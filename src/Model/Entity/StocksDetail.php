<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StocksDetail Entity
 *
 * @property int $id
 * @property int $company_id
 * @property string $high_price_52_week
 * @property string $high_price_52_ind
 * @property string $low_price_52_week
 * @property string $low_price_52_ind
 * @property string $days_high_price
 * @property string $days_low_price
 * @property string $close_price
 * @property string $close_net_change
 * @property string $close_percent_change
 * @property string $last_traded_price
 * @property string $bid_price
 * @property string $ask_price
 * @property string $total_traded_volume
 * @property string $trade_value
 * @property string $num_of_trades
 * @property string $market_cap
 * @property string $totalissuedshares
 * @property string $pre_dividend_amount
 * @property string $pre_div_curr
 * @property string $dividend_amount
 * @property string $div_curr
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\Company $company
 */
class StocksDetail extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
