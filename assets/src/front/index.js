import './scss/_main.scss';

import 'whatwg-fetch'
import './js/polyfills/closest'

import tableHandlerComponent from './js/components/tableHandlerComponent'

window.addEventListener('load', () => {
	tableHandlerComponent.init()
})