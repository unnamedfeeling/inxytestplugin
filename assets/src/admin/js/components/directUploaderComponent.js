let directUploaderComponent = new function () {
	let self = this
	
	self.init = () => {
		self.form = document.querySelector('.js-inxytestForm')
		
		if(self.form){
			self.formFields = self.form.querySelectorAll('input[type="text"]')
			self.submitButton = self.form.querySelector('.js-inxytestDirectUploadBtn')
			self.console = self.form.querySelector('.js-console')
		}
		
		if(self.submitButton){
			self.submitButton.addEventListener('click', self.handleSubmitClick)
		}
	}
	
	self.handleSubmitClick = () => {
		self.console.innerText = ''
		
		let url = self.form.action,
			data = {url: ''}
			
		self.formFields.forEach(field => {
			if (field.value !== '') data.url = field.value
		} )
		
		if(data.url === ''){
			self.console.innerText = 'No URL provided!'
			self.console.classList.remove('hidden')
		}
		
		let fetchConf = {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json;charset=utf-8',
			},
			body: JSON.stringify(data),
		}
		
		if(data.url !== '') {
			document.body.classList.add('requestPending')
			
			fetch(url, fetchConf)
				.then(response => {
					return response.json()
				})
				.then((response) => {
					self.console.classList.remove('hidden')
					self.console.innerText = JSON.stringify(response)
				})
				.catch(error => console.log(error))
				.finally(() => document.body.classList.remove('requestPending'))
		}
	}
}

export default directUploaderComponent