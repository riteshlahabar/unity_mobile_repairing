<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('revenue_pin', 60)->nullable()->after('password');
        });

        // Set default PIN '1234' (bcrypt hashed) for ALL existing users
        User::whereNull('revenue_pin')->update([
            'revenue_pin' => Hash::make('1234')
        ]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('revenue_pin');
        });
    }
};
