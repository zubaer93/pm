<?php
use Migrations\AbstractMigration;

class Indexes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->execute('ALTER TABLE companies ADD INDEX symbsol (symbol)');
        $this->execute('ALTER TABLE companies ADD INDEX name (name)');
        $this->execute('ALTER TABLE stocks ADD INDEX stock_company (company_id)');
        $this->execute('ALTER TABLE companies ADD INDEX company_exchange (exchange_id)');
        $this->execute('ALTER TABLE news ADD INDEX market (market)');
        $this->execute('ALTER TABLE rate ADD INDEX trader (trader_id, last_refreshed);');
    }
}
