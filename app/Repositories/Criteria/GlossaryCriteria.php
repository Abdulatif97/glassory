<?php


namespace App\Repositories\Criteria;


use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

class GlossaryCriteria extends RequestCriteria
{
    public function apply($model, RepositoryInterface $repository)
    {
        $glossary = $repository->glossary_id;

        if($glossary){
            return $model->where('glossary_id', $glossary);
        }
        return $model;
    }

}
