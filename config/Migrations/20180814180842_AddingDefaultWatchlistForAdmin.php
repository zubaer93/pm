<?php
use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;

class AddingDefaultWatchlistForAdmin extends AbstractMigration
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
        $AppUsers = TableRegistry::get('AppUsers');
        $WatchlistBonds = TableRegistry::get('WatchlistBonds');
        $WatchlistForex = TableRegistry::get('WatchlistForex');
        $WatchlistGroup = TableRegistry::get('WatchlistGroup');

        $users = $AppUsers->find()
            ->where(['role' => 'admin']);

        foreach ($users as $user) {
            $bond = $WatchlistBonds->newEntity();
            $bond->name = 'Default watchlist';
            $bond->is_default = true;
            $bond->user_id = $user->id;
            $WatchlistBonds->save($bond);

            $forex = $WatchlistForex->newEntity();
            $forex->name = 'Default watchlist';
            $forex->is_default = true;
            $forex->user_id = $user->id;
            $WatchlistForex->save($forex);

            $defaultGroup = $WatchlistGroup->newEntity();
            $defaultGroup->name = 'Default watchlist';
            $defaultGroup->is_default = true;
            $defaultGroup->user_id = $user->id;
            $WatchlistGroup->save($defaultGroup);
        }
    }
}
