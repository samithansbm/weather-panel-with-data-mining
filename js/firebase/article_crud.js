

function addArticleFire(title, content, keys, user, receiver, mode){

    //Collection References
    const refUser = firestore.collection('user');
    const refArticle = firestore.collection('article');
    
    const refMail = firestore.collection('mail_box');

    let titleval = document.getElementById('inputTitle');
    let keysval = document.getElementById('inputKey');
    console.log("MODE"+mode);
    title = titleval.value;
    keys = keysval.value;

    if((mode=='alert')&&(receiver)){
        refUser.where("username","==", receiver).get().then((results)=>{
            results.forEach((row)=>{
                refUser.doc(row.id).collection("alerts").add({
                    title: title,
                    sender: user,
                    content: content,
                    date: firebase.firestore.FieldValue.serverTimestamp()
                }).catch((error)=>{
                    console.log("Error has occured!"+ error);
                    alert("Error has occured while adding Alert. Try again Later!");
                })
                .finally(()=>{
                    alert("Alert is issued completely");
                    window.location.href = "map.php";
                });
            });
        });
    } else if(receiver){
        refMail.add({
            title: title,
            sender: user,
            receiver: receiver,
            content: content,
            date: firebase.firestore.FieldValue.serverTimestamp()
        }).catch((error)=>{
            console.log("Error has occured!"+ error);
            alert("Error has occured while sending mail. Try again Later!");
        })
        .finally(()=>{
            alert("Mail is sended completely!");
            window.location.href = "map.php";
        });
        
    } else {
        refArticle.add({
            title: title,
            keys: keys,
            content: content,
            writer: user,
            date: firebase.firestore.FieldValue.serverTimestamp()
        }).catch((error)=>{
            alert("Error has occured while adding your Article. Try again Later!");
            console.log("Error has occured!"+ error);
        }).finally(()=>{
            alert("You'r Article is added!");
            window.location.href = "articles.php";
        });
    }
}

function getArticleFire(){
    const refArticle = firestore.collection('article');
    const refUser = firestore.collection('user');
    const card_contain = document.getElementById('card-cap');
    refArticle.orderBy("date", "desc").get().then((snaps)=>{
        snaps.forEach((result)=>{

            refUser.where("username", "==", result.data().writer).get().then((snips)=>{
                snips.forEach((res)=>{
                    refUser.doc(res.id).collection('profile').doc('image').get().then((details)=>{
                        var output;
                        getImageFire(details.data().icon, output, result, res, card_contain, "article");

                        

                    }).catch((val)=>{
                        console.log(val);
                    }).finally((value)=>{
                        // user_img.src = output;
                    });
                    
                });
            });
        });
    });
    
}

function displayArticleFire(docid){
    const refArticle = firestore.collection('article');
    refArticle.doc(docid).get().then((results)=>{
        
        const topic = document.getElementById("topic");
        const contain = document.getElementById("contain-text");
        const author = document.getElementById("author");
        const date = document.getElementById("d-ate");

        topic.innerHTML = results.data().title;
        contain.innerHTML = results.data().content;
        author.innerHTML = results.data().writer;
        date.innerHTML = results.data().date.toDate();
    }).catch((error)=>{alert("Error has occured while loading the article. Try again!");});
}


function getImageFire(urlreq, output, result, res, card_contain, purpose){
    const refArticle = firestore.collection('article');
    const refDM = firestore.collection('disaster_identification_team');
    //Froxy Url format
    var proxy_url = 'https://cors-anywhere.herokuapp.com/';
    
    var storage = firebase.storage().ref(urlreq).getDownloadURL().then(function(url){
      var xhr = new XMLHttpRequest();
      xhr.responseType = 'blob';
      xhr.onload = function(event) {
        var blob = xhr.response;
      };
      xhr.open('GET', url);
      xhr.send();

      output = url;

    //   Setting a DIV to contain DOM elements with data 
                        var card = document.createElement('div');
                        card.className = 'card';

                        var preview = document.createElement('div');
                        preview.className = 'preview';

                        var cat = document.createElement('h6');
                        

                        var userlink = document.createElement('a');
                        userlink.href="#";

                        var center = document.createElement('center');
                        var user_img = document.createElement('img');
                        user_img.className = "user-bubble";
                        user_img.src = output;

                        var br = document.createElement('br'); 

                        var unamee = document.createElement('span');
                        
                        
                        center.appendChild(user_img);
                        center.appendChild(br);
                        center.appendChild(unamee);

                        userlink.appendChild(center);

                        preview.appendChild(cat);
                        preview.appendChild(userlink);

                        card.appendChild(preview);

                        var contain = document.createElement('div');
                        contain.className = 'content';
                        var wrapper = document.createElement('div');
                        wrapper.className = 'wrapper';
                        var wrapper_border = document.createElement('div');
                        wrapper_border.className = 'wrap-border';
                        var info_broad = document.createElement('span');
                        info_broad.className = 'info-broad';
                        
                    
                        wrapper.appendChild(wrapper_border);
                        wrapper.appendChild(info_broad);

                        var title = document.createElement('h6');
                        
                        var sub_title = document.createElement('h2');
                        

                        var para = document.createElement('p');
                        
                        para.className = 'para';
                        var nav_button = document.createElement('a');
                        nav_button.className = 'btn-card-lg';
                        
                        

                        // ------------------INNERHTML----------------------------//

                        if(purpose == "article"){
                            cat.innerHTML = "POSTED BY:";
                            unamee.innerHTML = res.data().username;
                            info_broad.innerHTML = "Got these Information";
                            title.innerHTML = "ARTICLE";
                            sub_title.innerHTML = result.data().title;
                            para.innerHTML = result.data().content;
                            nav_button.innerHTML = "VISIT";
                            nav_button.href = 'article-single.php?article='+result.id;
                        } else if(purpose == "dm") {
                            cat.innerHTML = "CREATED BY:";
                            unamee.innerHTML = res.data().username;
                            info_broad.innerHTML = "Got these Information";
                            title.innerHTML = "DMS";
                            sub_title.innerHTML = result.data().name;
                            para.innerHTML = "today we will look at how to create a ui card design and";
                            nav_button.innerHTML = "VISIT";
                            nav_button.href = 'dm_systems.php?dms='+result.id;
                        }

                        
                        para.innerText = para.innerText.substring(0, 200);

                        // -------------------------------------------------------//

                        contain.appendChild(wrapper);
                        contain.appendChild(title);
                        contain.appendChild(sub_title);
                        contain.appendChild(para);
                        contain.appendChild(nav_button);

                        card.appendChild(preview);
                        card.appendChild(contain);
                        
                        card_contain.appendChild(card);

    }).catch(function(error){
        alert("Couldn't load image you requested!");
        console.log(error);
    });
    
}

function deleteArticle(id){
    const refArticle = firestore.collection('article');
    refArticle.doc(id).delete().then(()=>{window.location.href="articles.php"});
}