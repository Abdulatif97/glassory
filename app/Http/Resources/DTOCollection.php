<?php

namespace App\Http\Resources;

use Illuminate\Container\Container;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DTOCollection extends ResourceCollection
{
    protected $className = '';
    /**
     * @var Container $container
     */
    protected $container;

    public function __construct($resource, string $className = '')
    {
        parent::__construct($resource);
        $this->className = $className;
        $this->container = new Container();
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return $this->container->make(
                    "\App\Http\Resources\\" . $this->className,
                    ['resource'=> $item]
                );
            }),

            'meta'=> [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'links' => [
                    'next' => $this->nextPageUrl(),
                    'previous' => $this->previousPageUrl(),
                ],
            ]
        ];
    }
}
