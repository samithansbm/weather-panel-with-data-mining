console.log("Forms Launched!");
// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyAbWnP6YfEdPsDSgmNZJE6pa86BOH6mkEM",
    authDomain: "addproadb.firebaseapp.com",
    databaseURL: "https://addproadb.firebaseio.com",
    projectId: "addproadb",
    storageBucket: "addproadb.appspot.com",
    messagingSenderId: "965456196408",
    appId: "1:965456196408:web:7efaf182ec8d6b079d07cd",
    measurementId: "G-BWNRQ0R9XW"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
  var firestore = firebase.firestore();

  //Creating Collection
  const refUsers = firestore.collection('user');

  //All Inputs
  const inputStaff = document.querySelector('#staff');
  const inputUsername = document.querySelector('#username');
  const inputName = document.querySelector('#name');
  const inputEmail = document.querySelector('#email');
  const inputPassword = document.querySelector('#password');
  const inputCPassword = document.querySelector('#cpassword');

  const register = document.querySelector('#btn_sub');

  const message = document.querySelectorAll('small');
  message.forEach((item)=>{
    item.style.display="none";
  });

  //All error message sects
  const vuname = document.querySelector('#un'); 
  const vname = document.querySelector('#na'); 
  const vaddress = document.querySelector('#ad'); 
  const vcontact = document.querySelector('#co'); 
  const vemail = document.querySelector('#em'); 
  const vpass = document.querySelector('#pa'); 
  const vcpass = document.querySelector('#cp'); 

  const vreguname = document.querySelector('#reguname'); 
  const vregpass = document.querySelector('#regpass'); 


  
  //Registration Process
  if(register!=null){
    console.log("ST1");
    var role = "Member";
    register.addEventListener('click', function(){
        console.log("ST1");
      if(inputStaff){
        role = "Staff";
      }
      console.log("ST1");
      //Validation Process
      if(inputUsername.value.length < 8){
        //   vuname.style.display="block";
        //   vuname.innerHTML = "Created Username is too short. Enter Username between 8 and 25 characters!";
          return;
      } else {
        //   vuname.style.display="none";
      }
    //   console.log("ST1");
    //   if(inputUsername.value.length > 25){
    //     //   vuname.style.display="block";
    //     //   vuname.innerHTML = "Created Username is too long. Enter Username between 8 and 25 characters!";
    //       return;
    //   } else {
    //     //   vuname.style.display="none";
    //   }

    //   if(inputName.value.length == 0){
    //     //   vname.style.display="block";
    //     //   vname.innerHTML = "Entering Name is necessary so don't keep Name field empty!";
    //       return;
    //   } else {
    //     //   vname.style.display="none";
    //   } 

    //   if(inputEmail.value.length == 0){
    //     //   vemail.style.display="block";
    //     //   vemail.innerHTML = "Enter a valid email address!";
    //       return;
    //   } else {
    //     //   vemail.style.display = "none"
    //   }

    //   if(inputPassword.value.length == 0){
    //     //   vpass.style.display="block";
    //     //   vpass.innerHTML = "Can't keep password field empty!";
    //       return;
    //   } else {
    //       vpass.style.display = "none"
    //   }

    //   if(inputPassword.value.length <= 8){
    //     //   vpass.style.display="block";
    //     //   vpass.innerHTML = "Password should contain more than 8 characters!";
    //       return;
    //   } else {
    //     //   vpass.style.display = "none"
    //   }
      

    //   if(inputPassword.value != inputCPassword.value){
    //     //   vcpass.style.display="block";
    //     //   vcpass.innerHTML = "Password dosen't match with Confirm password";
    //       return;
    //   } else {
    //     //   vcpass.style.display = "none"
    //   }

      console.log("ST2");
      
      const uname = inputUsername.value;
      const name = inputName.value;
      
      const email = inputEmail.value;
      const pass = inputPassword.value;
      const cpass = inputCPassword.value;

      
      //Adding data to table/Collection
      console.log("ST3");
      refUsers.add({
        username: uname,
        name: name,
        role: role,
        email: email,
        password: pass,
        accepted: false
      })
      .then(()=>{
        refUsers.where("username", "==", uname).get().then((results)=>{
            results.forEach((row)=>{
                alert("Profile added"); 
                console.log("ST4");
                refUsers.doc(row.id).collection('profile').doc('image').set({
                    icon: "resoures/users/usericon.png"
                });
                alert("Registration Process Complete! Your account is submitted for further validations.");
                window.location.href = "hold_page.html";
            });
        });
      })
      .catch((error)=>{
          console.log("Error"+error);
      })
    //   .finally(()=>{
          
    //   });

    });
  }
  
  
  const login = document.querySelector('#btn_log');
  const login2 = document.querySelector('#btn_log_min');

  if(login!=null){
    console.log("ST1");
    login.addEventListener('click', function(){
        
        const uname = inputUsername.value;
        const pass = inputPassword.value;

        //Validation Process
        if(inputUsername.value.length == 0){
            vreguname.style.display="block";
            vreguname.innerHTML = "Can't keep Password as Empty value!";
            return;
        } else {
            vreguname.style.display="none";
        }

        if(inputPassword.value.length == 0){
            vregpass.style.display="block";
            vregpass.innerHTML = "Can't keep Password as Empty value!";
            return;
        } else {
            vregpass.style.display="none";
        }


      

      console.log("Going to Enter these values");

      var get_pass, role = "Member";
      firestore.collection('user').where("username","==", uname).get().then(function(querySnapshot){
        querySnapshot.forEach(function(doc) {
          // doc.data() is never undefined for query doc snapshots
          get_pass = doc.data().password;
          role = doc.data().role;
          
          if((get_pass==pass)&&(doc.data().accepted==true)){
            console.log("Loging Successful!"+uname +" "+role);

            //PHP sessions
            window.location="../php/session.php?username="+uname+"&role="+role+"";
            
          } else if((get_pass==pass)&&(doc.data().accepted==false)){
            alert("Your account is not Approved Yet!");
            window.location.href = "hold_page.html";
          } else {
            alert("Entered Username or Password is Incorrect. Check again and Retype!");
          }
        });
      }).catch(function(error){
        console.log("Error getting documents: ", error);
        vregpass.style.display="block";
        vregpass.innerHTML = "Check your Username and Password";
      });
      
      
      
    });
  }

  if(login2!=null){
    login2.addEventListener('click', function(){

        const uname = inputUsername.value;
        const pass = inputPassword.value;

        //Validation Process
        if(inputUsername.value.length == 0){
            vreguname.style.display="block";
            vreguname.innerHTML = "Can't keep Password as Empty value!";
            return;
        } else {
            vreguname.style.display="none";
        }

        if(inputPassword.value.length == 0){
            vregpass.style.display="block";
            vregpass.innerHTML = "Can't keep Password as Empty value!";
            return;
        } else {
            vregpass.style.display="none";
        }


      

      console.log("Going to Enter these values");

      var get_pass, role = "Member";
      firestore.collection('user').where("username","==", uname).get().then(function(querySnapshot){
        querySnapshot.forEach(function(doc) {
          // doc.data() is never undefined for query doc snapshots
          get_pass = doc.data().password;
          role = doc.data().role;
          
          if((get_pass==pass)&&(doc.data().accepted==true)){
            console.log("Loging Successful!"+uname +" "+role);

            //PHP sessions
            window.location="php/session.php?username="+uname+"&role="+role+"";
            
          } else if((get_pass==pass)&&(doc.data().accepted==false)){
            alert("Your account is not Approved Yet!");
            window.location.href = "hold_page.html";
          } else {
            alert("Entered Username or Password is Incorrect. Check again and Retype!");
          }
        });
      }).catch(function(error){
        console.log("Error getting documents: ", error);
        vregpass.style.display="block";
        vregpass.innerHTML = "Check your Username and Password";
      });
      
      
      
    });
  }


  function getURL(){
    var url_string = "http://www.example.com/t.html?a=1&b=3&c=m2-m3-m4-m5"; //window.location.href
    var url = new URL(url_string);
    var c = url.searchParams.get("c");
    console.log(c);
  }
  
  

