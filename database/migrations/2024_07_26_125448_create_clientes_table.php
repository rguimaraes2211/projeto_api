<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_cliente', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->date('nascimento');
            $table->string('telefone');
            $table->string('estado');
            $table->string('cidade');
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('t_cliente');
    }
}

