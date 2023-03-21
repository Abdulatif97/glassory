<?php

namespace App\Services;

use App\Models\Glossary;
use App\Repositories\Contracts\GlossaryRepository;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Database\DatabaseManager;
use App\Services\Contracts\GlossaryService as GlossaryServiceInterface;

/**
 * @method bool destroy
 */
class GlossaryService  extends BaseService implements GlossaryServiceInterface
{
    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * @var GlossaryRepository $repository
     */
    protected $repository;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * Glossary constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param GlossaryRepository $repository
     * @param Logger $logger
     */
    public function __construct(
        DatabaseManager $databaseManager,
        GlossaryRepository $repository,
        Logger $logger
    ) {
        $this->databaseManager     = $databaseManager;
        $this->repository     = $repository;
        $this->logger     = $logger;
    }

    /**
     * Create glossary
     *
     * @param array $data
     * @return Glossary
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->beginTransaction();
        try {
            $glossary = $this->repository->newInstance();

            $glossary->fill($data);

            if (!$glossary->save()) {
                throw new Exception('Glossary was not saved to the database.');
            }

            $this->logger->info('Glossary successfully saved.', ['glossary_id' => $glossary->id]);
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while storing an ', [
                'data' => $data,
            ]);
        }

        $this->commit();
        return $glossary;
    }

    /**
     * Update block in the storage.
     *
     * @param  int  $id
     * @param  array  $data
     *
     * @return Glossary
     *
     * @throws
     */
    public function update($id, array $data)
    {
        $this->beginTransaction();

        try {
            $glossary = $this->repository->find($id);
            if (!$glossary->update($data)) {
                throw new Exception('An error occurred while updating a Glossary');
            }

            $this->logger->info('Glossary  was successfully updated.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while updating an articles.', [
                'id'   => $id,
                'data' => $data,
            ]);
        }
        $this->commit();
        return $glossary;
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
            $glossary = $this->repository->find($id);

            $buffer['id'] = $glossary->id;
            $buffer['name'] = $glossary->name;

            if (!$glossary->delete()) {
                throw new Exception(
                    'Glossary and  translations was not deleted from database.'
                );
            }
            $this->logger->info('Glossary  was successfully deleted from database.');
        } catch (Exception $e) {
            $this->rollback($e, 'An error occurred while deleting an.', [
                'id'   => $id,
            ]);
        }
        $this->commit();
        return $buffer;
    }
}
