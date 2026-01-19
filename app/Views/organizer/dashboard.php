<div class="container fade-in">
    <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>管理ダッシュボード</h2>
        <a href="<?php echo BASE_URL; ?>/organizer/new" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">+ 新規作成</a>
    </div>

    <?php if (empty($boards)): ?>
        <div class="card text-center">
            <p>まだボードがありません。まずは新しいギフトの場を作りましょう。</p>
            <div style="margin-top: 1.5rem;">
                <a href="<?php echo BASE_URL; ?>/organizer/new" class="btn-primary">新しいボードを作る</a>
            </div>
        </div>
    <?php else: ?>
        <div class="board-list">
            <?php foreach ($boards as $board): ?>
                <div class="card" style="margin-bottom: 1.5rem; gap: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 1rem;">
                        <div class="board-main">
                            <h3 style="margin: 0;"><?php echo htmlspecialchars($board['title']); ?></h3>
                            <div style="margin-top: 0.5rem;">
                                <span class="badge status-<?php echo $board['status']; ?>" style="padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem;">
                                    <?php 
                                        $status_map = ['collecting' => '募集中', 'ready' => '準備完了', 'opened' => '公開中'];
                                        echo $status_map[$board['status']] ?? strtoupper($board['status']);
                                    ?>
                                </span>
                                <span style="margin-left: 1rem; color: #666; font-size: 0.9rem;">
                                    投稿数: <strong><?php echo $board['post_count']; ?></strong> 件
                                </span>
                            </div>
                        </div>

                        <div class="board-actions" style="display: flex; gap: 0.5rem; align-items: center;">
                            <a href="<?php echo BASE_URL; ?>/gift?token=<?php echo htmlspecialchars($board['gift_token_hash']); ?>&preview=true" 
                               target="_blank" 
                               class="btn-outline" 
                               style="font-size: 0.8rem; text-decoration: none; padding: 0.4rem 0.8rem; border: 1px solid #ccc; border-radius: 4px; color: #333;">
                               プレビュー
                            </a>
                            
                            <form action="<?php echo BASE_URL; ?>/organizer/boards/status" method="POST" style="margin:0;">
                                <input type="hidden" name="board_id" value="<?php echo $board['id']; ?>">
                                <?php if ($board['status'] === 'collecting'): ?>
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="btn-accent" style="font-size: 0.8rem; cursor: pointer;">募集を締切る</button>
                                <?php elseif ($board['status'] === 'ready'): ?>
                                    <input type="hidden" name="status" value="opened">
                                    <button type="submit" class="btn-primary" style="font-size: 0.8rem; cursor: pointer;">ギフトを公開する</button>
                                <?php elseif ($board['status'] === 'opened'): ?>
                                    <span style="font-size: 0.8rem; color: #4caf50; font-weight: bold;">公開中</span>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <?php if ($board['status'] === 'opened'): ?>
                        <div class="share-box" style="background: #f0fdf4; border: 1px dashed #4caf50; padding: 1.5rem; border-radius: 8px; margin-top: 1rem;">
                            <p style="margin: 0 0 0.8rem 0; font-weight: bold; color: #166534; font-size: 0.9rem;">🎁 受取人さんにこの情報を伝えてください</p>
                            
                            <div style="margin-bottom: 1rem;">
                                <label style="display:block; font-size:0.75rem; color:#666; margin-bottom:4px;">受け取り用URL</label>
                                <div style="display:flex; gap:5px;">
                                    <input type="text" readonly value="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . BASE_URL . '/gift?token=' . $board['gift_token_hash']; ?>" 
                                           id="url-<?php echo $board['id']; ?>"
                                           style="flex:1; padding:8px; border:1px solid #ddd; border-radius:4px; font-size:0.85rem; background:#fff;">
                                    <button onclick="copyText('url-<?php echo $board['id']; ?>')" class="btn-outline" style="font-size:0.75rem; padding:0 10px;">コピー</button>
                                </div>
                            </div>

                            <div>
                                <label style="display:block; font-size:0.75rem; color:#666; margin-bottom:4px;">魔法の鍵（合言葉）</label>
                                <div style="display:flex; gap:5px;">
                                    <input type="text" readonly value="<?php echo htmlspecialchars($board['passphrase_display'] ?? '（作成時に設定した言葉）'); ?>" 
                                           id="key-<?php echo $board['id']; ?>"
                                           style="flex:1; padding:8px; border:1px solid #ddd; border-radius:4px; font-size:0.85rem; background:#fff; font-weight:bold; letter-spacing:1px;">
                                    <button onclick="copyText('key-<?php echo $board['id']; ?>')" class="btn-outline" style="font-size:0.75rem; padding:0 10px;">コピー</button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function copyText(id) {
    const copyTarget = document.getElementById(id);
    copyTarget.select();
    document.execCommand("Copy");
    alert("コピーしました！");
}
</script>

<style>
.status-collecting { background-color: #e3f2fd; color: #1976d2; }
.status-ready { background-color: #fff3e0; color: #f57c00; }
.status-opened { background-color: #e8f5e9; color: #388e3c; }
.card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eee; }
.btn-outline { background: white; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: 0.2s; }
.btn-outline:hover { background: #f9f9f9; border-color: #ccc; }
</style>