<?php

use App\Domain\Documents\Models\Document;
use App\Domain\Nomenclatures\Models\Nomenclature;
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
        Schema::create('document_nomenclatures', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')
                ->references('id')
                ->on('documents');

            $table->unsignedBigInteger('nomenclature_id');
            $table->foreign('nomenclature_id')
                ->references('id')
                ->on(Nomenclature::tableName());

            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('cost_price')->nullable();
            $table->bigInteger('base_price')->nullable();
            $table->bigInteger('discount')->nullable();
            $table->bigInteger('overheads')->nullable();
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
        Schema::dropIfExists('document_nomenclatures');
    }
};
