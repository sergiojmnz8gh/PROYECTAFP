fetch("url/obtenerToken.php"). //Hacer api obtenerToken
    then(response => response.json()).
    then((json)=>{
        sessionStorage.setItem("token", json.token);
    })
//Esto va en un js suelto en la cabecera de landing-alumno y landing-empresa


//Cómo hacer la petición
let f = new FormData(formulario);
fetch("url"), {
    headers: {Authorization: 'Bearer {' + sessionStorage.getItem("token") + '}'},
    method: "POST",
    body: f
}
