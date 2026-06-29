<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Film;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::truncate();
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => 'admin123', // Automatic hashing via casts/mutators or manual hash
        ]);

        // 2. Prepare Storage Directory for Film Posters
        Film::truncate();
        $targetDir = storage_path('app/public/films');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        } else {
            // Clean up old seeded files
            File::cleanDirectory($targetDir);
        }

        $dummyFilms = [
            // --- ACTION ---
            ['judul' => 'The Dark Knight', 'genre' => 'Action', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2008, 'color' => [15, 23, 42]],
            ['judul' => 'Gladiator', 'genre' => 'Action', 'sutradara' => 'Ridley Scott', 'tahun_rilis' => 2000, 'color' => [67, 20, 7]],
            ['judul' => 'John Wick: Chapter 4', 'genre' => 'Action', 'sutradara' => 'Chad Stahelski', 'tahun_rilis' => 2023, 'color' => [24, 24, 27]],
            ['judul' => 'Mad Max: Fury Road', 'genre' => 'Action', 'sutradara' => 'George Miller', 'tahun_rilis' => 2015, 'color' => [120, 53, 4]],
            ['judul' => 'Terminator 2: Judgment Day', 'genre' => 'Action', 'sutradara' => 'James Cameron', 'tahun_rilis' => 1991, 'color' => [30, 41, 59]],
            ['judul' => 'Die Hard', 'genre' => 'Action', 'sutradara' => 'John McTiernan', 'tahun_rilis' => 1988, 'color' => [69, 10, 10]],
            ['judul' => 'Avengers: Endgame', 'genre' => 'Action', 'sutradara' => 'Anthony & Joe Russo', 'tahun_rilis' => 2019, 'color' => [30, 27, 75]],
            ['judul' => 'Spider-Man: Into the Spider-Verse', 'genre' => 'Action', 'sutradara' => 'Bob Persichetti', 'tahun_rilis' => 2018, 'color' => [88, 28, 135]],
            ['judul' => 'Dunkirk', 'genre' => 'Action', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2017, 'color' => [15, 23, 42]],
            ['judul' => 'Django Unchained', 'genre' => 'Action', 'sutradara' => 'Quentin Tarantino', 'tahun_rilis' => 2012, 'color' => [69, 10, 10]],
            ['judul' => 'Inglourious Basterds', 'genre' => 'Action', 'sutradara' => 'Quentin Tarantino', 'tahun_rilis' => 2009, 'color' => [63, 63, 70]],
            ['judul' => 'Spider-Man: No Way Home', 'genre' => 'Action', 'sutradara' => 'Jon Watts', 'tahun_rilis' => 2021, 'color' => [30, 27, 75]],
            ['judul' => 'Top Gun: Maverick', 'genre' => 'Action', 'sutradara' => 'Joseph Kosinski', 'tahun_rilis' => 2022, 'color' => [120, 53, 4]],
            ['judul' => 'Mission: Impossible - Fallout', 'genre' => 'Action', 'sutradara' => 'Christopher McQuarrie', 'tahun_rilis' => 2018, 'color' => [9, 9, 11]],
            ['judul' => 'Baby Driver', 'genre' => 'Action', 'sutradara' => 'Edgar Wright', 'tahun_rilis' => 2017, 'color' => [131, 24, 67]],
            ['judul' => 'Deadpool', 'genre' => 'Action', 'sutradara' => 'Tim Miller', 'tahun_rilis' => 2016, 'color' => [153, 27, 27]],
            ['judul' => 'Ford v Ferrari', 'genre' => 'Action', 'sutradara' => 'James Mangold', 'tahun_rilis' => 2019, 'color' => [220, 38, 38]],

            // --- SCI-FI ---
            ['judul' => 'Inception', 'genre' => 'Sci-Fi', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2010, 'color' => [30, 41, 59]],
            ['judul' => 'Interstellar', 'genre' => 'Sci-Fi', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2014, 'color' => [9, 9, 11]],
            ['judul' => 'The Matrix', 'genre' => 'Sci-Fi', 'sutradara' => 'The Wachowskis', 'tahun_rilis' => 1999, 'color' => [6, 78, 59]],
            ['judul' => 'Avatar: The Way of Water', 'genre' => 'Sci-Fi', 'sutradara' => 'James Cameron', 'tahun_rilis' => 2022, 'color' => [8, 51, 68]],
            ['judul' => 'Dune: Part Two', 'genre' => 'Sci-Fi', 'sutradara' => 'Denis Villeneuve', 'tahun_rilis' => 2024, 'color' => [120, 53, 4]],
            ['judul' => 'Blade Runner 2049', 'genre' => 'Sci-Fi', 'sutradara' => 'Denis Villeneuve', 'tahun_rilis' => 2017, 'color' => [131, 24, 67]],
            ['judul' => 'Tenet', 'genre' => 'Sci-Fi', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2020, 'color' => [24, 24, 27]],
            ['judul' => 'Arrival', 'genre' => 'Sci-Fi', 'sutradara' => 'Denis Villeneuve', 'tahun_rilis' => 2016, 'color' => [4, 47, 46]],
            ['judul' => 'Gravity', 'genre' => 'Sci-Fi', 'sutradara' => 'Alfonso Cuaron', 'tahun_rilis' => 2013, 'color' => [9, 9, 11]],
            ['judul' => 'The Martian', 'genre' => 'Sci-Fi', 'sutradara' => 'Ridley Scott', 'tahun_rilis' => 2015, 'color' => [120, 53, 4]],
            ['judul' => 'Jurassic Park', 'genre' => 'Sci-Fi', 'sutradara' => 'Steven Spielberg', 'tahun_rilis' => 1993, 'color' => [6, 78, 59]],
            ['judul' => 'Star Wars: A New Hope', 'genre' => 'Sci-Fi', 'sutradara' => 'George Lucas', 'tahun_rilis' => 1977, 'color' => [15, 23, 42]],

            // --- DRAMA ---
            ['judul' => 'The Godfather', 'genre' => 'Drama', 'sutradara' => 'Francis Ford Coppola', 'tahun_rilis' => 1972, 'color' => [41, 37, 36]],
            ['judul' => 'The Shawshank Redemption', 'genre' => 'Drama', 'sutradara' => 'Frank Darabont', 'tahun_rilis' => 1994, 'color' => [15, 23, 42]],
            ['judul' => 'Forrest Gump', 'genre' => 'Drama', 'sutradara' => 'Robert Zemeckis', 'tahun_rilis' => 1994, 'color' => [8, 51, 68]],
            ['judul' => 'Pulp Fiction', 'genre' => 'Drama', 'sutradara' => 'Quentin Tarantino', 'tahun_rilis' => 1994, 'color' => [69, 10, 10]],
            ['judul' => 'Fight Club', 'genre' => 'Drama', 'sutradara' => 'David Fincher', 'tahun_rilis' => 1999, 'color' => [24, 24, 27]],
            ['judul' => 'Schindler\'s List', 'genre' => 'Drama', 'sutradara' => 'Steven Spielberg', 'tahun_rilis' => 1993, 'color' => [9, 9, 11]],
            ['judul' => 'Parasite', 'genre' => 'Drama', 'sutradara' => 'Bong Joon Ho', 'tahun_rilis' => 2019, 'color' => [6, 78, 59]],
            ['judul' => 'The Green Mile', 'genre' => 'Drama', 'sutradara' => 'Frank Darabont', 'tahun_rilis' => 1999, 'color' => [4, 47, 46]],
            ['judul' => 'The Prestige', 'genre' => 'Drama', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2006, 'color' => [30, 41, 59]],
            ['judul' => 'Goodfellas', 'genre' => 'Drama', 'sutradara' => 'Martin Scorsese', 'tahun_rilis' => 1990, 'color' => [69, 10, 10]],
            ['judul' => 'Whiplash', 'genre' => 'Drama', 'sutradara' => 'Damien Chazelle', 'tahun_rilis' => 2014, 'color' => [9, 9, 11]],
            ['judul' => 'The Wolf of Wall Street', 'genre' => 'Drama', 'sutradara' => 'Martin Scorsese', 'tahun_rilis' => 2013, 'color' => [20, 83, 45]],
            ['judul' => 'Catch Me If You Can', 'genre' => 'Drama', 'sutradara' => 'Steven Spielberg', 'tahun_rilis' => 2002, 'color' => [8, 51, 68]],
            ['judul' => 'The Lion King', 'genre' => 'Drama', 'sutradara' => 'Roger Allers', 'tahun_rilis' => 1994, 'color' => [120, 53, 4]],
            ['judul' => 'Coco', 'genre' => 'Drama', 'sutradara' => 'Lee Unkrich', 'tahun_rilis' => 2017, 'color' => [131, 24, 67]],
            ['judul' => '1917', 'genre' => 'Drama', 'sutradara' => 'Sam Mendes', 'tahun_rilis' => 2019, 'color' => [41, 37, 36]],

            // --- COMEDY ---
            ['judul' => 'Superbad', 'genre' => 'Comedy', 'sutradara' => 'Greg Mottola', 'tahun_rilis' => 2007, 'color' => [131, 24, 67]],
            ['judul' => 'The Hangover', 'genre' => 'Comedy', 'sutradara' => 'Todd Phillips', 'tahun_rilis' => 2009, 'color' => [120, 53, 4]],
            ['judul' => 'Dumb and Dumber', 'genre' => 'Comedy', 'sutradara' => 'Peter Farrelly', 'tahun_rilis' => 1994, 'color' => [8, 51, 68]],
            ['judul' => 'Mean Girls', 'genre' => 'Comedy', 'sutradara' => 'Mark Waters', 'tahun_rilis' => 2004, 'color' => [131, 24, 67]],
            ['judul' => 'Anchorman: The Legend of Ron Burgundy', 'genre' => 'Comedy', 'sutradara' => 'Adam McKay', 'tahun_rilis' => 2004, 'color' => [120, 53, 4]],
            ['judul' => 'Groundhog Day', 'genre' => 'Comedy', 'sutradara' => 'Harold Ramis', 'tahun_rilis' => 1993, 'color' => [15, 23, 42]],
            ['judul' => 'Step Brothers', 'genre' => 'Comedy', 'sutradara' => 'Adam McKay', 'tahun_rilis' => 2008, 'color' => [30, 41, 59]],
            ['judul' => 'The Truman Show', 'genre' => 'Comedy', 'sutradara' => 'Peter Weir', 'tahun_rilis' => 1998, 'color' => [8, 51, 68]],
            ['judul' => 'Knives Out', 'genre' => 'Comedy', 'sutradara' => 'Rian Johnson', 'tahun_rilis' => 2019, 'color' => [30, 27, 75]],
            ['judul' => 'Free Guy', 'genre' => 'Comedy', 'sutradara' => 'Shawn Levy', 'tahun_rilis' => 2021, 'color' => [8, 51, 68]],
            ['judul' => 'Toy Story 4', 'genre' => 'Comedy', 'sutradara' => 'Josh Cooley', 'tahun_rilis' => 2019, 'color' => [8, 51, 68]],
            ['judul' => 'Shaun of the Dead', 'genre' => 'Comedy', 'sutradara' => 'Edgar Wright', 'tahun_rilis' => 2004, 'color' => [69, 10, 10]],
            ['judul' => 'Scott Pilgrim vs. The World', 'genre' => 'Comedy', 'sutradara' => 'Edgar Wright', 'tahun_rilis' => 2010, 'color' => [88, 28, 135]],
            ['judul' => 'Zombieland', 'genre' => 'Comedy', 'sutradara' => 'Ruben Fleischer', 'tahun_rilis' => 2009, 'color' => [69, 10, 10]],
            ['judul' => 'The Grand Budapest Hotel', 'genre' => 'Comedy', 'sutradara' => 'Wes Anderson', 'tahun_rilis' => 2014, 'color' => [131, 24, 67]],

            // --- HORROR ---
            ['judul' => 'The Conjuring', 'genre' => 'Horror', 'sutradara' => 'James Wan', 'tahun_rilis' => 2013, 'color' => [9, 9, 11]],
            ['judul' => 'Hereditary', 'genre' => 'Horror', 'sutradara' => 'Ari Aster', 'tahun_rilis' => 2018, 'color' => [24, 24, 27]],
            ['judul' => 'Get Out', 'genre' => 'Horror', 'sutradara' => 'Jordan Peele', 'tahun_rilis' => 2017, 'color' => [9, 9, 11]],
            ['judul' => 'A Quiet Place', 'genre' => 'Horror', 'sutradara' => 'John Krasinski', 'tahun_rilis' => 2018, 'color' => [24, 24, 27]],
            ['judul' => 'The Shining', 'genre' => 'Horror', 'sutradara' => 'Stanley Kubrick', 'tahun_rilis' => 1980, 'color' => [69, 10, 10]],
            ['judul' => 'Psycho', 'genre' => 'Horror', 'sutradara' => 'Alfred Hitchcock', 'tahun_rilis' => 1960, 'color' => [41, 37, 36]],
            ['judul' => 'It', 'genre' => 'Horror', 'sutradara' => 'Andy Muschietti', 'tahun_rilis' => 2017, 'color' => [69, 10, 10]],
            ['judul' => 'Halloween', 'genre' => 'Horror', 'sutradara' => 'John Carpenter', 'tahun_rilis' => 1978, 'color' => [120, 53, 4]],
            ['judul' => 'Insidious', 'genre' => 'Horror', 'sutradara' => 'James Wan', 'tahun_rilis' => 2010, 'color' => [9, 9, 11]],
            ['judul' => 'The Exorcist', 'genre' => 'Horror', 'sutradara' => 'William Friedkin', 'tahun_rilis' => 1973, 'color' => [24, 24, 27]],
            ['judul' => 'Midsommar', 'genre' => 'Horror', 'sutradara' => 'Ari Aster', 'tahun_rilis' => 2019, 'color' => [120, 53, 4]],
            ['judul' => 'Alien', 'genre' => 'Horror', 'sutradara' => 'Ridley Scott', 'tahun_rilis' => 1979, 'color' => [9, 9, 11]],

            // --- ROMANCE ---
            ['judul' => 'Titanic', 'genre' => 'Romance', 'sutradara' => 'James Cameron', 'tahun_rilis' => 1997, 'color' => [8, 51, 68]],
            ['judul' => 'La La Land', 'genre' => 'Romance', 'sutradara' => 'Damien Chazelle', 'tahun_rilis' => 2016, 'color' => [88, 28, 135]],
            ['judul' => 'The Notebook', 'genre' => 'Romance', 'sutradara' => 'Nick Cassavetes', 'tahun_rilis' => 2004, 'color' => [131, 24, 67]],
            ['judul' => 'Pride & Prejudice', 'genre' => 'Romance', 'sutradara' => 'Joe Wright', 'tahun_rilis' => 2005, 'color' => [4, 47, 46]],
            ['judul' => 'Before Sunrise', 'genre' => 'Romance', 'sutradara' => 'Richard Linklater', 'tahun_rilis' => 1995, 'color' => [120, 53, 4]],
            ['judul' => 'About Time', 'genre' => 'Romance', 'sutradara' => 'Richard Curtis', 'tahun_rilis' => 2013, 'color' => [131, 24, 67]],
            ['judul' => 'Casablanca', 'genre' => 'Romance', 'sutradara' => 'Michael Curtiz', 'tahun_rilis' => 1942, 'color' => [41, 37, 36]],
            ['judul' => 'The Fault in Our Stars', 'genre' => 'Romance', 'sutradara' => 'Josh Boone', 'tahun_rilis' => 2014, 'color' => [8, 51, 68]],
            ['judul' => '500 Days of Summer', 'genre' => 'Romance', 'sutradara' => 'Marc Webb', 'tahun_rilis' => 2009, 'color' => [8, 51, 68]],
            ['judul' => 'Eternal Sunshine of the Spotless Mind', 'genre' => 'Romance', 'sutradara' => 'Michel Gondry', 'tahun_rilis' => 2004, 'color' => [30, 41, 59]],

            // --- THRILLER ---
            ['judul' => 'Seven', 'genre' => 'Thriller', 'sutradara' => 'David Fincher', 'tahun_rilis' => 1995, 'color' => [9, 9, 11]],
            ['judul' => 'Shutter Island', 'genre' => 'Thriller', 'sutradara' => 'Martin Scorsese', 'tahun_rilis' => 2010, 'color' => [30, 41, 59]],
            ['judul' => 'Zodiac', 'genre' => 'Thriller', 'sutradara' => 'David Fincher', 'tahun_rilis' => 2007, 'color' => [4, 47, 46]],
            ['judul' => 'Gone Girl', 'genre' => 'Thriller', 'sutradara' => 'David Fincher', 'tahun_rilis' => 2014, 'color' => [9, 9, 11]],
            ['judul' => 'Prisoners', 'genre' => 'Thriller', 'sutradara' => 'Denis Villeneuve', 'tahun_rilis' => 2013, 'color' => [24, 24, 27]],
            ['judul' => 'The Silence of the Lambs', 'genre' => 'Thriller', 'sutradara' => 'Jonathan Demme', 'tahun_rilis' => 1991, 'color' => [41, 37, 36]],
            ['judul' => 'Memento', 'genre' => 'Thriller', 'sutradara' => 'Christopher Nolan', 'tahun_rilis' => 2000, 'color' => [24, 24, 27]],
            ['judul' => 'Joker', 'genre' => 'Thriller', 'sutradara' => 'Todd Phillips', 'tahun_rilis' => 2019, 'color' => [69, 10, 10]],
            ['judul' => 'Nightcrawler', 'genre' => 'Thriller', 'sutradara' => 'Dan Gilroy', 'tahun_rilis' => 2014, 'color' => [9, 9, 11]],
            ['judul' => 'Oldboy', 'genre' => 'Thriller', 'sutradara' => 'Park Chan-wook', 'tahun_rilis' => 2003, 'color' => [69, 10, 10]],

            // --- ANIME ---
            ['judul' => 'Spirited Away', 'genre' => 'Anime', 'sutradara' => 'Hayao Miyazaki', 'tahun_rilis' => 2001, 'color' => [4, 47, 46]],
            ['judul' => 'Your Name.', 'genre' => 'Anime', 'sutradara' => 'Makoto Shinkai', 'tahun_rilis' => 2016, 'color' => [30, 27, 75]],
            ['judul' => 'Princess Mononoke', 'genre' => 'Anime', 'sutradara' => 'Hayao Miyazaki', 'tahun_rilis' => 1997, 'color' => [6, 78, 59]],
            ['judul' => 'My Neighbor Totoro', 'genre' => 'Anime', 'sutradara' => 'Hayao Miyazaki', 'tahun_rilis' => 1988, 'color' => [20, 83, 45]],
            ['judul' => 'Demon Slayer: Mugen Train', 'genre' => 'Anime', 'sutradara' => 'Haruo Sotozaki', 'tahun_rilis' => 2020, 'color' => [69, 10, 10]],
            ['judul' => 'Akira', 'genre' => 'Anime', 'sutradara' => 'Katsuhiro Otomo', 'tahun_rilis' => 1988, 'color' => [153, 27, 27]],
            ['judul' => 'Weathering with You', 'genre' => 'Anime', 'sutradara' => 'Makoto Shinkai', 'tahun_rilis' => 2019, 'color' => [8, 51, 68]],
            ['judul' => 'A Silent Voice', 'genre' => 'Anime', 'sutradara' => 'Naoko Yamada', 'tahun_rilis' => 2016, 'color' => [131, 24, 67]],
            ['judul' => 'Howl\'s Moving Castle', 'genre' => 'Anime', 'sutradara' => 'Hayao Miyazaki', 'tahun_rilis' => 2004, 'color' => [30, 41, 59]],
        ];

        foreach ($dummyFilms as $index => $filmData) {
            $filename = 'poster_' . ($index + 1) . '.png';
            $filepath = $targetDir . '/' . $filename;

            // Query FM-DB (Free Movie DB) API to get the real IMDb movie poster URL
            $searchUrl = 'https://imdb.iamidiotareyoutoo.com/search?q=' . urlencode($filmData['judul']);
            $ctx = stream_context_create(['http' => ['timeout' => 5]]);
            $response = @file_get_contents($searchUrl, false, $ctx);
            $posterUrl = null;
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['description'][0]['#IMG_POSTER'])) {
                    $posterUrl = $data['description'][0]['#IMG_POSTER'];
                }
            }

            if ($posterUrl) {
                // Save to database using the remote URL directly
                Film::create([
                    'judul' => $filmData['judul'],
                    'genre' => $filmData['genre'],
                    'sutradara' => $filmData['sutradara'],
                    'tahun_rilis' => $filmData['tahun_rilis'],
                    'gambar' => $posterUrl,
                ]);
            } else {
                // Generate premium dummy poster using GD
                $width = 400;
                $height = 600;
                $image = imagecreatetruecolor($width, $height);

                // Allocate colors
                $r = $filmData['color'][0];
                $g = $filmData['color'][1];
                $b = $filmData['color'][2];
                $bgColor = imagecolorallocate($image, $r, $g, $b);
                imagefill($image, 0, 0, $bgColor);

                // Draw clean subtle border
                $borderColor = imagecolorallocate($image, 63, 63, 70); // zinc-700
                imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

                // Add simple stylized text lines for movie info
                $textColor = imagecolorallocate($image, 255, 255, 255);
                $mutedColor = imagecolorallocate($image, 161, 161, 170); // zinc-400
                $redColor = imagecolorallocate($image, 229, 9, 20); // netflix-red

                // GD Fonts
                $title = $filmData['judul'];
                $genre = $filmData['genre'];
                $director = 'Dir: ' . $filmData['sutradara'];
                $year = '[' . $filmData['tahun_rilis'] . ']';

                // Draw simple decorative elements
                imagestring($image, 5, 20, 50, "C I N E M A N A G E", $redColor);
                imagestring($image, 2, 20, 75, "N E T F L I X  S T Y L E", $mutedColor);
                
                // Draw title text wrapping or truncation for safety
                if (strlen($title) > 28) {
                    $titlePart1 = substr($title, 0, 25) . '...';
                    imagestring($image, 5, 20, 200, strtoupper($titlePart1), $textColor);
                } else {
                    imagestring($image, 5, 20, 200, strtoupper($title), $textColor);
                }
                
                imagestring($image, 3, 20, 240, 'Genre: ' . $genre, $mutedColor);
                imagestring($image, 3, 20, 280, $director, $mutedColor);
                imagestring($image, 4, 20, 320, $year, $textColor);

                // Save the image
                imagepng($image, $filepath);
                imagedestroy($image);

                // Save to database
                Film::create([
                    'judul' => $filmData['judul'],
                    'genre' => $filmData['genre'],
                    'sutradara' => $filmData['sutradara'],
                    'tahun_rilis' => $filmData['tahun_rilis'],
                    'gambar' => 'films/' . $filename,
                ]);
            }
        }
    }
}
