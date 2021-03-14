<?php


namespace App\Entity;

use App\Constant\Role;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Šis el.paštas jau naudojamas")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    use TimestampableEntity;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @return string
     */
    public function getId(): string
    {
        if (null === $this->id) {
            $this->createId();
        }

        return $this->id;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function createId(): self
    {
        if (null === $this->id) {
            $this->id = Uuid::v1()->toBinary();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role) : self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array(Role::ROLE_ADMIN, $this->roles);
    }
}