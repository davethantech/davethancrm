<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('leads', function(Blueprint $table) { $table->id(); $table->string('name'); $table->string('company')->nullable(); $table->string('email')->nullable()->index(); $table->string('phone',50)->nullable(); $table->string('source',80)->nullable(); $table->string('status',30)->default('new')->index(); $table->decimal('value',12,2)->nullable(); $table->text('notes')->nullable(); $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('leads'); }
};