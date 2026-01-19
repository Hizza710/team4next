<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEAM for NEXT | 想いを形にするギフト体験</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@700&family=Zen+Maru+Gothic:wght@400;700&display=swap" rel="stylesheet">
</head>

<body style="margin: 0; background-color: #fcf8f3; color: #5a4a42;">

    <div class="page-wrapper" style="display: flex; flex-direction: column; min-height: 100vh;">

        <header style="padding: 8px 0 0 8px; text-align: left;">
            <a href="<?= BASE_URL ?>/" style="text-decoration: none; display: inline-block;">
                <img src="<?= BASE_URL ?>/assets/logo/logo_team4next_v1.jpeg"
                    alt="TEAM for NEXT"
                    style="height: 96px; filter: drop-shadow(2px 3px 3px rgba(0,0,0,0.08));">
            </a>
        </header>

        <main class="container" style="flex: 1; width: 100%; box-sizing: border-box;">
            <?= $content ?>
        </main>

        <footer class="site-footer">
            <hr class="footer-line">
            <p class="copyright">©︎PATHWEAVE / HIZZA710</p>
        </footer>

    </div>
</body>

</html>