<div class="shikishi-container fade-in">
    <header class="shikishi-header">
        <h1 class="serif-text"><?php echo htmlspecialchars($board['title']); ?></h1>
        <p class="subtitle">皆様からのメッセージが届いています</p>
    </header>

    <div class="message-grid">
        <?php foreach ($posts as $post): ?>
            <div class="message-card">
                <?php if (!empty($post['image_url'])): ?>
                    <div class="message-image">
                        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="思い出の写真">
                    </div>
                <?php endif; ?>
                
                <div class="message-body">
                    <p class="text"><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
                    <p class="author">ー <?php echo htmlspecialchars($post['author_name'] ?: '匿名'); ?> より</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <footer class="shikishi-footer">
        <button onclick="window.print()" class="btn-outline">この色紙を保存・印刷する</button>
    </footer>
</div>

<style>
/* 色紙専用のスタイル */
.shikishi-container {
    max-width: 1000px; margin: 0 auto; padding: 40px 20px;
    background: #fdfaf5; /* 柔らかな紙のような色 */
}
.shikishi-header { text-align: center; margin-bottom: 50px; }
.shikishi-header h1 { font-size: 2.2rem; color: #5a4a42; margin-bottom: 10px; }

.message-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.message-card {
    background: white; border-radius: 12px; overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}
.message-card:hover { transform: translateY(-5px); }

.message-image img { width: 100%; height: 200px; object-fit: cover; }

.message-body { padding: 20px; }
.message-body .text {
    font-size: 0.95rem; color: #333; line-height: 1.8; margin-bottom: 15px;
}
.message-body .author {
    font-size: 0.85rem; color: #888; text-align: right; font-style: italic;
}

.shikishi-footer { text-align: center; margin-top: 60px; }

/* 印刷用設定 */
@media print {
    .btn-outline, .simple-nav, .simple-footer { display: none; }
    .shikishi-container { width: 100%; padding: 0; }
}
</style>