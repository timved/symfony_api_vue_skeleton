<?php


namespace App\Entity\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="federal_districts", schema="public")
 */
class FederalDistrict
{
    use DictionaryTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id",type="integer")
     */
    private $id;

    /**
     * Субъекты
     * @var array
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="federalDistrict")
     */
    private $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getSubjects(): array
    {
        return $this->subjects->toArray();
    }
}