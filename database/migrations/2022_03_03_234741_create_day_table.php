<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day', function (Blueprint $table) {
            $table->id();
            $table->integer("date_code");
            $table->integer("sprint_id");
            $table->string("total_points_done");
            $table->string("total_points_remaining");
            $table->string("total_goal_points_done");
            $table->timestamps();
        });

        Schema::table('day', function (Blueprint $table) {
            $table
                ->foreign("sprint_id", "day_sprint_id_fk")
                ->references("id")
                ->on("sprint");
            $table->unique(
                [
                    "date_code",
                    "sprint_id",
                ],
                "day_date_code_sprint_id_uk"
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('day', function (Blueprint $table) {
            $table->dropUnique("day_date_code_sprint_id_uk");
            $table->dropForeign("day_sprint_id_fk");
        });
        Schema::dropIfExists('day');
    }
};
