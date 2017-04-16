<?php

class Application_Form_LoginForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Screen Name / Email<span class="text-danger">*</span>')
                ->addDecorator('Label',array('tag' => 'div', 'class' => 'top-margin', 'escape'=>false))
                ->addDecorator('HtmlTag',
                            array('tag' => 'div', 'class' => 'element'))
                ->addFilter('StripTags')
                ->setAttrib('class', 'required form-control');
                //->setRequired(true);
        $password= new Zend_Form_Element_Password('password');
        $password->setLabel('Password<span class="text-danger">*</span>')
                ->addDecorator('Label',array('tag' => 'div', 'class' => 'top-margin', 'escape'=>false))
                ->addDecorator('HtmlTag',
                            array('tag' => 'div', 'class' => 'element'))
                ->addFilter('StripTags')
                 ->setAttrib('class', 'required form-control');
                //->setRequired(true);
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Sign In')
                 ->addDecorator('HtmlTag',
                            array('tag' => 'div', 'class' => 'element'))
               ->setAttrib('class', 'btn btn-danger');
        $this->addElements(array($username,$password,$submit));
    }

	
	public function isValid($data)
    {
        $isValid = parent::isValid($data);
		if(!$data['username']){
			$this->username->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please enter Screen name'));
			$isValid = false;
		}
		if(!$data['password']){
			$this->password->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please enter Password'));
			$isValid = false;
		}
		return $isValid;
	}
	
}

