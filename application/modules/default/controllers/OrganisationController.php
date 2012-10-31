<?php
/**
 * Controller to handle actions to organisation data. It handles all CRUD and other operations for organisation.
 * @author YIPL Dev team
 */

class OrganisationController extends Zend_Controller_Action
{
    public function init()
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/published-list.phtml');

        // for role user check if the user has permission to add, publish ,if not disable menu.
        if($identity->role == 'user'){
            $model = new Model_Wep();
            $userPermission = $model->getUserPermission($identity->user_id);
            $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::ADD_ACTIVITY);
            $publishPermission = $userPermission->hasPermission(Iati_WEP_PermissionConts::PUBLISH);
            if(!$permission){
                $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
            }
            if(!$publishPermission){
                $this->view->blockManager()->disable('partial/published-list.phtml');
            }
        }
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
    }
    
    public function addAction()
    {
        $elementClass = $this->_getParam('classname');
        $parentId = $this->_getParam('parent_id');

        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        $elementName =  "Iati_Organisation_Element_".$elementClass;
        $element = new $elementName();
        
        if($data = $this->getRequest()->getPost()){
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if($form->validate()){
                $id = $element->save($data[$element->getClassName()] , $parentId);
                $this->_helper->FlashMessenger->addMessage(array('message' => "Data has been sucessfully saved."));
                if($parentId){
                    $idParam = "parent_id={$parentId}";
                } else {
                    $idParam = "id={$id}";
                }
                $this->_redirect("/organisation/edit?classname={$elementClass}&${idParam}");
            } else {
                $form->populate($data);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
            
        } else {
            $form = $element->getForm();            
        }
        $form->addSubmitButton('Save');
        $this->view->form = $form;
    }
    
    public function editAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        $parentId = $this->_getParam('parent_id');
        
        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        if(!$eleId && !$parentId){
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");  
        }
        
        $elementName =  "Iati_Organisation_Element_".$elementClass;
        $element = new $elementName();
    
        if($data = $this->getRequest()->getPost()){
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if($form->validate()){
                $element->save($data[$element->getClassName()] , $parentId);
                $this->_helper->FlashMessenger->addMessage(array('message' => "Data updated sucessfully."));
            } else {
                $form->populate($data);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
        } else {
            if($parentId){
                $data[$element->getClassName()] = $element->fetchData($parentId , true);
            } else {
                $data = $element->fetchData(array($eleId));
            }
            if(empty($data[$element->getClassName()])){
                $this->_helper->FlashMessenger->addMessage(array('info' => "Data not found for the element. Please add new data"));
                $this->_redirect("/organisation/add?classname=$elementClass");
            }
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();            
        }
        $form->addSubmitButton('Save');
        $this->view->form = $form;
        
    }
    
    public function deleteAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        if(!$eleId){
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");  
        }
        
        $elementName =  "Iati_Organisation_Element_".$elementClass;
        $element = new $elementName();
        $element->deleteElement($eleId);
        
        $this->_helper->FlashMessenger->addMessage(array('message' => "Element Deleted sucessfully."));
        $this->_redirect("/wep/dashboard"); 
    }
    
    public function generateXmlAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        if(!$eleId){
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");  
        }
        
        $elementName =  "Iati_Organisation_Element_".$elementClass;
        $element = new $elementName();
        $xmlObj = $element->getXml($eleId);
        if($xmlObj){
            echo ($xmlObj->asXml());exit;
        } else {
            echo "Sorry no data found";exit;
        }
    }
    
}