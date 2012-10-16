// Generated by CoffeeScript 1.3.3
var Shark;

Shark = {
  init: function() {},
  resetForm: function(form) {
    var fieldType, i;
    i = 0;
    while (i < form.elements.length) {
      fieldType = form.elements[i].type.toLowerCase();
      switch (fieldType) {
        case 'text':
        case 'password':
        case 'textarea':
        case 'hidden':
          form.elements[i].value = '';
          break;
        case 'radio':
        case 'checkbox':
          if (form.elements[i].checked) {
            form.elements[i].checked = false;
          }
          break;
        case 'select-one':
        case 'select-multi':
          form.elements[i].selectIndex = -1;
          break;
      }
      ++i;
    }
  }
};
