<?php
namespace Zf2ResqueEx\Options;
use Zend\Stdlib\AbstractOptions;

/**
 * ResqueOptions
 *
 * @package Zf2ResqueEx\Options
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class ResqueOptions extends AbstractOptions
{
    /** @var string $server */
    protected $server;

    /** @var int $database */
    protected $database =0;

    /** @var null|string $password */
    protected $password = null;

    /**
     * getServer
     *
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * setServer
     *
     * @param mixed $server
     * @return ResqueOptions
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * getDatabase
     *
     * @return int
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * setDatabase
     *
     * @param int $database
     * @return ResqueOptions
     */
    public function setDatabase($database)
    {
        $this->database = $database;
        return $this;
    }

    /**
     * getPassword
     *
     * @return null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * setPassword
     *
     * @param null $password
     * @return ResqueOptions
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}
