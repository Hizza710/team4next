<?php
// app/Services/InviteService.php

class InviteService {
    /**
     * トークンと合言葉を検証し、ボード情報を返す
     */
    public function verifyAccess($token, $passphrase = null) {
        $db = Db::getConnection();

        // 【修正1】カラム名の統一
        // GiftControllerでは gift_token_hash を使っているため、ここも合わせます。
        // ※もしDBのカラム名が invite_token_hash なら、ここはそのままでOKです。
        $stmt = $db->prepare("SELECT * FROM boards WHERE gift_token_hash = ?");
        $stmt->execute([$token]);
        $board = $stmt->fetch();

        if (!$board) return false;

        // 合言葉が提供された場合は検証
        if ($passphrase !== null) {
            // 【修正2】ハッシュ化せず「生文字」で比較
            // これにより、DBに arigato と入っていれば arigato で開くようになります。
            if ($passphrase !== $board['passphrase_hash']) {
                return false;
            }
        }
        return $board;
    }

    /**
     * 投稿を保存する
     */
    public function savePost($board_id, $name, $body, $image_url = null) {
        $db = Db::getConnection();
        // created_atにNOW()を追加。投稿日時を記録します。
        $sql = "INSERT INTO posts (board_id, author_name, body, image_url, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([
            (int)$board_id, 
            $name ?: '匿名', 
            $body, 
            $image_url 
        ]);
    }
}