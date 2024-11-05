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

function insertPostType(valueName) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/manageAdmin/insertPostType");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`data=${valueName}`);
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
}

function insertCategory(valueName) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/manageAdmin/insertCategory");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`data=${valueName}`);
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
}

function deleteCategory(categoryId) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
    xhttp.open("GET", `/manageAdmin/deleteCategory/${categoryId}`, true);
    xhttp.send();
}

function deletePostType(postTypeId) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        if(datajson.status == 1){
            location.reload();
        }
    }
    xhttp.open("GET", `/manageAdmin/deletePostType/${postTypeId}`, true);
    xhttp.send();
}