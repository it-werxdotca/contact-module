<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the 'contacts' table already exists
        if (Schema::hasTable('contacts')) {
            // Log an error message if the table already exists
            Log::error('Migration failed: The "contacts" table already exists.');
            // You might also choose to throw an exception if preferred
            throw new \Exception('The "contacts" table already exists.');
        }

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('subject');
            $table->text('message');
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
        if (Schema::hasTable('contacts')) {
            Schema::dropIfExists('contacts');
        }
    }
};
