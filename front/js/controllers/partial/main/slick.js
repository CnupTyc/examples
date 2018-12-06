import 'can-construct-super';
import 'slick-carousel';

import Control from 'can-control';
import {viewport} from "../../helper/viewport";

let Opinions = Control.extend(
    {
        defaults: {
            opinions: '.js-opinions',
        }
    },
    {
        init() {
            this.$element = $(this.element);
            this.initSlick();
        },

        initSlick: function () {
            $(this.element.querySelector(this.options.opinions)).slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: true,responsive: [
                    {
                        breakpoint: viewport.sm,
                        settings: {
                            settings: "unslick",
                        }
                    },
                ]
            });
        },
    }
);

export {Opinions};
