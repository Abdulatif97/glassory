<?php

namespace Tests\Feature\Api\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * update post
     *
     * @return void
     */
    /** @test */
    public function can_update_post() {
        $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        $data = [
            'title' => 'test Plus',
            'status' => 1,
            'description' => 'test Plus',
        ];

        $response = $this->put(route('api.v1.post.update',['id' => $post->id]), $data, [
            'Authorization' => 'Bearer ' . 'token',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'payload'
        ]);
        $this->assertEquals($data['title'], $response['payload']['title']);
        $this->assertEquals($data['status'], $response['payload']['status']);
    }
}
