<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\GlossaryRepository;
use App\Models\Glossary;
use App\Validators\GlossaryValidator;

/**
 * Class GlossaryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class GlossaryRepositoryEloquent extends BaseRepository implements GlossaryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Glossary::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return GlossaryValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
