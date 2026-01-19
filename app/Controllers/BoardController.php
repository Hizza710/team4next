<?php
// app/Controllers/BoardController.php

class BoardController {

    /**
     * 新規ボード作成の実行（POST）
     */
    public function create() {
        // 開発中のためエラー詳細を表示
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: " . BASE_URL . "/organizer/new");
                exit;
            }

            $title = $_POST['title'] ?? '';
            // 「arigato」などの合言葉をそのまま取得
            $passphrase = $_POST['passphrase'] ?? '';
            $organizer_id = 1; // プロトタイプ用固定ID

            $service = new BoardService();
            // 【重要】BoardService側でも password_hash を使わず、生文字で保存するようにしてください
            $tokens = $service->createBoard($organizer_id, $title, $passphrase);

            $data = [
                'board_title' => $title,
                'invite_token' => $tokens['invite_token'],
                'gift_token'   => $tokens['gift_token'],
                'passphrase_display' => $passphrase // 完了画面に表示用
            ];

            // 完了画面（共有URL表示）をレンダリング
            $this->render(__DIR__ . '/../Views/organizer/share.php', $data);

        } catch (Throwable $e) {
            echo "<div style='background:#fee; color:#c00; padding:20px; border:2px solid #c00; font-family:sans-serif;'>";
            echo "<h2 style='margin-top:0;'>Fatal Error! (Debug Mode)</h2>";
            echo "<p><b>Message:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><b>File:</b> " . $e->getFile() . "</p>";
            echo "<p><b>Line:</b> " . $e->getLine() . "</p>";
            echo "</div>";
            exit;
        }
    }

    /**
     * 管理画面：ボード一覧と進捗の表示
     */
    public function dashboard() {
        $db = Db::getConnection();
        
        // 全ボードの情報と、それぞれに紐づく投稿数を集計して取得
        $sql = "SELECT b.*, COUNT(p.id) as post_count 
                FROM boards b 
                LEFT JOIN posts p ON b.id = p.board_id 
                GROUP BY b.id 
                ORDER BY b.created_at DESC";
        $stmt = $db->query($sql);
        $boards = $stmt->fetchAll();

        // View(dashboard.php)で「魔法の鍵」を表示するためにデータを整理
        foreach ($boards as &$board) {
            // DBのカラム名が passphrase_hash でも、中身が生文字であればそのまま表示用にセット
            $board['passphrase_display'] = $board['passphrase_hash'];
        }

        $this->render(__DIR__ . '/../Views/organizer/dashboard.php', [
            'boards' => $boards
        ]);
    }

    /**
     * ステータス更新処理（募集終了や公開など）
     */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $board_id = $_POST['board_id'] ?? null;
        $new_status = $_POST['status'] ?? '';

        if ($board_id && $new_status) {
            $service = new BoardService();
            $service->updateStatus($board_id, $new_status);
        }

        // 処理後、ダッシュボードへ戻る
        header("Location: " . BASE_URL . "/organizer/dashboard");
        exit;
    }

    /**
     * 各Viewを共通レイアウトに流し込むメソッド
     */
    private function render($view_path, $data = []) {
        if (!file_exists($view_path)) {
            die("Viewファイルが見つかりません: " . htmlspecialchars($view_path));
        }

        extract($data);
        ob_start();
        include $view_path;
        $content = ob_get_clean();

        $layout_path = __DIR__ . '/../Views/layouts/base.php';
        if (file_exists($layout_path)) {
            include $layout_path;
        } else {
            echo $content;
        }
    }
}