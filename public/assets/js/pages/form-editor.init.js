ClassicEditor.create(document.querySelector("#ckeditor-classic")).then(function(e){e.ui.view.editable.element.style.height="200px"}).catch(function(e){console.error(e)});

ClassicEditor.create(document.querySelector("#ckeditor-classic-other")).then(function(e){e.ui.view.editable.element.style.height="200px"}).catch(function(e){console.error(e)});