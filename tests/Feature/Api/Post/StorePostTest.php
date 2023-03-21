<?php

namespace Tests\Feature\Api\Post;

use App\Models\Glossary;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StorePostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * create post
     *
     * @return void
     */
    /** @test */
    public function can_create_post() {
        $this->withoutExceptionHandling();
        $data = [
            'title' => 'test',
            'description' => 'test',
            'status' => 1,
            'glossary_id' => Glossary::factory()->create()->id,
        ];

        $response  = $this->postJson(route('api.v1.post.store'), $data, [
            'Authorization' => 'Bearer ' . 'token',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'payload'
        ]);
        $this->assertEquals($data['title'], $response['payload']['title']);
        $this->assertEquals($data['description'], $response['payload']['description']);
        $this->assertEquals($data['status'], $response['payload']['status']);
        $this->assertEquals($data['glossary_id'], $response['payload']['glossary_id']);
    }

}
