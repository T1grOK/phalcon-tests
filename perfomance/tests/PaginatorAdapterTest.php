<?php

namespace Tests;

class PaginatorAdapterTest extends \Phalcon\CLI\Task
{
    /**
     * @param int $currentPage
     * @param int $limit
     */

    public function modelAction($currentPage = 10, $limit = 10)
    {
        $startMemoryUsage = memory_get_peak_usage();
        $startTime = microtime(true);

        $currentPage = max(1, (int)$currentPage);
        $limit = max(1, (int)$limit);

        $users = \Models\User::find();
        $paginator = new \Phalcon\Paginator\Adapter\Model([
            "data"  => $users,
            "limit" => $limit,
            "page"  => $currentPage
        ]);

        $paginator->getPaginate();

        $finishTime = microtime(true);
        $finishMemoryUsage = memory_get_peak_usage();

        echo "Memory peak usage: ";
        echo $finishMemoryUsage - $startMemoryUsage."\n";
        echo "Time: ";
        echo $finishTime - $startTime."\n";
    }

    /**
     * @param int $currentPage
     * @param int $limit
     */

    public function queryBuilderAction($currentPage = 10, $limit = 10)
    {
        $startMemoryUsage = memory_get_peak_usage();
        $startTime = microtime(true);

        $currentPage = max(1, (int)$currentPage);
        $limit = max(1, (int)$limit);

        $modelsManager = $this->di->getShared('modelsManager');
        $builder = $modelsManager->createBuilder()->from('\Models\User');
        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder([
            "builder"  => $builder,
            "limit" => $limit,
            "page"  => $currentPage
        ]);

        $paginator->getPaginate();

        $finishTime = microtime(true);
        $finishMemoryUsage = memory_get_peak_usage();

        echo "Memory peak usage: ";
        echo $finishMemoryUsage - $startMemoryUsage."\n";
        echo "Time: ";
        echo $finishTime - $startTime."\n";
    }
}