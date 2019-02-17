<?php

namespace App\Controller;

use App\Controller\AppController;

class WatchlistController extends AppController
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
        $this->components()->unload('Csrf');
    }

    public function toggleWatchList()
    {
        $status = 'error';
        if ($this->Watchlist->updateWatchListItem($this->request->getData(), $this->Auth->user('id'), $this->_getCurrentLanguage())) {
            $status = 'success';
        }

        echo json_encode([
            'status' => $status
        ]);
        exit;
    }

    public function getWatchlist()
    {
        $watchlist = $this->Watchlist->getWatchlist($this->Auth->user('id'), $this->_getCurrentLanguage());

        echo json_encode([
            'watchlist' => $watchlist
        ]);
        exit;
    }

    public function getWatchlistAll()
    {
        $watchlist = $this->Watchlist->getWatchlistAll($this->Auth->user('id'), $this->_getCurrentLanguage());
        echo json_encode([
            'watchlist' => $watchlist
        ]);
        exit;
    }

    public function verifyWatchlist()
    {
        $status = 'error';
        if ($this->Watchlist->hasCompany($this->Auth->user('id'), $this->request->getData())) {
            $status = 'success';
        }

        echo json_encode([
            'status' => $status
        ]);
        exit;
    }

    public function deleteWatchlist($id)
    {
        if ($this->Auth->user('id')) {
            $watchlist = $this->Watchlist->deleteWatchlist($id, $this->Auth->user('id'));

            $this->loadModel('WatchlistGroup');
            $result = $this->WatchlistGroup->deleteRow($id, $this->Auth->user('id'));
            if ($result) {
                $this->Flash->success(__('Watchlist successfully deleted.'));
            } else {
                $this->Flash->error(__('Watchlist was not deleted.'));
            }
        }
        return $this->redirect(['_name' => 'watchlist_all']);
    }

    public function showAll()
    {
        $stockWatchlists = $this->getAllGroup();

        $this->set(compact('stockWatchlists'));
        $this->set('_serialize', ['stockWatchlists']);
    }

    public function getAllGroup()
    {
        $this->loadModel('WatchlistGroup');
        return $this->WatchlistGroup->getAllWatchlists($this->Auth->user('id'));
    }

    public function getWatchlistGroup($group_id)
    {
        $watchlist = $this->Watchlist->getWatchlistGroup($this->Auth->user('id'), $this->_getCurrentLanguage(), $group_id);
        $this->set(compact('watchlist'));
        $this->set('_serialize', ['watchlist']);
    }

    public function createWatchlist()
    {
        $requesData = $this->request->getData();

        if ($this->Auth->user('id') && !empty($requesData)) {
            $this->loadModel('WatchlistGroup');
            $watchlist = $this->WatchlistGroup->createWatchlistGroup($this->Auth->user('id'), $requesData);
            $url = \Cake\Routing\Router::url(['_name' => 'watchlist_delete', 'id' => $watchlist['id']]);
            $watchlist['url'] = $url;

            $this->set(compact('watchlist'));
            $this->set('_serialize', ['watchlist']);
        }
    }

    public function editWatchlist()
    {
        $requesData = $this->request->getQuery();

        if ($this->Auth->user('id') && count($requesData)) {
            $this->loadModel('WatchlistGroup');
            $result = $this->WatchlistGroup->editWatchlistGroup($this->Auth->user('id'), $requesData);
            if ($result) {
                $this->Flash->success(__('Watchlist successfully edeted.'));
            } else {
                $this->Flash->error(__('Watchlist was not edeted.'));
            }
        }
        return $this->redirect(['_name' => 'watchlist_all']);
    }
}
