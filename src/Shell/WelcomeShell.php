<?php

namespace App\Shell;

use Cake\Console\Shell;

class WelcomeShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('AppUsers');
    }

    public function main()
    {
        $users = $this->AppUsers->find()
            ->select('id')
            ->where(['active' => 1]);
        
        foreach ($users as $user) {
            $this->AppUsers->welcomeEmail($user);
        }
    }

}