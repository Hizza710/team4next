<?php
// app/Services/ProductDesignService.php

class ProductDesignService {
    /**
     * 色紙の内容からグッズの「擬似デザイン」を生成する
     */
    public function generateMockup($board_title, $posts) {
        // プロトでは固定のテンプレートにタイトルを合成するイメージ
        // 実際にはCanvas APIやImageMagickで画像を生成
        return [
            [
                'name' => '思い出の1ページ Tシャツ',
                'type' => 't-shirt',
                'price' => '3,500',
                'preview_url' => '/assets/img/mockup_tshirt.png', // テンプレ画像
                'description' => "「{$board_title}」のメッセージを背負う1枚。"
            ],
            [
                'name' => '絆のステッカーセット',
                'type' => 'sticker',
                'price' => '800',
                'preview_url' => '/assets/img/mockup_sticker.png',
                'description' => "PCやノートに。いつでもチームを感じられます。"
            ]
        ];
    }
}