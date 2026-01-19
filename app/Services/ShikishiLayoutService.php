<?php
// app/Services/ShikishiLayoutService.php

class ShikishiLayoutService {
    /**
     * 投稿数に応じて最適なテンプレートと配置を決定する
     */
    public function arrange($posts) {
        $count = count($posts);
        
        // 1. 投稿数によるテンプレート判定
        if ($count === 0) {
            $template = 'empty';
        } elseif ($count <= 2) {
            $template = 'focus';   // 少ない：中央に大きく配置
        } elseif ($count <= 8) {
            $template = 'harmony'; // 中規模：バランスよく散らす
        } else {
            $template = 'chaos';   // 多い：賑やかにタイル状
        }

        // 2. 各投稿に「手作り感」を出すためのランダム要素を付与
        foreach ($posts as &$post) {
            // -5度〜5度の範囲でランダムに傾ける
            $post['rotation'] = rand(-5, 5);
            // 表示される時のアニメーションの遅延（パラパラと出てくる演出用）
            $post['delay'] = (rand(0, 20) / 10) . 's';
            // フォントサイズも少しだけランダムに変えてリズムを出す
            $post['font_size'] = rand(95, 110) . '%';
        }

        return [
            'template' => $template,
            'posts' => $posts,
            'count' => $count
        ];
    }
}