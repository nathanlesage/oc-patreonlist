<?php namespace HendrikErz\PatreonList\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateHendrikerzPatreonlistTiers extends Migration
{
    public function up()
    {
        Schema::create('hendrikerz_patreonlist_tiers', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->text('name');
            $table->text('description')->nullable();
            $table->smallInteger('pledge_amount')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hendrikerz_patreonlist_tiers');
    }
}
