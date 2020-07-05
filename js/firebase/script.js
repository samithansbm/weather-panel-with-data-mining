// export 
var firestore;
// export 
function initFirestore(){

    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyD8ip5VifiFdp4U6MQA9rlrOrElPAsvqlI",
        authDomain: "weatheralpha-242b9.firebaseapp.com",
        databaseURL: "https://weatheralpha-242b9.firebaseio.com",
        projectId: "weatheralpha-242b9",
        storageBucket: "weatheralpha-242b9.appspot.com",
        messagingSenderId: "840870070508",
        appId: "1:840870070508:web:956e8aa39a04da96573dc9",
        measurementId: "G-882F4EPZ5Q"
    };
    // Initialize Firebase, FireStore

    //Initialize Firestore
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
    firestore = firebase.firestore();
    

    console.log("FireStore Initialized!");

}

initFirestore();

var gotURL = "";

//Collection References
const refUser = firestore.collection('user');
const refArticle = firestore.collection('article');
const refDM = firestore.collection('disaster_identification_team');
const refMail = firestore.collection('mail_box');

//DOM Elements declaration 

//User Profile
const prof = document.querySelector('.user-img');
const uname = document.querySelector('.user-name');
const role = document.querySelector('.user-role');
const summary = document.getElementById('desc');
const email = document.getElementById('email');
const contact = document.getElementById('contact');
const status = document.getElementById('status-up');

// Admin Pannel
const admin_table = document.getElementById('admin-list');

function addFire(id){
    const val = document.getElementById('status').value;
   

    refUser.where("username", "==", id).get().then((results)=>{
        results.forEach((row)=>{
            console.log(val);
            refUser.doc(row.id).collection('status').doc().set({
                description:val,
                date: firebase.firestore.FieldValue.serverTimestamp()
            });
          
        });
      }).catch((error)=>{
        alert("Connectivity Issue. Try again Later!");
      });
}

function uploadImFire(id){
    const upload = document.getElementById('upload').files[0];
    var name = upload.name;
                
    var storage = firebase.storage().ref('resoures/users/'+name);

    var uploadTask = storage.put(upload);
    uploadTask.on('state_changed', function(snap){
    var progress = (snap.bytesTranferred/snap.totalBytes);
    console.log("Progress is"+progress);
    }, function(error){
        console.log(error);
        }, async function(){

            refUser.where("username", "==", id).get().then((results)=>{
                results.forEach((row)=>{
                   
                    refUser.doc(row.id).collection('profile').doc('image').set({
                        icon: 'resoures/users/'+name
                    });
                  
                });
              });
            });

    console.log("Upload Activity completed!");
    // setInterval(location.reload(), 12000); --------------[FLAG] ---- Can't Update Image After Upload |
    //setTimeout(location.reload(), 120000);
}

function getImFire(doc){
    var storage = firebase.storage().ref(doc).getDownloadURL().then(function(url){
        var xhr = new XMLHttpRequest();
        xhr.responseType = 'blob';
        xhr.onload = function(event) {
          var blob = xhr.response;
        };
        xhr.open('GET', url);
        xhr.send();

        gotURL = url;
        prof.style.backgroundImage = `url('${gotURL}')`; 
        
    }).catch((error)=>{
        alert("Connectivity Issue. Try again Later!");
    });
}

function getUser(doc){
    console.log(doc);
}

function getProfile(user){
    refUser.where("username", "==", user).get().then((snaps)=>{
        snaps.forEach((row)=>{
            
            uname.innerHTML = row.data().username;
            role.innerHTML = row.data().role;
            summary.innerHTML = (row.data().summary==null?"No Information Available":row.data().summary);
            email.innerHTML = row.data().email;
            contact.innerHTML = row.data().contact;

            //Get User Image 
            refUser.doc(row.id).collection('profile').doc('image').get().then((snap)=>{
                getImFire(snap.data().icon)
            }).catch((error)=>{
                console.log("This User doesen't have profile avatar!");
            });

            //Get User Status
            refUser.doc(row.id).collection('status').get().then((snaps)=>{

                snaps.forEach((strow)=>{
                    let item = document.createElement('li');
                    status.appendChild(item);
                    item.innerHTML = strow.data().description;
                    status.appendChild(item);
                });
            });
        });
        //setInterval(getUser(snaps.data()), 5000);
    });
}

