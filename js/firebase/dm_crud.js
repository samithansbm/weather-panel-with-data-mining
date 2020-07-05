

function addDMFire(user){
    const refDM = firestore.collection('disaster_identification_team');
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
    }).catch((error)=>{
        alert("Error has occured while adding Disaster Management Group. Try Again!");
        console.log(error)
    })
    .finally(()=>{
        alert("You have completely added a Disaster Mangement Group");
        window.location.href="dm_list.php";
    });
}

function getDMsFire(){
    const refUser = firestore.collection('user');
    const refDM = firestore.collection('disaster_identification_team');
    const card_contain = document.getElementById('card-cap');
    refDM.get().then((snaps)=>{
        snaps.forEach((result)=>{

            if(result.data().created_by!=undefined){
                refUser.where("username", "==", result.data().created_by).get().then((snips)=>{
                    snips.forEach((res)=>{
                       
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
                            
                            // user_img.src = output;
                        });
                        
                    });
                });

            }
            
        });
    });
}

function displayDMFire(docid, user){
    const refDM = firestore.collection('disaster_identification_team');
    refDM.doc(docid).get().then((results)=>{
        
        const topic = document.getElementById("topic");
        const contain = document.getElementById("contain-text");
        const creator = document.getElementById("create");
        const spe = document.getElementById("cat");

        const upload = document.getElementById("up-sect");
        const add = document.getElementById("btn_add");
        upload.style.display = "none";
        add.style.display = "none";
        
            topic.innerHTML = results.data().name;
            contain.innerHTML = results.data().brief;
            creator.innerHTML = results.data().created_by;
            spe.innerHTML = results.data().speciality;
        
            if(user == results.data().created_by){
                upload.style.display = "block";
                add.style.display = "block";
            }
        

        if(results.data().hydrological){
            // console.log("Hydro");
        }

    }).catch((error)=>{
        alert("Error has occured while loading Disaster Management Group. Try again!");
        console.log(error);
    });
}

function sendDMMLData(){
    const refDM = firestore.collection('disaster_identification_team');
    var m = document.getElementById('slope').value;
    var c = document.getElementById('inter').value;
    refDM.doc("ML").set({
        slope: m, 
        intercept: c
    }).catch((error)=>{
        alert("Error has occured while sending Machine Learning Component Data. Try again!");
        console.log(error);
    })
    .finally(()=>{
        alert("Machine Learning Component data has uploaded!");
        window.location.href=""
    });
}


// ================================================|| Incomplete ||======================================================
function dmAddMember(id, sys_id, role){
    refDM.where("name", "==", dname).get().then((results)=>{
        results.forEach((row)=>{
            refDM.doc(row.id).collection("members").doc("MEM01").set({
                name:user
            });
        });
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
            

            if(cc != "dms"){
                let decline = document.createElement('span');
            }

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
                    refDM.doc(cc).collection("members").add({
                        name: row.data().username
                    });
                    alert(row.data().username+" is added to Group!");
                        // refDM.doc(cc).collection("members").where("name", "==",row.data().username).get().then((available)=>{
                        //     if(typeof available==undefined){
                        //         console.log(item.data());
                        //     } else {
                        //         console.log("Test");
                        //     }
                        //     available.forEach((item)=>{
                                
                                
                        //     });
                            
                        // });     
                    
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

            
            

            item.appendChild(num);
            item.appendChild(name);
            item.appendChild(cat);
            item.appendChild(approve);
            
            if(typeof decline==undefined){
                decline.appendChild(d_link);
                item.appendChild(decline);
            }

            

        });
    });
}

function displayUser(id){
    const refUser = firestore.collection('user');
    refUser.where("username", "==", id).get().then((results)=>{
        results.forEach((row)=>{
            
        });
      }).catch((error)=>{
        alert("Connectivity Issue. Try again Later!");
      });
}

function displayGroupMembers(dm){

    var main = document.getElementById('card-lg');
    
    const refUser = firestore.collection('user');
    const refDM = firestore.collection('disaster_identification_team');
    refDM.doc(dm).collection('members').get().then((members)=>{
        members.forEach((member)=>{
            
            refUser.where("username", "==", member.data().name).get().then((snaps)=>{
                snaps.forEach((snap)=>{
                    
                    var batch = document.createElement('div');
                    batch.className = "batch";
                    var user_img = document.createElement('div');
                    user_img.className = "user-img";
                    var name = document.createElement('span');
                    name.className = "user-name";
                    var br = document.createElement('br');
                    var role = document.createElement('span');
                    role.className = "user-role";
                    var sm_role = document.createElement('small');
                    var det_p = document.createElement('p');
                    var con_p = document.createElement('p');
                    var email = document.createElement('p');
                    var button = document.createElement('a');
                    button.className = "btn btn-primary";

                    name.innerText = snap.data().username;
                    sm_role.innerText = snap.data().role;
                    det_p.innerText = snap.data().name;
                    con_p.innerText = snap.data().contact;
                    email.innerText = snap.data().email;

                    button.innerText = "Visit";
                    button.href = "user_pannel.php?user="+snap.data().username;

                    batch.appendChild(user_img);
                    batch.appendChild(name);
                    batch.appendChild(br);
                    role.appendChild(sm_role);
                    batch.appendChild(role);
                    batch.appendChild(det_p);
                    batch.appendChild(con_p);
                    batch.appendChild(email);
                    batch.appendChild(button);

                    main.appendChild(batch);
                });
            });
        });
        
    });
}

function sendFiles(dm, fname){
    const refDM = firestore.collection('disaster_identification_team');
    refDM.doc(dm).collection("Documents").add({
        file_name: fname,
        date: firebase.firestore.FieldValue.serverTimestamp()
    }).catch((error)=>{
        console.log("Error has occured while sending Data. Try again!");
    });
}

function showFiles(dm){
    var list = document.getElementById("docs-list");
    const refDM = firestore.collection('disaster_identification_team');
    refDM.doc(dm).collection("Documents").get().then((docs)=>{
        docs.forEach((doc)=>{
            var item = document.createElement('a');
            var item_label = document.createElement('label');
            item_label.innerText = doc.data().file_name;
            var span = document.createElement('span');
            var small = document.createElement('small'); 
            small.innerText = doc.data().date.toDate();
            var br = document.createElement('br');
            var hr = document.createElement('hr');

            item.href = "uploads/"+ doc.data().file_name;
            item.appendChild(item_label);

            list.appendChild(item);
            span.appendChild(small);
            list.appendChild(span);
            list.appendChild(br);
            list.appendChild(hr);
        });
    });
}




