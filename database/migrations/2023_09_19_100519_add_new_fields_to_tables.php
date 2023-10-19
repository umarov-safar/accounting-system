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
        Schema::table('document_nomenclatures', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->default(1)
                ->comment('Статус номенклатуры в документе');

            $table->jsonb('data_offer')
                ->nullable()
                ->comment('Информация об оффера');
            
            $table->bigInteger('quantity_fix')->nullable();
            $table->bigInteger('quantity_no_fix')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('company_to_id')
                ->nullable();

            $table->unsignedBigInteger('parent_id')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_nomenclatures', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('data_offer');
            $table->dropColumn('quantity_fix');
            $table->dropColumn('quantity_no_fix');
            $table->dropColumn('parent_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('company_to_id ');

            $table->dropColumn('parent_id');
        });
    }
};
