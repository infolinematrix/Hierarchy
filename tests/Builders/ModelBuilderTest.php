<?php

use Reactor\Hierarchy\Builders\ModelBuilder;
use org\bovigo\vfs\vfsStream;

class ModelBuilderTest extends TestBase {

    protected function getBuilder()
    {
        return new ModelBuilder;
    }

    /** @test */
    function it_creates_a_model()
    {
        $builder = $this->getBuilder();

        $this->assertFileNotExists(
            $builder->getClassFilePath('projecttest')
        );

        $fields = collect(json_decode('[{"name":"date","search_priority":0,"type":"text"},{"name":"area","search_priority":0,"type":"integer"},{"name":"location","search_priority":10,"type":"markdown"}]'));

        $builder->build('projecttest', $fields);

        $this->assertFileExists(
            $builder->getClassFilePath('projecttest')
        );

        $this->assertFileEquals(
            $builder->getClassFilePath('projecttest'),
            dirname(__DIR__) . '/_stubs/entities/model.php'
        );
    }

    /** @test */
    function it_destroys_a_model()
    {
        $builder = $this->getBuilder();

        $fields = collect(json_decode('[{"name":"date","search_priority":0,"type":"text"},{"name":"area","search_priority":0,"type":"integer"},{"name":"location","search_priority":10,"type":"text"}]'));

        $builder->build('projecttest', $fields);

        $this->assertFileExists(
            $builder->getClassFilePath('projecttest')
        );

        $builder->destroy('projecttest');

        $this->assertFileNotExists(
            $builder->getClassFilePath('project')
        );
    }

    /** @test */
    function it_returns_the_class_name()
    {
        $builder = $this->getBuilder();

        $this->assertEquals(
            'NsProject',
            $builder->getClassName('project')
        );
    }

    /** @test */
    function it_returns_entities_path()
    {
        $builder = $this->getBuilder();

        $this->assertEquals(
            vfsStream::url('gen/Entities'),
            $builder->getBasePath()
        );
    }

    /** @test */
    function it_returns_the_class_path()
    {
        $builder = $this->getBuilder();

        $this->assertEquals(
            generated_path() . '/Entities/NsProject.php',
            $builder->getClassFilePath('project')
        );
    }

}