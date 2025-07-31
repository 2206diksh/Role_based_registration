<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');  // Original file name
            $table->string('file_path');      // Path where file is stored
            $table->string('mime_type');      // File type
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('uploads');
    }
};
