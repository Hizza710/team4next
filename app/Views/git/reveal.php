<div id="reveal-container" class="reveal-stage">
    <div id="step-1" class="reveal-message">
        <p class="fade-in-text">あなたへ、チームからの贈り物が届いています。</p>
    </div>

    <div id="step-2" class="hidden">
        <div class="expand-circle">
            <video id="gift-video" width="100%" controls class="hidden">
                <source src="/assets/demo/demo_video_01.mp4" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<script>
// シンプルな演出制御
setTimeout(() => {
    document.getElementById('step-1').classList.add('fade-out');
    setTimeout(() => {
        document.getElementById('step-1').style.display = 'none';
        document.getElementById('step-2').classList.remove('hidden');
        document.getElementById('step-2').classList.add('scale-up');
    }, 2000);
}, 3000);
</script>