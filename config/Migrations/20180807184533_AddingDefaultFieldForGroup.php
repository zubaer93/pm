<?php
use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;

class AddingDefaultFieldForGroup extends AbstractMigration
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
        $table = $this->table('watchlist_group');
        $table
            ->addColumn('is_default', 'boolean', [
                'default' => false,
                'null' => false
            ])
            ->save();

        $table = $this->table('watchlist_bonds');
        $table
            ->addColumn('is_default', 'boolean', [
                'default' => false,
                'null' => false
            ])
            ->save();

        $table = $this->table('watchlist_forex');
        $table
            ->addColumn('is_default', 'boolean', [
                'default' => false,
                'null' => false
            ])
            ->save();

        $this->__fixWatchlist();
    }

    /**
     * __fixWatchlist method it will add the default watchlist for all users.
     *
     * @return void
     */
    public function __fixWatchlist()
    {
        $Watchlist = TableRegistry::get('Watchlist');
        $WatchlistGroup = TableRegistry::get('WatchlistGroup');
        
        $userWatchlists = $Watchlist->find()
            ->where(['group_id IS NULL']);

        $watchlists = [];
        foreach ($userWatchlists as $watchlist) {
            $watchlists[$watchlist->user_id][] = $watchlist;
        }

        foreach ($watchlists as $userId => $items) {
            $defaultGroup = $WatchlistGroup->newEntity();
            $defaultGroup->name = 'Default watchlist';
            $defaultGroup->is_default = true;
            $defaultGroup->user_id = $userId;
            $WatchlistGroup->save($defaultGroup);
            foreach ($items as $item) {
                $item->group_id = $defaultGroup->id;
                $Watchlist->save($item);
            }
        }
    }
}
