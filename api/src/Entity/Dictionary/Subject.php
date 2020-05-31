<?php


namespace App\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\SubjectRepository")
 * @ORM\Table(name="subjects", schema="public")
 */
class Subject
{
    use DictionaryTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id",type="integer")
     */
    private $id;

    /**
     * @var FederalDistrict
     * @ORM\ManyToOne(targetEntity="FederalDistrict", inversedBy="subjects")
     * @ORM\JoinColumn(name="federal_district_id", referencedColumnName="id")
     */
    private $federalDistrict;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FederalDistrict
     */
    public function getFederalDistrict(): FederalDistrict
    {
        return $this->federalDistrict;
    }

    /**
     * @return boolean
     */
    public function isFederalCity() :bool
    {
        return $this->id === 77 || $this->id === 78 || $this->id === 79;
    }
}