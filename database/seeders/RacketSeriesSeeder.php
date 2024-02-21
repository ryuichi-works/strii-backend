<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RacketSeriesSeeder extends Seeder
{
    // name_ja,name_en,maker_id
    protected $series = [
        // ウィルソン
        ['ブレード','blade',1],
        ['プロスタッフ','pro staff',1],
        ['ウルトラ','ultra',1],
        ['クラッシュ','clash',1],
        ['バーン','burn',1],
        // ヨネックス
        ['パーセプト','percept',2],
        ['Eゾーン','ezone',2],
        ['アストレル','astrel',2],
        ['レグナ','regna',2],
        ['Vコア','vcore',2],
        // ヘッド
        ['ブーン','boom',3],
        ['スピード','speed',3],
        ['ラジカル','radical',3],
        ['グラビティ','gravity',3],
        ['エクストリーム','extreme',3],
        ['プレステージ','prestige',3],
        // プリンス
        ['ビースト','beast',4],
        ['エックス','x',4],
        ['ツアー','tour',4],
        ['エンブレム','emblem',4],
        ['ファントム','phantom',4],
        // バボラ
        ['ピュアストライク','pure strike',5],
        ['ピュアドライブ','pure drive',5],
        ['ピュアアエロ','pure aero',5],
        ['エボドライブ','evo drive',5],
        ['エボアエロ','evo aero',5],
        // ダンロップ
        ['CXシリーズ','cx series',6],
        ['SXシリーズ','sx series',6],
        ['FXシリーズ','fx series',6],
        ['LXシリーズ','lx series',6],
        // テクにファイバー
        ['T-ファイト','T-fight',8],
        ['テンポ','tempo',8],
        // トアルソン
        ['OVR','OVR',9],
        ['S-mach','s-mach',9],
        // ダイアデム
        ['ノバ','nova',10],
        ['エレベート','elevate',10],
        ['スーパーノバ','super nova',10],
        // マンティス
        ['プロ','pro',11],
        ['cs','cs',11],
        ['ps','ps',11],
        ['パフォーマ','performa',11],
        // スノワート
        ['グリンタ','grinta',12],
        ['ヴィタス','vitas',12],
        ['ハイテン','hi-ten',12],
    ];

    

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insertData = [];

        foreach ($this->series as $series) {
            $data = [
                'name_ja' => $series[0],
                'name_en' => $series[1],
                'maker_id' => $series[2],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            array_push($insertData, $data);
        }

        DB::table('racket_series')->insert($insertData);
    }
}
