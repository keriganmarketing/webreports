<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('year');
            $table->integer('month');
            $table->float('current_average_daily_sessions');
            $table->float('previous_average_daily_sessions');
            $table->float('percent_change_sessions');
            $table->integer('current_users');
            $table->integer('previous_users');
            $table->float('percent_change_users');
            $table->integer('current_page_views');
            $table->integer('previous_page_views');
            $table->float('percent_change_page_views');
            $table->float('current_pages_per_session');
            $table->float('previous_pages_per_session');
            $table->float('percent_change_pages_per_session');
            $table->float('current_average_session_duration');
            $table->float('previous_average_session_duration');
            $table->float('percent_change_average_session_duration');
            $table->float('current_bounce_rate');
            $table->float('previous_bounce_rate');
            $table->float('percent_change_bounce_rate');
            $table->float('desktop_percentage');
            $table->float('mobile_percentage');
            $table->float('tablet_percentage');
            $table->float('new_visitors');
            $table->float('returning_visitors');
            $table->float('organic_search');
            $table->float('referral');
            $table->float('direct_traffic');
            $table->float('email');
            $table->float('social');
            $table->float('paid_search');
            $table->longText('most_visited_page_name_1');
            $table->float('most_visited_page_percentage_1');
            $table->longText('most_visited_page_name_2');
            $table->float('most_visited_page_percentage_2');
            $table->longText('most_visited_page_name_3');
            $table->float('most_visited_page_percentage_3');
            $table->longText('most_visited_page_name_4');
            $table->float('most_visited_page_percentage_4');
            $table->longText('most_visited_page_name_5');
            $table->float('most_visited_page_percentage_5');
            $table->longText('most_visited_page_name_6');
            $table->float('most_visited_page_percentage_6');
            $table->longText('most_visited_page_name_7');
            $table->float('most_visited_page_percentage_7');
            $table->longText('most_visited_page_name_8');
            $table->float('most_visited_page_percentage_8');
            $table->longText('most_visited_page_name_9');
            $table->float('most_visited_page_percentage_9');
            $table->longText('most_visited_page_name_10');
            $table->float('most_visited_page_percentage_10');
            $table->boolean('comparedWithLastMonth')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
