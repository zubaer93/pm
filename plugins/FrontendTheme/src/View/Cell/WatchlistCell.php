<?php

namespace FrontendTheme\View\Cell;

use Cake\View\Cell;

class WatchlistCell extends Cell
{
    /**
     * bonds method this method will show all parters created in the admin section
     *
     * @param bool $all Check if the cell is being called from show all watchlist page
     * @return void
     */
    public function bonds($all = false)
    {
        $this->loadModel('Bonds');
        $this->loadModel('WatchlistBonds');

        if ($all) {
            $bonds = $this->WatchlistBonds->getAllWatchlists($this->request->session()->read('Auth.User.id'));
        } else {
            $bonds = $this->WatchlistBonds->getWatchlists($this->request->session()->read('Auth.User.id'));
        }

        $this->__setAuthUser();

        $this->set(compact('bonds', 'all'));
    }

    /**
     * forex method this will show all watchlist sta
     *
     * @param bool $all Check if the cell is being called from show all watchlist page
     * @return void
     */
    public function forex($all = false)
    {
        $this->loadModel('WatchlistForex');

        if ($all) {
            $forex = $this->WatchlistForex->getAllWatchlists($this->request->session()->read('Auth.User.id'));
        } else {
            $forex = $this->WatchlistForex->getWatchlists($this->request->session()->read('Auth.User.id'));
        }

        $this->__setAuthUser();
        $this->set(compact('forex', 'all'));
    }

    /**
     * __setAuthUser method it will set the logged user to the view.
     *
     * @return void
     */
    private function __setAuthUser()
    {
        $authUser = $this->request->session()->read('Auth');

        $accountType = 'FREE';
        if (!empty($authUser)) {
            $accountType = $authUser['User']['account_type'];
        }

        $this->set(compact('authUser', 'accountType'));
    }
}