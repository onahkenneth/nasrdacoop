<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Staff;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $staff = new Staff;
        $staff->ippis = '17679';
        $staff->full_name = 'John Okpanachi';
        $staff->pay_point = 7;
        $staff->save();

        $user = User::create([
            'name'      => $staff->full_name, 
            'username'  => $staff->ippis, 
            'ippis'     => $staff->ippis, 
            'password'  => \Hash::make('17679'), 
        ]);

        // assign role
        $user->assignRole('secretary');
    }
}
