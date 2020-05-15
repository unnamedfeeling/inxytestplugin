import './scss/_main.scss';

import 'whatwg-fetch';

import mediaUploadComponent from './js/components/mediaUploaderComponent'
import directUploaderComponent from './js/components/directUploaderComponent'

window.addEventListener('load', () => {
	if(document.querySelectorAll('.js-inxytestUploadBtn')){
		mediaUploadComponent.init()
		directUploaderComponent.init()
	}
})