<?php
/**
 * Contains App\Entity\Drink
 *
 * @package App
 * @subpackage Entity
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(
 *     name="drink",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_uidx", columns={"name"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Drink")
 * 
 * @package App
 * @subpackage Entity
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class Drink
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @JMS\Groups(
     *     {
     *         "get_read_user",
     *         "get_read_drinks",
     *         "post_write_user",
     *         "post_write_userdrinks",
     *         "post_read_userdrinks"
     *     }
     * )
     *
     * @JMS\Type("integer")
     * @JMS\Accessor(getter="getId")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * 
     * @JMS\Groups(
     *     {
     *         "get_read_user",
     *         "get_read_drinks",
     *         "post_read_userdrinks"
     *     }
     * )
     *
     * @JMS\Type("string")
     * @JMS\Accessor(getter="getName", setter="setName")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     * 
     * @JMS\Groups(
     *     {
     *         "get_read_drinks"
     *     }
     * )
     *
     * @JMS\Type("string")
     * @JMS\Accessor(getter="getDescription", setter="setDescription")
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(name="caffeine", type="integer", options={"unsigned":true}, nullable=false)
     * 
     * @JMS\Groups(
     *     {
     *         "get_read_user",
     *         "get_read_drinks",
     *         "post_read_userdrinks"
     *     }
     * )
     *
     * @JMS\Type("integer")
     * @JMS\Accessor(getter="getCaffeine", setter="setCaffeine")
     */
    protected $caffeine;

    /**
     * @var UserDrink[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserDrink", mappedBy="drink")
     *
     * @JMS\Accessor(getter="getUserDrinks", setter="setUserDrinks")
     */
    protected $userDrinks;

    public function __construct()
    {
        $this->setUsers(new ArrayCollection);
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getName(): ?string 
    {
        return $this->name;
    }

    /**
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getCaffeine(): ?int 
    {
        return $this->caffeine;
    }

    /**
     * @param ?int $caffeine
     *
     * @return self
     */
    public function setCaffeine(?int $caffeine): self
    {
        $this->caffeine = $caffeine;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getDescription(): ?string 
    {
        return $this->description;
    }

    /**
     * @param ?string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection[UserDrink]
     */
    public function getUserDrinks(): ?Collection
    {
        return $this->userDrinks;
    }

    /**
     * @param ?ArrayCollection $userDrinks
     *
     * @return self
     */
    public function setUserDrinks(?ArrayCollection $userDrinks): self
    {
        $this->userDrinks = $userDrinks;

        return $this;
    }
}
