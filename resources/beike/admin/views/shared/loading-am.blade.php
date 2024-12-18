<div class="loading-am loaded"><svg viewBox="0 0 50 50" width="80" height="80" ><circle class="ring" cx="25" cy="25" r="20"></circle><circle class="ball" cx="25" cy="5" r="3.5"></circle></svg></div>

@push('footer')
<style>
.loading-am {
  position: absolute;
  left: 50%;
  display: flex;
  transform: translateX(-50%);
  top: 40%;
}

.loading-am svg {
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
@endpush