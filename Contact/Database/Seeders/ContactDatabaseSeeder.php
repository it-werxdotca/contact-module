<?php

namespace Modules\Contact\Database\Seeders;

use Illuminate\Database\Seeder;

class ContactDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      /**
       * Run the database seeds.
       */
      public function run(): void
      {
          Schema::create('contacts', function (Blueprint $table) {
              $table->id();
              $table->string('name');
              $table->string('email');
              $table->string('phone');
              $table->string('subject');
              $table->text('message');
              $table->timestamps();
          });
      }  // $this->call([]);
    }
}
