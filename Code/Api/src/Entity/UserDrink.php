<?php
/**
 * Contains App\Entity\UserDrink
 *
 * @package App
 * @subpackage Entity
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(
 *      name="user_drink"
 * )
 * @ORM\Entity(repositoryClass="App\Entity\UserDrinkRepository")
 *
 * @package App
 * @subpackage Entity
 */
class UserDrink
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @JMS\Type("integer")
     * @JMS\Accessor(getter="getId")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userDrinks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @JMS\Type("App\Entity\User")
     * @JMS\Accessor(getter="getUser", setter="setUser")
     */
    protected $user; 

    /**
     * @var Drink
     *
     * @ORM\ManyToOne(targetEntity="Drink", inversedBy="userDrinks", fetch="EAGER")
     * @ORM\JoinColumn(name="drink_id", referencedColumnName="id")
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
     * @JMS\Type("App\Entity\Drink")
     * @JMS\Accessor(getter="getDrink", setter="setDrink")
     */
    protected $drink;

    /**
     * @return User
     */    
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Drink
     */
    public function getDrink(): ?Drink
    {
        return $this->drink;
    }

    /**
     * @param Drink $drink
     *
     * @return $this
     */
    public function setDrink(?Drink $drink): self
    {
        $this->drink = $drink;

        return $this;
    }
}
