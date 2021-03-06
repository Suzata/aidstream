<?php

class Form_General_Support extends App_Form
{
    public function init()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        $user = $model->getRowById('profile', 'user_id', $identity->user_id);
        $this->setAttrib('id', 'support-form')
            ->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/user/user/support');
            
        $form['referer'] = new Zend_Form_Element_Hidden('referer');
        $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        $form['referer']->setValue($uri);

        $form['support_name'] = new Zend_Form_Element_Text('support_name');
        $form['support_name']->setLabel('Name')
            ->setRequired()
            ->setValue($user['first_name']." ".$user['last_name'])
            ->setAttrib('readonly' ,'true')
            ->addErrorMessage('Please enter your name');

        $form['support_email'] = new Zend_Form_Element_Text('support_email');
        $form['support_email']->setLabel('Email')
            ->setRequired()
            ->setValue($identity->email)
            ->setAttrib('readonly' ,'true')
            ->addErrorMessage('Please enter your email');

        $form['support_query'] = new Zend_Form_Element_Textarea('support_query');
        $form['support_query']->setLabel('Query')
            ->setRequired()
            ->setAttrib('rows','10')
            ->addErrorMessage('Please enter your query');

        $form['support_submit'] = new Zend_Form_Element_Submit('support_submit');
        $form['support_submit']->setLabel('Send now!');

        $this->addElements($form);

    }
}