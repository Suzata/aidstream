<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('layout_wep');
    }

    public function indexAction()
    {
        $this->_helper->layout->setLayout('layout_wep_index');
     
  		$form = new Form_Wep_Contact();
  		$this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if ($form->isValid($formData)) {
            	$data = array();
            	$data['name'] = $form->getValue('name');
            	$data['email'] = $form->getValue('email');
            	$data['message'] = $form->getValue('message');
            	
                $contact = new Model_Contact;
              	$contact->insert($data);
              	
                $mail['subject'] = 'Feedback for Aidtype received';
                $mail['to'] = 'bhabishyat.kc@yipl.com.np';
                

                $mail['message'] = 'The following user provided feedback:';
                $mail['message'] .=  "\nName: ".$data['name'];
                $mail['message'] .=  "\nEmail: ".$data['email'];
                $mail['message'] .= "\n\nMessage:\n".$data['message'];
                
                $modelMail = new Model_Mail();
                $modelMail->sendMail($mail);
               	$this->_helper->FlashMessenger->addMessage(array('message' => 'Thank you for the message.'));
  				$this->_redirect('#contacts');
  				            	
            }
        }    
    }
}

