<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddPinRenameId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Rename 'id' to 'User_id' and ensure it's the primary key
            // Laravel's renameColumn method should handle primary key constraint if it was already set.
            // If 'id' was already auto-incrementing primary key, it will remain so.
            $table->renameColumn('id', 'User_id');

            // 2. Add a PIN column after the email address
            // We'll make it a string, assuming it might be numeric but stored as text
            // You can change nullable() to ->nullable(false) if it's mandatory
            $table->string('PIN')->nullable()->after('email');

            // 3. Encrypt all passwords:
            // This is handled at the application level (e.g., in your AuthController)
            // when users register or update their password. The column type itself
            // remains a string (VARCHAR 255) to store the hashed password.
            // No schema change needed for encryption itself, just ensuring it's string.
            // If your 'password' column is not already a string, you might need to
            // adjust its type, but it's typically already a string.
            // Example: $table->string('password', 255)->change();
            // Assuming it's already a string, no explicit change is needed here.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes in the down method
            // 1. Rename 'User_id' back to 'id'
            $table->renameColumn('User_id', 'id');

            // 2. Drop the PIN column
            $table->dropColumn('PIN');
        });
    }
}


