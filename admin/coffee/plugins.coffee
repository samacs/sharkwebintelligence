Shark = {
	init: () ->
	resetForm: (form) ->
		i = 0
		while i < form.elements.length
			fieldType = form.elements[i].type.toLowerCase()
			switch fieldType
				when 'text', 'password', 'textarea', 'hidden'
					form.elements[i].value = ''
				when 'radio', 'checkbox'
					form.elements[i].checked = false if form.elements[i].checked
				when 'select-one', 'select-multi'
					form.elements[i].selectIndex = -1
				else
			++i
		return
}