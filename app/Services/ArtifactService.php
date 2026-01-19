<?php
// app/Services/ArtifactService.php

class ArtifactService
{
    /**
     * ボードに紐づく素材（動画・音楽）を取得する
     * プロトではDBになければ assets/demo/ の素材をデフォルトで返す
     */
    public function getArtifactsByBoardId($board_id)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT * FROM artifacts WHERE board_id = ? AND is_active = 1");
        $stmt->execute([$board_id]);
        $artifacts = $stmt->fetchAll();

        // プロト用：データが空ならダミー素材のURLをセット
        if (empty($artifacts)) {
            // BASE_URL が定義されていればそれを使、なければルート参照にフォールバック
            $base = defined('BASE_URL') ? BASE_URL : '';
            // デモ素材は現状 .mov（動画） と .mp3（音声）が assets/demo にあります
            return [
                ['type' => 'video', 'url' => $base . '/assets/demo/demo_video_01.mov'],
                ['type' => 'music', 'url' => $base . '/assets/demo/demo_music_01.mp3']
            ];
        }
        return $artifacts;
    }
}
