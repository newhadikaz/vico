<?php

namespace App\Service;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewService
{
   private $entityManager;

   private $serializer;

   public function __construct(EntityManagerInterface $entityManager,
                               SerializerInterface $serializer) {

        $this->entityManager = $entityManager;

        $this->serializer = $serializer;
   }

   public function setProperties($review, $inputs)
   {
     // set review properties
     $review->setOverall($inputs['overall']);
     $review->setFeedback($inputs['feedback']);
     $review->setQuality($inputs['quality']);
     $review->setCommunication($inputs['communication']);
     $review->setPrice($inputs['price']);

     // get and set project
     try {
         $project = $this->entityManager->getRepository(Project::class)->find($inputs['project']);
         $review->setProject($project);
     } catch (\Throwable $th) {
         return null;
     }

     return $review;

   }

   public function serializeEntity($data)
   {
    $json = $this->serializer->serialize($data, 'json', [
        'circular_reference_handler' => function ($object) {
            return $object->getId();
        },
        'groups' => ['project', 'review']
    ]);
    return $json;
   }
}