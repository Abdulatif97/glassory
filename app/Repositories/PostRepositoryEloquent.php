<?php

namespace App\Repositories;

use App\Repositories\Criteria\GlossaryCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\PostRepository;
use App\Models\Post;
use App\Validators\PostValidator;

/**
 * Class PostRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PostRepositoryEloquent extends BaseRepository implements PostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PostValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * @param $glossary_id
     * @return $this
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function filterByGlossaryId($glossary_id)
    {
        $this->glossary_id = $glossary_id;
        $this->pushCriteria(app(GlossaryCriteria::class));
        return $this;
    }

}
