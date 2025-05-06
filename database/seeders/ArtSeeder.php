<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\ArtPiece;

class ArtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $artistsArray = [
            [
                'name' => 'Jesper Liengaard',
                'description' => 'Jesper Liengaard maler mere end det man ser. Han kommunikerer gennem et enkelt motiv, men i et stærkt formsprog. Motiverne er underfundige, men samtidig dybt alvorlige og med en dybereliggende mening eller kommentar til den verden vi lever i.',
                'image' => 'https://scontent.fcph4-1.fna.fbcdn.net/v/t39.30808-6/292454482_463597999103333_1570529497224714354_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=5T_TAdSN1lQQ7kNvwGcHoqE&_nc_oc=Adnwsy0kMnxeaIa8AgYM6IUE88zemz76g4ibdW64zm0XGJ1G7Vmb3mMeyeuIjX9c0J4wEmvOsMUjangOJNniKws-&_nc_zt=23&_nc_ht=scontent.fcph4-1.fna&_nc_gid=pamerwtLyiliYH5u4ijnkQ&oh=00_AfFNiUdJBe2XSuhYlPlY8cTnFlpzX6lidvhkoFsiuNiTag&oe=68128858',
                'art_pieces' => [
                    [
                        'title' => 'Wishing Well',
                        'description' => 'acrylic on canvas',
                        'image' => 'https://impro.usercontent.one/appid/oneComWsb/domain/galleri-a.nu/media/galleri-a.nu/onewebmedia/wishing-well-50x100.jpg?etag=W%2F%2213f85a-67e3d824%22&sourceContentType=image%2Fjpeg&ignoreAspectRatio&resize=2500,4978&quality=85',
                        'medium' => 'acrylic on canvas',
                        'height' => '100',
                        'width' => '50',
                        'year' => '2024',
                        'price' => '17000',
                    ],
                    [
                        'title' => 'Ready For The Ball',
                        'description' => 'acrylic on canvas',
                        'image' => 'https://impro.usercontent.one/appid/oneComWsb/domain/galleri-a.nu/media/galleri-a.nu/onewebmedia/Ready-for-the-ball-80x100.jpg?etag=%2216a2e4-67b85de4%22&sourceContentType=image%2Fjpeg&ignoreAspectRatio&resize=2500,3333&quality=85',
                        'medium' => 'acrylic on canvas',
                        'height' => '100',
                        'width' => '80',
                        'year' => '2024',
                        'price' => '19000',
                    ],
                    [
                        'title' => 'Let the Sunshine In',
                        'description' => 'oil on canvas',
                        'image' => 'https://impro.usercontent.one/appid/oneComWsb/domain/galleri-a.nu/media/galleri-a.nu/onewebmedia/Let-the-sunshine-in-80x80-80x80.jpg?etag=%221a765b-67b85de5%22&sourceContentType=image%2Fjpeg&ignoreAspectRatio&resize=2500,2561&quality=85',
                        'medium' => 'oil on canvas',
                        'height' => '80',
                        'width' => '80',
                        'year' => '2024',
                        'price' => '0',
                    ],
                    [
                        'title' => 'All Good Things Comes To Those Who Wait',
                        'description' => 'acrylic on canvas',
                        'image' => 'https://impro.usercontent.one/appid/oneComWsb/domain/galleri-a.nu/media/galleri-a.nu/onewebmedia/All-good-comes-to-those-who-wait-100x100.jpg?etag=%223fc56b-67b85de7%22&sourceContentType=image%2Fjpeg&ignoreAspectRatio&resize=2500,2500&quality=85',
                        'medium' => 'acrylic on canvas',
                        'height' => '100',
                        'width' => '100',
                        'year' => '2024',
                        'price' => '21000',
                    ],
                    [
                        'title' => 'Carry On',
                        'description' => 'acrylic on canvas',
                        'image' => 'https://impro.usercontent.one/appid/oneComWsb/domain/galleri-a.nu/media/galleri-a.nu/onewebmedia/Carry-on-150x50.jpg?etag=%2224e0bc-67b85de6%22&sourceContentType=image%2Fjpeg&ignoreAspectRatio&resize=2500,895&quality=85',
                        'medium' => 'acrylic on canvas',
                        'height' => '50',
                        'width' => '150',
                        'year' => '2024',
                        'price' => '0',
                    ]
                ],
            ],
            
        ];

        foreach ($artistsArray as $artistInfo) {
            $artist = Artist::create([
                'name' => $artistInfo['name'],
                'description' => $artistInfo['description'],
            ]);
            $artist
                ->addMediaFromUrl($artistInfo['image'])
                ->toMediaCollection('profile_images');

            foreach ($artistInfo['art_pieces'] as $artPiece) {
                $artPieceModel = ArtPiece::create([
                    'title' => $artPiece['title'],
                    'description' => $artPiece['description'],
                    'medium' => $artPiece['medium'],
                    'height' => $artPiece['height'],
                    'width' => $artPiece['width'],
                    'year' => $artPiece['year'],
                    'price' => $artPiece['price'],
                    'artist_id' => $artist->id,
                ]);
                $artPieceModel
                    ->addMediaFromUrl($artPiece['image'])
                    ->toMediaCollection('art_pieces');
                #$artist->artPieces()->attach($artPiece);
            }
        }

    }
}
