<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class VerifierAddColumns extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
    @foreach ($tables as $table)
        Schema::table('{{$table}}', function(Blueprint $table)
        {
            $table->string('{{$codeColumn}}')->nullable();
            $table->boolean('{{$flagColumn}}');
        });
    @endforeach
    }

    public function down()
    {
    @foreach ($tables as $table)
        Schema::table({{$table}}, function(Blueprint $table)
        {
            $table->dropColumn('{{$codeColumn}}');
            $table->dropColumn('{{$flagColumn}}');
        });
    @endforeach
    }
}
