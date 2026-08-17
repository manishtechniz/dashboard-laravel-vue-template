<?php

namespace Database\Seeders;

use App\Model\Client;
use App\Model\ClientGuest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'id'        => 1,
                'name'      => 'Tester User',
                'email'     => 'test@email.com',
                'phone'     => '9876543210',
                'avatar'    => 'avatars/client_1.jpg',
                'age'       => '28',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 1, // Full Mobile App Access
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Meera Kapoor', 'email' => 'meera.k@gmail.com', 'phone' => '9811001123', 'age' => '26', 'gender' => 'Female'],
                    ['name' => 'Rohan Malhotra', 'email' => 'rohan.m@gmail.com', 'phone' => '9811001124', 'age' => '29', 'gender' => 'Male'],
                    ['name' => 'Kunal Sehgal', 'email' => 'kunal.s@gmail.com', 'phone' => '9811001125', 'age' => '27', 'gender' => 'Male'],
                ],
            ],
            [
                'id'        => 2,
                'name'      => 'Ananya Verma',
                'email'     => 'ananya.verma@yahoo.com',
                'phone'     => '9822113344',
                'avatar'    => 'avatars/client_2.jpg',
                'age'       => '25',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Sneha Sen', 'email' => 'sneha.sen@gmail.com', 'phone' => '9822113345', 'age' => '25', 'gender' => 'Female'],
                    ['name' => 'Pooja Rawat', 'email' => 'pooja.r@gmail.com', 'phone' => '9822113346', 'age' => '24', 'gender' => 'Female'],
                ],
            ],
            [
                'id'        => 3,
                'name'      => 'Kabir Sethi',
                'email'     => 'kabir.sethi@outlook.com',
                'phone'     => '9833224455',
                'avatar'    => 'avatars/client_3.jpg',
                'age'       => '31',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // VIP Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Tarun Bhasin', 'email' => 'tarun.b@gmail.com', 'phone' => '9833224456', 'age' => '32', 'gender' => 'Male'],
                    ['name' => 'Varun Arora', 'email' => 'varun.a@gmail.com', 'phone' => '9833224457', 'age' => '30', 'gender' => 'Male'],
                ],
            ],
            [
                'id'        => 4,
                'name'      => 'Riya Sen',
                'email'     => 'riya.sen@gmail.com',
                'phone'     => '9844335566',
                'avatar'    => 'avatars/client_4.jpg',
                'age'       => '27',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Divya Deshmukh', 'email' => 'divya.d@gmail.com', 'phone' => '9844335567', 'age' => '26', 'gender' => 'Female'],
                ],
            ],
            [
                'id'        => 5,
                'name'      => 'Vikramaditya Rao',
                'email'     => 'vikram.rao@enterprise.com',
                'phone'     => '9855446677',
                'avatar'    => 'avatars/client_5.jpg',
                'age'       => '35',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // VIP Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Aditya Kashyap', 'email' => 'aditya.k@gmail.com', 'phone' => '9855446678', 'age' => '34', 'gender' => 'Male'],
                    ['name' => 'Sameer Nair', 'email' => 'sameer.n@gmail.com', 'phone' => '9855446679', 'age' => '36', 'gender' => 'Male'],
                ],
            ],
            [
                'id'        => 6,
                'name'      => 'Isha Oberoi',
                'email'     => 'isha.oberoi@gmail.com',
                'phone'     => '9866557788',
                'avatar'    => 'avatars/client_6.jpg',
                'age'       => '24',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Kriti Roy', 'email' => 'kriti.roy@gmail.com', 'phone' => '9866557789', 'age' => '24', 'gender' => 'Female'],
                ],
            ],
            [
                'id'        => 7,
                'name'      => 'Siddharth Mehra',
                'email'     => 'siddharth.m@gmail.com',
                'phone'     => '9877668899',
                'avatar'    => 'avatars/client_7.jpg',
                'age'       => '29',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Nikhil Joshi', 'email' => 'nikhil.j@gmail.com', 'phone' => '9877668890', 'age' => '29', 'gender' => 'Male'],
                ],
            ],
            [
                'id'        => 8,
                'name'      => 'Pooja Bhattacharya',
                'email'     => 'pooja.bhatt@gmail.com',
                'phone'     => '9888779900',
                'avatar'    => 'avatars/client_8.jpg',
                'age'       => '26',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Shruti Gupta', 'email' => 'shruti.g@gmail.com', 'phone' => '9888779901', 'age' => '26', 'gender' => 'Female'],
                ],
            ],
            [
                'id'        => 9,
                'name'      => 'Devendra Rajput',
                'email'     => 'dev.rajput@gmail.com',
                'phone'     => '9899880011',
                'avatar'    => 'avatars/client_9.jpg',
                'age'       => '33',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // VIP Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Gaurav Rana', 'email' => 'gaurav.r@gmail.com', 'phone' => '9899880012', 'age' => '33', 'gender' => 'Male'],
                ],
            ],
            [
                'id'        => 10,
                'name'      => 'Natasha Gulati',
                'email'     => 'natasha.gulati@gmail.com',
                'phone'     => '9811223355',
                'avatar'    => 'avatars/client_10.jpg',
                'age'       => '25',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [
                    ['name' => 'Simran Ahluwalia', 'email' => 'simran.a@gmail.com', 'phone' => '9811223356', 'age' => '25', 'gender' => 'Female'],
                ],
            ],
            [
                'id'        => 11,
                'name'      => 'Karan Johar',
                'email'     => 'karan.j@gmail.com',
                'phone'     => '9822334466',
                'avatar'    => 'avatars/client_11.jpg',
                'age'       => '30',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // VIP Member
                'is_active' => true,
                'guests'    => [],
            ],
            [
                'id'        => 12,
                'name'      => 'Tara Sutaria',
                'email'     => 'tara.s@gmail.com',
                'phone'     => '9833445577',
                'avatar'    => 'avatars/client_12.jpg',
                'age'       => '23',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [],
            ],
            [
                'id'        => 13,
                'name'      => 'Manish Sharma',
                'email'     => 'manish.sharma@gmail.com',
                'phone'     => '9844556688',
                'avatar'    => 'avatars/client_13.jpg',
                'age'       => '28',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Staff Role
                'is_active' => true,
                'guests'    => [],
            ],
            [
                'id'        => 14,
                'name'      => 'Neha Dhupia',
                'email'     => 'neha.dhupia@gmail.com',
                'phone'     => '9855667799',
                'avatar'    => 'avatars/client_14.jpg',
                'age'       => '32',
                'gender'    => 'Female',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // Member
                'is_active' => true,
                'guests'    => [],
            ],
            [
                'id'        => 15,
                'name'      => 'Armaan Malik',
                'email'     => 'armaan.malik@gmail.com',
                'phone'     => '9866778800',
                'avatar'    => 'avatars/client_15.jpg',
                'age'       => '27',
                'gender'    => 'Male',
                'password'  => Hash::make('password'),
                'role_id'   => 2, // VIP Member
                'is_active' => true,
                'guests'    => [],
            ],
        ];

        foreach ($clients as $clientData) {
            $guests = $clientData['guests'] ?? [];
            unset($clientData['guests']);

            $client = Client::updateOrCreate(
                ['id' => $clientData['id']],
                $clientData
            );

            foreach ($guests as $guestData) {
                ClientGuest::updateOrCreate(
                    [
                        'client_id' => $client->id,
                        'email'     => $guestData['email'],
                    ],
                    $guestData
                );
            }
        }

        $this->command->info('✅ Clients & Client Guests seeded successfully.');
    }
}
