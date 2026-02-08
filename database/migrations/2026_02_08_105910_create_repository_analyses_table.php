<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repository_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repository_id')->index();
            $table->text('summary')->nullable();
            $table->text('architecture_diagram')->nullable();
            $table->mediumText('readme')->nullable();
            $table->json('files')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repository_analyses');
    }
};