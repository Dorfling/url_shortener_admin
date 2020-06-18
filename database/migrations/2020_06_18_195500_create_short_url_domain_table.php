<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortUrlDomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('short_urls.short_url_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')
                ->default(\Illuminate\Support\Facades\DB::raw('uuid_generate_v4()'))
                ->unique();
            $table->timestampTz('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('dt_deleted')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->text('domain');
            $table->foreign('company_id')
                ->references('id')
                ->on('users.companies')
                ->onDelete('cascade');
            $table->index(['uuid']);
            $table->index(['domain']);
            $table->index(['company_id']);
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('short_urls.short_url_domains');
    }
}
