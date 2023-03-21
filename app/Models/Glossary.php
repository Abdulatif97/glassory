<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Glossary.
 *
 * @package namespace App\Models;
 *
 * @OA\Schema ( title="Glossary", description="Glossary model", @OA\Xml( name="Glossary" ) )
 * @OA\Property(property="id", title="id", description="id", type="integer", example="1")
 * @OA\Property(property="title", title="title", description="title", type="string", example="Technique")
 * @OA\Property(property="description", title="description", description="description", type="string", example="A very sound technique")
 * @OA\Property(property="status", title="status", description="status", type="integer", example="1")
 * @OA\Property(property="created_at", title="created_at", description="created_at", type="string", example="2021-03-01 00:00:00")
 * @OA\Property(property="updated_at", title="updated_at", description="updated_at", type="string", example="2021-03-01 00:00:00")
 * @OA\Property(property="deleted_at", title="deleted_at", description="deleted_at", type="string", example="2021-03-01 00:00:00")
 * @OA\Property(property="posts", title="posts", description="posts", type="array", @OA\Items(ref="#/components/schemas/Post"))
 */
class Glossary extends Model implements Transformable
{
    use TransformableTrait, HasFactory, SoftDeletes;

    protected $table = 'glossaries';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'title',
		'description',
        'status',
    ];

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Post::class, 'glossary_id', 'id');
    }

}
