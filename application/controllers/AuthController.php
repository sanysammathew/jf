<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthController
 *
 * @author FABT SAM
 */
class AuthController extends Zend_Controller_Action
{
  
    public function loginAction()
    {	
	$this->view->isNew = $isNewlyReg = $this->getParam('reg');
	$this->view->forgot = $isNewlyReg = $this->getParam('forgot');
        $users = new Application_Model_DbTable_Users();
        $form = new Application_Form_LoginForm();
        $this->view->form = $form;
        $this->view->flogin = $this->_flogin();
        $this->view->glogin = $this->_glogin();
        $this->view->form = $form;
        if($this->getRequest()->isPost()) {
            if($form->isValid($_POST)) {
                $data = $form->getValues();
                $auth = Zend_Auth::getInstance();
                $authAdapter = new Sany_Auth_Adapter_DbTable($users->getAdapter(),'users');
                $authAdapter->setIdentityColumn('username')->setIdentityColumn2('email')->setCredentialColumn('password');
                $authAdapter->setIdentity($data['username'])->setCredential(base64_encode($data['password']));
                $result = $auth->authenticate($authAdapter);
		$userData = $authAdapter->getResultRowObject();

                if($result->isValid() && $userData->status == 'Y') {
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($userData);
                    $mysession = new Zend_Session_Namespace('mysession');
                    if(isset($mysession->destination_url) && $isNewlyReg!=1) {
                        $url = $mysession->destination_url;
                        unset($mysession->destination_url);
                        $this->_redirect($url);
                    }
                    $this->_redirect('index/index');
                } elseif($userData->status == 'N') {
                    $this->view->errorMessage = "<div class='autherror'> User is disabled, please contact admin </div>";
		    $storage = new Zend_Auth_Storage_Session();
		    $storage->clear();
                }else{
		    $this->view->errorMessage = "<div class='autherror'> Invalid email or password. Please try again. </div>";
		    $storage = new Zend_Auth_Storage_Session();
		    $storage->clear();
		}
            }
        }
    }
 
    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('index/index');
     }
     
    public function forgotAction()
    {
        $form = new Application_Form_Forgot();
        $this->view->form = $form;
	$formData = $this->_request->getPost();	
	if($this->getRequest()->isPost()) {
	    $isValid = $form->isValid($formData);
	    if($isValid){
		$email = trim($formData['email']);
		$mapper_Users = new Application_Model_Mapper_Users();
		$result = $mapper_Users->getData(array('field'=>'email','value'=>$email));
		if(!empty($result)){
		    $body='Hi,<br/><br/>Your Password is:';
		    $body.=base64_decode($result[0]['password']);
		    $body.='<br/><br/>Regards,<br/>Admin SportsFanatix.';
		    $subject='SportsFanatix password credentials';
                   // $headers = 'From: admin@sportsfanatix.com' . "\r\n" .
                                //'Reply-To: admin@sportsfanatix.com' . "\r\n" .
                              //  'X-Mailer: PHP/' . phpversion();


                    $headers = "From: noreply@sportzfanatix.com\r\n"; 
                    $headers.= "MIME-Version: 1.0\r\n"; 
                    $headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
                    $headers.= "X-Priority: 1\r\n";
                    mail($email, $subject, $body, $headers);
			
                    //$this->view->errorMessage = "<div class='autherror'> Password is mailed to your registered email address. </div>";
		    $this->_redirect(SITEPATH.'/auth/login/forgot/1');
		}else{
		    $this->view->errorMessage = "<div class='autherror'> This email does not exist in our system. Please try again. </div>";
		}
	    }
	    
	}
    }
    
    protected function _flogin()
    {	
	$config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
	$facebookConfig = $config->getOption('facebook');
	$fb = new Facebook\Facebook([
	'app_id' => $facebookConfig['appid'],// Replace {app-id} with your app id
	'app_secret' => $facebookConfig['appsecret'],
	'default_graph_version' => 'v2.4',
	]);

      $helper = $fb->getRedirectLoginHelper();

      $permissions = ['email']; // Optional permissions
      $loginUrl = $helper->getLoginUrl($facebookConfig['fb-callback-url'], $permissions);

      return $loginUrl;
    }
    
    public function faceloginAction()
    {
	// Disable the main layout renderer
	$this->_helper->layout->disableLayout();
	// Do not even attempt to render a view
	$this->_helper->viewRenderer->setNoRender(true);
	$config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
	$facebookConfig = $config->getOption('facebook');
	$fb = new Facebook\Facebook([
	    'app_id' => $facebookConfig['appid'], // Replace {app-id} with your app id
	    'app_secret' => $facebookConfig['appsecret'],
	    'default_graph_version' => 'v2.4',
	    ]);

	  $helper = $fb->getRedirectLoginHelper();

	  try {
	    $accessToken = $helper->getAccessToken();
	  } catch(Facebook\Exceptions\FacebookResponseException $e) {
	    // When Graph returns an error
	    //echo 'Graph returned an error: ' . $e->getMessage();
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err1');
	    exit;
	  } catch(Facebook\Exceptions\FacebookSDKException $e) {
	    // When validation fails or other local issues
//	    /echo 'Facebook SDK returned an error: ' . $e->getMessage();
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err2');
	    exit;
	  }

	  if (! isset($accessToken)) {
/*	    if ($helper->getError()) {
	      header('HTTP/1.0 401 Unauthorized');
	      echo "Error: " . $helper->getError() . "\n";
	      echo "Error Code: " . $helper->getErrorCode() . "\n";
	      echo "Error Reason: " . $helper->getErrorReason() . "\n";
	      echo "Error Description: " . $helper->getErrorDescription() . "\n";
	    } else {
	      header('HTTP/1.0 400 Bad Request');
	      echo 'Bad request';
	    }*/
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err3');
	    exit;
	  }

	  // Logged in

	  // The OAuth 2.0 client handler helps us manage access tokens
	  $oAuth2Client = $fb->getOAuth2Client();

	  // Get the access token metadata from /debug_token
	  $tokenMetadata = $oAuth2Client->debugToken($accessToken);


	  // Validation (these will throw FacebookSDKException's when they fail)
	  $tokenMetadata->validateAppId($facebookConfig['appid']); // Replace {app-id} with your app id
	  // If you know the user ID this access token belongs to, you can validate it here
	  //$tokenMetadata->validateUserId('123');
	  $tokenMetadata->validateExpiration();

	  if (! $accessToken->isLongLived()) {
	    // Exchanges a short-lived access token for a long-lived one
	    try {
	      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	    } catch (Facebook\Exceptions\FacebookSDKException $e) {
	      //echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
	      $this->_redirect(SITEPATH.'/auth/login/flogin/err4');
	      exit;
	    }

	  }
	  $accessTk = $accessToken->getValue();
	  $_SESSION['fb_access_token'] = (string) $accessToken;

	  try {
	    // Returns a `Facebook\FacebookResponse` object
	    $response = $fb->get('/me?fields=id,name,email,first_name,last_name,middle_name', $accessTk);
	  } catch(Facebook\Exceptions\FacebookResponseException $e) {
	    //echo 'Graph returned an error: ' . $e->getMessage();
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err5');
	    exit;
	  } catch(Facebook\Exceptions\FacebookSDKException $e) {
	    //echo 'Facebook SDK returned an error: ' . $e->getMessage();
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err6');
	    exit;
	  }

	  $user = $response->getGraphUser();
	  $id = 'f'.trim($user->getId());
	  $fname = trim($user->getFirstName());
	  //$mname = $user->getMiddleName();
	  $lname = trim($user->getLastName());
	  $email = trim($user->getEmail());
	  #code for insert into db
	  if(!empty($email)){
	      $userMapper = new Application_Model_Mapper_Users();
	      $emres = $userMapper->getData(array('field'=>'email','value'=>$email));	      
	      #checking if user already exist
	      if(!empty($emres[0])){
		$password = base64_decode($emres[0]['password']);
                //inserting userdetails
                $userdetailsMapper = new Application_Model_Mapper_Usersdetails();
                $dbData = $userdetailsMapper->getUserDetails(array('userid'=>$emres[0]['id']));
                if(!empty($dbData[0])){			
                    $userdetailsData = $userdetailsMapper->loadModel(array(
                        'id'=>$dbData[0]['id'],
                        'userid'=>$emres[0]['id'],
                        'firstname'=>$fname,
                        'lastname'=>$lname,
                        'recorddate'=>date('YmdHis')
                    ), NULL);			
                }else{
                    $userdetailsData = $userdetailsMapper->loadModel(array(
                        'userid'=>$emres[0]['id'],
                        'firstname'=>$fname,
                        'lastname'=>$lname,
                        'recorddate'=>date('YmdHis')
                    ), NULL);	
                }	  
                $userdetailsMapper->save($userdetailsData);
		  
	      }else{
		$password = md5(uniqid(rand(), true));
		$usersData = $userMapper->loadModel(array(
							    'id'=>null,
							    'username'=>  $id,
							    'password'=>  base64_encode($password),
							    'email'=>$email,
							    'id_role'=>'user',
							    'status'=>'Y',
							    'recorddate'=>date('YmdHis'),
							    ), NULL);
                
		$insResult = $userMapper->save($usersData);
		if($insResult){
		    //inserting userdetails
		    $dbuserData = $userMapper->getData(array('field'=>'email','value'=>$email));
		    $userdetailsMapper = new Application_Model_Mapper_Usersdetails();
                    $dbData = $userdetailsMapper->getUserDetails(array('userid'=>$dbuserData[0]['id']));
		    if(!empty($dbData[0])){			
			$userdetailsData = $userdetailsMapper->loadModel(array(
			    'id'=>$dbData[0]['id'],
			    'userid'=>$dbuserData[0]['id'],
			    'firstname'=>$fname,
			    'lastname'=>$lname,
			    'recorddate'=>date('YmdHis')
			), NULL);			
		    }else{
			$userdetailsData = $userdetailsMapper->loadModel(array(
			    'userid'=>$dbuserData[0]['id'],
			    'firstname'=>$fname,
			    'lastname'=>$lname,
			    'recorddate'=>date('YmdHis')
			), NULL);	
		    }	  
		    $userdetailsMapper->save($userdetailsData);
		}		    
	    }
	    $data=array('email'=>$email,'password'=>$password);
	    $this->_Sauthc($data);
	}	      	  
    }
    
    protected function _Sauthc($data)
    {
	$auth = Zend_Auth::getInstance();
	$users = new Application_Model_DbTable_Users();
	$authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'users');
	
	
	$authAdapter->setIdentityColumn('email')->setCredentialColumn('password');
	$authAdapter->setIdentity($data['email'])->setCredential(base64_encode($data['password']));
	
	$result = $auth->authenticate($authAdapter);
	$userData = $authAdapter->getResultRowObject();
	if($result->isValid() && $userData->status == 'Y') {
	    $storage = new Zend_Auth_Storage_Session();
	    $storage->write($userData);
	    $this->_redirect('index/profile');
	} elseif($userData->status == 'N') {	    
	    $storage = new Zend_Auth_Storage_Session();
	    $storage->clear();
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err11');
	    exit;
	}else{
	    $storage = new Zend_Auth_Storage_Session();
	    $storage->clear();
	    $this->_redirect(SITEPATH.'/auth/login/flogin/err12');
	    exit;
	}
    }
    
    protected function _glogin()
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
	$googleConfig = $config->getOption('google');
	$clientId = $googleConfig['clientId']; //Google CLIENT ID
	$clientSecret = $googleConfig['clientSecret']; //Google CLIENT SECRET
	$redirectUrl = $googleConfig['redirectUrl'];  //return url (url to script)
	$homeUrl = $googleConfig['homeUrl'];  //return to home
	
	$gClient = new Google_Client();
	$gClient->setApplicationName('Login to codexworld.com');
	$gClient->setClientId($clientId);
	$gClient->setClientSecret($clientSecret);
	$gClient->setRedirectUri($redirectUrl);
	$google_oauthV2 = new Google_Oauth2Service($gClient);
	$authUrl = $gClient->createAuthUrl();
	return $authUrl;
    }
    
    public function googleloginAction()
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
	$googleConfig = $config->getOption('google');
