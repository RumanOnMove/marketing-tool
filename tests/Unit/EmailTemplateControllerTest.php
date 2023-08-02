<?php

namespace Tests\Unit;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Moveon\EmailTemplate\Http\Controllers\EmailTemplateController;
use Moveon\EmailTemplate\Repositories\EmailTemplateRepository;
use Moveon\EmailTemplate\Services\EmailTemplateService;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class EmailTemplateControllerTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testIndex()
    {
        $data = [
            [
                'id' => 3,
                'name' => 'Wel come template 456',
                'subject' => 'Welcome to Our Platform!',
                'type' => 'Welcome',
                'placeholders' => [
                    'user_name' => 'Katrine',
                    'confirmation_link' => 'https://www.google.com/'
                ],
                'content' => '<html><head></head><body><h1>Hello {user_name},</h1><p>Welcome to our platform! Please click the link below to confirm your account:</p><a href="{confirmation_link}">Confirm Account</a></body></html>',
                'status' => 'active'
            ]
        ];

        $mockedCollection = new Collection($data);

        $emailTemplateServiceMock = $this->createMock(EmailTemplateService::class);

        $lengthAwarePaginator = new LengthAwarePaginator(
            $mockedCollection,
            count($mockedCollection),
            10,
            1
        );

        $emailTemplateServiceMock->expects($this->once())
            ->method('getTemplates')
            ->with($this->isInstanceOf(Request::class))
            ->willReturn($lengthAwarePaginator);

        $controller = new EmailTemplateController($emailTemplateServiceMock);

        $requestMock = $this->createMock(Request::class);

        $response = $controller->index($requestMock);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $responseData);

        $this->assertArrayHasKey('links', $responseData);

        $this->assertArrayHasKey('meta', $responseData);

    }
}

