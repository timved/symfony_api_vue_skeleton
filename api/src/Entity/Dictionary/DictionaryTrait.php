<?php


namespace App\Entity\Dictionary;

use Doctrine\ORM\Mapping as ORM;

trait DictionaryTrait
{
    /**
     * Заголовок
     * @var string
     * @ORM\Column(name="title",  type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * Код
     * @var string
     * @ORM\Column(name="code",  type="string", length=32, nullable=false)
     */
    protected $code;

    /**
     * @return string
     */
    public function getTitle():string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCode():string
    {
        return $this->code;
    }

}