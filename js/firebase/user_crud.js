//User Profile
const prof = document.querySelector('.user-img');
const uname = document.querySelector('.user-name');
const role = document.querySelector('.user-role');
const summary = document.getElementById('desc');
const email = document.getElementById('email');
const contact = document.getElementById('contact');
const status = document.getElementById('status-up');

function addFire(id){
    const refUser = firestore.collection('user');
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
    const refUser = firestore.collection('user');
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
        console.log(error);
    });
}

function getUser(doc){
    console.log(doc);
}

function getProfile(user){
    const refUser = firestore.collection('user');
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
    const refUser = firestore.collection('user');
    
    // Admin Pannel
    const admin_table = document.getElementById('admin-list');

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
                const refDM = firestore.collection('disaster_identification_team');
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

function updateFire(col, val, user){
    const refUser = firestore.collection('user');
    refUser.where('username','==', user).get().then((snaps)=>{
        snaps.forEach((row)=>{
            refUser.doc(row.id).update({
                accepted: true
            });

            alert("Account accepted for "+user);
        });
    });
}

function deleteFire(user){
    const refUser = firestore.collection('user');
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