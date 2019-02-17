<?php

namespace App\Shell;

use Cake\Console\Shell;

class InvitationShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Api.AppUsers');
        $this->sendInvitation();
    }

    public function sendInvitation()
    {
        $userEmail = $this->AppUsers->find()
                    //->where(['stripe_id !=' => ''])
                    ->where(['active' => 1])
                    ->select(['id','first_name','last_name','email'])
                    ->toArray();

        foreach($userEmail as $user){
             
            //$invitation_code = strtoupper(uniqid()); //generate a unique code
            $invitation_code = strtoupper(substr(md5(uniqid($user['id'], true)), 22, 32)); //generate a unique code
            $date = strtotime("+7 day");
            $inv_code_exp = date('d M Y', $date);
            $options = array('template' => 'invitation_code',
                    'to' => $user['email'], 'invitation_code' => $invitation_code, 
                    'user_details' => $user , 'inv_code_exp' => $inv_code_exp,
                    'subject' => 'Stockgitter Invitation');
            
            // send email with activation code
            if($this->send_email($options)) {
                $user->invitation_code = $invitation_code;
                $user->inv_code_exp = date("Y-m-d", strtotime($inv_code_exp));
                $this->AppUsers->save($user); 
                echo "Email sent successfully to ".$user['email']."\n";
            }
            else{
                echo "Cannot sent email to ".$user['email']."\n";
            }
        }       
    }

    public function send_email($options){
        $template = $options['template'];
        $email = new \Cake\Mailer\Email();

        if (!empty($options['invitation_code'])) {
            $email->viewVars(array('invitation_code' => $options['invitation_code']));
        }
        if (!empty($options['user_details'])) {
            $email->viewVars(array('user_details' => $options['user_details']));
        }
        if (!empty($options['subject'])) {
            $email->viewVars(array('subject' => $options['subject']));
        }
        if (!empty($options['inv_code_exp'])) {
            $email->viewVars(array('inv_code_exp' => $options['inv_code_exp']));
        }
        try {
            $email->template($template, 'email_layout')
                ->emailFormat('html')
                ->to($options['to'])
                ->from('donotreply@leapinglogic.com')
                ->subject($options['subject'])
                ->send();
            return true;
        } 
        catch (\SocketException $exception) {
            return false;
        }
    }

}