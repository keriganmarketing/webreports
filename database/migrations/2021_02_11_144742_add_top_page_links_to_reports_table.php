<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTopPageLinksToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->longText('most_visited_page_link_1');
            $table->longText('most_visited_page_link_2');
            $table->longText('most_visited_page_link_3');
            $table->longText('most_visited_page_link_4');
            $table->longText('most_visited_page_link_5');
            $table->longText('most_visited_page_link_6');
            $table->longText('most_visited_page_link_7');
            $table->longText('most_visited_page_link_8');
            $table->longText('most_visited_page_link_9');
            $table->longText('most_visited_page_link_10');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('most_visited_page_link_1');
            $table->dropColumn('most_visited_page_link_2');
            $table->dropColumn('most_visited_page_link_3');
            $table->dropColumn('most_visited_page_link_4');
            $table->dropColumn('most_visited_page_link_5');
            $table->dropColumn('most_visited_page_link_6');
            $table->dropColumn('most_visited_page_link_7');
            $table->dropColumn('most_visited_page_link_8');
            $table->dropColumn('most_visited_page_link_9');
            $table->dropColumn('most_visited_page_link_10');
        });
    }
}
