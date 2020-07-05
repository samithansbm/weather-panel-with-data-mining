console.log("ML component Launched!");

let xs = [];
let ys = [];

let m, c;

const learningRate = 0.2;
const optimizer = tf.train.sgd(learningRate);

function setup(){
    var can = createCanvas(800, 800);
    can.parent('graph-area');
    
    m = tf.variable(tf.scalar(random(1)));
    c = tf.variable(tf.scalar(random(1)));
}

function windowResized(){
    resizeCanvas(windowWidth, windowHeight);
}

function loss(predict, real){
    return predict.sub(real).square().mean();
} 

function predict(xs){
    const txs = tf.tensor1d(xs);

    // Applying to the formula y = mx + c
    const tys = txs.mul(m).add(c);
    // tys.print();
    return tys;
}

function mousePressed(){
    // let d = dist(mouseX, mouseY, 360, 200);
    if (mouseX > 0 && mouseX < 567 && mouseY > 0 && mouseY < 820){
        let x = map(mouseX, 0, width, 0, 1);
        let y = map(mouseY, 0, height, 1, 0);
    
        xs.push(x);
        ys.push(y);     
    }
    
}

function draw(){

    tf.tidy(()=>{
        if(xs.length>0){
            const tys = tf.tensor1d(ys);
            optimizer.minimize(()=> loss(predict(xs), tys));
            
        }
    });
    
    

    
    
    stroke(0);
    strokeWeight(10);
    clear();

    for(let i = 0; i < xs.length; i++){
        
        let px = map(xs[i], 0, 1, 0, width);
        let py = map(ys[i], 0, 1, height, 0);
        
        point(px, py);
    }

    //Drawing Line ---[Illustration]

    tf.tidy(()=>{
        const txs = [0 , 1];
        const tys = predict(txs);



        let x1 = map(txs[0], 0, 1, 0, width);
        let x2 = map(txs[1], 0, 1, 0, width);

        let lineY = tys.dataSync();
        strokeWeight(2);

        let y1 = map(lineY[0], 0, 1, height, 0);
        let y2 = map(lineY[1], 0, 1, height, 0);


        line(x1, y1, x2, y2);
    });

    


    var slope = m.dataSync();
    var intercept = c.dataSync();
    document.getElementById("slope").value = slope;
    document.getElementById("inter").value = intercept*10;


    var add = document.getElementById("btnmarkeradd");

    add.addEventListener('click', ()=>{
        sendDMMLData();
    });

}



function formEntered(){
    var loca = document.getElementById("loc_a").value;
    var locb = document.getElementById("loc_b").value;

    var slope = parseInt(locb) / parseInt(loca);

    // document.getElementById("slope").value = slope;

    xs.push(parseInt(loca));
    ys.push(parseInt(locb));

}






