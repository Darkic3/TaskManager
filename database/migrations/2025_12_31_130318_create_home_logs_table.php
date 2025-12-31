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

        Schema::create('home_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();
            $table->text('url')->nullable();
            $table->string('method')->nullable();
            $table->json('payload')->nullable();
            $table->string('language')->nullable();
            $table->string('event_type')->default('visit'); // visit یا click
            $table->string('target_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('screen_res')->nullable();
            $table->string('timezone')->nullable();
            $table->string('gpu')->nullable();
            $table->integer('cpu_threads')->nullable();
            $table->string('battery')->nullable();
            $table->string('browser_lang')->nullable();
            $table->boolean('is_vpn')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_logs');
    }
};
