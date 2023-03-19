<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ReviewTest extends ApiTestCase
{
    protected static $http;
    protected static $token;
    protected static $review;

    public static function setUpBeforeClass() : void
    {
      self::$token = self::getToken();

    }

    protected static function getToken()
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => 
        [
                "username" => "shadi",
                "password" => "12345678",
        ],
                'headers' => 
        [
            'ACCEPT'     => 'application/json',
            'CONTENT_TYPE' =>  'application/json'
        ]    
        ]);
        return json_decode($response->getContent())->token;
    }
   
    protected static function createHeaders($token = "INVALID_TOKEN")
    {
        return   [
            'ACCEPT'     => 'application/json',
            'CONTENT_TYPE' =>  'application/json',
            'authorization' => 'Bearer '.$token
        ];
    }

    // NEW REVIEW

    //  - check if member is authorized

    public function testInvalidToken() : void
    {
        $response = static::createClient()->request('POST', '/api/review', [
            'json' => 
                [
                        "project" => 1,
                        "overall" => 4,
                        "quality" => 5,
                        "communication" => 2,
                        "price" => 4,
                        "feedback" => "FEEDBACK"       
                ],
            'headers' => self::createHeaders()
        ]);
        $this->assertResponseStatusCodeSame(401);
    }

    //  - project not fonud return status code 404

    public function testProjectNotFound() : void
    {
        $response = static::createClient()->request('POST', '/api/review', [
            'json' => 
                [
                        "project" => 2,
                        "overall" => 4,
                        "quality" => 5,
                        "communication" => 2,
                        "price" => 4,
                        "feedback" => "FEEDBACK"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(404);
    }

    // - validation failed return status code 422

    public function testInvalidUserInputs() : void
    {
        $response = static::createClient()->request('POST', '/api/review', [
            'json' => 
                [
                        "project" => 1,
                        "overall" => -1,
                        "quality" => -1,
                        "communication" => -1,
                        "price" => -1,
                        "feedback" => "1"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(422);
    }


    // - created return status code 201

    public function testCreateNewReivew() : void
    {
        $response = static::createClient()->request('POST', '/api/review', [
            'json' => 
                [
                        "project" => 1,
                        "overall" => 4,
                        "quality" => 4,
                        "communication" => 3,
                        "price" => 5,
                        "feedback" => "feedback"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        self::$review = json_decode($response->getContent())->id;
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains($response->getContent());
    }

    // - bad request when adding the same record more than one
    public function testBadRequestOnInsertingTheSameRecord() : void
    {
        $response = static::createClient()->request('POST', '/api/review', [
            'json' => 
                [
                        "project" => 1,
                        "overall" => 4,
                        "quality" => 4,
                        "communication" => 3,
                        "price" => 5,
                        "feedback" => "feedback"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(400);
    }

     // UPDATE REVIEW TESTS

    //  - check if member is authorized

    public function testUpdateInvalidToken() : void
    {
        $response = static::createClient()->request('PUT', '/api/review/' .  self::$review, [
            'json' => 
                [
                        "project" => 1,
                        "overall" => 4,
                        "quality" => 5,
                        "communication" => 2,
                        "price" => 4,
                        "feedback" => "FEEDBACK"       
                ],
            'headers' => self::createHeaders()
        ]);
        $this->assertResponseStatusCodeSame(401);
    }

    //  - project not fonud return status code 404

    public function testUpdateProjectNotFound() : void
    {
        $response = static::createClient()->request('PUT', '/api/review/' . self::$review, [
            'json' => 
                [
                        "project" => 2,
                        "overall" => 4,
                        "quality" => 5,
                        "communication" => 2,
                        "price" => 4,
                        "feedback" => "FEEDBACK"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(404);
    }

    // - validation failed return status code 422

    public function testUpdateInvalidUserInputs() : void
    {
        $response = static::createClient()->request('PUT', '/api/review/' . self::$review, [
            'json' => 
                [
                        "project" => 1,
                        "overall" => -1,
                        "quality" => -1,
                        "communication" => -1,
                        "price" => -1,
                        "feedback" => "1"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(422);
    }


    // - updated return status code 204

    public function testUpdateExistingReivew() : void
    {
        $response = static::createClient()->request('PUT', '/api/review/' . self::$review, [
            'json' => 
                [
                        "project" => 1,
                        "overall" => 4,
                        "quality" => 4,
                        "communication" => 3,
                        "price" => 5,
                        "feedback" => "feedback"       
                ],
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

    // GET REVIEW TESTS
    
    // review not found return 404 
    public function testGetReviewNotFound(): void
    {
        $response = static::createClient()->request('GET', '/api/review/' . 'NOT_VALID_ID',[
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(404);
        
    }

    // get review retrun 200
    public function testGetReview(): void
    {
        $response = static::createClient()->request('GET', '/api/review/' . self::$review,[
            'headers' => self::createHeaders(self::$token)
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains($response->getContent());
        
    }
}
