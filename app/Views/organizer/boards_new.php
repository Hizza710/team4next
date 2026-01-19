<div class="container">
    <div class="card">
        <h2>新しいギフトの場を作る</h2>
        <p class="description">贈る相手の誕生日や、チームのプロジェクト名をタイトルにしましょう。</p>
        
        <form action="/leaderskit/team4next/www/organizer/boards/create" method="POST">
            <div class="form-group">
                <label>タイトル</label>
                <input type="text" name="title" placeholder="例：〇〇さんの送別会" required>
            </div>

            <div class="form-group">
                <label>メンバー用の合言葉</label>
                <input type="text" name="passphrase" placeholder="例：arigato" required>
                <small>メンバーが投稿する際に入力する合言葉です。</small>
            </div>

            <button type="submit" class="btn-primary">ボードを作成する</button>
        </form>
    </div>
</div>