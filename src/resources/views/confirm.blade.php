<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>入力内容の確認</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}" />
</head>

<body>
    <div class="confirm-container">
        <h1 class="confirm-title">入力内容の確認</h1>
        <form action="/contacts" method="post">
            @csrf
            <table class="confirm-table">
                <tr>
                    <th>お名前</th>
                    <td>{{ $inputs['last_name'] }} {{ $inputs['first_name'] }}</td>
                    <input type="hidden" name="last_name" value="{{ $inputs['last_name'] }}">
                    <input type="hidden" name="first_name" value="{{ $inputs['first_name'] }}">
                </tr>

                <tr>
                    <th>性別</th>
                    <td>{{ $inputs['gender'] }}</td>
                    <input type="hidden" name="gender" value="{{ $inputs['gender'] }}">
                </tr>

                <tr>
                    <th>メールアドレス</th>
                    <td>{{ $inputs['email'] }}</td>
                    <input type="hidden" name="email" value="{{ $inputs['email'] }}">
                </tr>

                <tr>
                    <th>電話番号</th>
                    <td>{{ $inputs['tel1'] }}-{{ $inputs['tel2'] }}-{{ $inputs['tel3'] }}</td>
                    <input type="hidden" name="tel1" value="{{ $inputs['tel1'] }}">
                    <input type="hidden" name="tel2" value="{{ $inputs['tel2'] }}">
                    <input type="hidden" name="tel3" value="{{ $inputs['tel3'] }}">
                </tr>

                <tr>
                    <th>住所</th>
                    <td>{{ $inputs['address'] }}</td>
                    <input type="hidden" name="address" value="{{ $inputs['address'] }}">
                </tr>

                <tr>
                    <th>建物名</th>
                    <td>{{ $inputs['building'] }}</td>
                    <input type="hidden" name="building" value="{{ $inputs['building'] }}">
                </tr>

                <tr>
                    <th>お問い合わせの種類</th>
                    <td>{{ $categories[$inputs['category_id']] ?? '未設定' }}</td>
                    <input type="hidden" name="category_id" value="{{ $inputs['category_id'] }}">
                </tr>

                <tr>
                    <th>お問い合わせ内容</th>
                    <td>{{ $inputs['detail'] }}</td>
                    <input type="hidden" name="detail" value="{{ inputs['detail'] }}">
                </tr>
            </table>

            <div class="button-group">
                <button type="submit" name="action" value="back" class="back-btn">修正する</button>
                <button type="submit" name="action" value="submit" class=submit-btn">送信する</button>
            </div>
        </form>
    </div>
</body>

</html>