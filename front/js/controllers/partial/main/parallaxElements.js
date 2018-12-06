import 'can-construct-super';
import Parallax from 'parallax-js';

import Control from 'can-control';

let ParallaxElements = Control.extend(
    {
        defaults: {
            parallax: '.js-parallax',
        }
    },
    {
        init() {
            this.$element = $(this.element);
            this.initParallax();
        },

        initParallax: function () {
            this.element.querySelectorAll(this.options.parallax).forEach(function (el, i) {
                new Parallax(el);
            })
        },
    }
);

export {ParallaxElements};
