let tableHandlerComponent = new function () {
	let self = this
	
	self.init = () => {
		self.tables = document.querySelectorAll('.js-testPluginTable')
		
		if(self.tables){
			self.tables.forEach(table => {
				table.addEventListener('click', self.linkHandler)
			})
		}
	}
	
	self.linkHandler = (event) => {
		let trg = event.target,
				table = trg.closest('table')
		if(trg.nodeName === 'A'){
			event.preventDefault()
			
			const regex = /paged=(\d*)/gm;
			const str = trg.getAttribute('href')
			let m = regex.exec(str);
			let page = 1
			
			if(m) page = m[1]
			
			let fetchConf = {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json;charset=utf-8',
				},
			}
			
			table.classList.add('fetchingData')
			
			fetch('/wp-json/testplugin/v1/getTableRowsHtml?paged=' + page, fetchConf)
				.then(response => {
					return response.json()
				})
				.then((response) => {
					if(response && response.tableRows && response.pagination){
						table.querySelector('tbody').innerHTML = response.tableRows
						table.querySelector('tfoot .text-center').innerHTML = response.pagination
					}
				})
				.catch(error => console.log(error))
				.finally(() => table.classList.remove('fetchingData'))
		}
	}
}

export default tableHandlerComponent