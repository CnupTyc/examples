import 'can-construct-super';
import Control from 'can-control';
import 'babel-polyfill';
import 'can-jquery';


/**
 * Базовый контроллер страницы
 * @type {void | *}
 */
let BasePage = Control.extend(
    {
        defaults: {

        }
    },
    {
        init() {

        }
    }
);

export {BasePage};
