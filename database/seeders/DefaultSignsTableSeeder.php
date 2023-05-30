<?php

namespace Database\Seeders;

use App\Models\DefaultSign;
use Illuminate\Database\Seeder;

class DefaultSignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultSigns = [
            [
                'name' => 'Fastball',
                'abbreviation' => 'FB',
                'color' => '#00F83B',
                'category' => 'pitching'
            ],
            [
                'name' => 'Change',
                'abbreviation' => 'X',
                'color' => '#6EE0DE',
                'category' => 'pitching'
            ],
            [
                'name' => 'Rise',
                'abbreviation' => 'R',
                'color' => '#FE5BB7',
                'category' => 'pitching'
            ],
            [
                'name' => 'Drop',
                'abbreviation' => 'D',
                'color' => '#0046FE',
                'category' => 'pitching'
            ],
            [
                'name' => 'Screw',
                'abbreviation' => 'S',
                'color' => '#BF1B1C',
                'category' => 'pitching'
            ],
            [
                'name' => 'Curve',
                'abbreviation' => 'C',
                'color' => '#FFA57D',
                'category' => 'pitching'
            ],
            [
                'name' => 'Drop change',
                'abbreviation' => 'DX',
                'color' => '#00F83B',
                'category' => 'pitching'
            ],
            [
                'name' => 'Drop curve',
                'abbreviation' => 'DC',
                'color' => '#FFC4AB',
                'category' => 'pitching'
            ],
            [
                'name' => 'Off speed',
                'abbreviation' => 'O',
                'color' => '#5600FD',
                'category' => 'pitching'
            ],
            [
                'name' => 'Back door curve -',
                'abbreviation' => 'BC',
                'color' => '#FE5BB7',
                'category' => 'pitching'
            ],
            [
                'name' => 'Pitchout',
                'abbreviation' => 'PO',
                'color' => '#000000',
                'category' => 'pitching'
            ],

            [
                'name' => 'Sac bunt',
                'abbreviation' => 'SB',
                'color' => '#FE5BB7',
                'category' => 'offensive'
            ],
            [
                'name' => 'Suicide Squeeze',
                'abbreviation' => 'SQ',
                'color' => '#5600FD',
                'category' => 'offensive'
            ],
            [
                'name' => 'Bunt and run',
                'abbreviation' => 'BR',
                'color' => '#932A82',
                'category' => 'offensive'
            ],
            [
                'name' => 'Bunt for a hit',
                'abbreviation' => 'BH',
                'color' => '#890B0B',
                'category' => 'offensive'
            ],
            [
                'name' => 'Swing away',
                'abbreviation' => 'SA',
                'color' => '#7A5252',
                'category' => 'offensive'
            ],
            [
                'name' => 'Take',
                'abbreviation' => 'T',
                'color' => '#ECACAC',
                'category' => 'offensive'
            ],
            [
                'name' => 'Steal',
                'abbreviation' => 'ST',
                'color' => '#AB7C98',
                'category' => 'offensive'
            ],
            [
                'name' => 'Delay steal',
                'abbreviation' => 'DS',
                'color' => '#FFF700',
                'category' => 'offensive'
            ],
            [
                'name' => 'Delay on catcher',
                'abbreviation' => 'DC',
                'color' => '#BF1B1C',
                'category' => 'offensive'
            ],
            [
                'name' => 'Delay on pitcher',
                'abbreviation' => 'DP',
                'color' => '#8842C2',
                'category' => 'offensive'
            ],
            [
                'name' => 'Hit and run',
                'abbreviation' => 'HR',
                'color' => '#38A5AD',
                'category' => 'offensive'
            ],
            [
                'name' => 'Fake bunt',
                'abbreviation' => 'FB',
                'color' => '#8EB2F5',
                'category' => 'offensive'
            ],
            [
                'name' => 'Slap',
                'abbreviation' => 'SP',
                'color' => '#DA7E16',
                'category' => 'offensive'
            ],
            [
                'name' => 'Drag bunt',
                'abbreviation' => 'DB',
                'color' => '#8F4242',
                'category' => 'offensive'
            ],
            [
                'name' => 'Slash/ slug',
                'abbreviation' => 'SL',
                'color' => '#FF00BB',
                'category' => 'offensive'
            ],
            [
                'name' => 'Signs off',
                'abbreviation' => 'X',
                'color' => '#E5511F',
                'category' => 'offensive'
            ],
            [
                'name' => 'Angle down',
                'abbreviation' => 'AD',
                'color' => '#FF0000',
                'category' => 'offensive'
            ],
            [
                'name' => 'Push bunt',
                'abbreviation' => 'PB',
                'color' => '#FF6200',
                'category' => 'offensive'
            ],
            [
                'name' => 'Hit away',
                'abbreviation' => 'HW',
                'color' => '#6EE0DE',
                'category' => 'offensive'
            ],
            [
                'name' => 'Hard slap',
                'abbreviation' => 'HS',
                'color' => '#FE5BB7',
                'category' => 'offensive'
            ],
            [
                'name' => 'Soft slap',
                'abbreviation' => 'SS',
                'color' => '#00F83B',
                'category' => 'offensive'
            ],
            [
                'name' => '3/4 steal',
                'abbreviation' => 'QS',
                'color' => '#BF1B1C',
                'category' => 'offensive'
            ],
            [
                'name' => 'Bunt to 1st',
                'abbreviation' => 'B1',
                'color' => '#0046FE',
                'category' => 'offensive'
            ],
            [
                'name' => 'Bunt to 3rd',
                'abbreviation' => 'B3',
                'color' => '#CFCFCF',
                'category' => 'offensive'
            ],
            [
                'name' => 'Swing thru',
                'abbreviation' => 'SW',
                'color' => '#0046FE',
                'category' => 'offensive'
            ],

            [
                'name' => 'Outfield in',
                'abbreviation' => 'OI',
                'color' => '#844707',
                'category' => 'defensive'
            ],
            [
                'name' => 'Outfield back',
                'abbreviation' => 'OB',
                'color' => '#0046FE',
                'category' => 'defensive'
            ],
            [
                'name' => 'Shift left',
                'abbreviation' => 'SL',
                'color' => '#FE5BB7',
                'category' => 'defensive'
            ],
            [
                'name' => 'Shift right',
                'abbreviation' => 'SR',
                'color' => '#007F42',
                'category' => 'defensive'
            ],
            [
                'name' => 'Slapper defense',
                'abbreviation' => 'SD',
                'color' => '#6EE0DE',
                'category' => 'defensive'
            ],
            [
                'name' => 'First and third plays',
                'abbreviation' => '13',
                'color' => '#FE5BB7',
                'category' => 'defensive'
            ],
            [
                'name' => 'Pick offs',
                'abbreviation' => 'PO',
                'color' => '#BF1B1C',
                'category' => 'defensive'
            ],
            [
                'name' => 'Pinch middles',
                'abbreviation' => 'PM',
                'color' => '#0046FE',
                'category' => 'defensive'
            ],
        ];
        foreach ($defaultSigns as $defaultSign) {
            DefaultSign::create($defaultSign);
        }
    }
}
