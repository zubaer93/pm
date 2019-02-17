<?php

namespace App\Controller;

use App\Controller\AppController;

class WatchlistBondsController extends AppController
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
        $watchlistBond = $this->WatchlistBonds->newEntity();
        if ($this->request->is('post')) {
            $watchlistBond = $this->WatchlistBonds->patchEntity($watchlistBond, $this->request->getData());
            $watchlistBond->user_id = $this->Auth->user('id');
            $watchlistBond->is_default = 0;
            $response = $this->WatchlistBonds->add($watchlistBond);

            echo json_encode($response);
            exit;
        }

        $this->redirect(['controller' => 'bonds']);
    }

    /**
     * edit method it will edit the current watchlist
     *
     * @param int $id Watchlist id
     * @return void
     */
    public function edit($id)
    {
        $watchlistBond = $this->WatchlistBonds->get($id);
        if ($this->request->is('post') || $this->request->is('put')) {
            $watchlistBond = $this->WatchlistBonds->patchEntity($watchlistBond, $this->request->getData());
            if ($this->WatchlistBonds->save($watchlistBond)) {
                $this->Flash->success(__('Watchlist was edited successfully.'));
            } else {
                $this->Flash->error(__('Watchlist was not edited. Please try again!'));
            }
            $this->redirect(['controller' => 'watchlist', 'action' => 'showAll']);
        }

        $this->set(compact('watchlistBond'));
    }

    /**
     * addItem method it will add or remove the item to the watchlist.
     *
     * @param string $isinCode Bond code
     * @return void
     */
    public function addItem($isinCode)
    {
        if (empty($this->Auth->user())) {
            $this->render('/AppUsers/modal_login');
        }
        $watchlistItem = $this->WatchlistBonds->WatchlistBondItems->newEntity();
        if ($this->request->is('post')) {
            $watchlistItem = $this->WatchlistBonds->WatchlistBondItems->patchEntity($watchlistItem, $this->request->getData());
            $watchlistItem->user_id = $this->Auth->user('id');
            $watchlistItem->isin_code = $isinCode;
            if ($this->WatchlistBonds->WatchlistBondItems->save($watchlistItem)) {
                $this->Flash->success(__('Bond item was added to your watchlist.'));
            } else {
                $this->Flash->error(__('Bond item was not added to your watchlist.'));
            }
            $this->redirect(['controller' => 'bonds']);
        }

        $watchlistBonds = $this->WatchlistBonds->getList($this->Auth->user('id'));
        $this->set(compact('watchlistItem', 'watchlistBonds'));
    }

    /**
     * removeItem method it will add or remove the item to the watchlist.
     *
     * @param int $traderId Trader id
     * @return void
     */
    public function removeItem($isinCode)
    {
        $this->request->allowMethod(['post', 'delete']);
        $watchlistItem = $this->WatchlistBonds->WatchlistBondItems->find()
            ->where([
                'user_id' => $this->Auth->user('id'),
                'isin_code' => $isinCode
            ])->first();

        if ($this->WatchlistBonds->WatchlistBondItems->delete($watchlistItem)) {
            $this->Flash->success(__('Bond items was removed.'));
        } else {
            $this->Flash->error(__('Bond item was not removed.'));
        }
        $this->redirect(['controller' => 'bonds']);
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
        $watchlistItem = $this->WatchlistBonds->get($id);

        if ($this->WatchlistBonds->delete($watchlistItem)) {
            $this->Flash->success(__('Bond watchlist was removed.'));
        } else {
            $this->Flash->error(__('Bond watchlist was not removed.'));
        }
        $this->redirect(['controller' => 'watchlist', 'action' => 'showAll']);
    }
}
