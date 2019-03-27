<?php

use org\bovigo\vfs\vfsStream;

class HierarchyServiceProviderTest extends TestBase {

    /** @test */
    function it_registers_generated_path()
    {
        $this->assertStringStartsWith(vfsStream::url('gen'), app('path.generated'));
        $this->assertInternalType('string', app('path.generated'));
    }

    /** @test */
    function it_registers_nodebag()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\NodeBag',
            $this->app['hierarchy.nodebag']
        );
    }

    /** @test */
    function it_registers_locale_manager()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Support\LocaleManager',
            $this->app['hierarchy.support.locale']
        );
    }

    /** @test */
    function it_registers_node_type_bag()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Bags\NodeTypeBag',
            $this->app['hierarchy.bags.nodetype']
        );
    }

}