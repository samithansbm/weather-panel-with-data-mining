let output = document.getElementsByClassName('output-text');
let buttons = document.getElementsByClassName('btn-tool');

for(let btn of buttons){
    btn.addEventListener('click', ()=>{
        // console.log('Click!');
        let command = btn.dataset['command'];
        
        if(command=='createlink'){
            let url = prompt("Enter Hyperlink address: ", "http:\/\/");
            document.execCommand(command, false, url);

        } else {
            document.execCommand(command, false, null);

        }
    });
}