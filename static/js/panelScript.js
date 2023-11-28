// Evento que se ejecuta cuando se carga completamente la pagina
document.addEventListener("DOMContentLoaded", () => {

	// pedimos las estaciones
	loadEstaciones().then( data => {

		// recorremos el listado de estaciones
		data.forEach(function(element, index){

			// creamos los botones de estaciones
			addBtnEstacion(element)
		})
	})
})

// Peticion asincrona de la lista de estaciones
async function loadEstaciones(){
	const response = await fetch("https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations")
	const data = await response.json()
	return data
}

// Crea un nuevo boton con los datos de info
function addBtnEstacion(info){

	let tpl = document.querySelector("#tpl-btn-estacion");
	let clon = tpl.content.cloneNode(true);

	// cargamos los datos del boton clonado
	clon.querySelector(".btn-estacion").setAttribute("href", "./detalle/"+info.chipid);
	clon.querySelector(".estacion-ubicacion").innerHTML= '<i class="rojo material-symbols-outlined">location_on</i>'+'<h3>'+info.ubicacion+'<h3>';
	clon.querySelector(".estacion-visitas").innerHTML = '<h3>'+info.visitas+'</h3>'+'<i class="rojo material-symbols-outlined">rss_feed</i>'
	clon.querySelector(".estacion-apodo").innerHTML = '<h2>'+info.apodo+'</h2>';
	
	// Agrega un nuevo boton de estacion
	document.querySelector("#list-estacion").appendChild(clon)
}