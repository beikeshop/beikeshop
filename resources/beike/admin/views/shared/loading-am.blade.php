<div class="loading-am"><svg viewBox="0 0 50 50"><circle class="ring" cx="25" cy="25" r="20"></circle><circle class="ball" cx="25" cy="5" r="3.5"></circle></svg></div>

<style>
.loading-am {
  position: absolute;
  left: 50%;
  display: flex;
  transform: translateX(-50%);
  top: 40%;
}

.loading-am svg {
  width: 80px;
  animation: 1.5s loading_am_spin ease infinite;
}

.loading-am .ring {
  fill: transparent;
  stroke: hsla(15, 100%, 48%, 0.3);
  stroke-width: 2;
}

.loading-am .ball {
  fill: #fd560f;
  stroke: none;
}

@keyframes loading_am_spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
