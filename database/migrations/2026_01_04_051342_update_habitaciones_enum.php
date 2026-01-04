<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE fact_registro_clientes MODIFY habitacion ENUM('201','202','203','204','205','206','207','301','302','303','304','305','306','307','401','402','403','404','405','406','407','408','409','501','502','503','504','505','506','507','508','509','601','602','603','604')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a la configuración anterior si es necesario
        DB::statement("ALTER TABLE fact_registro_clientes MODIFY habitacion ENUM('201','202','203','204','205','206','207','208','209','210','301','302','303','304','305','306','307','308','309','310','401','402','403','404','405','406','407','408','409','410')");
    }
};