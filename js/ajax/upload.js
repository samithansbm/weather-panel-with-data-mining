var app = app || {};

(function(o){
    "user strict";

    // Private methods
    var ajax, getFormData, setProgress;

    ajax = function(data){
        // console.log(data);
        var xmlhttp = new XMLHttpRequest();
        var uploaded;


        // Status Error Handling
        xmlhttp.addEventListener('readystatechange', function(){
            if(this.readyState === 4){
                if(this.status === 200){
                    // console.log("Status Fine");

                    uploaded = JSON.parse(this.response);
                    if(typeof o.options.finished === 'function'){
                        console.log(uploaded.length);
                        o.options.finished(uploaded);
                    }


                } else {
                    if(typeof o.options.error === 'function'){
                        o.options.error();
                    }
                }
            }
        });

        xmlhttp.upload.addEventListener('progress', function(event){
            var percent;

            if(event.lengthComputable === true){
                percent = Math.round((event.loaded/event.total)*100);
                setProgress(percent);
            }
        });

        xmlhttp.open('post', o.options.processor);
        xmlhttp.send(data);
    };

    getFormData = function(source){
        
        var data = new FormData();
        var i;

        for(i=0; i < source.length; ++i){
            data.append('files[]', source[i]);
        }

        return data;
    };

    setProgress = function(value){
        if(o.options.progressBar !== undefined){
            o.options.progressBar.style.width = value ? value + '%' : 0;
        }

        if(o.options.progressText !== undefined){
            o.options.progressText.textContent = value ? value + '%' : 0;
        }
    };

    o.uploader = function(options){
        o.options = options;
        
        if(o.options.files !== undefined){
            // getFormData(o.options.files);
            ajax(getFormData(o.options.files));
        }
    };

})(app);