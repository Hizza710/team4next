<div class="container fade-in">
    <div class="card text-center">
        <h2 style="color: var(--accent-color);">🎉 ボードの準備が整いました！</h2>
        <p>以下のURLをコピーして、メンバーや受取人に共有してください。</p>

        <div class="share-section" style="margin-top: 2rem; text-align: left;">
            <div class="share-item" style="margin-bottom: 2rem;">
                <label style="font-weight: bold; display: block; margin-bottom: 0.5rem;">1. メンバーに教える（投稿用URL）</label>
                <div class="copy-box" onclick="copyToClipboard('invite-url', this)" style="cursor: pointer; background: #f9f9f9; border: 2px dashed #ccc; padding: 1rem; border-radius: 8px; position: relative;">
                    <span id="invite-url" style="word-break: break-all; color: #333;">
                        <?php echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . BASE_URL . '/invite?token=' . $invite_token; ?>
                    </span>
                    <div class="copy-hint" style="font-size: 0.7rem; color: #888; margin-top: 0.5rem;">クリックしてコピー</div>
                </div>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">合言葉：<strong><?php echo htmlspecialchars($passphrase_display); ?></strong></p>
            </div>

            <div class="share-item">
                <label style="font-weight: bold; display: block; margin-bottom: 0.5rem;">2. 本人に贈る（ギフト開封用URL）</label>
                <div class="copy-box" onclick="copyToClipboard('gift-url', this)" style="cursor: pointer; background: #fff5f5; border: 2px dashed #ff8a80; padding: 1rem; border-radius: 8px; position: relative;">
                    <span id="gift-url" style="word-break: break-all; color: #333;">
                        <?php echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . BASE_URL . '/gift?token=' . $gift_token; ?>
                    </span>
                    <div class="copy-hint" style="font-size: 0.7rem; color: #888; margin-top: 0.5rem;">クリックしてコピー</div>
                </div>
                <p style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">※幹司が「公開」に設定するまで、中身は見られません。</p>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <a href="<?php echo BASE_URL; ?>/organizer/dashboard" class="btn-primary">管理画面（ダッシュボード）へ</a>
        </div>
    </div>
</div>

<script>
/**
 * 指定したIDのテキストをクリップボードにコピーする
 */
function copyToClipboard(elementId, container) {
    const text = document.getElementById(elementId).innerText.trim();
    
    navigator.clipboard.writeText(text).then(() => {
        // コピー成功時の演出
        const hint = container.querySelector('.copy-hint');
        const originalText = hint.innerText;
        
        hint.innerText = '✅ コピーしました！';
        hint.style.color = '#4caf50';
        container.style.backgroundColor = '#e8f5e9';
        
        // 2秒後に元に戻す
        setTimeout(() => {
            hint.innerText = originalText;
            hint.style.color = '#888';
            container.style.backgroundColor = (elementId === 'invite-url') ? '#f9f9f9' : '#fff5f5';
        }, 2000);
    }).catch(err => {
        console.error('コピーに失敗しました', err);
        alert('コピーに失敗しました。手動で選択してコピーしてください。');
    });
}
</script>