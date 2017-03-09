<?php

namespace App\Data;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $isCapstone;

    /**
     * User constructor.
     * @param int $id
     * @param string $name
     * @param string $password
     */
    public function __construct(int $id, string $name, string $password, bool $isCapstone)
    {
        parent::__construct();

        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->isCapstone = $isCapstone;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @ignore Unused
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @ignore Unused
     *
     * @return null|string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIsCapstone(): bool
    {
        return $this->isCapstone;
    }

    public function setIsCapstone(bool $isCapstone)
    {
        $this->isCapstone = $isCapstone;
    }
}
