<div id="app" class="container"><h2 class="text-center mt-3">站點管理
<button class="btn btn-success" data-target="#busModal" data-toggle="modal" @click="openmodal">新增站點</button>
</h2>
<div class="modal fade" id="busModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{modaltitle}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <form @submit.prevent="submitbus">
            <td class="form-text"><h4>站點</h4></td>
            <td><input type="text" name="sitename" v-model="sitename" id="sitename" required class="form-control mt-2"></td></tr>
            <td class="form-text"><h4>已行駛時間</h4></td>
            <td><input type="number" name="driventime" min="0" v-model="driventime" id="driventime" size="40" required class="form-control mt-2"><p></p>
            <td class="form-text"><h4>停留時間</h4></td>
            <td><input type="number" name="stime" v-model="stime" min="0" id="stime" size="40" required class="form-control mt-2"><p></p>
            <button type="submit" class="btn btn-success btn-block">提交</button><p></p>
            <a href="admin.php"><button type="button" class="btn btn-dark btn-block" >回上頁</button></a>
        </td></tr>
            </div>
        </div>

</div>
</div></form>
<table class="table table-striped">
    <thead>
        <td>站點</td>
        <td>已行駛時間</td>
        <td>停留時間</td>
        <td>操作</td>
    </thead>
<tbody>
    <tr v-for="bus in buses" :key="bus.id">
        <td>{{bus.sitename}}</td>
        <td>{{bus.driventime}}</td>
        <td>{{bus.stime}}</td>
        <td>
        <button class="btn btn-warning" @click="update(bus)">修改</button>&ensp;
        <button class="btn btn-dark" @click="edit(bus.id)">刪除</button>
        </td>
    </tr>
</tbody>
</table>    </div>
<script>
    Vue.createApp({
        data(){
            return{
                buses:[],
                sitename:'',
                driventime:'',
                stime:'',
                currentbus:null,
                modaltitle:'新增站點',
            };
        },
        mounted(){
            this.fetchbuses();
        },
        methods:{
            fetchbuses(){
                fetch('./api/station.php')
                .then(response=>response.json())
                .then(data=>{
                    this.buses=data;
                });
            },
            submitbus(){
                if(this.currentbus){
                    this.updatebus();
                }else{
                    this.addbus();
                }
            },
            addbus(){
                fetch('./api/station.php',{
                    method:'POST',
                    header:{
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify({
                        sitename:this.sitename,
                        driventime:this.driventime,
                        stime:this.stime
                    })
                })
                .then(response=>response.json())
                .then(()=>{
                    this.fetchbuses();
                    this.resetForm();
                    $('#busModal').modal('hide');
                });
            },
            update(bus){
                this.currentbus=bus;
                this.sitename=bus.sitename;
                this.driventime=bus.driventime;
                this.stime=bus.stime;
                this.modaltitle='修改站點';
                $('#busModal').modal('show');
            },
            updatebus(){
                fetch('./api/station.php',{
                    method:'PUT',
                    header:{
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify({
                        id:this.currentbus.id,
                        sitename:this.sitename,
                        driventime:this.driventime,
                        stime:this.stime,
                    })
                })
                .then(response=>response.json())
                .then(()=>{
                    this.fetchbuses();
                    this.resetForm();
                    $('#busModal').modal('hide');
                });
            },
            edit(id){
                fetch('./api/station.php',{
                    method:'DELETE',
                    header:{
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify({id:id })
                })
                .then(response=>response.json())
                .then(()=>{
                    this.fetchbuses();
                });
            },
resetForm(){
this.sitename='';
this.driventime='';
this.stime='';
this.currentbus=null;
this.modaltitle='新增站點';
},
    openmodal(){
        this.resetForm();
        $('#busModal').modal('show');
    }        
        }
    }).mount("#app");
</script>