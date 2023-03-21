<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DTOCollection;
use App\Http\Resources\GlossaryResource;
use App\Services\Contracts\GlossaryService;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\GlossaryCreateRequest;
use App\Http\Requests\GlossaryUpdateRequest;
use App\Repositories\Contracts\GlossaryRepository;
use App\Validators\GlossaryValidator;

/**
 *
 * @resource Glossary
 *
 * Class GlossaryController.
 *
 * @package namespace App\Http\Controllers\Api\v1;
 */
class GlossaryController extends Controller
{
    /**
     * @var GlossaryRepository
     */
    protected $repository;

    /**
     * @var GlossaryValidator
     */
    protected $validator;

    /**
     * @var GlossaryService
     */
    protected $service;

    /**
     * GlossaryController constructor.
     *
     * @param GlossaryRepository $repository
     * @param GlossaryValidator $validator
     * @param GlossaryService $service
     */
    public function __construct(GlossaryRepository $repository, GlossaryValidator $validator, GlossaryService $service)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/glossary",
     *     tags={"Glossary"},
     *     summary="Get list of glossary",
     *     description="Returns list of glossary",
     *     security={{"bearer_token":{}}},
     *     operationId="api.v1.glossary.index",
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
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Glossary")
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
        $this->response_array['payload'] = new DTOCollection($this->repository->paginate(request()->limit ?? self::PAGE_SIZE), 'GlossaryResource');
        return response()->json($this->response_array);    }


    /**
     * @OA\Post( path="/api/v1/glossary", tags={"Glossary"}, summary="Create new glossary", description="Create new glossary",   security={{"bearer_token":{}}}, operationId="api.v1.glossary.store",
     *     @OA\RequestBody( required=true, @OA\JsonContent( ref="#/components/schemas/Glossary" ) ),
     *     @OA\Response( response=201, description="successful operation",
     *     @OA\JsonContent( ref="#/components/schemas/Glossary" ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent(
     *     @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array",
     *     @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array",
     *     @OA\Items( type="string" ) ), ) ) ) )
     */
    public function store(GlossaryCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $glossary = $this->service->store($request->all());
            $this->response_array['message'] = 'Glossary created.';
            $this->response_array['payload'] = $glossary->toArray();

        } catch (ValidatorException $e) {
            $this->response_array['success'] = false;
            $this->response_array['message'] = $e->getMessageBag();

        }
        return response()->json($this->response_array, 201);

    }

    /**
     * @OA\Get( path="/api/v1/glossary/{id}", tags={"Glossary"}, summary="Get glossary by id", description="Get glossary by id",  security={{"bearer_token":{}}}, operationId="api.v1.glossary.show",
     *     @OA\Parameter( name="id", in="path", required=true, @OA\Schema( type="integer" ) ),
     *     @OA\Response( response=200, description="successful operation",
     *     @OA\JsonContent( type="array", @OA\Items(ref="#/components/schemas/Glossary") ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent( @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array", @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array", @OA\Items( type="string" ) ), ) ) )
     * )
     */
    public function show($id)
    {
        $glossary = $this->repository->find($id);
        $this->response_array['payload'] = new GlossaryResource($glossary);
        return response()->json($this->response_array);

    }

    /**
     * @OA\Put( path="/api/v1/glossary/{id}", tags={"Glossary"}, summary="Update glossary by id", description="Update glossary by id",  security={{"bearer_token":{}}}, operationId="api.v1.glossary.update",
     *     @OA\Parameter( name="id", in="path", required=true, @OA\Schema( type="integer" ) ),
     *     @OA\RequestBody( required=true,
     *     @OA\MediaType( mediaType="application/json",
     *     @OA\Schema(
     *     @OA\Property( property="name", type="string", example="New glossary" ),
     *     @OA\Property( property="color", type="string", example="#000000" ),))),
     *     @OA\Response( response=200, description="successful operation",
     *     @OA\JsonContent( type="array", @OA\Items(ref="#/components/schemas/Glossary") ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent( @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array", @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array", @OA\Items( type="string" ) ), ) ) ) )
     */
    public function update(GlossaryUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $glossary = $this->service->update($id, $request->all());
            $this->response_array['message'] = 'Glossary updated.';
            $this->response_array['payload'] = $glossary->toArray();

        } catch (ValidatorException $e) {
            $this->response_array['success'] = false;
            $this->response_array['message'] = $e->getMessageBag();
        }
        return response()->json($this->response_array);
    }


    /**
     * @OA\Delete( path="/api/v1/glossary/{id}", tags={"Glossary"}, summary="Delete glossary by id", description="Delete glossary by id",   security={{"bearer_token":{}}}, operationId="api.v1.glossary.destroy",
     *     @OA\Parameter( name="id", in="path", required=true, @OA\Schema( type="integer" ) ),
     *     @OA\Response( response=200, description="successful operation",
     *     @OA\JsonContent( type="array", @OA\Items(ref="#/components/schemas/Glossary") ) ),
     *     @OA\Response( response="default", description="unexpected error",
     *     @OA\JsonContent( @OA\Property( property="message", type="string", example="" ),
     *     @OA\Property( property="errors", type="array", @OA\Items( type="string" ) ),
     *     @OA\Property( property="payload", type="array", @OA\Items( type="string" ) ), ) ) )
     * )
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        $this->response_array['message'] = 'Glossary deleted.';
        return response()->json($this->response_array);

    }
}
