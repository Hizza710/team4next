<?php
// app/Controllers/InviteController.php

class InviteController {
    /**
     * 投稿画面（エディタ）の表示
     */
    public function editor($token) {
        $db = Db::getConnection();
        // トークンをハッシュ化して照合
        $stmt = $db->prepare("SELECT * FROM boards WHERE invite_token_hash = ?");
        $stmt->execute([hash('sha256', $token)]);
        $board = $stmt->fetch();

        if (!$board) {
            die("無効な招待リンクです。");
        }
        
        $this->render(__DIR__ . '/../Views/invite/editor.php', ['board' => $board]);
    }

    /**
     * 投稿の実行（POST処理）
     */
    public function submit() {
        // 全てのエラーを画面に表示させる設定
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

            // データの受け取り
            $board_id = $_POST['board_id'] ?? null;
            $name = $_POST['name'] ?: '匿名';
            $body = $_POST['body'] ?? '';
            $image_url = null;

            // バリデーション
            if (empty($board_id)) {
                throw new Exception("board_id が空です。フォームのhidden項目を確認してください。");
            }
            if (empty($body)) {
                throw new Exception("メッセージ本文が空です。");
            }

            // 画像処理（StorageServiceが定義されている前提）
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $storage = new StorageService();
                $image_url = $storage->store($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            }

            // 保存実行
            $service = new InviteService();
            $result = $service->savePost((int)$board_id, $name, $body, $image_url);

            if ($result) {
                // --- 【成功画面】ワークショップデザインを適用 ---
                echo "
                <div class='workshop-container' style='text-align: center; padding-top: 60px;'>
                    <div class='workshop-card' style='max-width: 600px; margin: 0 auto;'>
                        <div class='masking-tape'></div>
                        <div style='font-size: 4rem; margin-bottom: 20px;'>💌</div>
                        <h1 class='serif-text' style='font-size: 2.2rem; color: #5a4a42; margin-bottom: 20px;'>投稿成功！</h1>
                        <p style='color: #8b7d77; margin-bottom: 40px; line-height: 1.8; font-size: 1.1rem;'>
                            想いを届けていただきありがとうございます。<br>
                            あなたの言葉が、大切な人の明日を彩るギフトの一部になります。
                        </p>
                        <div class='action-area'>
                            <a href='" . BASE_URL . "' class='btn-handmade'>
                                トップページへ
                            </a>
                        </div>
                    </div>
                </div>
                ";
            } else {
                throw new Exception("DB保存時に不明なエラーが発生しました。");
            }

        } catch (Throwable $e) {
            // エラーの詳細表示
            echo "<div style='color:red; background:#fff0f0; padding:20px; border:1px solid red; border-radius: 10px; max-width: 600px; margin: 40px auto;'>";
            echo "<h3>投稿エラーが発生しました</h3>";
            echo "メッセージ: " . htmlspecialchars($e->getMessage()) . "<br>";
            echo "場所: " . $e->getFile() . " (Line: " . $e->getLine() . ")";
            echo "</div>";
            exit;
        }
    }

    /**
     * 共通レイアウト(base.php)に流し込んで描画
     */
    private function render($view_path, $data = []) {
        extract($data);
        ob_start();
        include $view_path;
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/base.php';
    }
}