<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DTOCollection;
use App\Http\Resources\PostResource;
use App\Services\Contracts\PostService;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Repositories\Contracts\PostRepository;
use App\Validators\PostValidator;

/**
 *
 * @resource Post
 *
 * Class PostController.
 *
 * @package namespace App\Http\Controllers\Api\v1;
 */
class PostController extends Controller
{
    /**
     * @var PostRepository
     */
    protected $repository;

    /**
     * @var PostValidator
     */
    protected $validator;

    /**
     * @var PostService
     */
    protected $service;

    /**
     * PostController constructor.
     *
     * @param PostRepository $repository
     * @param PostValidator $validator
     * @param PostService $service
     */
    public function __construct(PostRepository $repository, PostValidator $validator, PostService $service)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/post",
     *     tags={"Post"},
     *     summary="Get list of post",
     *     description="Returns list of post",
     *     security={{"bearer_token":{}}},
     *     operationId="api.v1.post.index",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit number of items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter (
     *     name="glossary_id",
     *     in="query",
     *     description="Glossary id",
     *     required=false,
     *     @OA\Schema(
     *     type="integer",
     *     format="int32"
     *    )
     *  ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="payload", type="array", @OA\Items(type="string")),
     *         )
     *     )
     * )
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $this->response_array['payload'] = new DTOCollection($this->repository
            ->filterByGlossaryId(request()->get('glossary_id'))
            ->paginate(request()->limit ?? self::PAGE_SIZE), 'PostResource');
        return response()->json($this->response_array);    }


    /**
     * @OA\Post( path="/api/v1/post", tags={"Post"}, summary="Create new post", description="Create new post",   security={{"bearer_token":{}}}, operationId="api.v1.post.store",
     *     @OA\RequestBody( required=true, @OA\JsonContent( ref="#/components/schemas/Post" ) ),
     *     @OA\Response( response=201, description="successful operation",
     *     @OA\JsonContent( ref="#/components/schemas/Post" ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent(
     *     @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array",
     *     @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array",
     *     @OA\Items( type="string" ) ), ) ) ) )
     */
    public function store(PostCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $post = $this->service->store($request->all());
            $this->response_array['message'] = 'Post created.';
            $this->response_array['payload'] = $post->toArray();

        } catch (ValidatorException $e) {
            $this->response_array['success'] = false;
            $this->response_array['message'] = $e->getMessageBag();

        }
        return response()->json($this->response_array, 201);

    }

    /**
     * @OA\Get( path="/api/v1/post/{id}", tags={"Post"}, summary="Get post information", description="Returns post data",   security={{"bearer_token":{}}}, operationId="api.v1.post.show",
     *     @OA\Parameter( name="id", in="path", description="ID of post to return", required=true, @OA\Schema( type="integer", format="int64" ) ),
     *     @OA\Response( response=200, description="successful operation",
     *     @OA\JsonContent( ref="#/components/schemas/Post" ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent(
     *     @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array",
     *     @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array",
     *     @OA\Items( type="string" ) ), ) ) ) )
     */
    public function show($id)
    {
        $post = $this->repository->find($id);
        $this->response_array['payload'] = new PostResource($post);
        return response()->json($this->response_array);

    }

    /**
     * @OA\Put( path="/api/v1/post/{id}", tags={"Post"}, summary="Update post by id", description="Update post by id",  security={{"bearer_token":{}}}, operationId="api.v1.post.update",
     *     @OA\Parameter( name="id", in="path", required=true, @OA\Schema( type="integer" ) ),
     *     @OA\RequestBody( required=true, @OA\JsonContent( ref="#/components/schemas/Post" ) ),
     *     @OA\Response( response=200, description="successful operation",
     *     @OA\JsonContent( ref="#/components/schemas/Post" ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent( @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array", @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array", @OA\Items( type="string" ) ), ) ) )
     * )
     */
    public function update(PostUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $post = $this->service->update($id, $request->all());
            $this->response_array['message'] = 'Post updated.';
            $this->response_array['payload'] = $post->toArray();

        } catch (ValidatorException $e) {
            $this->response_array['success'] = false;
            $this->response_array['message'] = $e->getMessageBag();
        }
        return response()->json($this->response_array);
    }


    /**
     * @OA\Delete( path="/api/v1/post/{id}", tags={"Post"}, summary="Delete post by id", description="Delete post by id",   security={{"bearer_token":{}}}, operationId="api.v1.post.destroy",
     *     @OA\Parameter( name="id", in="path", required=true, @OA\Schema( type="integer" ) ),
     *     @OA\Response( response=200, description="successful operation",
     *     @OA\JsonContent( type="array", @OA\Items(ref="#/components/schemas/Post") ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent( @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array", @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array", @OA\Items( type="string" ) ), ) ) )
     * )
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        $this->response_array['message'] = 'Post deleted.';
        return response()->json($this->response_array);

    }
}
