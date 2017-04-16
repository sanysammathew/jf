<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	$this->setName('register');
        $this->setMethod('POST');
        $this->setAction(SITEPATH.'/register/index');
	
	$username = new Zend_Form_Element_Text('username');
        $username->removeDecorator('label')
			->removeDecorator('htmlTag')
			->addFilter(new Zend_Filter_StringTrim())
			->setAttribs(array('class'=>"required form-control"));
	$first_name = new Zend_Form_Element_Text('first_name');
        $first_name->removeDecorator('label')
			->removeDecorator('htmlTag')
			->addFilter(new Zend_Filter_StringTrim())
			->setAttribs(array('class'=>"required form-control"));
	$last_name = new Zend_Form_Element_Text('last_name');
        $last_name->removeDecorator('label')
			->removeDecorator('htmlTag')
			->addFilter(new Zend_Filter_StringTrim())
			->setAttribs(array('class'=>"required form-control"));
	$email = new Zend_Form_Element_Text('email');
        $email->removeDecorator('label')
			->removeDecorator('htmlTag')
			->addFilter(new Zend_Filter_StringTrim())
			->setAttribs(array('class'=>"required form-control"));
	$password = new Zend_Form_Element_Password('password');
        $password->removeDecorator('label')
			->removeDecorator('htmlTag')
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator('StringLength', false, array(6,24))
			->setAttribs(array('class'=>"required form-control"));
	$confpassword = new Zend_Form_Element_Password('confpassword');
        $confpassword->removeDecorator('label')
			->removeDecorator('htmlTag')
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator(new Zend_Validate_Identical('password'))
			->setAttribs(array('class'=>"required form-control"));
	$terms = new Zend_Form_Element_Checkbox('terms');
        $terms->removeDecorator('label')
			->removeDecorator('htmlTag');

	$submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Register')
			->removeDecorator('htmlTag')
			->setAttribs(array('class'=>"btn btn-danger"));
	$this->addElements(
                array($username,$first_name,$last_name,$email,$password,$confpassword,$terms,$submit));
    }
    
    public function isValid($data)
    {
        $isValid = parent::isValid($data);
	if(!$data['username']){
	    $this->username->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please specify Screen name'));
	    $isValid = false;
	}
	if(!$data['password']){
	    $this->password->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please specify Password'));
	    $isValid = false;
	}
	if(!$data['confpassword']){
	    $this->confpassword->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please confirm Password'));
	    $isValid = false;
	}
	if(!$data['first_name']){
	    $this->first_name->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please specify First name'));
	    $isValid = false;
	}
	if(!$data['last_name']){
	    $this->last_name->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please specify Last name'));
	    $isValid = false;
	}
	/*
	//rajnikant Manual Registration error caused by this below 
	if(!$data['terms']){
	    $this->terms->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please read the Terms and Condition and tick the checkbox'));
	    $isValid = false;
	}
	*/
	if(!$data['email']){
	    $this->email->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please specify email'));
	    $isValid = false;
	}else{
        $validator = new Zend_Validate_EmailAddress();
	    if(!$validator->isValid($data['email'])) {
		$this->email->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Please provide valid Email Id'));
	$isValid = false;
	    }
        $validator = new Zend_Validate_Alnum();
        if (!$validator->isValid($data['username'])) {
            $this->username->setErrors(array(Zend_Validate_NotEmpty::IS_EMPTY=>'Screen name should not contain space or special character'));
	    $isValid = false;
        }
  
	}
	return $isValid;
    }


}

