<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
</head>

<body>
    <a href="/login" class="login-button">ログイン</a>

    <div class="register-container">
        <h2>新規登録</h2>
        <form action="/register" method="post">
            @csrf
            <div class="form-group">
                <label for="name">お名前</label>
                <input type="text" id="name" required>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="register-submit">登録する</button>
        </form>
    </div>
</body>

</html>