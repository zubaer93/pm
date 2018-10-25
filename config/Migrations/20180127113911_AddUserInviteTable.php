<?php
use Migrations\AbstractMigration;

class AddUserInviteTable extends AbstractMigration
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
        $table = $this->table('user_invite');

        $table->addColumn('user_id','char',[
            'default'=>null,
            'null'=>true,
            ])
            ->addColumn('invite_id','integer',[
                'default'=>null,
                'null'=>true,
            ])
            ->addColumn('created_at','timestamp',[
                'default' => 'CURRENT_TIMESTAMP'
            ]);

        $table->addForeignKey('invite_id', 'private', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();

    }
}
