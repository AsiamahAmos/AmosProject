<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        
        Schema::create('offline_report', function (Blueprint $table) {
        Schema::rename('offline_report', 'offline_reports');
            $table->bigIncrements('id');
            $table->char('document_name', 255);
            $table->char('ref_number', 255);
            $table->char('description', 255);
            $table->char('branch_name', 255);
            $table->char('department', 255);
            $table->char('document_path', 255);
            $table->char('date_created', 255);
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
        Schema::dropIfExists('offline_report');
    }
}
