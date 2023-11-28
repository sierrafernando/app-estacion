//Cuando la pagina carga...
document.addEventListener("DOMContentLoaded", () => {
	// obtengo el mensaje
	const mensaje = document.querySelector('#mensaje').textContent;

	//imprimo el mensaje en el lbl del form
	if(document.querySelector('#mensaje_lbl')){
		if (mensaje != "{{MENSAJE}}"){
			document.querySelector('#mensaje_lbl').textContent=mensaje;
		}	
	} else if (document.querySelector('#mensaje_grande')) {
		if (mensaje != "{{MENSAJE}}"){
			document.querySelector('#mensaje_grande').textContent=mensaje;
		}	
	}
}); 