<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;

/**
 * Contacts Controller
 *
 * @property \App\Model\Table\ContactsTable $Contacts
 *
 * @method \App\Model\Entity\Country[] paginate($object = null, array $settings = [])
 */
class ContactsController extends AppController
{

    /**
     * send method
     *
     * @return \Cake\Http\Response|void
     */
    public function send()
    {
        $email = new Email('default');
        $email->from([$this->request->getData('email') => 'Subscribe'])
            ->to('stockgitter.info@gmail.com')
            ->subject('Subscribe')
            ->send('Hey, I have subscribed, let me know when the site is online. My email is:' . $this->request->getData('email'));

        $this->redirect('/');
    }

    /**
     * sendModal method
     *
     * @return \Cake\Http\Response|void
     */
    public function sendModal()
    {
        $email = new Email('default');
        $subject = $this->request->getData('subject') . ' - ' . $this->Contacts->getDepartments($this->request->getData('department'));
        $message = $this->request->getData('message') . ' - My phone: ' . $this->request->getData('phone') . ' - My email: ' . $this->request->getData('email');
        $email->from([$this->request->getData('email') => 'Subscribe'])
            ->to('stockgitter.info@gmail.com')
            ->subject($subject)
            ->send($message);

        $this->redirect('/');
    }
}
