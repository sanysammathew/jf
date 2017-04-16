<?php

/**
 * Application Models
 *
 * @package Application_Model
 * @subpackage Model
 * @author Sany
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * 
 *
 * @package Application_Model
 * @subpackage Model
 * @author Sany
 */
class Application_Model_Users extends Application_Model_ModelAbstract
{

    /**
     * Database var type bigint(20)
     *
     * @var int
     */
    protected $_Id;

    /**
     * Database var type varchar(255)
     *
     * @var string
     */
    protected $_Username;

    /**
     * Database var type varchar(255)
     *
     * @var string
     */
    protected $_Password;

    /**
     * Database var type varchar(255)
     *
     * @var string
     */
    protected $_Email;

    /**
     * Database var type varchar(50)
     *
     * @var string
     */
    protected $_IdRole;

    /**
     * Database var type enum('Y','N')
     *
     * @var string
     */
    protected $_Status;

    /**
     * Database var type bigint(20)
     *
     * @var int
     */
    protected $_Recorddate;

    /**
     * Database var type timestamp
     *
     * @var string
     */
    protected $_Luta;



    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        parent::init();
        $this->setColumnsList(array(
            'id'=>'Id',
            'username'=>'Username',
            'password'=>'Password',
            'email'=>'Email',
            'id_role'=>'IdRole',
            'status'=>'Status',
            'recorddate'=>'Recorddate',
            'luta'=>'Luta',
        ));

        $this->setParentList(array(
        ));

        $this->setDependentList(array(
        ));
    }

    /**
     * Sets column id
     *
     * @param int $data
     * @return Application_Model_Users
     */
    public function setId($data)
    {
        $this->_Id = $data;
        return $this;
    }

    /**
     * Gets column id
     *
     * @return int
     */
    public function getId()
    {
        return $this->_Id;
    }

    /**
     * Sets column username
     *
     * @param string $data
     * @return Application_Model_Users
     */
    public function setUsername($data)
    {
        $this->_Username = $data;
        return $this;
    }

    /**
     * Gets column username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->_Username;
    }

    /**
     * Sets column password
     *
     * @param string $data
     * @return Application_Model_Users
     */
    public function setPassword($data)
    {
        $this->_Password = $data;
        return $this;
    }

    /**
     * Gets column password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->_Password;
    }

    /**
     * Sets column email
     *
     * @param string $data
     * @return Application_Model_Users
     */
    public function setEmail($data)
    {
        $this->_Email = $data;
        return $this;
    }

    /**
     * Gets column email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->_Email;
    }

    /**
     * Sets column id_role
     *
     * @param string $data
     * @return Application_Model_Users
     */
    public function setIdRole($data)
    {
        $this->_IdRole = $data;
        return $this;
    }

    /**
     * Gets column id_role
     *
     * @return string
     */
    public function getIdRole()
    {
        return $this->_IdRole;
    }

    /**
     * Sets column status
     *
     * @param string $data
     * @return Application_Model_Users
     */
    public function setStatus($data)
    {
        $this->_Status = $data;
        return $this;
    }

    /**
     * Gets column status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->_Status;
    }

    /**
     * Sets column recorddate
     *
     * @param int $data
     * @return Application_Model_Users
     */
    public function setRecorddate($data)
    {
        $this->_Recorddate = $data;
        return $this;
    }

    /**
     * Gets column recorddate
     *
     * @return int
     */
    public function getRecorddate()
    {
        return $this->_Recorddate;
    }

    /**
     * Sets column luta
     *
     * @param string $data
     * @return Application_Model_Users
     */
    public function setLuta($data)
    {
        $this->_Luta = $data;
        return $this;
    }

    /**
     * Gets column luta
     *
     * @return string
     */
    public function getLuta()
    {
        return $this->_Luta;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Application_Model_Mapper_Users
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {
            $this->setMapper(new Application_Model_Mapper_Users());
        }

        return $this->_mapper;
    }

    /**
     * Deletes current row by deleting the row that matches the primary key
     *
	 * @see Application_Model_Mapper_Users::delete
     * @return int|boolean Number of rows deleted or boolean if doing soft delete
     */
    public function deleteRowByPrimaryKey()
    {
        if ($this->getId() === null) {
            throw new Exception('Primary Key does not contain a value');
        }

        return $this->getMapper()
                    ->getDbTable()
                    ->delete('id = ' .
                             $this->getMapper()
                                  ->getDbTable()
                                  ->getAdapter()
                                  ->quote($this->getId()));
    }
}
