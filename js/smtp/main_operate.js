function smtpMail(sender_add, receiver_add, topic, content){
    Email.send({
        SecureToken : "3d988c99-222f-40ef-962f-8f78b1f1072e",
        To : receiver_add,
        From : sender_add,
        Subject : topic,
        Body : content
    }).then(
    message => alert(message)
    );
}

function getInputsSMTP(content, sender, receiver){
    let titleval = document.getElementById('inputTitle');
    let keysval = document.getElementById('inputKey');
    
    title = titleval.value;
    keys = keysval.value;

    var send_mail, recev_mail;

    const refUser = firestore.collection('user');
    refUser.where("username", "==", sender).get().then((results)=>{
        results.forEach((row)=>{
            send_mail = row.data().email;
        });
    }).then(()=>{
        refUser.where("username", "==", receiver).get().then((results)=>{
            results.forEach((row)=>{
                recev_mail = row.data().email;
            });
        }).then(()=>{console.log(send_mail +" | " + recev_mail)})
    });
}
