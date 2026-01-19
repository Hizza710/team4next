<?php

/**
 * TEAM for NEXT - Entry Point
 * コンセプト：手作りギフト工房（ワークショップ）
 */

// 1. エラーを画面に表示させる設定（開発完了後は 0 に変更してください）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. セッションと基本設定

session_start();

// 環境判定
if (file_exists('/home/path-weave/leaderskit_sec/config/env.php')) {
    $env = require '/home/path-weave/leaderskit_sec/config/env.php';
} else {
    $env = require __DIR__ . '/../config/env.php';
}
if ($env === 'local') {
    require_once __DIR__ . '/../app/bootstrap.php';
} else {
    require_once '/home/path-weave/leaderskit_sec/app/bootstrap.php';
}

// 環境に合わせたベースURL（自動算出）
// SCRIPT_NAME から現在の公開ルートを算出するため、ローカル / 本番 の差分を吸収します。
$script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
$base = rtrim(str_replace('/index.php', '', $script), '/');
if ($base === '') {
    $base = '/';
}
define('BASE_URL', $base);

// 3. URLのパスを取得・解析
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// サブディレクトリ（/leaderskit/team4next/www/）のズレを調整
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace('/index.php', '', $script_name);
$request_path = str_replace($base_path, '', $path);

// 空文字やスラッシュのみの場合はトップページ扱い
if ($request_path === '' || $request_path === '/') {
    $request_path = '/';
}

