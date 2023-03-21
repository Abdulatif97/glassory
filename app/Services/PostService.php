<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Contracts\PostRepository;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Database\DatabaseManager;
use App\Services\Contracts\PostService as PostServiceInterface;

/**
 * @method bool destroy
 */
class PostService  extends BaseService implements PostServiceInterface
{
    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var PostRepository $repository
     */
    protected $repository;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * Post constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param PostRepository $repository
     * @param Logger $logger
     */
    public function __construct(
        DatabaseManager $databaseManager,
        PostRepository $repository,
        Logger $logger
    ) {
        $this->databaseManager     = $databaseManager;
        $this->repository     = $repository;
        $this->logger     = $logger;
    }

    /**
     * Create post
     *
     * @param array $data
     * @return Post
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $post = $this->repository->newInstance();

            $post->fill($data);

            if (!$post->save()) {
                throw new Exception('Post was not saved to the database.');
            }

            $this->logger->info('Post successfully saved.', ['post_id' => $post->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $post;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return Post
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $post = $this->repository->find($id);
            if (!$post->update($data)) {
                throw new Exception('An error occurred while updating a Post');
            }

            $this->logger->info('Post  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $post;
    }
    /**
     * Delete block in the storage.
     *
     * @param  int  $id
     *
     * @return array
     *
     * @throws
     */
    public function delete($id)
    {

        $this->beginTransaction();

        try {
            $buffer = [];
            $post = $this->repository->find($id);

            $buffer['id'] = $post->id;
            $buffer['name'] = $post->name;

            if (!$post->delete()) {
                throw new Exception(
                    'Post and  translations was not deleted from database.'
                );
            }
            $this->logger->info('Post  was successfully deleted from database.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while deleting an.', [
                'id'   => $id,
            ]);
        }
        $this->commit();
        return $buffer;
    }
}
