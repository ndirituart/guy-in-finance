<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();
            // Link to the user who made the transaction
            // Assuming your users table has 'User_id' as its primary key
            $table->foreignId('_User_id')->constrained('users', 'User_id')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Amount of the transaction (e.g., 100.00)
            $table->enum('type', ['deposit', 'withdrawal']); // Type of transaction
            $table->string('description')->nullable(); // Optional description (e.g., "Online deposit", "ATM withdrawal")
            $table->decimal('current_balance', 10, 2); // The user's balance *after* this transaction
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings_transactions');
    }
}

