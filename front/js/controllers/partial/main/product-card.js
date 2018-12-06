import canControl from 'can-control';
import '../../libs/jQuery.Brazzers-Carousel';
import LazyLoad from '../../libs/lazyload';
import {isTouch} from "../../helper/is-touch";


const ProductCard = canControl.extend(
    {
        defaults: {
            brazzers: '.js-brazzers',
            initedClass: 'inited',
        }
    },
    {
        init: function() {
            this.$element = $(this.element);
            this.$element.addClass(this.options.initedClass);

            if (!isTouch()) {
                this.initGallery();
            } else {
                this.initImageWrap();
            }
        },

        initGallery: function() {
            let carouselList = this.$element.find(this.options.brazzers).not('.inited');

            if (carouselList.length) {
                carouselList.each((index, element) => {
                    let $el = $(element);
                    $el.brazzersCarousel();
                    $el.addClass('inited');
                });
            }
        },

        initImageWrap() {
            this.$element.find(this.options.brazzers).find('img').wrapAll('<div class="image-wrap"/>');
        },
    }
);

export { ProductCard };