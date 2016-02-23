<?php

namespace Models;

class User extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     */

    public $id;

    /**
     * @var string
     */

    public $email;

    /**
     * @var string
     */

    public $username;

    /**
     * @var string
     */

    public $fname;

    /**
     * @var string
     */

    public $lname;

    /**
     * @var string
     */

    public $address;

    /**
     * @var string
     */

    public $phone;

    /**
     * @var string
     */

    public $credit_card;

    /**
     * @var float
     */

    public $balance;

    /**
     * @var string
     */

    public $timezone;

    /**
     * @var string
     */

    public $birthday;

    /**
     * @var string
     */

    public $registered_at;

    /**
     * @var integer
     */

    public $logins;

    public function initialize()
    {
        $this->setSource('users');
    }
}