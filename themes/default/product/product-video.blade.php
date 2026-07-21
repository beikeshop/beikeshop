@if ($product['video'])
  <div class="video-wrap">
    @php
      $isYouTube = str_contains($product['video'], 'youtube.com/watch') || str_contains($product['video'], 'youtu.be/');
    @endphp

    @if ($isYouTube)
      <div id="product-video"></div>
    @else
      <video
        id="product-video"
        class="video-js vjs-big-play-centered vjs-fluid vjs-16-9"
        controls loop muted
      >
        <source src="{{ image_origin($product['video']) }}" type="video/mp4"/>
      </video>
    @endif

    <div class="close-video d-none"><i class="bi bi-x-circle"></i></div>
    <div class="open-video"><i class="bi bi-play-circle"></i></div>
  </div>
@endif


@push('add-scripts')
  <script>
    const videoUrl = '{!! $product['video'] !!}';
    const videoId = (function(url) {
      try {
        const parsed = new URL(url);
        if (parsed.hostname.includes('youtu.be')) return parsed.pathname.slice(1);
        if (parsed.hostname.includes('youtube.com') && parsed.searchParams.has('v')) return parsed.searchParams.get('v');
      } catch (e) { return null; }
      return null;
    })(videoUrl);

    const isYouTube = !!videoId;
    let pVideo = null;

    $(function () {
      // 点击播放
      if ($('#product-video').length && !isYouTube) {
        pVideo = videojs("product-video");
      }

      $(document).on('click', '.open-video', function () {
        if (isYouTube) {
          $('#product-video').html(`
            <iframe width="100%" height="100%"
              src="https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen>
            </iframe>
          `);
        } else if ($('#product-video').length) {
          pVideo.play();
          pVideo.currentTime(0);
        }

        $(this).addClass('d-none');
        $('#product-video').fadeIn();
        $('.close-video').removeClass('d-none');
      });

      // 点击关闭
      $(document).on('click', '.close-video', function () {
        if (isYouTube) {
          $('#product-video').fadeOut();
        } else if (pVideo) {
          pVideo.pause();
          $('#product-video').fadeOut();
        }

        $('.close-video').addClass('d-none');
        $('.open-video').removeClass('d-none');
      });
    });

    function closeVideo() {
      if (pVideo) {
        pVideo.pause();
      }

      $('.close-video').addClass('d-none');
      $('.open-video').removeClass('d-none');
      $('#product-video').fadeOut();
    }
  </script>
@endpush
