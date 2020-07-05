var firestore;
// export 
function initFirestore(){

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
      firestore = firebase.firestore();
    
    


    console.log("FireStore Initialized!");

}

