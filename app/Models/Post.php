<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Post.
 *
 * @package namespace App\Models;
 *
 * @OA\Schema ( title="Post", description="Post model", @OA\Xml( name="Post" ) )
 * @OA\Property(property="id", title="id", description="id", type="integer", example="1")
 * @OA\Property(property="title", title="title", description="title", type="string", example="Graphics")
 * @OA\Property(property="description", title="description", description="description", type="string", example="Center the graphic.")
 * @OA\Property(property="status", title="status", description="status", type="integer", example="1")
 * @OA\Property(property="created_at", title="created_at", description="created_at", type="string", example="2021-03-01 00:00:00")
 * @OA\Property(property="updated_at", title="updated_at", description="updated_at", type="string", example="2021-03-01 00:00:00")
 * @OA\Property(property="deleted_at", title="deleted_at", description="deleted_at", type="string", example="2021-03-01 00:00:00")
 * @OA\Property(property="glossary", title="glossary", description="glossary", type="object", ref="#/components/schemas/Glossary")
 * @OA\Property(property="glossary_id", title="glossary_id", description="glossary_id", type="integer", example="1")
 *
 */
class Post extends Model implements Transformable
{
    use TransformableTrait, HasFactory, SoftDeletes;

    protected $table = 'posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'title',
		'description',
        'status',
        'glossary_id'
	];

    public function glossary()
    {
        return $this->belongsTo(Glossary::class, 'glossary_id');
    }

}
