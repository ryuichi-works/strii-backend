<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MakerSeeder extends Seeder
{
    // 本番と同じmakerデータ
    protected $makers = [
        ['ウィルソン', 'wilson'],
        ['ヨネックス', 'yonex'],
        ['ヘッド', 'head'],
        ['プリンス', 'prince'],
        ['バボラ', 'babolat'],
        ['ダンロップ', 'dunlop'],
        ['スリクソン', 'srixon'],
        ['テクニファイバー', 'tecnifibre'],
        ['トアルソン', 'toalson'],
        ['ダイアデム', 'diadem'],
        ['マンティス', 'mantis'],
        ['ゴーセン', 'gosen'],
        ['ルキシロン', 'luxilon'],
        ['キルシュバウム', 'kirshbaum'],
        ['シグナムプロ', 'signum pro'],
        ['ソリンコ', 'solinco'],
        ['ポリスター', 'poly star'],
        ['トロライン', 'toroline'],
        ['スノワート', 'snauwaert'],
        ['ジェネシス', 'genesis'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insertData = [];

        // db登録用データを整形
        foreach ($this->makers as $maker) {
            $data = [
                'name_ja' => $maker[0],
                'name_en' => $maker[1],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            array_push($insertData, $data);
        };

        DB::table('makers')->insert($insertData);
    }
}
