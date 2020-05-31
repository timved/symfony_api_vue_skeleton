<?php


namespace App\Serializer;


use App\Entity\Dictionary\Subject;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * CircularReferenceHandler constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke($object)
    {
        switch ($object){
            case $object instanceof Subject:
                return $this->router->generate('dictionary_subjects', ['federalDistrict' => $object->getId()]);
        }
        return $object->getId();
    }

}