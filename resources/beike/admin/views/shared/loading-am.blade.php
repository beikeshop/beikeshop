<div class="loading-am">
  <div class="loading-content">
    <div class="loading-logo"><img src="{{ asset('image/logo.png') }}" class="img-fluid"></div>
    <svg class="rotating-arc" viewBox="0 0 100 50">
      <defs>
        <linearGradient id="arc-gradient" x1="100%" y1="0%" x2="0%" y2="0%">
          <stop offset="0%" stop-color="var(--bkload-arc-color, #ff0000)" />
          <stop offset="30%" stop-color="var(--bkload-arc-color, #ff0000)" stop-opacity="0.4" />
          <stop offset="80%" stop-color="var(--bkload-arc-color, #ff0000)" stop-opacity="0" />
        </linearGradient>
      </defs>
      <path
        class="arc"
        d="M 10 25 A 40 40 0 0 1 90 25"
        fill="none"
        stroke="url(#arc-gradient)"
        stroke-width="var(--bkload-arc-width, 4)"
        stroke-linecap="round"
      />
    </svg>
  </div>
</div>

<style>
:root {
  --bkload-loading-size: 280px;
  --bkload-logo-size: 170px;
  --bkload-arc-color: #fd560f;
  --bkload-arc-width: 4px;
  --bkload-arc-offset: 20%;
  --bkload-animation-duration: 1.5s;
}

.loading-am {
  position: absolute;
  left: 50%;
  display: flex;
  top: 45%;
  transform: translate(-50%, -50%);
}

.loading-content {
  position: relative;
  width: var(--bkload-loading-size);
  height: var(--bkload-loading-size);
  display: flex;
  justify-content: center;
  align-items: center;
  --bkload-arc-radius: calc(var(--bkload-loading-size) / 2 - var(--bkload-arc-offset));
}

.loading-logo {
  position: relative;
  z-index: 2;
  width: var(--bkload-logo-size);
  height: var(--bkload-logo-size);
  display: flex;
  justify-content: center;
  align-items: center;
}

.loading-logo img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.rotating-arc {
  position: absolute;
  width: 100%;
  height: 100%;
  animation: rotate var(--bkload-animation-duration) linear infinite;
  transform-origin: center;
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* 响应式调整 */
@media (max-width: 768px) {
  :root {
    --bkload-loading-size: 180px;
    --bkload-logo-size: 100px;
    --bkload-arc-width: 3px;
  }
}
</style>
