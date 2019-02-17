<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Exchanges Controller
 *
 * @property \App\Model\Table\ExchangesTable $Exchanges
 *
 * @method \App\Model\Entity\Exchange[] paginate($object = null, array $settings = [])
 */
class ExchangesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Countries']
        ];
        $exchanges = $this->paginate($this->Exchanges);

        $this->set(compact('exchanges'));
        $this->set('_serialize', ['exchanges']);
    }

    /**
     * View method
     *
     * @param string|null $id Exchange id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exchange = $this->Exchanges->get($id, [
            'contain' => ['Countries', 'Companies']
        ]);

        $this->set('exchange', $exchange);
        $this->set('_serialize', ['exchange']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $exchange = $this->Exchanges->newEntity();
        if ($this->request->is('post')) {
            $exchange = $this->Exchanges->patchEntity($exchange, $this->request->getData());
            if ($this->Exchanges->save($exchange)) {
                $this->Flash->success(__('The exchange has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exchange could not be saved. Please, try again.'));
        }
        $countries = $this->Exchanges->Countries->find('list', ['limit' => 200]);
        $this->set(compact('exchange', 'countries'));
        $this->set('_serialize', ['exchange']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Exchange id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exchange = $this->Exchanges->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exchange = $this->Exchanges->patchEntity($exchange, $this->request->getData());
            if ($this->Exchanges->save($exchange)) {
                $this->Flash->success(__('The exchange has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exchange could not be saved. Please, try again.'));
        }
        $countries = $this->Exchanges->Countries->find('list', ['limit' => 200]);
        $this->set(compact('exchange', 'countries'));
        $this->set('_serialize', ['exchange']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Exchange id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exchange = $this->Exchanges->get($id);
        if ($this->Exchanges->delete($exchange)) {
            $this->Flash->success(__('The exchange has been deleted.'));
        } else {
            $this->Flash->error(__('The exchange could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
