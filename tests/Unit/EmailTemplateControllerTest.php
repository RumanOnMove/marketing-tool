<?php

namespace Tests\Unit;

use Mockery;
use Moveon\EmailTemplate\Http\Controllers\EmailTemplateController;
use Moveon\EmailTemplate\Models\EmailTemplate;
use PHPUnit\Framework\TestCase;

class EmailTemplateControllerTest extends TestCase
{
    /** @test  */
    public function test_index(): void
    {
        $dummyEmailTemplates = collect([
            ['id' => 1, 'name' => 'Template 1', 'content' => '...'],
            ['id' => 2, 'name' => 'Template 2', 'content' => '...'],
        ]);

        $emailTemplateMock = Mockery::mock(EmailTemplate::class);
        $emailTemplateMock->shouldReceive('all')->andReturn($dummyEmailTemplates);

        $controller = new EmailTemplateController($emailTemplateMock);

        $response = $controller->index();

        $response->assertJson($dummyEmailTemplates->toArray());
    }
}
