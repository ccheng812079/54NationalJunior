<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>台北101接駁專車搭乘意願調查</title>
    <?php include 'link.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>
    <div id="app" class="container mt-4">
        <h1 class="mb-4">接駁車意願調查</h1>

        <form v-if="formEnabled" @submit.prevent="submitForm">
            <div class="form-group">
                <label for="name">姓名:</label>
                <input type="text" class="form-control" id="name" v-model="name" required>
            </div>
            <div class="form-group">
                <label for="email">信箱:</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
            </div>
            <button type="submit" class="btn btn-primary">提交</button>
        </form>

        <div v-else>
            <p>該表單目前不接受回應</p>
        </div>

        <div v-if="message" class="mt-3">
            <p>{{ message }}</p>
        </div>
    </div>

    <script>
        const app = Vue.createApp({
            data() {
                return {
                    name: '',
                    email: '',
                    message: '',
                    formEnabled: true
                };
            },
            mounted() {
                this.checkFormStatus();
            },
            methods: {
                checkFormStatus() {
                    fetch('./api/form.php?type=settings')
                        .then(response => response.json())
                        .then(data => {
                            this.formEnabled = data.form_enabled;
                        });
                },
                submitForm() {
                    fetch('./api/form.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.name,
                            email: this.email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.message = data.message;
                        if (data.message === "感謝您的參與") {
                            this.name = '';
                            this.email = '';
                        }
                    });
                }
            }
        });

        app.mount('#app');
    </script>
</body>
</html>