try {
    // ---------------------------------------------------------
    // --- メンバー投稿用（招待リンク経由） ---
    // ---------------------------------------------------------

    // 投稿画面の表示（エディタ）
    if ($request_path === '/invite') {
        $controller = new InviteController();
        $controller->editor($_GET['token'] ?? '');
    }

    // 投稿の実行（POST先）
    elseif ($request_path === '/invite/submit') {
        $controller = new InviteController();
        $controller->submit();
    }

    // ---------------------------------------------------------
    // --- 受取人ギフト用（開封・閲覧） ---
    // ---------------------------------------------------------

    // ギフト開封（サプライズボックス）
    elseif ($request_path === '/gift') {
        $controller = new GiftController();
        $controller->reveal($_GET['token'] ?? '');
    }

    // 魔法の鍵（合言葉）の照合
    elseif ($request_path === '/gift/authenticate') {
        $controller = new GiftController();
        $controller->authenticate();
    }

    // 寄せ書き（色紙）の表示
    elseif ($request_path === '/gift/shikishi') {
        $controller = new ShikishiController();
        $controller->show($_GET['token'] ?? '');
    }

    // 作品棚・グッズ棚：共通レイアウト(base.php)に統合
    elseif ($request_path === '/gift/shelf') {
        $board_title = "team memory for future";
        $products = [
            ['name' => 'チーム色紙', 'price' => '1,800', 'preview_url' => ''],
            ['name' => 'チームアルバム', 'price' => '4,500', 'preview_url' => ''],
            ['name' => 'チームTシャツ', 'price' => '3,500', 'preview_url' => ''],
        ];

        ob_start();
        if ($env === 'local') {
            include __DIR__ . '/../app/Views/gift/shelf.php';
        } else {
            include '/home/path-weave/leaderskit_sec/app/Views/gift/shelf.php';
        }
        $content = ob_get_clean();

        if ($env === 'local') {
            include __DIR__ . '/../app/Views/layouts/base.php';
        } else {
            include '/home/path-weave/leaderskit_sec/app/Views/layouts/base.php';
        }
    }

    // ---------------------------------------------------------
    // --- 幹司用（管理機能・製作開始） ---
    // ---------------------------------------------------------

    // ボード新規作成画面：ワークショップデザイン
    elseif ($request_path === '/organizer/new') {
        $content = "
            <div class='workshop-container' style='max-width: 600px; margin: 40px auto; padding: 20px; text-align: center;'>
                <div class='workshop-card'>
                    <div class='masking-tape'></div>
                    
                    <h2 class='serif-text' style='font-size: 1.8rem; color: #5a4a42; margin-bottom: 10px;'>新しいギフトを企画する</h2>
                    <p style='color: #8b7d77; font-size: 0.9rem; margin-bottom: 30px;'>贈る相手やプロジェクト名をタイトルにして、製作を開始しましょう。</p>
                    
                    <form action='" . BASE_URL . "/organizer/boards/create' method='POST' style='display: flex; flex-direction: column; gap: 20px; text-align: left;'>
                        <div class='form-group'>
                            <label>ギフトのタイトル</label>
                            <input type='text' name='title' placeholder='例：〇〇さんの送別記念ボード' required>
                        </div>
                        
                        <div class='form-group'>
                            <label>メンバー用の合言葉</label>
                            <input type='text' name='passphrase' placeholder='例：arigato' required>
                            <small style='color: #8b7d77; font-size: 0.8rem; margin-top: 5px; display: block;'>※ メンバーが投稿する際に入力する鍵になります。</small>
                        </div>
                        
                        <div style='text-align: center; margin-top: 10px;'>
                            <button type='submit' class='btn-handmade'>
                                製作ボードを作成する
                            </button>
                        </div>
                    </form>
                </div>
                
                <div style='margin-top: 20px;'>
                    <a href='" . BASE_URL . "/' style='color: #8b7d77; text-decoration: none; font-size: 0.9rem;'>← トップページに戻る</a>
                </div>
            </div>
        ";
        if ($env === 'local') {
            include __DIR__ . '/../app/Views/layouts/base.php';
        } else {
            include '/home/path-weave/leaderskit_sec/app/Views/layouts/base.php';
        }
    }

    // ボード作成の実行（POST）
    elseif ($request_path === '/organizer/boards/create') {
        $controller = new BoardController();
        $controller->create();
    }

    // 管理ダッシュボード
    elseif ($request_path === '/organizer/dashboard') {
        $controller = new BoardController();
        $controller->dashboard();
    }

    // ステータス更新（募集終了・公開など）
    elseif ($request_path === '/organizer/boards/status') {
        $controller = new BoardController();
        $controller->updateStatus();
    }

    // ---------------------------------------------------------
    // --- 共通ページ ---
    // ---------------------------------------------------------

    // トップページ：手作り製作部屋コンセプト
    elseif ($request_path === '/') {
        $content = "
            <div class='workshop-container' style='max-width: 800px; margin: 0 auto; padding: 30px 16px; text-align: center;'>
                
                <div class='workshop-card'>
                    <div class='masking-tape'></div>
                    
                    <h1 class='serif-text' style='font-size: 1.8rem; margin-bottom: 16px; color: #5a4a42; letter-spacing: 0.04em;'>
                        想いを織りなす、<br>手作りギフト工房へ
                    </h1>
                    <p style='line-height: 1.7; color: #8b7d77; margin-bottom: 24px; font-size: 0.95rem;'>
                        ここは、チームの記憶を世界にひとつの「物語」へと仕立てる場所。<br>
                        大切な人への贈り物を、みんなで一緒に作り始めませんか？
                    </p>
                    
                    <div class='action-area'>
                        <a href='" . BASE_URL . "/organizer/new' class='btn-handmade'>
                            製作を開始する（ボード作成）
                        </a>
                    </div>
                </div>

                <div class='steps-area' style='margin-top: 50px; display: flex; justify-content: space-around; flex-wrap: wrap; gap: 24px;'>
                    <div class='step-item'>
                        <div class='step-icon'>📝</div>
                        <h3 class='serif-text' style='color: #5a4a42;'>1. 想いを集める</h3>
                        <p style='color: #8b7d77;'>ボードを作って、メンバーから<br>メッセージや写真を切り取って集める。</p>
                    </div>
                    <div class='step-item'>
                        <div class='step-icon'>✨</div>
                        <h3 class='serif-text' style='color: #5a4a42;'>2. AIが紡ぐ</h3>
                        <p style='color: #8b7d77;'>集まった素材をAIが魔法のように<br>繋ぎ合わせ、歌詞と動画にする。</p>
                    </div>
                    <div class='step-item'>
                        <div class='step-icon'>👕</div>
                        <h3 class='serif-text' style='color: #5a4a42;'>3. 日常へつなぐ</h3>
                        <p style='color: #8b7d77;'>完成した物語をグッズにして、<br>これからの日常へ持ち帰りましょう。</p>
                    </div>
                </div>
            </div>
        ";
        if ($env === 'local') {
            include __DIR__ . '/../app/Views/layouts/base.php';
        } else {
            include '/home/path-weave/leaderskit_sec/app/Views/layouts/base.php';
        }
    }

    // 404
    else {
        header("HTTP/1.0 404 Not Found");
        echo "<div class='workshop-container' style='text-align:center; padding:100px;'>";
        echo "<h1>404 Not Found</h1><p>TEAM for NEXT：ページが見つかりません。</p>";
        echo "<small>Debug Path: " . htmlspecialchars($request_path) . "</small></div>";
    }
} catch (Throwable $e) {
    // 致命的エラー
    echo "<div style='background:#fff0f0; border:1px solid red; padding:20px; border-radius:12px; max-width: 800px; margin: 40px auto; font-family: sans-serif;'>";
    echo "<h3 style='color: #d32f2f; margin-top: 0;'>工房でトラブルが発生しました</h3>";
    echo "<p style='color: #555;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
