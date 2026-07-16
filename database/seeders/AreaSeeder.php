<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            // Tokyo
            ['area_name' => 'Asakusa', 'image_url' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=500'],
            ['area_name' => 'Shibuya', 'image_url' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=500'],
            ['area_name' => 'Akihabara', 'image_url' => 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?w=500'],
            ['area_name' => 'Shinjuku', 'image_url' => 'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?w=500'],

            // Hokkaido
            ['area_name' => 'Niseko', 'image_url' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=500'],
            ['area_name' => 'Sapporo', 'image_url' => 'https://images.unsplash.com/photo-1578507065211-176001a4ac24?w=500'],
            ['area_name' => 'Otaru', 'image_url' => 'https://images.unsplash.com/photo-1590483259850-2f9862ca8541?w=500'],
            ['area_name' => 'Hakodate', 'image_url' => 'https://images.unsplash.com/photo-1536693540056-aa156bb3fc60?w=500'],

            // Aichi
            ['area_name' => 'Nagoya Castle', 'image_url' => 'https://images.unsplash.com/photo-1601042879364-f3947d3f9c16?w=500'],
            ['area_name' => 'Sakae, Osu', 'image_url' => 'https://images.unsplash.com/photo-1618386230353-3631c1ca65be?w=500'],
            ['area_name' => 'Nagakute (Ghibli Park)', 'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=500'],
            ['area_name' => 'Inuyama (Inuyama Castle)', 'image_url' => 'https://images.unsplash.com/photo-1599839343648-523c91a036bf?w=500'],

            // Osaka
            ['area_name' => 'Osaka Castle', 'image_url' => 'https://images.unsplash.com/photo-1590244921253-53f29518f265?w=500'],
            ['area_name' => 'Dotonbori, Shinsaibashi (Minami)', 'image_url' => 'https://images.unsplash.com/photo-1571345639682-16fc7da4e857?w=500'],
            ['area_name' => 'Konohana (USJ)', 'image_url' => 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=500'],
            ['area_name' => 'Shinsekai (Tsutenkaku)', 'image_url' => 'https://images.unsplash.com/photo-1598211684814-25e27a696bf5?w=500'],

            // Fukuoka
            ['area_name' => 'Tenjin, Daimyo', 'image_url' => 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?w=500'],
            ['area_name' => 'Hakata, Nakasu', 'image_url' => 'https://images.unsplash.com/photo-1624138784614-87fd1b6528f8?w=500'],
            ['area_name' => 'Dazaifu', 'image_url' => 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?w=500'],
            ['area_name' => 'Itoshima', 'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500'],

            // Okinawa
            ['area_name' => 'Naha, Kokusai dori', 'image_url' => 'https://images.unsplash.com/photo-1627915509930-4e3230c1e05d?w=500'],
            ['area_name' => 'Motobu, Nago (Churaumi Aquarium)', 'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=500'],
            ['area_name' => 'Onna, Chatan (American Village)', 'image_url' => 'https://images.unsplash.com/photo-1498654896293-37aaea113fd9?w=500'],
            ['area_name' => 'Ishigaki Islands, Yaeyama Islands', 'image_url' => 'https://images.unsplash.com/photo-1538964173425-93884d739596?w=500'],
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate(
                ['area_name' => $area['area_name']],
                ['image_url' => $area['image_url']]
            );
        }
    }
}