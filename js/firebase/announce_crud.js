
function sample(){
    console.log("GOIIA");
}

function getAlerts(col, cat){
    const refAlert = firestore.collection('alert');
    
    refAlert.where(col, "==", cat).orderBy("Date").get().then((results)=>{
        results.forEach((row)=>{
            //Data Equalations
        });
    });
}

function sendAlerts(col, cat){
    //Common Settings
    const refAlert = firestore.collection('alert');
    //Different Settings 
    if(col=="alert"){
        refAlert.doc("kalu_river_overflow").update({
            date: firebase.firestore.FieldValue.serverTimestamp(),
            message: "No message",
            type: col
        });
    }

}

function sendAlertswithLocation(receiver){
    const refUser = firestore.collection('user');
    const refArticle = firestore.collection('article');
    const refMail = firestore.collection('mail_box');

    let titleval = document.getElementById('title');
    let descval = document.getElementById('desc');
    var lat = document.getElementById('lat');
    var lng = document.getElementById('lng');
    let sender = document.getElementById('user');

    var title = titleval.value;
    var content = descval.value;
    var send = sender.value;
    var receiver = receiver;

    refUser.where("username","==", receiver).get().then((results)=>{
        results.forEach((row)=>{
            refUser.doc(row.id).collection("alerts").add({
                title: title,
                sender: send,
                receiver: receiver,
                latitude: parseFloat(lat.value),
                longitude:parseFloat(lng.value),
                content: content,
                date: firebase.firestore.FieldValue.serverTimestamp()
            }).catch((error)=>{
                console.log("Error has occured!"+ error);
            })
            .finally(()=>{
                window.location.href = "";
            });
        });
    });

}

function issueAnnounce(user, output){
    const refAlert = firestore.collection('alert');

    // Input Data
    var crowd = document.getElementById('inputCrowd').value;
    var valid = document.getElementById('inputTarget').value;
    var title = document.getElementById('inputTitle').value;
    // var content = document.getElementById('text-div');

    refAlert.add({
        title: title,
        sender: user,
        target: crowd,
        valid_for: valid,
        content: output,
        date: firebase.firestore.FieldValue.serverTimestamp()
    }).catch((error)=>{
        console.log("Error has occured!"+ error);
        alert("A error occured while submitting Announcement!");
    })
    .finally(()=>{
        alert("Your Announcemnt has Submitted!");
        window.location.href = "announcements.php";
    });
}

function getAnnouncements(col, val, container) {
    const refAlert = firestore.collection('alert');
    const box = document.getElementById(container);
    var arr = val;
    
    refAlert.where(col, "in", arr).orderBy("date", "desc").get().then((snaps)=>{
        var count = 0;
        snaps.forEach((row)=>{
            
            // Elapsed Time Calculation
            var elp = getElapsedTime(row.data().date.toDate());
            var valid = row.data().valid_for;

            var elapsed = elp / (24*3600*1000);
            var bal = 0;
            if(valid == "A day" && elapsed > 1){
                // Dele
                deleteAnnounce(row.id);
            } else if(valid == "A week" && elapsed > 7){
                // Delete
                deleteAnnounce(row.id);
            } else if(valid == "A month" && elapsed > 30){
                // Delete
                deleteAnnounce(row.id);
            }
            
            count++;
            let item = document.createElement('li');
            box.appendChild(item);

            let num = document.createElement('span');
            var name = document.createElement('span');
            let cat = document.createElement('span');
            let approve = document.createElement('span');
            let decline = document.createElement('span');

            let access = document.createElement('a');
            access.className = "btn btn-primary"
            access.innerText = "View";
            access.href = "announce_single.php?box="+container+"&id="+row.id;

            num.innerHTML = count;
            name.innerHTML = row.data().sender;
            
            name.appendChild(access);
            name.style.marginRight=10;
            

            cat.innerHTML = row.data().title;
            // approve.innerHTML = '2020-05-03';
            // decline.innerHTML = "Decline";

            

            let a_link = document.createElement('a');
            let d_link = document.createElement('a');

            let a_icon = document.createElement('i');
            let d_icon = document.createElement('i');

            a_icon.className = "far fa-check-square";
            d_icon.className = "far fa-window-close";

            a_icon.onclick = function(){
                
            };
            
            d_link.onclick = function(){ 
                
            };
            
            
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

function getElapsedTime(date){
    if(typeof date!=undefined){
        var now = new Date();
        var elapsed = now.getTime() - date.getTime();
    } else {
        console.log("Date invalid!");
    }

    return elapsed;
    
}

function getAnnounceFire(id, cat){
    const refAlert = firestore.collection('alert');
    

    refAlert.doc(id).get().then((result)=>{
        console.log(result.data());
        const message = document.getElementById("message");
        const title = document.getElementById("title");
        const own = document.getElementById("own");
        const dated = document.getElementById("dated");
        const valid = document.getElementById("valid");

        message.innerText = result.data().content;
        title.innerText = result.data().title;
        dated.innerText = result.data().date.toDate();
        own.innerText = result.data().sender;
        valid.innerText = result.data().valid_for;

        
        
    });
}

function deleteAnnounce(id){
    const refAlert = firestore.collection('alert');
    refAlert.doc(id).delete().then(()=>{console.log("Expired Announcements are deleted!")});
}