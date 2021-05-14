<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_history', function (Blueprint $table) {
            $table->increments('id');
            
            // Laravel doesnt support FLOAT columns in migrations
            // The best solution I have found is there: https://laracasts.com/discuss/channels/laravel/schema-float-function-generated-a-double-type
            // $table->float('value');
            // $table->float('balance');

            $table->integer('user_id');
            $table->dateTime('created_at');

            $table->index('user_id');
        });

        DB::statement('ALTER TABLE balance_history
            ADD value FLOAT NOT NULL AFTER id,
            ADD balance FLOAT NOT NULL AFTER value;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_history');
    }
}
