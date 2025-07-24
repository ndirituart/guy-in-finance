<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_requests', function (Blueprint $table) {
            $table->id();
           $table->foreignId('user_id')->constrained('users')->onDelete('cascade');//foreign key from Users table
            $table->decimal('amount', 10, 2); // 10 total digits, 2 after decimal
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamp('requested_on')->useCurrent();
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_requests');
    }
}