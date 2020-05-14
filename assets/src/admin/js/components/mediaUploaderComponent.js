let mediaUploadComponent = new function () {
	let self = this
	
	self.init = () => {
		self.buttons = document.querySelectorAll('.js-inxytestUploadBtn')
		
		self.buttons.forEach(button => {
			button.addEventListener('click', (event) => {
				event.preventDefault()
				
				let input = button.closest('td').querySelector('input[type="text"]'),
						custom_uploader = wp.media({
							title: 'Select or upload json file',
							library : {
								type : 'text/plain'
							},
							button: {
								text: 'Use this file' // button label text
							},
							multiple: false // for multiple image selection set to true
						}).on('select', function() { // it also has "open" and "close" events
							var attachment = custom_uploader.state().get('selection').first().toJSON();
							input.value = attachment.url
						})
						.open();
			})
		})
	}
}

export default mediaUploadComponent