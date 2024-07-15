<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>台北 101 接駁專車</title>
    <?php include 'link.php'; ?>
    <style>
    .map{
        padding-top:10em;
        display:grid;
        justify-content:center;
    }
    .mapIcon{
        position:absolute;
        width: 1em;
        height: 1em;
        background-color:var(--blue);
        border-radius:50%;
        box-shadow:0 0 0 .2em #fff, 0 0 0 .4em var(--blue);
        z-index:100;
    }
    .station{
        position:relative;
        width: calc(var(--w));
        height: 200px;
        display:flex;
        justify-content:center;
        align-items:flex-start;
    }
    .station::after,.station::before{
        position:absolute;
        content:"";
        width: calc(var(--w)/2);
        height:1em;
        background-color:var(--info);
    }
    .station::after{
        left:0;
    }
    .station::before{
        right:0;
    }
    .row{
        position:relative;
        width: calc(65vw - 20em);
        margin-top:-1em;
        display:flex;
    }
    .left-row{
        flex-direction:row-reverse;
    }
    .right-row{
        flex-direction:row;
    }
    .row:first-of-type>:nth-child(2)::after,
    .row:last-of-type.left-row>:nth-last-child(2)::after,
    .row:last-of-type.right-row>:nth-last-child(2)::before{
        content:none;
    }
.border-left,.border-right{
    position:absolute;
    width: 1em;
    height: 200px;
    background-color:var(--info);
    display:none;
}
.border-left{
    left:-10px;
    border-radius:10px;
}
.border-right{
    right:-10px;
    border-radius:10px;
}
.left-row>.border-left{
display:inline;
}
.right-row>.border-right{
display:inline;
}

.right-row:last-of-type>div:last-child,
.left-row:last-of-type>div:first-child{
    display:none;
}

.mapIcon~.data .busData{
    max-width:var(--w);
    transform:translateY(-12em);
    opacity:0;
    z-index:0;
}
.mapIcon:hover~.data .busData{
    opacity:1;
    z-index:200;
}
</style>

</head>

<body>
    <?php include 'header.php'; ?>
  <div id="app" class="container mt-5">
    <div class="card p-1 shadow w-25">
        <label for="busRange" class="text-center">地圖每列顯示
            <select v-model="data.row" class="custom-select" style="width:auto;">
                <option :value="idx" v-for="idx in 5">{{idx}}站</option>
            </select>
        </label>
        <input type="range" min="1" max="5"   v-model="data.row" class="custom-range" id="busRange" @click="getData">
    </div>
    <div class="map shadow rounded mt-5" style="width:65vw; height:50vh; overflow:auto;" :style="{'--w':`calc((65vw - 20em)/${data.row})`}">
        <div class="row" v-for="(row,i) in data.data" style="width:65vw - 20em; display:flex;" :class="i%2?'left-row':'right-row'">
<div class="border-left"></div>
<div class="station" v-for="(item,idx) in row">
    <div class="mapIcon"></div>
    <div class="data text-center">
        <p style="transform:translateY(-4em);" :class="item.bus[0].textColor" v-html="item.bus[0].htmlContent"></p>
        <button class="btn btn-outline-dark">{{item.sitename}}</button>
        <div class="card p-1 busData shadow">
<p v-for="bus in item.bus" class="m-0" :class="bus.textColor" v-html="bus.htmlContentForCard"></p>

    </div>
</div>
</div>
<div class="border-right"></div>
        </div>
    </div>
  </div>


<script>
    const{
        createApp,
        reactive,
        onMounted
    }=Vue
createApp({
    setup(){
        const data=reactive({
            row:3,
            data:[]
        })
        let station,busData=undefined
        const procData=()=>{
            if(!station || typeof bus==="undefined")return;
            let alltime=0;
            console.log("Starting procData");
            station.forEach((stationInfo,idx)=>{
                alltime+=stationInfo.driventime-stationInfo.stime;
                stationInfo.bus=[];
                bus.forEach((busInfo)=>{
                    let busCopy={
                        ...busInfo
                    };
                    if(busCopy.driventime<=alltime){
                        if(busCopy.driventime>=alltime-stationInfo.stime){
                            busCopy.textColor="text-dark";
                            busCopy.htmlContent=busCopy.busnum+"已到站";
                            busCopy.htmlContentForCard=busCopy.busnum+"已到站";
                        }else{
                            busCopy.textColor="text-danger";
                            busCopy.relArri=alltime-stationInfo.stime-busCopy.driventime;
                            busCopy.htmlContent=busCopy.busnum+"約"+busCopy.relArri+"分鐘";
                            busCopy.htmlContentForCard=busCopy.busnum+"約"+busCopy.relArri+"分鐘";
                        }
                    }else{
                        busCopy.textColor="text-secondary";
                            busCopy.relArri=alltime-stationInfo.stime-busCopy.driventime;
                            busCopy.htmlContent=idx==0?"未發車":busCopy.busnum+"已過站";
                            busCopy.htmlContentForCard=idx==0?"未發車":busCopy.busnum+"已過站";
                    }
                    stationInfo.bus.push(busCopy);
                });
                stationInfo.bus.sort((a,b)=>a.relArri-b.relArri);
                stationInfo.bus.splice(3);
            });
            data.data=[];
            let rowCount=Math.ceil(station.length / data.row);
            for(i=0; i<rowCount; i++){
                data.data.push(station.slice(i* data.row,(i+1)* data.row));
            }
            console.log("Processed Data:",data.data);
        };
        const getData=async()=>{
            await $.getJSON("./api/station.php",{},(r)=>{
                console.log("bus Data:",r);
                station=structuredClone(r);
            });
            await $.getJSON("./api/bus.php",{},(r)=>{
                console.log("bus Data:",r);
                bus=structuredClone(r);
            });
        };
        onMounted(()=>{
            getData()
            setInterval(()=>{
                procData()
            },1)
        })
        return{
            data,
            getData,
            procData
        }
    }
}).mount("#app")
</script>
</body>

</html>