<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
=======

class AddRevenuePinToUsersTable extends Migration
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
<<<<<<< HEAD
            $table->string('revenue_pin', 60)->nullable()->after('password');
        });

        // Set default PIN '1234' (bcrypt hashed) for ALL existing users
        User::whereNull('revenue_pin')->update([
            'revenue_pin' => Hash::make('1234')
        ]);
=======
            $table->string('revenue_pin', 10)->nullable()->after('password')->comment('PIN for revenue report access');
        });
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('revenue_pin');
        });
    }
<<<<<<< HEAD
};
=======
}
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
