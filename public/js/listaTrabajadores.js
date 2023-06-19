
function sendAsistance(userEventId, event) {
    event.preventDefault();

    var asistanceValue = event.target.value;

    axios.post('http://127.0.0.1:8000/user/event/update-asistance/' + userEventId, {
        asistance: asistanceValue
    })
        .then(function (response) {
            console.log("Actualización exitosa");
        })
        .catch(function (error) {
            console.log(error);
        });
}

function updateCoordinador(userEventId, isChecked) {
    event.preventDefault();
    axios.post('http://127.0.0.1:8000/user/event/update-coordinador/' + userEventId, {
        coordinador: isChecked ? '1' : '0' // Invertir el valor para mantener la estructura actual
    })
        .then(function (response) {
            console.log("Coordinador actualizado correctamente");
        })
        .catch(function (error) {
            console.log(error);
        });
}

function updateDriving(userEventId, isChecked) {
    event.preventDefault();
    axios.post('http://127.0.0.1:8000/user/event/update-driving/' + userEventId, {
        driving: isChecked ? '1' : '0'
    })
        .then(function (response) {
            console.log("Conductor actualizado correctamente");
        })
        .catch(function (error) {
            console.log(error);
        });
}

function updatePrivateCar(userEventId, isChecked) {
    event.preventDefault();
    axios.post('http://127.0.0.1:8000/user/event/update-privateCar/' + userEventId, {
        privateCar: isChecked ? '1' : '0'
    })
        .then(function (response) {
            console.log("Coche particular actualizado correctamente");
        })
        .catch(function (error) {
            console.log(error);
        });
}

function updateEstimatedHours(userEventId, value) {
    axios.post('http://127.0.0.1:8000/user/event/update-estimated-hours/' + userEventId, {
        estimatedHours: parseFloat(value)
    })
        .then(function (response) {
            console.log("Horario estimado actualizado correctamente");
        })
        .catch(function (error) {
            console.log(error);
        });
}

function updateExtraHours(userEventId, value) {
    axios.post('http://127.0.0.1:8000/user/event/update-extra-hours/' + userEventId, {
        extraHours: parseFloat(value)
    })
        .then(function (response) {
            console.log("Horas extra actualizadas correctamente");
        })
        .catch(function (error) {
            console.log(error);
        });
}