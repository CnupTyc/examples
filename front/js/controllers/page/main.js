import {BasePage} from "../partial/base-page";
import {ParallaxElements} from "../partial/main/parallaxElements";
import {Opinions} from "../partial/main/slick";
import {viewport} from "../helper/viewport";
import {ProductCard} from "../partial/main/product-card";
import '../libs/jQuery.Brazzers-Carousel';

import Aos from 'aos';
import Fullpage from 'fullpage.js';


const mainPage = BasePage.extend(
    {
        defaults: {
            logoSection          : '.logo-section',
            pictureSection       : '.picture-section',
            updateContent        : '.update-content-section',
            updateContentWhite   : '.update-content-white-section',
            fullPage             : '.js-fullpage',
            fullpageDestroyer    : '.fp-destroyed',
            fullpageWrapper      : '.fullpage-wrapper',
            productCard          : '.js-product-card',
            initedClass          : 'inited',
            updateFixedContent   : '.update-fixed-content',
            noShow               : 'no-show',
            whiteColor           : 'white-color',
            textOnWhite          : '.js-text-on-white',
            textOnBlack          : '.js-text-on-black',
            content              : '.content',
            aosAnimate           : 'aos-init aos-animate',
            updateDataAttr       : '.update-fixed-content [data-aos]',
            designMainSite       : '.design-main-site-section',
            video                : '.js-video',
            incResponsiveSection : '.inc-responsive-section',
            excellentOpinions    : '.excellent-opinions-section',
            incIndicators        : '.inc-indicators-section',
            mainSite             : '.main-site-section',
        }
    },

    {
        init() {
            this._super();
            this.$element = $(this.element);
            this.element.querySelectorAll(this.options.logoSection).forEach(el =>  new ParallaxElements(el));
            this.element.querySelectorAll(this.options.incResponsiveSection).forEach(el =>  new ParallaxElements(el));
            this.element.querySelectorAll(this.options.incIndicators).forEach(el =>  new ParallaxElements(el));
            this.element.querySelectorAll(this.options.mainSite).forEach(el =>  new ParallaxElements(el));
            this.element.querySelectorAll(this.options.excellentOpinions).forEach(el =>  new Opinions(el));

            this.$fp = $(this.element.querySelector(this.options.fullPage));
            this.updateFixedContent = this.element.querySelector(this.options.updateFixedContent);
            this.updateContent = this.element.querySelector(this.options.updateContent);
            this.updateContentWhite = this.element.querySelector(this.options.updateContentWhite);
            this.mobileContent = this.updateContent.querySelector(this.options.content);

            this.initAos();

            this.$element.find(this.options.productCard).not(`.${this.options.initedClass}`).each(function(i, el) {
                new ProductCard(el);
            }.bind(this));

            if (!viewport.isMobile()) {
                this.initFullpage();
            } else {
                $(this.mobileContent).append($(this.updateFixedContent.children));
                $(this.updateFixedContent).empty();
            }
        },

        initFullpage() {
            this.fullpage = new Fullpage(this.element.querySelector(this.options.fullPage), {
                //Навигация
                scrollHorizontally: false,
                loopHorizontal: false,
                scrollOverflow: false,
                keyboardScrolling: false,
                responsiveWidth: viewport.sm,
                licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',
                afterLoad: this.afterLoad.bind(this),
                onLeave: this.onLeave.bind(this),

            });
        },

        onLeave(from, to) {
            if (to.item === this.element.querySelector(this.options.updateContentWhite) ||
                to.item === this.updateContent) {

                $(this.updateFixedContent).toggleClass(this.options.whiteColor, to.item === this.updateContent);

                $(this.updateFixedContent.querySelector(this.options.textOnWhite)).toggleClass(this.options.noShow, to.item === this.updateContent);
                $(this.updateFixedContent.querySelector(this.options.textOnBlack)).toggleClass(this.options.noShow, to.item === this.element.querySelector(this.options.updateContentWhite));

                this.updateFixedContent.style.zIndex  = '2';
                this.updateFixedContent.style.opacity = '1';
            } else {
                this.updateFixedContent.style.zIndex  = '0';
                this.updateFixedContent.style.opacity = '0';
            }

            if (to.item !== this.element.querySelector(this.options.designMainSite)) {
                this.element.querySelector(this.options.video).pause();
            }
        },

        afterLoad(from, to) {
            if (
                !viewport.isMobile() &&
                fullpage_api.getActiveSection().item === this.element.querySelector(this.options.pictureSection) &&
                from
            ) {
                from.index < to.index ? this.fullpage.moveSectionDown() : this.fullpage.moveSectionUp();
            } else {
                $(this.options.updateDataAttr).removeClass(this.options.aosAnimate);
            }

            if (to.item === this.updateContent || to.item === this.updateContentWhite) {
                let aosAnimate = this.options.aosAnimate;

                $(this.options.updateDataAttr).each((i, el) => {
                    $(el).each(function () {
                        $(el).addClass(aosAnimate);
                    });
                });
            }

            if (to.item === this.element.querySelector(this.options.designMainSite)) {
                this.element.querySelector(this.options.video).play();
            }
        },

        initAos() {
            Aos.init({
                offset: 0,
                duration: 500,
                easing: 'ease-in-sine',
                delay: 0,
            });
        },

        '{window} resize'() {
            if (
                viewport.isMobile() &&
                !this.element.querySelector(this.options.fullpageDestroyer) &&
                this.element.querySelector(this.options.fullpageWrapper)
            ) {
                if (!this.mobileContent.children) {
                    $(this.mobileContent).append($(this.updateFixedContent.children));
                    $(this.updateFixedContent).empty();
                }
                this.updateFixedContent.style.zIndex  = '0';
                this.updateFixedContent.style.opacity = '0';
                this.fullpage.destroy(true);
            } else if (
                (!viewport.isMobile() &&
                    this.element.querySelector(this.options.fullpageDestroyer) &&
                    this.element.querySelector(this.options.fullpageWrapper)) ||
                (!viewport.isMobile() && !this.element.querySelector(this.options.fullpageWrapper))
            ){
                $(this.updateFixedContent).append($(this.mobileContent.children));
                $(this.mobileContent).empty();
                this.initFullpage();
            }
        }
    }
);

new mainPage(document.querySelector('body'));


