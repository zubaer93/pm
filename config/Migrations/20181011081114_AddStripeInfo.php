<?php
use Migrations\AbstractMigration;

class AddStripeInfo extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('stripe_data', 'string', [
            'limit' => 2048,
            'null' => true,
        ]);
        $table->save();

    }
}
