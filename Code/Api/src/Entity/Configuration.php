<?php
/**
 * Contains App\Entity\Configuration
 *
 * @package App
 * @subpackage Entity
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(
 *     name="configuration",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_uidx", columns={"name"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Configuration")
 * 
 * @package App
 * @subpackage Entity
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class Configuration
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     *
     * @JMS\Type("string")
     * @JMS\Accessor(getter="getName", setter="setName")
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     *
     * @JMS\Type("string")
     * @JMS\Accessor(getter="getValue", setter="setValue")
     */
    protected $value;

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
     * @return ?string
     */
    public function getValue(): ?string 
    {
        return $this->value;
    }

    /**
     * @param ?string $value
     *
     * @return self
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
