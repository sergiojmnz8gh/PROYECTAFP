fetch("url/obtenerToken.php").
    then(response => response.json()).
    then((json)=>{
        sessionStorage.setItem("token", json.token);
    })


//Cómo hacer la petición
let f = new FormData(formulario);
fetch("url"), {
    headers: {Authorization: 'Bearer {' + sessionStorage.getItem("token") + '}'},
    method: "POST",
    body: f
}
