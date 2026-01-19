<?php
// app/Controllers/GiftController.php

class GiftController
{
    /**
     * ギフトの入り口（演出画面 / 合言葉入力画面）
     */
    public function reveal($token)
    {
        $db = Db::getConnection();

        // 1. まず、送られてきたトークンをそのまま照合（ハッシュ済みトークン想定）
        $stmt = $db->prepare("SELECT * FROM boards WHERE gift_token_hash = ?");
        $stmt->execute([$token]);
        $board = $stmt->fetch();

        // 2. 見つからない場合、生のトークンである可能性を考慮してハッシュ化して再検索
        if (!$board) {
            $token_hash = hash('sha256', $token);
            $stmt->execute([$token_hash]);
            $board = $stmt->fetch();
            if ($board) {
                $token = $token_hash;
            }
        }

        if (!$board) {
            die("ギフトが見つかりません。URLをご確認ください。");
        }

        // プレビュー判定
        $is_preview = isset($_GET['preview']) && $_GET['preview'] === 'true';

        // --- 公開前（collecting）のチェック ---
        if (!$is_preview && $board['status'] === 'collecting') {
            $this->renderWaitPage();
            return; // 処理を終了
        }

        // --- 演出用：全員のメッセージと投稿者名を取得（色紙・エンドロール用） ---
        $stmt_posts = $db->prepare("SELECT * FROM posts WHERE board_id = ? ORDER BY created_at ASC");
        $stmt_posts->execute([$board['id']]);
        $posts = $stmt_posts->fetchAll();

        // --- アーティファクト取得（動画など） ---
        $artService = new ArtifactService();
        $artifacts = $artService->getArtifactsByBoardId($board['id']);

        // 動画URLがない場合はデモ用をセット
        $main_artifact = !empty($artifacts) ? $artifacts[0] : [
            'type' => 'video',
            'url' => (defined('BASE_URL') ? BASE_URL : '') . '/assets/demo/demo_video_01.mov'
        ];

        $this->render(__DIR__ . '/../Views/gift/reveal.php', [
            'token' => $token,
            'board_title' => $board['title'],
            'main_artifact' => $main_artifact,
            'posts' => $posts, // 全員のメッセージを渡す
            'is_preview' => $is_preview,
            'is_authenticated' => ($is_preview === true)
        ]);
    }

    /**
     * 合言葉（魔法の鍵）の照合
     */
    public function authenticate()
    {
        $token = $_POST['token'] ?? '';
        $passphrase = $_POST['passphrase'] ?? '';

        $service = new InviteService();
        $board = $service->verifyAccess($token, $passphrase);

        if ($board) {
            $db = Db::getConnection();
            $stmt_posts = $db->prepare("SELECT * FROM posts WHERE board_id = ? ORDER BY created_at ASC");
            $stmt_posts->execute([$board['id']]);
            $posts = $stmt_posts->fetchAll();

            $artService = new ArtifactService();
            $artifacts = $artService->getArtifactsByBoardId($board['id']);
            $main_artifact = !empty($artifacts) ? $artifacts[0] : [
                'type' => 'video',
                'url' => 'https://www.w3schools.com/html/mov_bbb.mp4'
            ];

            $this->render(__DIR__ . '/../Views/gift/reveal.php', [
                'token' => $token,
                'board_title' => $board['title'],
                'main_artifact' => $main_artifact,
                'posts' => $posts,
                'is_authenticated' => true
            ]);
        } else {
            $this->render(__DIR__ . '/../Views/gift/reveal.php', [
                'token' => $token,
                'error' => '鍵が合いません。もう一度入力してください。',
                'main_artifact' => ['type' => 'none'],
                'posts' => [],
                'is_authenticated' => false
            ]);
        }
    }

    /**
     * 公開前の待機画面を表示
     */
    private function renderWaitPage()
    {
        $content = "
            <div style='display:flex; justify-content:center; align-items:center; height:100vh; background:#fcf8f3; text-align:center; padding:20px;'>
                <div style='background:white; padding:40px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.05); max-width:500px; width:100%;'>
                    <div style='font-size:3.5rem; margin-bottom:20px;'>🎁</div>
                    <h2 style='color:#5a4a42; font-family:serif; margin-bottom:20px; font-size:1.5rem;'>今チームでボードを作成中</h2>
                    <p style='color:#888; line-height:1.8; font-size:1rem;'>幹司が「公開」するまで、<br>楽しみにお待ちください🎵</p>
                </div>
            </div>
        ";
        include __DIR__ . '/../Views/layouts/base.php';
    }

    /**
     * Viewファイルをレイアウトに流し込んで出力
     */
    private function render($view_path, $data = [])
    {
        if (!file_exists($view_path)) {
            die("Error: Viewファイルが見つかりません。");
        }
        extract($data);
        ob_start();
        include $view_path;
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/base.php';
    }
}
