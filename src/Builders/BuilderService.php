<?php

namespace Reactor\Hierarchy\Builders;


use Reactor\Hierarchy\Contract\Builders\BuilderServiceContract;
use Reactor\Hierarchy\Contract\Builders\FormBuilderContract;
use Reactor\Hierarchy\Contract\Builders\MigrationBuilderContract;
use Reactor\Hierarchy\Contract\Builders\ModelBuilderContract;
use Reactor\Hierarchy\Contract\Migration\MigratorContract;
use Reactor\Hierarchy\Contract\NodeTypeContract;

class BuilderService implements BuilderServiceContract {

    /**
     * Builders for the service
     *
     * @var ModelBuilderContract
     * @var MigrationBuilderContract
     * @var FormBuilderContract
     */
    protected $modelBuilder;
    protected $migrationBuilder;
    protected $formBuilder;

    /**
     * Constructor
     *
     * @param ModelBuilderContract $modelBuilder
     * @param MigrationBuilderContract $migrationBuilder
     * @param FormBuilderContract $formBuilder
     */
    public function __construct(
        ModelBuilderContract $modelBuilder,
        MigrationBuilderContract $migrationBuilder,
        FormBuilderContract $formBuilder
    )
    {
        $this->modelBuilder = $modelBuilder;
        $this->migrationBuilder = $migrationBuilder;
        $this->formBuilder = $formBuilder;
    }

    /**
     * Builds a source table and associated entities
     *
     * @param string $name
     * @param int $id
     */
    public function buildTable($name, $id)
    {
        $this->modelBuilder->build($name);
        $this->formBuilder->build($name);
        $migration = $this->migrationBuilder->buildSourceTableMigration($name);

        $this->migrateUp($migration);
    }

    /**
     * Builds a field on a source table and associated entities
     *
     * @param string $name
     * @param string $type
     * @param bool $indexed
     * @param string $tableName
     * @param NodeTypeContract $nodeType
     */
    public function buildField($name, $type, $indexed, $tableName, NodeTypeContract $nodeType)
    {
        $this->modelBuilder->build($tableName, $nodeType->getFields());
        $this->buildForm($nodeType);
        $migration = $this->migrationBuilder->buildFieldMigrationForTable($name, $type, $indexed, $tableName);

        $this->migrateUp($migration);
    }

    /**
     * (Re)builds a form for given NodeType
     *
     * @param NodeTypeContract $nodeType
     */
    public function buildForm(NodeTypeContract $nodeType)
    {
        $this->formBuilder->build($nodeType->getName(), $nodeType->getFields());
    }

    /**
     * Destroys a source table and all associated entities
     *
     * @param string $name
     * @param array $fields
     * @param int $id
     */
    public function destroyTable($name, array $fields, $id)
    {
        $this->modelBuilder->destroy($name);
        $this->formBuilder->destroy($name);

        $migration = $this->migrationBuilder
            ->getMigrationClassPathByKey($name);

        $this->migrateDown($migration);

        $this->migrationBuilder->destroySourceTableMigration($name, $fields);
    }

    /**
     * Destroys a field on a source table and all associated entities
     *
     * @param string $name
     * @param string $tableName
     * @param NodeTypeContract $nodeType
     */
    public function destroyField($name, $tableName, NodeTypeContract $nodeType)
    {
        $this->modelBuilder->build($tableName, $nodeType->getFields());
        $this->formBuilder->build($tableName, $nodeType->getFields());

        $migration = $this->migrationBuilder
            ->getMigrationClassPathByKey($tableName, $name);

        $this->migrateDown($migration);

        $this->migrationBuilder->destroyFieldMigrationForTable($name, $tableName);
    }

    /**
     * Migrates a migration
     *
     * @param string $class
     */
    protected function migrateUp($class)
    {
        $this->resolveMigration($class)->up();
    }

    /**
     * Reverses a migration
     *
     * @param string $class
     */
    protected function migrateDown($class)
    {
        if($this->resolveMigration($class)){
            $this->resolveMigration($class)->down();
        };
    }

    /**
     * Creates a migration class
     *
     * @param string $class
     * @return MigrationContract
     * @throws \RuntimeException
     */
    protected function resolveMigration($class)
    {
        if (class_exists($class))
        {
            return new $class;
        }
        return false;

        throw new \RuntimeException('Class ' . $class . ' does not exist.');
    }

}