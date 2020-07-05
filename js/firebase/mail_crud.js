function mail_launch(){
    // Collections
    const refMail = firestore.collection('mail_box');
    return refMail;
}

function getMailsFire(col, val, container) {
    const refMail = firestore.collection('mail_box');
    const box = document.getElementById(container);

    refMail.where(col, "==", val).get().then((snaps)=>{
        var count = 0;
        snaps.forEach((row)=>{
            
            console.log("Process is going on!"+ row.data());
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
            access.href = "mail_single.php?box="+container+"&id="+row.id;

            num.innerHTML = count;
            if(container=="inbox"){
                name.innerHTML = row.data().sender;
            } else {
                name.innerHTML = row.data().receiver;
            }
            
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
                updateFire('accepted', true, row.data().username);
            };
            
            d_link.onclick = function(){ 
                deleteFire(row.data().username);
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

            console.log("Process is going on!");
            

        });
    });
}


function getMailFire(id, cat){
    const refMail = firestore.collection('mail_box');
    

    refMail.doc(id).get().then((result)=>{
        console.log(result.data());
        const message = document.getElementById("message");
        const title = document.getElementById("title");
        const own = document.getElementById("own");
        const dated = document.getElementById("dated");

        message.innerText = result.data().content;
        title.innerText = result.data().title;
        dated.innerText = result.data().date.toDate();


        if(cat=="inbox"){
            own.innerText = result.data().sender;
        } else {
            own.innerText = result.data().receiver;
        }
        
    });
}

function deleteMail(id){
    const refMail = firestore.collection('mail_box');
    refMail.doc(id).delete().then(()=>{window.location.href="mails.php"});
}