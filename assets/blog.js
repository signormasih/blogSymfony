const xhttp = new XMLHttpRequest();
containerBlog = document.getElementById("containerBlog");
document.body.onload = getData();
function getData() {
    xhttp.onload = function() {
        datajson = JSON.parse(this.responseText)
        console.log(datajson);
        for(i = 0; i < datajson.count; i++){
            item = createRow(datajson[i].title, 
                             datajson[i].name, 
                             datajson[i].creator, 
                             datajson[i].category, 
                             datajson[i].posttype,
                             datajson[i].publishedAt,
                             datajson[i].id
                            );
            containerBlog.appendChild(item);
        }
    }
    xhttp.open("GET", `/blog/data`, true);
    xhttp.send();
}

function createRow(postTitle, postName, postCreator, postCategory, postType, postPublish, postId){
    const param = document.createElement("p");
    const link = document.createElement("a");

    param.classList.add("border");
    param.classList.add("p-3");
    param.classList.add("m-3");
    param.classList.add("rounded-lg");
    param.classList.add("flex");
    param.classList.add("flex-wrap");
    param.classList.add("justify-around");

    const spanTitle = document.createElement("div");
    spanTitle.innerHTML = postTitle;
    spanTitle.classList.add("text-lg");
    spanTitle.classList.add("font-bold");

    const spanName = document.createElement("span");
    spanName.innerHTML = postName;
    const spancreator = document.createElement("span");
    spancreator.innerHTML = postCreator;
    const spancategory = document.createElement("span");
    spancategory.innerHTML = postCategory;
    const spanpostType = document.createElement("span");
    spanpostType.innerHTML = postType;
    const spanPublish = document.createElement("span");
    spanPublish.innerHTML = postPublish;

    param.appendChild(spanTitle);
    param.appendChild(spanName);
    param.appendChild(spancreator);
    param.appendChild(spancategory);
    param.appendChild(spanpostType);
    param.appendChild(spanPublish);

    link.appendChild(param);
    link.href = `/post/${postId}`

    return link;
}