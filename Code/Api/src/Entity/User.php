<?php
/**
 * Contains App\Entity\User
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_uidx", columns={"name"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\User")
 * 
 * @UniqueEntity(
 *     fields={"name"},
 *     groups={
 *         "post_write_user"
 *     },
 *     message="user.name.unique"
 * )
 * 
 * @package App
 * @subpackage Entity
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class User
{
    /**
     * User Id
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @JMS\Groups(
     *     {
     *         "get_read_user",
     *         "get_read_users",
     *         "post_read_user",
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
     * User name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * 
     * @JMS\Groups(
     *     {
     *         "get_read_user",
     *         "get_read_users",
     *         "post_write_user"
     *     }
     * )
     *
     * @JMS\Type("string")
     * @JMS\Accessor(getter="getName", setter="setName")
     */
    protected $name;
    
    /**
     * @var UserDrink[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserDrink", mappedBy="user", cascade={"persist"})
     *
     * @JMS\Groups(
     *     {
     *         "get_read_user",
     *         "post_write_user",
     *         "post_write_userdrinks",
     *         "post_read_userdrinks"
     *     }
     * )
     *
     * @JMS\Accessor(getter="getUserDrinks", setter="setUserDrinks")
     */
    protected $userDrinks;

    public function __construct()
    {
        $this->setDrinks(new ArrayCollection);
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
     * @param ArrayCollection|null $userDrinks
     *
     * @return self
     */
    public function setUserDrinks(?ArrayCollection $userDrinks): self
    {
        $this->userDrinks = $userDrinks;

        return $this;
    }

    /**
     * @return ?Collection[UserDrink]
     */
    public function getUserDrinks(): ?Collection
    {
        return $this->userDrinks;
    }

    public function addDrink(Drink $drink): self
    {
        $this->userDrinks[] = (new UserDrink)
            ->setUser($this)
            ->setDrink($drink);

        return $this;
    }
    
    /**
     * @return Drink[]
     */
    public function getDrinks(): array
    {
        $drinks = [];

        foreach($this->userDrinks as $userDrink) {
            $drinks[] = $userDrink->getDrink();
        }

        return $drinks;
    }

    /**
     * @return int[]
     */
    public function getDrinkIds(): array
    {
        $drinkIds = [];

        foreach($this->userDrinks as $userDrink) {
            $drinkIds[] = $userDrink->getDrink()->getId();
        }

        return $drinkIds;
    }

    /**
     * @return int
     */
    public function getTotalDrinkCaffeine(): int
    {
        $totalCaffeine = 0;

        foreach($this->userDrinks as $userDrink) {
            $totalCaffeine += $userDrink->getDrink()->getCaffeine();
        }

        return $totalCaffeine;
    }
}
