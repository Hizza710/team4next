<?php
// app/Services/StorageService.php

class StorageService {
    private $upload_dir;
    private $public_path = '/leaderskit/team4next/www/uploads/';

    public function __construct() {
        // 相対パスを、OSが100%理解できる絶対パスに変換します
        $this->upload_dir = realpath(__DIR__ . '/../../www') . '/uploads/';
    }

    public function store($file_tmp_name, $original_name) {
        // フォルダがない場合は作成を試みる
        if (!is_dir($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }

        $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $new_filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $target_path = $this->upload_dir . $new_filename;

        // 保存実行
        if (move_uploaded_file($file_tmp_name, $target_path)) {
            return $this->public_path . $new_filename;
        }

        // 失敗した場合はログを出す（XAMPPのerror_logで確認可能）
        error_log("画像保存失敗: " . $target_path);
        return null;
    }
}