//	/\Zend_Session::destroy( true );
	// Disable the main layout renderer
	$this->_helper->layout->disableLayout();
	// Do not even attempt to render a view
	$this->_helper->viewRenderer->setNoRender(true);
	$authNamespace = new Zend_Session_Namespace('Zend_Auth');
	$clientId = $googleConfig['clientId']; //Google CLIENT ID
	$clientSecret = $googleConfig['clientSecret']; //Google CLIENT SECRET
	$redirectUrl = $googleConfig['redirectUrl'];  //return url (url to script)
	$homeUrl = $googleConfig['homeUrl'];  //return to home
	
	$gClient = new Google_Client();
	$gClient->setApplicationName('Login to codexworld.com');
	$gClient->setClientId($clientId);
	$gClient->setClientSecret($clientSecret);
	$gClient->setRedirectUri($redirectUrl);
	$code = $this->getParam('code');
	$error = $this->getParam('error');
	if($error){
	    $this->_redirect(SITEPATH.'/auth/login/glogin/'.$error);
	    exit;
	}
	if($code){
	    $gClient->authenticate();
	    $access_token = $gClient->getAccessToken();	    
	    $authNamespace->google_accesstoken = $access_token;
	    $this->redirect(SITEPATH.'/auth/googlelogin/');
	}
        var_dump("<pre>",$authNamespace);
	if($authNamespace->google_accesstoken){
	    $gClient->setAccessToken($authNamespace->google_accesstoken);
	    $google_oauthV2 = new Google_Oauth2Service($gClient);
	    $userProfile = $google_oauthV2->userinfo->get();
	    $data = array('email'=>$userProfile['email'],
			    'id'=>$userProfile['id'],
			    'fname'=>$userProfile['given_name'],
			    'lname'=>$userProfile['family_name']);
	    $this->_loginmech($data);
	}
    }
    
    protected function _loginmech($data)
    {
	$email = trim($data['email']);
	$id = 'g'.trim($data['id']);
	$fname = trim($data['fname']);
	$lname = trim($data['lname']);
	if(!empty($email)){
	      $userMapper = new Application_Model_Mapper_Users();
	      $emres = $userMapper->getData(array('field'=>'email','value'=>$email));	      
	      #checking if user already exist
	      if(!empty($emres[0])){
		$usersData = $userMapper->loadModel(array(
							    'id'=>$emres[0]['id'],
							  ), NULL);
		$password = base64_decode($emres[0]['password']);
		$updResult = $userMapper->save($usersData);
		if($updResult){
		    //inserting userdetails
		    $userdetailsMapper = new Application_Model_Mapper_Usersdetails();
		    $dbData = $userdetailsMapper->getUserDetails(array('userid'=>$emres[0]['id']));
		    if(!empty($dbData[0])){			
			$userdetailsData = $userdetailsMapper->loadModel(array(
			    'id'=>$dbData[0]['id'],
			    'userid'=>$emres[0]['id'],
			    'firstname'=>$fname,
			    'lastname'=>$lname,
			    'recorddate'=>date('YmdHis')
			), NULL);			
		    }else{
			$userdetailsData = $userdetailsMapper->loadModel(array(
			    'userid'=>$emres[0]['id'],
			    'firstname'=>$fname,
			    'lastname'=>$lname,
			    'recorddate'=>date('YmdHis')
			), NULL);	
		    }	  
		    $userdetailsMapper->save($userdetailsData);
		}  
	      }else{
		$password = md5(uniqid(rand(), true));
		$usersData = $userMapper->loadModel(array(
							    'id'=>null,
							    'username'=>  $id,
							    'password'=>  base64_encode($password),
							    'email'=>$email,
							    'id_role'=>'user',
							    'status'=>'Y',
							    'recorddate'=>date('YmdHis'),
							    ), NULL);

		$insResult = $userMapper->save($usersData);
		if($insResult){
		    //inserting userdetails
		    $dbuserData = $userMapper->getData(array('field'=>'email','value'=>$email));
		    $userdetailsMapper = new Application_Model_Mapper_Usersdetails();
		    $dbData = $userdetailsMapper->getUserDetails(array('userid'=>$dbuserData[0]['id']));
		    if(!empty($dbData[0])){			
			$userdetailsData = $userdetailsMapper->loadModel(array(
			    'id'=>$dbData[0]['id'],
			    'userid'=>$dbuserData[0]['id'],
			    'firstname'=>$fname,
			    'lastname'=>$lname,
			    'recorddate'=>date('YmdHis')
			), NULL);			
		    }else{
			$userdetailsData = $userdetailsMapper->loadModel(array(
			    'userid'=>$dbuserData[0]['id'],
			    'firstname'=>$fname,
			    'lastname'=>$lname,
			    'recorddate'=>date('YmdHis')
			), NULL);	
		    }	  
		    $userdetailsMapper->save($userdetailsData);
		}		    
	    }
	    $data=array('email'=>$email,'password'=>$password);
	    $this->_Sauthc($data);
	}
	 $this->_redirect(SITEPATH.'/auth/login/glogin/err');
	 exit;
    }
}