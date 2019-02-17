<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * SectorPerformances Controller
 *
 *
 * @method \App\Model\Entity\SectorPerformance[] paginate($object = null, array $settings = [])
 */
class SectorPerformancesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function all()
    {
        $sectorPerformances = $this->paginate($this->SectorPerformances);

        $this->set(compact('sectorPerformances'));
        $this->set('_serialize', ['sectorPerformances']);
    }

    /**
     * View method
     *
     * @param string|null $id Sector Performance id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sectorPerformance = $this->SectorPerformances->get($id, [
            'contain' => []
        ]);

        $this->set('sectorPerformance', $sectorPerformance);
        $this->set('_serialize', ['sectorPerformance']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sectorPerformance = $this->SectorPerformances->newEntity();
        if ($this->request->is('post')) {
            $sectorPerformance = $this->SectorPerformances->patchEntity($sectorPerformance, $this->request->getData());
            if ($this->SectorPerformances->save($sectorPerformance)) {
                $this->Flash->success(__('The sector performance has been saved.'));

                return $this->redirect(['_name' => 'sector_performances']);
            }
            $this->Flash->error(__('The sector performance could not be saved. Please, try again.'));
        }
        $this->set(compact('sectorPerformance'));
        $this->set('_serialize', ['sectorPerformance']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sector Performance id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sectorPerformance = $this->SectorPerformances->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sectorPerformance = $this->SectorPerformances->patchEntity($sectorPerformance, $this->request->getData());
            if ($this->SectorPerformances->save($sectorPerformance)) {
                $this->Flash->success(__('The sector performance has been saved.'));

                return $this->redirect(['_name' => 'sector_performances']);
            }
            $this->Flash->error(__('The sector performance could not be saved. Please, try again.'));
        }
        $this->set(compact('sectorPerformance'));
        $this->set('_serialize', ['sectorPerformance']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sector Performance id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    { 
//        $this->request->allowMethod(['post', 'delete']);
       
        $sectorPerformance = $this->SectorPerformances->get($id);
        
        if ($this->SectorPerformances->delete($sectorPerformance)) {
            $this->Flash->success(__('The sector performance has been deleted.'));
        } else {
            $this->Flash->error(__('The sector performance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['_name' => 'sector_performances']);
    }

}
