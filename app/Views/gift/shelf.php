<div class="container fade-in">
    <section class="shelf-header">
        <h2>作品棚</h2>
        <p>このギフトはいつでもこのURLから見返すことができます。</p>
    </section>

    <div class="archive-section grid">
        <div class="card mini-card">
            <span class="icon">🎥</span>
            <h4>Video Message</h4>
            <a href="#" onclick="replayVideo()" class="btn-text">もう一度見る</a>
        </div>
        <div class="card mini-card">
            <span class="icon">📄</span>
            <h4>Digital Shikishi</h4>
            <a href="/gift/shikishi?token=<?php echo $_GET['token']; ?>" class="btn-text">色紙を開く</a>
        </div>
    </div>

    <section class="products-section">
        <h3>形にして持ち運ぶ</h3>
        <p class="subtitle">チームの想いを、日常に馴染むグッズにしました。</p>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-visual">
                    <div class="design-label"><?php echo htmlspecialchars($board_title); ?></div>
                    <img src="<?php echo $product['preview_url']; ?>" alt="">
                </div>
                <div class="product-info">
                    <h4><?php echo $product['name']; ?></h4>
                    <p class="price">¥<?php echo $product['price']; ?> (tax in)</p>
                    <button class="btn-buy" onclick="alert('プロトタイプのため、決済機能は準備中です')">購入を検討する</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>