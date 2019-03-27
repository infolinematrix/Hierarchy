<?php


class NodeRepositoryTest extends TestBase {

    protected function getNodeRepository()
    {
        return $this->app->make('Reactor\Hierarchy\NodeRepository');
    }

    /** @test */
    function it_is_instantiatable()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\NodeRepository',
            $this->getNodeRepository()
        );
    }

}