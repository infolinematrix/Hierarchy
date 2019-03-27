<?php

namespace Reactor\Hierarchy\Contract\Builders;


use Reactor\Hierarchy\Contract\NodeTypeContract;

interface BuilderServiceContract {

    /**
     * Builds a source table and associated entities
     *
     * @param string $name
     * @param int $id
     */
    public function buildTable($name, $id);

    /**
     * Builds a field on a source table and associated entities
     *
     * @param string $name
     * @param string $type
     * @param bool $indexed
     * @param string $tableName
     * @param NodeTypeContract $nodeType
     */
    public function buildField($name, $type, $indexed, $tableName, NodeTypeContract $nodeType);

    /**
     * Destroys a source table and all associated entities
     *
     * @param string $name
     * @param array $fields
     * @param int $id
     */
    public function destroyTable($name, array $fields, $id);

    /**
     * Destroys a field on a source table and all associated entities
     *
     * @param string $name
     * @param string $tableName
     * @param NodeTypeContract $nodeType
     */
    public function destroyField($name, $tableName, NodeTypeContract $nodeType);

}