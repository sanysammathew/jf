<?php

/**
 * Application Model DbTables
 *
 * @package Application_Model
 * @subpackage DbTable
 * @author Sany
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Table definition for users
 *
 * @package Application_Model
 * @subpackage DbTable
 * @author Sany
 */
class Application_Model_DbTable_Users extends Application_Model_DbTable_TableAbstract
{
    /**
     * $_name - name of database table
     *
     * @var string
     */
    protected $_name = 'users';

    /**
     * $_id - this is the primary key name
     *
     * @var int
     */
    protected $_id = 'id';

    protected $_sequence = true;
    
    public function fetchData($data){
	$select = $this->select()->from($this->_name)
                    ->where($this->_name.'.'.$data['field'].' = ?',$data['value'])
                    ->limit('1');
	
	return $select;
    }
    public function fetchallData(){
	$select = $this->select()->from($this->_name)
                ->setIntegrityCheck(FALSE)
                ->joinLeft('usersdetails', $this->_name.'.id=usersdetails.userid',array('firstname','lastname'))
                    ->where($this->_name.'.status = "Y"');
	
	return $select;
    }
    public function chkUserData($data){
	$select = $this->select()->from($this->_name)
                    ->where($this->_name.'.username = ?',$data['username'])
                    ->where($this->_name.'.id != ?',$data['id'])
                    ->limit('1');
	
	return $select;
    }
    public function chkemptyuser($data){
	$select = $this->select()->from($this->_name,array('username'))
                    ->where($this->_name.'.id = ?',$data['id'])
                    ->where($this->_name.'.username != ""')
                    ->limit('1');
	
	return $select;
    }

    
    



}
