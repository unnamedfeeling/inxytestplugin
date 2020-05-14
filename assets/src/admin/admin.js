import mediaUploadComponent from './js/components/mediaUploaderComponent'

window.addEventListener('load', () => {
	if(document.querySelectorAll('.js-inxytestUploadBtn')){
		mediaUploadComponent.init()
	}
})