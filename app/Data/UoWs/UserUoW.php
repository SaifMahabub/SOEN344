<?php

namespace App\Data\UoWs;

use App\Data\Mappers\UserMapper;
use App\Data\User;
use App\Singleton;

/**
 * @ignore Unused
 *
 * @method static UserUoW getInstance()
 */
class UserUoW extends Singleton
{
    private $newList = array();
    private $changedList = array();
    private $deletedList = array();

    /**
     * @var UserMapper
     */
    private $userMapper;

    protected function __construct()
    {
        parent::__construct();

        $this->userMapper = UserMapper::getInstance();
    }

    public function registerNew(User $user)
    {
        array_push($this->newList, $user);
    }

    public function registerDirty(User $user)
    {
        array_push($this->changedList, $user);
    }

    public function registerDeleted(User $user)
    {
        array_push($this->deletedList, $user);
    }

    public function commit()
    {
        $this->userMapper->addMany($this->newList);
        $this->userMapper->updateMany($this->changedList);
        $this->userMapper->deleteMany($this->deletedList);

        // empty the lists after the commit
        unset($this->newList);
        $this->newList = array();

        unset($this->changedList);
        $this->changedList = array();

        unset($this->deletedList);
        $this->deletedList = array();

    }
}
