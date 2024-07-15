<div id="app" class="container mt-4">
    <h1 class="mb-4 text-center">表單管理</h1>
    <div class="form-group">
        <label for="formStatus"><h4>是否啟用表單</h4></label>
        <select id="formStatus" class="form-control" v-model="formStatus">
            <option value="1">啟用</option>
            <option value="0">停用</option>
        </select>
    </div>
    <button @click="update" class="btn btn-success">更新設定</button>&ensp;
    <button @click="generate" class="btn btn-primary">生成接駁車</button>&ensp;
    <a href="search.php"><button class="btn btn-info">班次查詢</button></a>&ensp;
    <a href="form.php"><button class="btn btn-dark">接駁車意願調查</button></a><p></p>
    <h2 class="mt-4">當前需派遣接駁車輛數</h2>
    <p>{{ busCount }}</p>

    <h2 class="mt-4 text-center">新增參與者信箱</h2>
    <div class="form-group">
                <label for="email">信箱:</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
            </div>
    <button @click="addemail" class="btn btn-primary">新增信箱</button>
    <p>{{ addEmailMessage }}</p>
</div>
<hr>
<div id="api" class="container mt-4">
    <h2 class="mb-4 text-center">修改</h2>


    <!-- 模態框 -->
    <div class="modal fade" id="stationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- 模態框頭部 -->
                <div class="modal-header">
                    <h4 class="modal-title">{{ modalTitle }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- 模態框主體 -->
                <div class="modal-body">
                    <form @submit.prevent="submitStation">
                        <div class="form-group">
                            <label for="name">姓名:</label>
                            <input type="text" class="form-control" id="name" v-model="name" required>
                        </div>
                        <div class="form-group">
                            <label for="drivenTime">信箱:</label>
                            <input type="email" class="form-control" id="email" v-model="email" required>
                        </div>
                        <div class="form-group">
                            <label for="stopTime">車牌編碼 :</label>
                            <input type="number" class="form-control" id="bus_number" v-model="bus_number" required>
                        </div>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 站點列表表格 -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>姓名</th>
                <th>信箱</th>
                <th>車牌編碼</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="station in stations" :key="station.id">
                <td>{{ station.name }}</td>
                <td>{{ station.email }}</td>
                <td>{{ station.bus_number }}</td>
                <td>
                    <button class="btn btn-warning btn-sm m-1" @click="edit(station)">編輯</button>
                    <button class="btn btn-danger btn-sm m-1" @click="deleteStation(station.id)">刪除</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    const app = Vue.createApp({
        data() {
            return {
                formStatus: '1',
                busCount: 0,
                newEmail: '',
                addEmailMessage: ''
            };
        },
        mounted() {

            this.fetchsettings();
            this.fetchBus();
        },
        methods: {
            fetchsettings() {
                fetch('./api/form.php?type=settings')
                    .then(response => response.json())
                    .then(data => {

                        this.formStatus = data.form_enabled ? '1' : '0';
                    });
            },
            update() {
                fetch('./api/form.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        form_enabled: this.formStatus === '1'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                });
            },
            generate() {
                fetch('./api/generateBuses.php', {
                    method: 'PUT'
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    this.fetchBus();
                });
            },
            fetchBus() {
                fetch('./api/paricipants.php?type=count')
                    .then(response => response.json())
                    .then(data => {
                        this.busCount = Math.ceil(data.count / 50);
                    });
            },
            addemail() {
                fetch('./api/form.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        new_email: this.newEmail
                    })
                })
                .then(response => response.json())
                .then(data => {
                    this.addEmailMessage = data.message;
                });
            }
        }
    });
    app.mount('#app');
</script><script>
    Vue.createApp({
        data() {
            return {
                stations: [], // 站點列表
                name: '', // 站點名稱
                email: '', // 行駛時間
                bus_number: '', // 停留時間
                currentStation: null, // 當前編輯的站點
                modalTitle: '新增站點' // 模態框標題
            };
        },
        mounted() {
            this.fetchStations(); // 取得站點列表
        },
        methods: {
            fetchStations() { // 取得站點列表
                fetch('./api/addform.php')
                    .then(response => response.json()) // 取得回應
                    .then(data => {
                        this.stations = data; // 將取得的資料存入stations
                    });
            },
            submitStation() { // 提交站點
                if (this.currentStation) {
                    this.updateStation(); // 如果有當前編輯的站點，則更新站點
                } else {
                    this.addStation(); // 否則新增站點
                }
            },
            addStation() { // 新增站點
                fetch('./api/addform.php', {
                    method: 'POST', // 使用POST方法
                    headers: { // 設定標頭
                        'Content-Type': 'application/json' // 告訴伺服器要使用json格式
                    },
                    body: JSON.stringify({ // 將資料轉為json格式
                        name: this.name, // 站點名稱
                        email: this.email, // 行駛時間
                        bus_number: this.bus_number // 停留時間
                    })
                })
                .then(response => response.json()) // 取得回應
                .then(() => { // 成功後
                    this.fetchStations(); // 取得站點列表
                    this.resetForm(); // 重設表單
                    $('#stationModal').modal('hide'); // 關閉模態框
                });
            },
            edit(station) { // 編輯站點
                this.currentStation = station; // 將station存入currentStation
                this.name = station.name;
                this.email = station.email;
                this.bus_number = station.bus_number;
                this.modalTitle = '編輯站點'; // 設定模態框標題
                $('#stationModal').modal('show'); // 打開模態框
            },
            updateStation() { // 更新站點
                fetch('./api/addform.php', {
                    method: 'PUT', // 使用PUT方法
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: this.currentStation.id,
                        name: this.name,
                        email: this.email,
                        bus_number: this.bus_number
                    })
                })
                .then(response => response.json())
                .then(() => {
                    this.fetchStations();
                    this.resetForm();
                    $('#stationModal').modal('hide');
                });
            },
            deleteStation(id) { // 刪除站點
                fetch('./api/addform.php', { // 使用DELETE方法
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id }) // 將id轉為json格式
                })
                .then(response => response.json())
                .then(() => {
                    this.fetchStations();
                });
            },
            resetForm() { // 重設表單
                this.name = '';
                this.email = '';
                this.bus_number = '';
                this.currentStation = null;
                this.modalTitle = '新增站點';
            },
            openModal() { // 打開模態框
                this.resetForm();
                $('#stationModal').modal('show');
            }
        }
    }).mount('#api');
</script>