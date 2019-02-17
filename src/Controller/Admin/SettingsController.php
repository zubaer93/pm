<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Settings Controller
 *
 *
 * @method \App\Model\Entity\Penny[] paginate($object = null, array $settings = [])
 */
class SettingsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $settings = $this->Settings->find()->first();

        $this->set(compact('settings'));
        $this->set('_serialize', ['settings']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Penny id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if (!$this->request->getData('enabled_penny')) {
                $this->request->data('enabled_penny', false);
            }
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please, try again.'));
        }
        $this->set(compact('setting'));
        $this->set('_serialize', ['setting']);
    }
}
