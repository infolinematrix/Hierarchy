<?php

namespace Reactor\Hierarchy\Contract\Migration;


interface MigrationContract {

    /**
     * Run the migrations.
     */
    public function up();

    /**
     * Reverse the migrations.
     */
    public function down();

}