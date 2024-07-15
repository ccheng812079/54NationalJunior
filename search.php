<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>台北101接駁專車</title>
    <?php include 'link.php';?>
</head>
<body>    <?php include 'header.php';?>
<div id="app" class="continer mt-5">
    <form @submit.prevent="submitform">
    <table class="w-30 mx-auto">
            <h2 class="text-center">班次查詢-信箱</h2>
            <td class="form-text"><h4>信箱</h4></td>
            <td><input type="email" name="email" v-model="email" id="email" size="40" required class="form-control mt-2"><p></p>
            <button type="submit" class="btn btn-success btn-block" >查詢</button>
        </td></tr></table>
</form>
<div class="mt-3" v-if="message">
    <p>{{message}}</p>
</div>
<div class="mt-3" v-if="busInfo">
    <h4>接駁車班次</h4>
    <p>{{busInfo.name}}</p>
    <p>信箱{{busInfo.email}}</p>
    <p>接駁車編號{{busInfo.bus_number}}</p>
    <p>乘客名單{{busInfo.passengers.join(',')}}</p>
</div>
<script>
    const app=Vue.createApp({
        data(){
            return{
                name:'',
                email:'',
                message:'',
                bus_number:'',
                busInfo:null
            };
        },
        methods:{
            submitform(){
                fetch('./api/result.php?email=${this.email}')
                .then(response=>response.json())
                .then(data=>{
                    if(data.message){
                        this.message=data.message;
                        this.busInfo=null;
                    }else{
                        this.busInfo=data;
                        this.message='';
                    }
                })
                .catch(error=>{
                    this.message='查詢發生錯誤';
                    this.busInfo=null;
                });
            }
        }
    });
    app.mount("#app");
</script>