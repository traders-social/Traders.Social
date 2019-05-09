<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 24/10/2018
 * Time: 22:38
 */

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait ModificationAware
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $updated_by;

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    /**
     * @param User|null $created_by
     * @return ModificationAware
     */
    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUpdatedBy(): ?User
    {
        return $this->updated_by;
    }

    /**
     * @param User|null $updated_by
     * @return ModificationAware
     */
    public function setUpdatedBy(?User $updated_by): self
    {
        $this->updated_by = $updated_by;

        return $this;
    }

}