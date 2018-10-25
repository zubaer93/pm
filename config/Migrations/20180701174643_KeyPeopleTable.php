<?php
use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;
use Cake\ORM\Query;

class KeyPeopleTable extends AbstractMigration
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
        $table = $this->table('key_people');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('age', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('company_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false
            ])
            ->addColumn('photo', 'string', [
                'default' => 'no-user.png',
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

            // $companies = $this->__loadCompanies();

            // $this->__fixKeypeople($companies);

        // $table = $this->table('companies');
        // $table->removeColumn('key_people')
        //     ->save();
    }

    /**
     * __loadCompanies method get all companies that are needing to be fixed.
     *
     * @return Query
     */
    public function __loadCompanies()
    {
        $Companies = TableRegistry::get('Companies');

        return $Companies->find()
            ->where(['key_people IS NOT' => null])
            ->where(['key_people IS NOT' => '']);
    }

    /**
     * fixKeypeople method it will convert the field values into the key people table
     *
     * @param Query $companies Companies where we have keypeople.
     * @return void
     */
    public function __fixKeypeople(Query $companies)
    {
        $KeyPeople = TableRegistry::get('KeyPeople');
        $Companies = TableRegistry::get('Companies');
        foreach ($companies as $key => $company) {
            $keyPeople = json_decode($company->key_people, true);
            if ($keyPeople) {
                foreach ($keyPeople as $keyPerson) {
                    if (!empty($keyPerson['name']) || !empty($keyPerson['title']) || !empty($keyPerson['age'])) {
                        $newKeyPeople = $KeyPeople->newEntity();
                        $newKeyPeople->name = $keyPerson['name'];
                        $newKeyPeople->title = $keyPerson['title'];
                        $newKeyPeople->age = $keyPerson['age'];
                        $newKeyPeople->photo = 'no-user.png';
                        $newKeyPeople->company_id = $company->id;
                        $KeyPeople->save($newKeyPeople);
                    }
                }
            }
        }
    }
}
