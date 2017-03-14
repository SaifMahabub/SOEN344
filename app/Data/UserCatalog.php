<?php

namespace App\Data\Mappers;

use App\Data\IdentityMaps\Mappers\UserMapper;


class UserCatalog
{

    /**
     * @var UserMapper
     */
    private $userMapper;

    protected function __construct()
    {
        parent::__construct();

        $this->userMapper = UserMapper::getInstance();
    }

    /**
     * Handles the creation of a new object of type User
     *
     * @ignore Unused
     *
     * @param int $id
     * @param string $name
     * @param string $password
     * @return User
     */
    public function create(int $id, string $name, string $password, bool $isCapstone): User
    {
        $this->userMapper->add($id, $name, $password, $isCapstone);

        return $user;
    }

    /**
     * Fetch message for retrieving a User with the given ID
     *
     * @return User
     */
    public function find(int $id): User
    {
        $this->userMapper->find($id);

        return $user;
    }

    /**
     * @ignore Unused
     *
     * @param int $id
     * @param string $name
     */
    public function set(int $id, string $name)
    {
        $this->userMapper->set($id, $name);
    }

    /**
     * @ignore Unused
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->userMapper->delete($id);
    }

    /**
     * @ignore Unused
     *
     * Finalize changes
     */
    public function done()
    {
        $this->userMapper->done();
    }

    /**
     * Pass the list of Users to add to DB to the TDG
     *
     * @ignore Unused
     *
     * @param array $newList
     */
    public function addMany(array $newList)
    {
        $this->userMapper->addMany($newList);
    }

    /**
     * Pass the list of Users to update in the DB to the TDG
     *
     * @ignore Unused
     *
     * @param array $updateList
     */
    public function updateMany(array $updateList)
    {
        $this->userMapper->updateMany($updateList);
    }

    /**
     * Pass the list of Users to remove from DB to the TDG
     *
     * @ignore Unused
     *
     * @param array $deleteList
     */
    public function deleteMany(array $deleteList)
    {
        $this->userMapper->deleteMany($deleteList);
    }
}