function getDataFire(col, val, cc) {
    
    refUser.where(col, "==", val).get().then((snaps)=>{
        var count = 0;
        snaps.forEach((row)=>{
            count++;
            let item = document.createElement('li');
            admin_table.appendChild(item);

            let num = document.createElement('span');
            var name = document.createElement('span');
            let cat = document.createElement('span');
            let approve = document.createElement('span');
            let decline = document.createElement('span');



            num.innerHTML = count;
            name.innerHTML = row.data().username;
            // name.id = "user-rec " + count;

            cat.innerHTML = row.data().role;
            // approve.innerHTML = "Approve";
            // decline.innerHTML = "Decline";



            let a_link = document.createElement('a');
            let d_link = document.createElement('a');

            let a_icon = document.createElement('i');
            let d_icon = document.createElement('i');

            a_icon.className = "far fa-check-square";
            d_icon.className = "far fa-window-close";

            if(cc){
                a_icon.onclick = function(){
                    refDM.doc(cc).get().then((result)=>{
                        // console.log(result);
                        refDM.doc(cc).collection("members").where("name", "==",row.data().username).get().then((available)=>{
                            available.forEach((item)=>{
                                if(item.data()){
                                    console.log(item.data());
                                } else {
                                    console.log("Test");
                                }
                                
                            });
                            
                        });
                        
                        // console.log("LOG SD");
                        // refDM.doc(cc).collection("members").add({
                        //     name: row.data().username
                        // });
                        // console.log(row.data().username);
                            
                            
                    }); 
                }

            } else {
                a_icon.onclick = function(){
                    updateFire('accepted', true, row.data().username);
                };
            } 
            
            if(cc){

            } else {
                d_link.onclick = function(){ 
                    deleteFire(row.data().username);
                };
            }
            
            
            a_link.appendChild(a_icon);
            d_link.appendChild(d_icon);

            approve.appendChild(a_link);
            decline.appendChild(d_link);

            item.appendChild(num);
            item.appendChild(name);
            item.appendChild(cat);
            item.appendChild(approve);
            item.appendChild(decline);

            

        });
    });
}

function addArticleFire(title, content, keys, user, receiver, mode){

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
                })
                .finally(()=>{
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
        })
        .finally(()=>{
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
            console.log("Error has occured!"+ error);
        }).finally(()=>{
            window.location.href = "articles.php";
        });
    }
}

function getArticleFire(){
    const card_contain = document.getElementById('card-cap');
    refArticle.get().then((snaps)=>{
        snaps.forEach((result)=>{

            refUser.where("username", "==", result.data().writer).get().then((snips)=>{
                snips.forEach((res)=>{
                    console.log(res.id);
                    refUser.doc(res.id).collection('profile').doc('image').get().then((details)=>{
                        console.log(details.data());
                        var output;
                        getImageFire(details.data().icon, output, result, res, card_contain, "article");

                        

                    }).catch((val)=>{
                        console.log(val);
                    }).finally((value)=>{
                        console.log("GOG");
                        // user_img.src = output;
                    });
                    
                });
            });

            console.log(result.data().title);

        });
    });
    
}

function displayArticleFire(docid){
    refArticle.doc(docid).get().then((results)=>{
        console.log(results.data());
        const topic = document.getElementById("topic");
        const contain = document.getElementById("contain-text");
        const author = document.getElementById("author");

        topic.innerHTML = results.data().title;
        contain.innerHTML = results.data().content;
        author.innerHTML = results.data().writer;
    });
}

function updateFire(col, val, user){
    refUser.where('username','==', user).get().then((snaps)=>{
        snaps.forEach((row)=>{
            refUser.doc(row.id).update({
                accepted: true
            });

            alert("Account accepted for ",user);
        });
    });
}

function deleteFire(user){
    refUser.where("username", "==", user).get().then((results)=>{
        results.forEach((row)=>{
            refUser.doc(row.id).delete().then(()=>{
            alert("User Deleted Completely!");
          }).catch((err)=>{
            console.log("Error has occured! Error", err);
          })
        });
      })
}


