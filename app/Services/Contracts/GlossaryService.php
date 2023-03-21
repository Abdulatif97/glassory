<?php

namespace App\Services\Contracts;

use App\Models\Glossary;

/**
 * Interface GlossaryService.
 *
 * @package namespace App\Services\Contracts;
 */
interface GlossaryService extends BaseService
{
    /**
     * Store a newly created resource in storage
     *
     * @param array $data
     * @return Glossary
     */
    public function store(array $data);

    /**
     * Update block in the storage.
     *
     * @param  string  $id
     * @param  array  $data
     * @return Glossary
     */
    public function update(string $id, array $data);

    /**
     * Update block in the storage.
     *
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id);
}
