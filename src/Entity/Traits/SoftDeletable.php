<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 24/10/2018
 * Time: 22:39
 */

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeletable
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $deleted_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $deleted_by;

}