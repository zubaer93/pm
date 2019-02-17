<?php

namespace App\Controller;

use App\Controller\AppController;

class WatchlistForexController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }

    /**
     * add method it will add a new watchlist group.
     *
     * @return void
     */
    public function add()
    {
        $watchlistForex = $this->WatchlistForex->newEntity();
        if ($this->request->is('post')) {
            $watchlistForex = $this->WatchlistForex->patchEntity($watchlistForex, $this->request->getData());
            $watchlistForex->user_id = $this->Auth->user('id');
            $watchlistForex->is_default = 0;
            $response = $this->WatchlistForex->add($watchlistForex);

            echo json_encode($response);
            exit;
        }

        $this->redirect(['controller' => 'fx']);
    }

    /**
     * edit method it will edit the current watchlist
     *
     * @param int $id Watchlist id
     * @return void
     */
    public function edit($id)
    {
        $watchlistForex = $this->WatchlistForex->get($id);
        if ($this->request->is('post') || $this->request->is('put')) {
            $watchlistForex = $this->WatchlistForex->patchEntity($watchlistForex, $this->request->getData());
            if ($this->WatchlistForex->save($watchlistForex)) {
                $this->Flash->success(__('Watchlist was edited successfully.'));
            } else {
                $this->Flash->error(__('Watchlist was not edited. Please try again!'));
            }
            $this->redirect(['controller' => 'watchlist', 'action' => 'showAll']);
        }

        $this->set(compact('watchlistForex'));
    }

    /**
     * addItem method it will add or remove the item to the watchlist.
     *
     * @param int $traderId Trader id
     * @return void
     */
    public function addItem($traderId)
    {
        if (empty($this->Auth->user())) {
            $this->render('/AppUsers/modal_login');
        }
        $watchlistItem = $this->WatchlistForex->WatchlistForexItems->newEntity();
        if ($this->request->is('post')) {
            $watchlistItem = $this->WatchlistForex->WatchlistForexItems->patchEntity($watchlistItem, $this->request->getData());
            $watchlistItem->user_id = $this->Auth->user('id');
            $watchlistItem->trader_id = $traderId;
            if ($this->WatchlistForex->WatchlistForexItems->save($watchlistItem)) {
                $this->Flash->success(__('Forex item was added to your watchlist.'));
            } else {
                $this->Flash->error(__('Forex item was not added to your watchlist.'));
            }
            $this->redirect(['controller' => 'fx']);
        }

        $watchlistForex = $this->WatchlistForex->getList($this->Auth->user('id'));

        $this->set(compact('watchlistItem', 'watchlistForex'));
    }

    /**
     * removeItem method it will add or remove the item to the watchlist.
     *
     * @param int $traderId Trader id
     * @return void
     */
    public function removeItem($traderId)
    {
        $this->request->allowMethod(['post', 'delete']);
        $watchlistItem = $this->WatchlistForex->WatchlistForexItems->find()
            ->where([
                'user_id' => $this->Auth->user('id'),
                'trader_id' => $traderId
            ])->first();

        if ($this->WatchlistForex->WatchlistForexItems->delete($watchlistItem)) {
            $this->Flash->success(__('Forex items was removed.'));
        } else {
            $this->Flash->error(__('Forex item was not removed.'));
        }
        $this->redirect(['controller' => 'fx']);
    }

    /**
     * delete method it will add or remove the item to the watchlist.
     *
     * @param int $id Watchlist Forex id
     * @return void
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $watchlistItem = $this->WatchlistForex->get($id);

        if ($this->WatchlistForex->delete($watchlistItem)) {
            $this->Flash->success(__('Forex watchlist was removed.'));
        } else {
            $this->Flash->error(__('Forex watchlist was not removed.'));
        }
        $this->redirect(['controller' => 'watchlist', 'action' => 'showAll']);
    }
}
