function deleteUser(userid) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
    xhttp.open("GET", `/manageAdmin/deleteUser/${userid}`, true);
    xhttp.send();
}
function upgradeUser(userid) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
    xhttp.open("GET", `/manageAdmin/upgradeUser/${userid}`, true);
    xhttp.send();
}

function downgradeUser(userid) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
    xhttp.open("GET", `/manageAdmin/downgradeUser/${userid}`, true);
    xhttp.send();
}