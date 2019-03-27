<?php

namespace Reactor\Hierarchy\Repositories;


use Reactor\Hierarchy\Contract\Builders\BuilderServiceContract;

abstract class Repository {

    /**
     * The builder service for the node type
     *
     * @var BuilderServiceContract
     */
    protected $builderService;

    /**
     * Constructor
     *
     * @param BuilderServiceContract $builderService
     */
    public function __construct(BuilderServiceContract $builderService)
    {
        $this->builderService = $builderService;
    }

}