<?php

namespace Test\Integration\Controller\Resource;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

/**
 * Class UserControllerTest
 */
class UserControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @test
     *
     * @return void
     */
    public function indexTest(): void
    {
        $this->client->request('GET', '/resources/users');

        // @todo add fixtures and change expected result
        $this->assertEquals(
            json_encode(
                [
                    'jsonapi' => ['version' => '1.0'],
                    'meta'    => ['pagination' => ['count' => 0]],
                    'links'   => [
                        'self'  => null,
                        'first' => null,
                        'last'  => null,
                        'prev'  => null,
                        'next'  => null,
                    ],
                    'data'    => [],
                ]
            ),
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function newTest(): void
    {
    }

    /**
     * @test
     *
     * @return void
     */
    public function showTest(): void
    {
    }

    /**
     * @test
     *
     * @return void
     */
    public function editTest(): void
    {
    }

    /**
     * @test
     *
     * @return void
     */
    public function deleteTest(): void
    {
    }

    /**
     * @test
     *
     * @return void
     */
    public function resetPasswordTest(): void
    {
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }
}
