<?php

namespace App\Controller;

use App\Entity\Review;
use App\Service\ReviewService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewController extends AbstractController
{


    private $entityManager;

    private $validator;

    public function __construct(EntityManagerInterface $entityManager,
                               ValidatorInterface $validator) {

        $this->entityManager = $entityManager;

        $this->validator = $validator;

    }

     /**
     * @Route("/api/review/{id}", name="get_review", defaults={"id" : 0}, methods={"GET"})
     */
    public function show($id, ReviewService $reviewService): Response
    {
        // get review entity
        $review = $this->entityManager->getRepository(Review::class)->find($id);

        // return not fonud if null
        if(!$review) return $this->json(['errors' => 'Review Not Found'], 404);

        // serialize review
        $data = $reviewService->serializeEntity($review);

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }


    /**
    * @Route("/api/review", name="create_review", methods={"POST"})
    */
    public function create(Request $request, ReviewService $reviewService) : Response
    {
        // get user inputs
        $inputs = json_decode($request->getContent(), true);
        // create a new review
        $review = new Review();

        // set review properties
      
        $review = $reviewService->setProperties($review, $inputs);
        if(!$review) return new Response(json_encode(['errors' => 'Project Not Found']), 404, ['Content-Type' => 'application/json']);
        // validate user inputs
        $errors = $this->validator->validate($review);

        if(count($errors) > 0) {
            return $this->json(['errors' => $errors], 422);
        };
        
        // save user review
        try {
            $this->entityManager->persist($review);
            $this->entityManager->flush();
        } catch (\Throwable $th) {
            return $this->json(['errors' => 'Bad Request'], 400);
        }
        

        // serilaize review object 
        $data = $reviewService->serializeEntity($review);

        return new Response($data, 201, ['Content-Type' => 'application/json']);
    }

     /**
    * @Route("/api/review/{id}", name="update_review", defaults={"id" : 0}, methods={"PUT"})
    */
    public function update($id, Request $request, ReviewService $reviewService) : Response
    {
       // get user inputs
       $inputs = json_decode($request->getContent(), true);

       $review = $this->entityManager->getRepository(Review::class)->find($id);

       if(!$review) return $this->json(['errors' => 'Review Not Found'], 404);

       // set new properties
       $review = $reviewService->setProperties($review, $inputs);
       if(!$review) return $this->json(['errors' => 'Project Not Found'], 404);

        // validate user inputs
        $errors = $this->validator->validate($review);

        if(count($errors) > 0) {
            return $this->json(['errors' => $errors], 422);
        };

        // update review
        $this->entityManager->flush();

        return new Response(null, 204, ['Content-Type' => 'application/json']);
      
    }


}
