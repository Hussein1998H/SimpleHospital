<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Patient;
use App\Models\Specialize;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'doctor']);

       $spic1= Specialize::create([
            'name'=>'bone'
        ]);
        $spic2= Specialize::create([
            'name'=>'heart'
        ]);
        $user = User::create([
            'special_id'=>$spic1->id,
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '0999462635',
            'password' => Hash::make(123456789),
        ]);
        $user->assignRole('admin');
        $user = User::create([
            'special_id'=>$spic1->id,
            'name' => 'doctor',
            'email' => 'doctor@gmail.com',
            'phone' => '0999462635',
            'password' => Hash::make(123456789),
        ]);
        $user->assignRole('doctor');
        $user2 = User::create([
            'special_id'=>$spic2->id,
            'name' => 'doctor2',
            'email' => 'doctor2@gmail.com',
            'phone' => '0999462635',
            'password' => Hash::make(123456789),
        ]);
        $user2->assignRole('doctor');

        $paint = Patient::create([
            'name' => 'Patient',
            'email' => 'user@gmail.com',
            'phone' => '0999462635',
            'password' => Hash::make(123456789),
        ]);
    }
}
