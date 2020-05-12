import './scss/main.scss';

import './js/polyfills/closest'
import linkHandlerComponent from './js/components/linkHandlerComponent'

window.addEventListener('load', () => {
	linkHandlerComponent.init()
})