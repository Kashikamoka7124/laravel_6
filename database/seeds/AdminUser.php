<?php

use App\Profile;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;
class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
        	'name'=>'customer',
        	'description'=>'customer Role'
        ]);
        $role = Role::create([
        	'name'=>'admin',
        	'description'=>'User Role'
        ]);

        $user = User::create([

        	'email'=>'admin@admin.com',
        	'password'=>bcrypt('secret'),
        	'role_id'=>$role->id
        ]);

        $profile = Profile::create([
        	'user_id'=>$user->id
        ]);

    }
}
