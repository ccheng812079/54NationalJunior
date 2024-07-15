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
            <h2 class="text-center">網站管理-登入</h2>
            <td class="form-text"><h4>帳號</h4></td>
            <td><input type="text" name="username" v-model="this.username" id="username" required class="form-control mt-2"></td></tr>
            <td class="form-text"><h4>密碼</h4></td>
            <td><input type="password" name="password" v-model="this.password" id="password" required class="form-control mt-2"></td></tr>
            <td class="form-text"><h4>驗證碼</h4></td>
            <td><input type="text" name="veri" v-model="this.veri" id="veri" size="50" required class="form-control mt-2"><p></p>
        <div class="btn btn btn-info" id="check">{{check}}</div>&ensp;
        <button type="button" class="btn btn-primary" @click="regener">重新產生驗證碼</button><p></p>
        <button type="submit" class="btn btn-success btn-block" >登入</button>
        </td></tr>
        </table>
    </form>
</div>    
<script>
    Vue.createApp({
        data(){
            return{
                check:'',
                veri:'',
            };
        },
        methods:{
            submitform(){
                if(this.veri==this.check){
                    $.get("./api/loginDb.php",{username:this.username,password:this.password},(r)=>{
                        if(this.username!=="admin"){
                            alert("帳號錯誤")
                        }else if(this.password!=="1234"){
                            alert("密碼錯誤")
                        }else{
                            alert("登入成功")
                            location.href="admin.php"
                        }
                        conaole.log(r,this.username,this.password)
                    })
                }else{
                    alert("驗證碼錯誤")
                }
            },
            regener(){
                this.check=Math.floor(Math.random()*8999+1000);
            }
        },
        mounted(){
            this.check=Math.floor(Math.random()*8999+1000);
        }
    }).mount("#app");
</script>


</body>
</html>