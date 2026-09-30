<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string("medical_record_number", 20)->nullable()->unique();
            $table->text("nik");
            $table->char("nik_hash", 64)->unique();
            $table->string("name", 150);
            $table->text("email");
            $table->char("email_hash", 64)->index();
            $table->string("phone", 20);
            $table->enum("gender", ["male", "female"]);
            $table->date("birth_date");
            $table->text("address");
            $table->timestamps();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
