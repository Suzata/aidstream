<?php
class Simplified_Form_Activity_Budget extends Iati_Form
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['start_id'] = new Zend_Form_Element_Hidden('start_id');
        $form['start_id']->setValue($this->data['start_id']);
        
        $form['end_id'] = new Zend_Form_Element_Hidden('end_id');
        $form['end_id']->setValue($this->data['end_id']);
        
        $form['value_id'] = new Zend_Form_Element_Hidden('value_id');
        $form['value_id']->setValue($this->data['value_id']);
        $this->addElements($form);

        $form['amount'] = new Zend_Form_Element_Text('amount');
        $form['amount']->setLabel('Amount')
            ->setValue($this->data['amount'])
            ->addValidator(new App_Validate_Numeric())
            ->setAttrib('class', 'form-text');

        $form['start_date'] = new Zend_Form_Element_Text('start_date');
        $form['start_date']->setLabel('Start Date')
            ->setValue($this->data['start_date'])
            ->setAttrib('class', 'form-text datepicker');

        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('End Date')
            ->setValue($this->data['end_date'])
            ->setAttrib('class', 'form-text datepicker');
            
        $currency = $model->getCodeArray('Currency' , '' , 1 , true);
        $form['currency'] = new Zend_Form_Element_Select('currency');
        $form['currency']->setLabel('Currency')
            ->addMultiOptions($currency)
            ->setValue($this->data['currency'])
            ->setAttrib('class', 'form-select');
        
        $form['signed_date'] = new Zend_Form_Element_Text('signed_date');
        $form['signed_date']->setLabel('Contract Signed  Date')
            ->setValue($this->data['signed_date'])
            ->setAttrib('class', 'form-text datepicker');

        $this->addElements($form);

        $this->setElementsBelongTo("budget[{$this->count}]");
        // Add remove button
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
        $remove->setValue("<a href='#' class='button' value='Budget'> Remove element</a>");
        $this->addElement($remove);
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
}