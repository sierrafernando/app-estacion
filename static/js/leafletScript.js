const map = L.map('map').setView([-27.4692131, -58.8306349], 2);

const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 19
}).addTo(map);


/* Obtenemos el listado de datos */
loadTracker().then( info => {

	/* Recorremos la lista por fila */
	info.forEach( fila => {

		/* Recuperamos la información necesaria para colocar los marcadores */
		let latitud = fila["latitud"];
		let longitud = fila["longitud"];
		let ip = fila["ip"];
		let accesos = fila["visitas"];

		/* Genera un marcador con un popup dentro del mapa*/
		const marker = L.marker([latitud, longitud]).addTo(map)
		.bindPopup(ip+': '+accesos+' accesos.')
		.openPopup();
	})
})

/**
 * 
 * Función asincrona para acceder al listado que tiene las latitudes
 * y longitudes a pintar como marcadores en el mapa
 * 
 * */
async function loadTracker(){
	const response = await fetch("https://mattprofe.com.ar/alumno/3893/app-estacion/api/tracker/list-clients-location");
	const data = await response.json();

	return data;
}
