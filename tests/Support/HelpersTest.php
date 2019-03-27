<?php

class HelpersTest extends TestBase {

    /** @test */
    function it_registers_generated_path_helper()
    {
        $this->assertEquals(
            $this->app['path.generated'],
            generated_path()
        );
    }

    /** @test */
    function it_registers_source_model_name_helper()
    {
        $this->assertEquals(
            'NsProject',
            source_model_name('project')
        );
    }

    /** @test */
    function it_registers_source_form_name_helper()
    {
        $this->assertEquals(
            'EditProjectForm',
            source_form_name('project')
        );

        $this->assertEquals(
            'gen\\Forms\\EditProjectForm',
            source_form_name('project', true)
        );
    }

    /** @test */
    function it_registers_source_table_name_helper()
    {
        $this->assertEquals(
            'ns_projects',
            source_table_name('project')
        );
    }

    /** @test */
    function it_registers_hierarchy_bag_helper()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\Bags\NodeTypeBag',
            hierarchy_bag('nodetype')
        );
    }

    /** @test */
    function it_registers_node_getter_helpers()
    {
        $this->assertNull(
            get_node_by_id(1337)
        );

        // Cannot test this since there is no field function in sqlite
        $this->assertTrue(function_exists('get_nodes_by_ids'));
    }

    /** @test */
    function it_registers_nodetype_getter_helper()
    {
        // Cannot test this since there is no field function in sqlite
        $this->assertTrue(function_exists('get_nodetypes_by_ids'));
    }

    /** @test */
    function it_registers_locale_helpers()
    {
        $this->assertTrue(function_exists('set_app_locale'));
        $this->assertTrue(function_exists('set_time_locale'));
        $this->assertTrue(function_exists('locales'));
        $this->assertTrue(function_exists('locale_count'));
        $this->assertTrue(function_exists('has_translated'));
    }

    /** @test */
    function it_registers_nodebag_helper()
    {
        $this->assertInstanceOf(
            'Reactor\Hierarchy\NodeBag',
            node_bag()
        );

        $this->assertNull(
            node_bag(1337)
        );
    }

}