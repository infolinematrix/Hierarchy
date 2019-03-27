<?php echo '<?php'; ?>

// WARNING! THIS IS A GENERATED FILE, PLEASE DO NOT EDIT!

namespace gen\Migrations;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Reactor\Hierarchy\Contract\Migration\MigrationContract;

class {{ $migration }} extends Migration implements MigrationContract {

    /**
     * Run the migrations.
     */
    public function up()
    {
        \Schema::table('{{ $table }}', function (Blueprint $table)
        {
            $table->{{ $type }}('{{ $field }}')->nullable();

            @if($indexed)
            $table->index('{{ $field }}');
            @endif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        \Schema::table('{{ $table }}', function (Blueprint $table)
        {
            $table->dropColumn('{{ $field }}');
        });
    }

}