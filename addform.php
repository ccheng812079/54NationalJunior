<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>網站管理</title>
    <?php include 'link.php'; ?>
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>
    <div id="app" class="container mt-4">
    <h1 class="mb-4">修改/刪除</h1>


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
                            <input type="number" class="form-control" id="email" v-model="email" required>
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
    }).mount('#app');
</script></body>
</html>