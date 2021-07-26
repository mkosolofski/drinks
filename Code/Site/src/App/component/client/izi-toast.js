import $ from 'jquery';
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import 'font-awesome/css/font-awesome.min.css';
import {
    Button
} from 'react-bootstrap';
$.fn.iziToast = iziToast;

class IziToast {

	constructor() {
		this.state = {
            id: null, 
            class: '',
            title: '',
            titleColor: '',
            titleSize: '',
            titleLineHeight: '',
            message: '',
            messageColor: '',
            messageSize: '',
            messageLineHeight: '',
            backgroundColor: '',
            theme: 'light', // dark
            color: '', // blue, red, green, yellow
            icon: '',
            iconText: '',
            iconColor: '',
            iconUrl: null,
            image: '',
            imageWidth: 50,
            maxWidth: null,
            zindex: null,
            layout: 1,
            balloon: false,
            close: true,
            closeOnEscape: false,
            closeOnClick: false,
            displayMode: 0, // once, replace
            position: 'bottomRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
            target: '',
            targetFirst: true,
            timeout: 5000,
            rtl: false,
            animateInside: true,
            drag: true,
            pauseOnHover: true,
            resetOnHover: false,
            progressBar: true,
            progressBarColor: '',
            progressBarEasing: 'linear',
            overlay: false,
            overlayClose: false,
            overlayColor: 'rgba(73, 80, 87, 0.7)',
            transitionIn: 'fadeInUp',
            transitionOut: 'fadeOut',
            transitionInMobile: 'fadeInUp',
            transitionOutMobile: 'fadeOutDown',
            buttons: {},
            inputs: {},
            onOpening: function () {},
            onOpened: function () {},
            onClosing: function () {},
            onClosed: function () {}
		}
	}

    warning(title, message, options = {}) {
        this.getIziToast()
            .warning(
                {
                    title: title,
                    message: message,
                    ...options
                }
            );
    }
    
    info(title, message, options = {}) {
        this.getIziToast()
            .info(
                {
                    title: title,
                    message: message,
                    ...options
                }
            );
    }
    
    error(title, message, options = {}) {
        this.getIziToast()
            .error(
                {
                    title: title,
                    message: message,
                    ...options
                }
            );
    }

    success(title, message, options = {}) {
        this.getIziToast()
            .success(
                {
                    title: title,
                    message: message,
                    ...options
                }
            );
    }

    hide(toast, options = {}) {
        this.getIziToast().hide(options, toast);
    }

    destroy() {
        this.getIziToast().destroy();
    }

    getIziToast() {
        return $.fn.iziToast;
    }
}

export default (new IziToast());