function getImageFire(urlreq, output, result, res, card_contain, purpose){
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
      console.log("URL:",output,"|Real URL:",url);

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
                        
                        console.log(card_contain);
                        card_contain.appendChild(card);

    }).catch(function(error){
        console.log(error);
    });
    
}

function addDMFire(user){
    
    var dname, dspe, dbrief;
    var geo, hydro, clim, meteo, bio; 
    var isgeo, ishydro, isclim, ismeteo, isbio; 

    let dmname = document.getElementById('inputName');
    let dmspe = document.getElementById('inputSpe');
    let dmbrief = document.getElementById('inputBrief');

    geo = document.getElementById("geo");
    hydro = document.getElementById("hydro");
    clim = document.getElementById("clim");
    meteo = document.getElementById("meteo");
    bio = document.getElementById("bio");
    

    if(geo.checked){
        isgeo = true;
    } else {
        isgeo = false;
    }

    if(hydro.checked){
        ishydro = true;
    } else {
        ishydro = false;
    }

    if(clim.checked){
        isclim = true;
    } else {
        isclim = false;
    }

    if(meteo.checked){
        ismeteo = true;
    } else {
        ismeteo = false;
    }

    if(bio.checked){
        isbio = true;
    } else {
        isbio = false;
    }

    

    dname = dmname.value;
    dspe = dmspe.value;
    dbrief = dmbrief.value;


    refDM.add({
        created_by: user,
        name: dname,
        speciality: dspe,
        brief: dbrief,
        geophysical: isgeo,
        hydrological: ishydro,
        climatological: isclim,
        meteorological: ismeteo,
        biological: isbio
    }).catch((error)=>{console.log(error)})
    .finally(()=>{
        window.location.href="dm_list.php";
    });
}

function getDMsFire(){
    const card_contain = document.getElementById('card-cap');
    refDM.get().then((snaps)=>{
        snaps.forEach((result)=>{
            console.log(result.data().created_by);
            refUser.where("username", "==", result.data().created_by).get().then((snips)=>{
                snips.forEach((res)=>{
                    console.log(res.id);
                    refUser.doc(res.id).collection('profile').doc('image').get().then((details)=>{
                        var output;
                        if(details.data()){
                            getImageFire(details.data().icon, output, result, res, card_contain,"dm");
                        } else {
                            getImageFire('undefined', output, result, res, card_contain, "dm");
                        }
                        

                    }).catch((val)=>{
                        console.log(val);
                    }).finally((value)=>{
                        console.log("GOG");
                        // user_img.src = output;
                    });
                    
                });
            });

            
        });
    });
}

function displayDMFire(docid){
    refDM.doc(docid).get().then((results)=>{
        
        const topic = document.getElementById("topic");
        const contain = document.getElementById("contain-text");
        const creator = document.getElementById("create");
        const spe = document.getElementById("cat");

        topic.innerHTML = results.data().name;
        contain.innerHTML = results.data().brief;
        creator.innerHTML = results.data().created_by;
        spe.innerHTML = results.data().speciality;

        if(results.data().hydrological){
            console.log("Hydro");
        }

    });
}

function dmAddMember(id, sys_id, role){
    refDM.where("name", "==", dname).get().then((results)=>{
        results.forEach((row)=>{
            refDM.doc(row.id).collection("members").doc("MEM01").set({
                name:user
            });
        });
    });
}

function displayUser(id){
    refUser.where("username", "==", id).get().then((results)=>{
        results.forEach((row)=>{
            
            console.log(row.data());
        });
      }).catch((error)=>{
        alert("Connectivity Issue. Try again Later!");
      });
}

// function getImageFire(urlreq, output){
//     //Froxy Url format
//     var proxy_url = 'https://cors-anywhere.herokuapp.com/';
    
//     var storage = firebase.storage().ref(urlreq).getDownloadURL().then(function(url){
//       var xhr = new XMLHttpRequest();
//       xhr.responseType = 'blob';
//       xhr.onload = function(event) {
//         var blob = xhr.response;
//       };
//       xhr.open('GET', url);
//       xhr.send();

//       output = url;
//       console.log("URL:",output,"|Real URL:",url);
//     }).catch(function(error){
//         console.log(error);
//     });
//     return output;
// }