<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert('CREATE SCHEMA billing');

        Schema::create('billing.payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')
                ->default(\Illuminate\Support\Facades\DB::raw('uuid_generate_v4()'))
                ->unique();
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('name')->unique();
        });
        \App\Libraries\Helper\DatabaseLibrary::setUpdatedAtTrigger('billing.payment_gateways');
        $sql = 'INSERT INTO billing.payment_gateways (id, uuid, name)
                VALUES (1, \'5ba11c62-29bf-4108-aa89-9e2400c4b8a4\', \'Payfast\')';
        DB::insert($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('DROP SCHEMA IF EXISTS tags CASCADE');
    }
}
