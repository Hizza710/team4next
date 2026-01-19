<div class="container">
    <header class="editor-header">
        <h2><?php echo htmlspecialchars($board['title']); ?></h2>
        <p>あなたの感謝や思い出を言葉にしましょう。</p>
    </header>

    <div class="editor-layout">
        <section class="input-form card">
            <form action="<?= BASE_URL ?>/invite/submit" method="POST" id="post-form" enctype="multipart/form-data">

                <input type="hidden" name="board_id" value="<?php echo (int)$board['id']; ?>">

                <div class="form-group">
                    <label>お名前（匿名希望は空欄でOK）</label>
                    <input type="text" name="name" id="input-name" placeholder="例：田中より">
                </div>

                <div class="form-group">
                    <label>メッセージ</label>
                    <textarea name="body" id="input-body" rows="8" placeholder="ここでの思い出を自由に書いてください..." required></textarea>
                </div>

                <div class="form-group">
                    <label>写真（任意）</label>
                    <input type="file" name="image" id="input-image" accept="image/*">
                </div>

                <button type="submit" class="btn-primary">この内容で投稿する</button>
            </form>
        </section>

        <section class="preview-area">
            <div class="preview-card" id="preview-card">
                <div class="preview-image" id="preview-img-container" style="display:none;">
                    <img src="" id="img-output" style="width:100%; border-radius:8px;">
                </div>
                <div class="preview-content">
                    <p id="preview-body-text">メッセージがここに表示されます...</p>
                    <div id="preview-name-text" class="preview-name"></div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    /* プレビューエリアを横並びにするための簡易スタイル */
    .editor-layout {
        display: flex;
        gap: 40px;
        margin-top: 30px;
        align-items: flex-start;
    }

    .input-form {
        flex: 1;
        padding: 30px;
    }

    .preview-area {
        flex: 1;
        position: sticky;
        top: 20px;
    }

    .preview-card {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 15px 45px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0e6d6;
        min-height: 300px;
    }

    .preview-name {
        text-align: right;
        margin-top: 20px;
        font-style: italic;
        color: #888;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    @media (max-width: 768px) {
        .editor-layout {
            flex-direction: column;
        }
    }
</style>

<script>
    // ライブプレビューのJavaScript
    const inputBody = document.getElementById('input-body');
    const inputName = document.getElementById('input-name');
    const previewBody = document.getElementById('preview-body-text');
    const previewName = document.getElementById('preview-name-text');

    inputBody.addEventListener('input', () => {
        previewBody.textContent = inputBody.value || 'メッセージがここに表示されます...';
    });

    inputName.addEventListener('input', () => {
        previewName.textContent = inputName.value ? `— ${inputName.value}` : '';
    });

    // 画像プレビュー
    document.getElementById('input-image').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-output').src = e.target.result;
                document.getElementById('preview-img-container').style.display = 'block';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>