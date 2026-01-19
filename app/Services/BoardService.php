<?php
// app/Services/BoardService.php

class BoardService {
    /**
     * 新しいボードを作成し、トークンと合言葉（生文字）を保存する
     */
    public function createBoard($organizer_id, $title, $passphrase) {
        $db = Db::getConnection();

        // 1. URL用のランダムなトークンを生成
        $invite_token = bin2hex(random_bytes(16)); // メンバー招待用
        $gift_token   = bin2hex(random_bytes(16)); // 受取人ギフト用

        // 2. トークンはURL照合用にハッシュ化（これはセキュリティ上推奨）
        $invite_token_hash = hash('sha256', $invite_token);
        $gift_token_hash   = hash('sha256', $gift_token);
        
        // 【ここを修正】合言葉はハッシュ化せず、そのまま保存します
        // これにより、DBに「arigato」という文字がそのまま保存されます
        $passphrase_save = $passphrase; 

        $sql = "INSERT INTO boards (organizer_id, title, invite_token_hash, gift_token_hash, passphrase_hash, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'collecting', NOW())";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $organizer_id, 
            $title, 
            $invite_token_hash, 
            $gift_token_hash, 
            $passphrase_save,
            // status は 'collecting' で固定
        ]);

        // 幹司に表示するために、ハッシュ化前の生のトークンを返す
        return [
            'invite_token' => $invite_token,
            'gift_token'   => $gift_token
        ];
    }

    /**
     * ボードのステータスを更新する
     */
    public function updateStatus($board_id, $new_status) {
        $allowed_statuses = ['draft', 'collecting', 'ready', 'opened'];
        if (!in_array($new_status, $allowed_statuses)) return false;

        $db = Db::getConnection();
        $stmt = $db->prepare("UPDATE boards SET status = ? WHERE id = ?");
        return $stmt->execute([$new_status, $board_id]);
    }

    /**
     * 受取人がアクセスした際、現在のステータスを確認する
     */
    public function canReveal($board_id) {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT status FROM boards WHERE id = ?");
        $stmt->execute([$board_id]);
        $status = $stmt->fetchColumn();
        
        // ready または opened の時だけ開封体験に進める
        return ($status === 'ready' || $status === 'opened');
    }
}