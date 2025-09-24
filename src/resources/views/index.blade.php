<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <mete http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
</head>

<body>
    <div class="form-container">
        <h1>お問い合わせフォーム</h1>
        <form action="/confirm" method="post">
            @csrf

            <div class="form-group">
                <label for="last_name">お名前</label>
                <div class="name-inputs">
                    <div class="name-box">
                        <input type="text" name="last_name" id="last_name" placeholder="例：山田" value="{{ old('last_name') }}">
                        <small>姓</small>
                    </div>
                    <div class="form__error">
                        @error('last_name')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="name-box">
                        <input type="text" name="first_name" id="first_name" placeholder="例：太郎" value="{{ old('first_name') }}">
                        <small>名</small>
                    </div>
                    <div class="form__error">
                        @error('first_name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>性別</label>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="男性" {{ old('gender') == '男性' ? 'checked' : '' }}> 男性</label>
                    <label><input type="radio" name="gender" value="女性" {{ old('gender') == '女性' ? 'checked' : '' }}> 女性</label>
                    <label><input type="radio" name="gender" value="その他" {{ old('gender') == 'その他' ? 'checked' : '' }}> その他</label>
                </div>

                <div class="form__error">
                    @error('gender')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>

            <div class="form-group">
                <label>電話番号</label>
                <div class="tel-input">
                    <input type="text" name="tel1" id="tel1" maxlength="5" class="tel-box" value="{{ old('tel1') }}">
                    -
                    <input type="text" name="tel2" id="tel2" maxlength="5" class="tel-box" value="{{ old('tel2') }}">
                    -
                    <input type="text" name="tel3" id="tel3" maxlength="5" class="tel-box" value="{{ old('tel3') }}">
                </div>
            </div>

            <div class="form__error">
                @error('tel1')
                {{ $message }}
                @enderror
                @error('tel2')
                {{ $message }}
                @enderror
                @error('tel3')
                {{ $message }}
                @enderror
            </div>

            <div class="form-group">
                <label>住所</label>
                <input type="text" name="address" value="{{ old('address') }}">
            </div>

            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>

            <div class="form-group">
                <label>建物名（任意）</label>
                <input type="text" name="building" value="{{ old('building') }}">
            </div>

            <div class="form__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>

            <div class="form-group">
                @php
                $categories = [
                1 => '商品のお届けについて',
                2 => '商品の交換について',
                3 => '商品トラブル',
                4 => 'ショップへのお問い合わせ',
                5 => 'その他',
                ];
                @endphp

                <label>お問い合わせの種類</label>
                <select name="category_id">
                    <option value="">選択してください</option>
                    @foreach ($categories as $id => $label)
                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form__error">
                @error('category_id')
                {{ $message }}
                @enderror
            </div>

            <div class="form-group">
                <label>お問い合わせ内容</label>
                <textarea name="detail">{{ old('detail') }}</textarea>
            </div>

            <div class="form__error">
                @error('detail')
                {{ $message }}
                @enderror
            </div>

            <button type="submit">確認画面</button>
        </form>
    </div>
</body>

</html>