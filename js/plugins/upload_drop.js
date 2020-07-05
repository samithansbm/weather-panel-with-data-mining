const drop_zone = document.querySelector('.drop-zone');
const btn_upload = document.getElementById('upload-btn');
const progress = document.getElementById('bar-fill');
const progress_txt = document.getElementById('bar-fill-text');

var fileset = [];

function dropBox(){
    "use strict";

    var startUp = function(files){
        // console.log(files);
        app.uploader({
            files: files,
            progressBar: progress,
            progressText: progress_txt,
            processor: 'php/upload.php',

            finished: function(data){
                var x;
                var file;
                var filestatus;
                var fileanchor;
                var current;
                
                var filecontain = document.getElementById('result-list');
                // console.log("Hii" +data.length);
                for(x=0; x<data.length; x++){
                    console.log("Hii");
                    current = data[x];
                    fileanchor = document.createElement('a');
                    file = document.createElement('label');
                    file.textContent = current.name;

                    filestatus = document.createElement('span');
                    filestatus.textContent = current.uploaded ? 'Uploaded' : 'Failed';

                    var br = document.createElement('br');
                    var hr = document.createElement('hr'); 

                    if(current.uploaded){
                        fileanchor.href = "../../uploads/"+ current.file;
                    }

                    fileanchor.appendChild(file);
                    fileanchor.appendChild(filestatus);
                    fileanchor.appendChild(br);
                    fileanchor.appendChild(hr);

                    filecontain.appendChild(fileanchor);

                }
            },
            error: function(){
                console.log("An Error");
            }
        });
    }

    // Form Upload
    btn_upload.addEventListener('click', function(event){
        var stfiles = document.getElementById('upload-file').files;
        event.preventDefault();

        // Firestore Send
        for(var i = 0; i < fileset.length; i++){
            sendFiles(getUrlVars()["dms"], fileset[i]);
        }

        startUp(stfiles);

    });

    
    drop_zone.addEventListener('dragover', function(){
        this.className = "drop-zone over";
    });

    drop_zone.addEventListener('dragleave', function(){
        this.className = "drop-zone";
    });

    drop_zone.ondrop = function(event) {
        event.preventDefault();
        console.log(event.dataTransfer.files);
        startUp(event.dataTransfer.files);
        

        for(var i=0; i<event.dataTransfer.files.length; i++){
            fileset.push(event.dataTransfer.files[i].name);
        }
        console.log(fileset);
    };

    
}

dropBox();