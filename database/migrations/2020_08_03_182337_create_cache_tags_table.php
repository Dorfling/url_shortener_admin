<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCacheTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags.cache_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')
                ->default(\Illuminate\Support\Facades\DB::raw('uuid_generate_v4()'))
                ->unique();
            $table->timestampTz('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('name');
            $table->index(['name']);
        });

        \App\Libraries\Helper\DatabaseLibrary::setUpdatedAtTrigger('tags.cache_tags');

        DB::insert(
            "INSERT INTO tags.cache_tags (id, uuid, name) VALUES (1, 'be46902c-eeda-4c78-a1af-6a4d5087fec5', 'domains')"
        );
        DB::insert(
            "INSERT INTO tags.cache_tags (id, uuid, name) VALUES (2, '951af650-587c-4b53-9d7f-b7d945e4762d', 'short_urls')"
        );
        DB::insert(
            "INSERT INTO tags.cache_tags (id, uuid, name) VALUES (3, 'e71ea2bf-eae2-49c5-bcd9-70abdd3aad28', 'companies')"
        );

        \App\Libraries\Helper\DatabaseLibrary::updateSequenceAfterInsert(
            'tags.cache_tags'
        );


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags.cache_tags');
    }
}
