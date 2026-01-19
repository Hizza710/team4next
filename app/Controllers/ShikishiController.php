<?php
// app/Controllers/ShikishiController.php

class ShikishiController {
    /**
     * 色紙画面の表示
     * URL: /gift/shikishi?token=xxxx
     */
    public function show($token) {
        $db = Db::getConnection();
        $token_hash = hash('sha256', $token);
        
        // 1. ボード情報を取得（ギフト用トークンで照合）
        $stmt = $db->prepare("SELECT * FROM boards WHERE gift_token_hash = ?");
        $stmt->execute([$token_hash]);
        $board = $stmt->fetch();

        if (!$board) {
            die("ギフトが見つかりません。URLが正しいかご確認ください。");
        }

        // 2. このボードに紐づく投稿メッセージをすべて取得
        $stmt = $db->prepare("SELECT * FROM posts WHERE board_id = ? ORDER BY created_at ASC");
        $stmt->execute([$board['id']]);
        $posts = $stmt->fetchAll();

        // 3. ビューを表示
        // $posts 配列をそのまま渡し、Views/gift/shikishi.php 側でループ処理します
        $this->render(__DIR__ . '/../Views/gift/shikishi.php', [
            'board' => $board,
            'posts' => $posts,
            'token' => $token
        ]);
    }

    /**
     * 共通レイアウト(base.php)にコンテンツを埋め込んで表示
     */
    private function render($view_path, $data = []) {
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