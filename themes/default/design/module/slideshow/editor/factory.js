(function () {
  $globalConfig.factories.slideshow = {
    make: function () {
      return {
        content: {
          style: {
            background_color: ''
          },
          full: true,
          floor: languagesFill(''),
          images: [
            {
              image: languagesFill('catalog/demo/slideshow/2.jpg'),
              show: true,
              link: {
                type: 'product',
                value:''
              }
            },
            {
              image: languagesFill('catalog/demo/slideshow/1.jpg'),
              show: false,
              link: {
                type: 'product',
                value:''
              }
            }
          ],
        }
      }
    }
  }
})();
