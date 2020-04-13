<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartorios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 255);
            $table->string('razao', 255);
            $table->string('tipo_documento', 2);
            $table->string('documento', 14);
            $table->string('cep', 9);
            $table->string('logradouro', 255); // endereco
            $table->string('bairro', 255);
            $table->string('localidade', 255); //cidade
            $table->string('uf', 2);
            $table->string('email', 255)->nullable();
            $table->string('telefone', 14)->nullable();
            $table->string('nome_tabeliao', 255);
            $table->boolean('ativo');
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
        Schema::dropIfExists('cartorios');
    }
}
