<?php
// reveal.phpのcssだけ表現が難しくまとめられず、本ファイルに追加
// 開封フラグを厳格にチェック
$is_opened = (isset($is_authenticated) && $is_authenticated === true);
?>

<div class="gift-page-frame">
    <div id="gift-stage" class="gift-wrap-container <?php echo $is_opened ? 'is-opened' : ''; ?>">

        <div class="gift-box-outer" id="box-outer">
            <div class="ribbon-vertical"></div>
            <div class="ribbon-horizontal"></div>
            <div class="ribbon-knot">
                <div class="knot-left"></div>
                <div class="knot-right"></div>
            </div>

            <?php if (!$is_opened): ?>
                <div id="login-card" class="gift-message-card <?php echo isset($error) ? 'shake' : ''; ?>">
                    <div class="card-inner">
                        <div style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;">🎁</div>
                        <h1 class="handwritten-title">想いを受け取る</h1>
                        <p class="subtitle">幹事さんから託された<br>「魔法の鍵」を入力してください</p>
                        <form action="<?php echo BASE_URL; ?>/gift/authenticate" method="POST">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <input type="password" name="passphrase" class="input-handwritten" placeholder="鍵を入力..." required autofocus>
                            <button type="submit" class="btn-handwritten">リボンを解いて開封する</button>
                        </form>
                        <?php if (isset($error)): ?>
                            <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div id="gift-content" class="gift-content-area">

            <!--
                ▼歌詞の自動表示（デモ用）
                本来は生成AIで生成された歌詞をここに挿入予定。

                ▼BGM自動再生（デモ用）
                本来は生成AIで生成された動画を再生しBGMが流れる予定。
                現状は demo_video_01.movをデモ音源として再生。
                
                ▼動画の自動表示（デモ用）
                本来は生成AIで生成された動画をここに挿入予定。
                現状は demo_video_01.movを動画として再生。
            -->
            <?php
            // BGM／動画には main_artifact の URL を使う（デモ時は demo_video_01.mov）。
            $fallbackDemo = (defined('BASE_URL') ? BASE_URL . '/assets/demo/demo_video_01.mov' : '/assets/demo/demo_video_01.mov');
            $bgmSrc = isset($main_artifact['url']) && !empty($main_artifact['url']) ? $main_artifact['url'] : $fallbackDemo;
            $videoSrc = $bgmSrc; // デモでは音だけ/動画どちらも同じファイルを使う
            ?>
            <audio id="bgm-audio" src="<?= htmlspecialchars($bgmSrc) ?>" preload="auto"></audio>
            <div id="persistent-lyrics" class="lyrics-overlay-right">
                <div class="lyrics-inner">
                    <p>共に歩んだ 季節のなかで</p>
                    <p>紡いだ言葉は 星の輝き</p>
                    <p>迷った夜も 笑い合えた日も</p>
                    <p>すべてが僕らの 大切な宝物</p>
                    <p>明日への扉を また開けて...</p>
                </div>
            </div>

            <div class="story-viewport">
                <section id="stage-title" class="story-stage active">
                    <h2 class="hero-title fade-in-up"><?php echo htmlspecialchars($board_title ?? ''); ?></h2>
                </section>

                <section id="stage-video" class="story-stage">
                    <div class="video-center-box">
                        <video id="main-video" src="<?php echo htmlspecialchars($videoSrc); ?>" controls class="hero-video-player"></video>
                    </div>
                </section>

                <section id="stage-shikishi" class="story-stage" onclick="handleStageClick()">
                    <div class="shikishi-center-box">
                        <h3 class="shikishi-title">Team Messages</h3>
                        <div class="shikishi-grid">
                            <?php if (!empty($posts)): foreach ($posts as $post): ?>
                                    <div class="message-card">
                                        <p class="msg-body"><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
                                        <p class="msg-author">from: <?php echo htmlspecialchars($post['author_name']); ?></p>
                                    </div>
                            <?php endforeach;
                            endif; ?>
                        </div>
                        <p class="hint-text">画面をクリックして日常へ ＞</p>
                    </div>
                </section>

                <section id="stage-ec" class="story-stage">
                    <div class="ec-center-box">
                        <h2 class="ec-title">team memory for future</h2>
                        <p class="ec-subtitle">チームメンバーで紡いだグッズ -- これからの日常へつなぐチームツール</p>
                        <div class="ec-product-shelf">
                            <div class="product-item">
                                <div class="prod-img">📄</div>
                                <h4>チーム色紙</h4>
                                <p class="prod-note">印刷してご自宅へ送付</p>
                                <p class="price">¥1,800</p>
                                <button class="buy-btn">手に入れる</button>
                            </div>
                            <div class="product-item">
                                <div class="prod-img">📖</div>
                                <h4>チームアルバム</h4>
                                <p class="prod-note">記憶の集大成</p>
                                <p class="price">¥4,500</p>
                                <button class="buy-btn">手に入れる</button>
                            </div>
                            <div class="product-item">
                                <div class="prod-img">👕</div>
                                <h4>チームTシャツ</h4>
                                <p class="prod-note">ロゴ & 手書きフォント</p>
                                <p class="price">¥3,500</p>
                                <button class="buy-btn">手に入れる</button>
                            </div>
                            <div class="product-item">
                                <div class="prod-img">☕</div>
                                <h4>マグカップ</h4>
                                <p class="prod-note">チームロゴ入り</p>
                                <p class="price">¥2,200</p>
                                <button class="buy-btn">手に入れる</button>
                            </div>
                            <div class="product-item">
                                <div class="prod-img">🔑</div>
                                <h4>チームキーホルダー</h4>
                                <p class="prod-note">ロゴと集合写真</p>
                                <p class="price">¥1,200</p>
                                <button class="buy-btn">手に入れる</button>
                            </div>
                            <div class="product-item add-tool-box" onclick="showAddToolForm()">
                                <div class="plus-icon">+</div>
                                <p>ツールを追加</p>
                            </div>
                        </div>
                        <button class="skip-btn" onclick="goToStage('stage-final')">ECサイトをスキップして最後へ</button>
                    </div>
                </section>

                <section id="stage-final" class="story-stage">
                    <div class="final-farewell-box">
                        <h2 class="final-msg">これからはそれぞれの日常で、<br>次の冒険を楽しんでいこう。</h2>
                        <h3 class="final-submsg">また会う日まで</h3>
                        <a href="javascript:location.reload()" class="restart-link">もう一度ギフトボックスに戻る</a>
                    </div>
                </section>
            </div>

            <div id="nav-footer" class="navigation-footer">
                <button id="next-memory-trigger" class="next-memory-btn floating" onclick="handleNextStage()">next memory</button>
            </div>

            <div class="starring-box-fixed-right">
                <span class="credit-label">Starring:</span>
                <div class="credits-list">
                    <?php if (!empty($posts)): foreach ($posts as $post): ?>
                            <span class="credit-name"><?php echo htmlspecialchars($post['author_name']); ?></span>
                    <?php endforeach;
                    endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* --- フレーム・全体設定 --- */
    .gift-page-frame {
        position: fixed;
        inset: 0;
        padding: 25px;
        background: #5a4a42;
        box-sizing: border-box;
    }

    .gift-page-frame::after {
        content: '';
        position: absolute;
        inset: 10px;
        border: 2px solid rgba(255, 215, 0, 0.3);
        border-radius: 12px;
        pointer-events: none;
    }

    .gift-wrap-container {
        position: relative;
        width: 100%;
        height: 100%;
        background: #fcf8f3;
        border-radius: 8px;
        overflow: hidden;
    }

    /* --- リボン演出（z-indexを下げて背面に） --- */
    .ribbon-vertical {
        position: absolute;
        left: 50%;
        height: 100%;
        width: 60px;
        background: #ff8a80;
        transform: translateX(-50%);
        z-index: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .ribbon-horizontal {
        position: absolute;
        top: 50%;
        width: 100%;
        height: 60px;
        background: #ff8a80;
        transform: translateY(-50%);
        z-index: 1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .ribbon-knot {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
    }

    /* --- 【変更】温かみのある手書き風ギフトボックスデザイン --- */
    .gift-message-card {
        position: relative;
        z-index: 10;
        /* リボンより前面 */
        /* 温かみのあるクリーム色の背景と微細なテクスチャ */
        background-color: #fffaf0;
        background-image: linear-gradient(to bottom right, #fffaf0, #f8f0e0);
        padding: 3.5rem 3rem;
        width: 400px;
        text-align: center;

        /* 手書き風の不規則な丸み */
        border-radius: 25px 20px 30px 15px / 20px 30px 15px 25px;

        /* 直線的なボーダーを廃止し、多重の影で柔らかい輪郭と厚みを表現 */
        border: none;
        box-shadow:
            /* 輪郭線（ぼかした茶色） */
            0 0 0 3px rgba(139, 69, 19, 0.08),
            /* 柔らかい接地影 */
            0 15px 35px rgba(139, 69, 19, 0.15),
            /* 手書き風の厚み（少し歪んだ影） */
            3px 8px 0px rgba(139, 69, 19, 0.1);

        /* 以前の擬似要素による直線的な厚み表現は削除 */
    }

    .gift-message-card::after,
    .gift-message-card::before {
        display: none;
    }

    /* 手書き風タイポグラフィ（フォントは環境依存ですが、雰囲気を寄せます） */
    .handwritten-title {
        font-family: 'Comic Sans MS', 'Marker Felt', cursive, serif;
        /* 手書き風フォントの例 */
        color: #8b4513;
        /* 温かい茶色 */
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .subtitle {
        color: #a0522d;
        /* 少し明るい茶色 */
        font-size: 0.95rem;
    }

    /* 手書き風入力欄 */
    .input-handwritten {
        width: 100%;
        border: 2px solid #d2b48c;
        /* タン色のボーダー */
        /* 不規則な丸み */
        border-radius: 15px 10px 18px 12px / 12px 18px 10px 15px;
        padding: 12px;
        margin-bottom: 20px;
        text-align: center;
        background-color: #fffaf0;
        color: #8b4513;
        font-family: inherit;
        outline: none;
        box-shadow: inset 0 2px 4px rgba(139, 69, 19, 0.05);
    }

    .input-handwritten:focus {
        border-color: #8b4513;
    }

    /* 手書き風ボタン */
    .btn-handwritten {
        width: 100%;
        padding: 12px;
        background: #8b4513;
        /* 茶色 */
        color: white;
        /* 不規則な丸み */
        border-radius: 30px 25px 35px 20px / 25px 30px 20px 35px;
        cursor: pointer;
        border: none;
        font-size: 1.05rem;
        font-family: 'Comic Sans MS', 'Marker Felt', cursive, serif;
        transition: 0.3s;
        /* 手書き風の厚み影 */
        box-shadow: 0 4px 0 #5e2f0d;
    }

    .btn-handwritten:active {
        transform: translateY(2px);
        box-shadow: 0 2px 0 #5e2f0d;
    }

    /* --- 以下、既存のスタイルを維持 --- */
    /* 歌詞固定（右側：余白80px） */
    .lyrics-overlay-right {
        position: absolute;
        right: 80px;
        top: 15%;
        width: 25%;
        text-align: right;
        color: #5a4a42;
        font-family: serif;
        font-size: 1.1rem;
        line-height: 3;
        z-index: 5;
        opacity: 0.5;
        pointer-events: none;
    }

    /* コンテンツレイアウト（左8%パディング） */
    .gift-content-area {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transition: 1.5s;
        background: white;
    }

    .is-opened .gift-content-area {
        opacity: 1;
    }

    .story-viewport {
        flex: 1;
        position: relative;
        padding-left: 8%;
        width: 100%;
        height: 100%;
    }

    .story-stage {
        display: none;
        width: 100%;
        height: 100%;
        padding: 40px;
        box-sizing: border-box;
        overflow-y: auto;
    }

    .story-stage.active {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        animation: fadeIn 1.2s;
    }

    /* コンテンツ幅制限（60%） */
    .video-center-box,
    .shikishi-center-box,
    .ec-center-box,
    .hero-title,
    .final-farewell-box {
        width: 60%;
    }

    .hero-video-player {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    }

    /* 色紙メッセージ：センタリング */
    .shikishi-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        overflow-y: auto;
        max-height: 60%;
        width: 100%;
    }

    .message-card {
        background: #fffaf0;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.05);
        width: 200px;
        text-align: left;
    }

    /* ECサイト：縦スクロール・ボタン位置統一 */
    .ec-product-shelf {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 20px;
        padding: 20px 0;
        width: 100%;
    }

    .product-item {
        background: white;
        border: 1px solid #eee;
        padding: 20px;
        border-radius: 15px;
        width: 160px;
        min-height: 320px;
        display: flex;
        flex-direction: column;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
    }

    .buy-btn {
        margin-top: auto;
        background: #5a4a42;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        cursor: pointer;
    }

    /* 固定ナビゲーション：中央ボタン & 右下Starring */
    .navigation-footer {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 20;
    }

    .next-memory-btn {
        background: #5a4a42;
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        cursor: pointer;
        font-size: 0.95rem;
        letter-spacing: 2px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .starring-box-fixed-right {
        position: absolute;
        bottom: 40px;
        right: 40px;
        max-width: 300px;
        text-align: right;
        z-index: 15;
    }

    /* アニメーション */
    .floating {
        animation: floatBtn 3s infinite ease-in-out;
    }

    @keyframes floatBtn {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* 外装（維持） */
    .gift-box-outer {
        position: absolute;
        inset: 0;
        background: #fff0f0;
        border: 15px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        transition: transform 1.5s cubic-bezier(0.7, 0, 0.3, 1);
    }

    .is-opened .gift-box-outer {
        transform: translateY(-100%);
    }

    .shake {
        animation: shake 0.5s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-10px);
        }

        75% {
            transform: translateX(10px);
        }
    }
</style>

<script>
    // (既存のJavaScriptをそのまま貼り付けてください)
    let currentStageIndex = 0;
    const stages = ['stage-title', 'stage-video', 'stage-shikishi', 'stage-ec', 'stage-final'];

    function handleNextStage() {
        if (currentStageIndex < stages.length - 1) {
            currentStageIndex++;
            goToStage(stages[currentStageIndex]);
        }
    }

    function handleStageClick() {
        if (stages[currentStageIndex] === 'stage-shikishi') handleNextStage();
    }

    function goToStage(stageId) {
        document.querySelectorAll('.story-stage').forEach(s => s.classList.remove('active'));
        const nextStage = document.getElementById(stageId);
        if (nextStage) {
            nextStage.classList.add('active');
            if (stageId === 'stage-video') {
                const v = document.getElementById('main-video');
                if (v) v.play();
                // 歌詞用BGM（audio）を停止
                const bgm = document.getElementById('bgm-audio');
                if (bgm && !bgm.paused) bgm.pause();
            }
            if (stageId === 'stage-final') {
                const nav = document.getElementById('nav-footer');
                const starring = document.querySelector('.starring-box-fixed-right');
                nav.style.transition = starring.style.transition = 'opacity 1s';
                nav.style.opacity = starring.style.opacity = '0';
                setTimeout(() => {
                    nav.style.display = starring.style.display = 'none';
                }, 1000);
            }
        }
    }

    function showAddToolForm() {
        const name = prompt("追加したいアイテム名を入力してください");
        if (name) alert(name + " を追加します。");
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($is_opened): ?>
            setTimeout(() => {
                document.getElementById('gift-stage').classList.add('is-opened');
                // ▼BGM自動再生（デモ用）
                // 本来はAI生成BGMをここで再生予定。現状はデモ音源。
                const bgm = document.getElementById('bgm-audio');
                const mainVideo = document.getElementById('main-video');
                console.log('[reveal] bgm src=', bgm ? bgm.src : null, ' mainVideo src=', mainVideo ? mainVideo.src : null);
                if (bgm) {
                    bgm.volume = 0.12; // 小さめの音量（調整）
                    const playPromise = bgm.play();
                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            console.log('[reveal] bgm autoplay succeeded');
                        }).catch((err) => {
                            console.warn('[reveal] bgm autoplay failed:', err);
                            // ブラウザの自動再生制限が原因の可能性あり。ユーザー操作で再生できるよう案内ボタンを表示。
                            showUserPlayButton(bgm, mainVideo);
                        });
                    }
                }
            }, 500);
        <?php endif; ?>
    });

    // ユーザー操作で音声／動画を開始するためのシンプルなUIを追加
    function showUserPlayButton(bgm, mainVideo) {
        if (document.getElementById('user-play-overlay')) return;
        const overlay = document.createElement('div');
        overlay.id = 'user-play-overlay';
        overlay.style.position = 'fixed';
        overlay.style.left = '0';
        overlay.style.top = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.background = 'rgba(0,0,0,0.35)';
        overlay.style.zIndex = '9999';

        const btn = document.createElement('button');
        btn.textContent = '音を再生して続行する';
        btn.style.padding = '14px 20px';
        btn.style.fontSize = '16px';
        btn.style.borderRadius = '8px';
        btn.style.border = 'none';
        btn.style.cursor = 'pointer';
        btn.style.background = '#5a4a42';
        btn.style.color = 'white';

        btn.addEventListener('click', () => {
            // 再生を試みる
            const promises = [];
            if (bgm) {
                bgm.muted = false;
                bgm.volume = 0.12; // ユーザー操作時も同じ低音量で再生
                promises.push(bgm.play().catch(e => console.warn('bgm play failed on user click', e)));
            }
            if (mainVideo) {
                promises.push(mainVideo.play().catch(e => console.warn('video play failed on user click', e)));
            }
            Promise.all(promises).finally(() => {
                overlay.remove();
            });
        });

        overlay.appendChild(btn);
        document.body.appendChild(overlay);
    }
</script>