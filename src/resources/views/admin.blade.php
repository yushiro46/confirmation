<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
</head>

<body>
    <div class="page-wrapper">
        <form class="search-form" action="/admin/search" method="get">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="名前またはメールアドレスを入力してください" class="form-control">

            <select name="gender" class="form-control">
                <option value="" {{ request('gender')==='' ? 'selected' : '' }}>性別</option>
                <option value="all" {{ request('gender')==='all' ? 'selected' : '' }}>全て</option>
                <option value="男性" {{ request('gender')==='男性' ? 'selected' : '' }}>男性</option>
                <option value="女性" {{ request('gender')==='女性' ? 'selected' : '' }}>女性</option>
                <option value="その他" {{ request('gender')==='その他' ? 'selected' : '' }}>その他</option>
            </select>

            <select name="category_id" class="form-control">
                <option value="">お問い合わせの種類</option>
                <option value="1" {{ request('category_id')==='1' ? 'selected' : '' }}>商品のお届けについて</option>
                <option value="2" {{ request('category_id')==='2' ? 'selected' : '' }}>商品の交換について</option>
                <option value="3" {{ request('category_id')==='3' ? 'selected' : '' }}>商品トラブル</option>
                <option value="4" {{ request('category_id')==='4' ? 'selected' : '' }}>ショップへのお問い合わせ</option>
                <option value="5" {{ request('category_id')==='5' ? 'selected' : '' }}>その他</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}">
            ~
            <input type="date" name="date_to" value="{{ request('date_to') }}">

            <button type="submit" class="btn btn-primary">検索</button>
            <a href="/admin" class="btn btn-secondary">リセット</a>
        </form>

        <a href="{{ route('admin.export', request()->query()) }}" class="btn btn-outline">エクスポート</a>

        <div class="table-header">
            @if (method_exists($contacts, 'links'))
            {{ $contacts->links('pagination::default') }}
            @endif
        </div>
        @if (Auth::check())
        <form class="form" action="/logout" method="post">
            @csrf
            <button class="header-nav__button">ログアウト</button>
        </form>

        <table class="contact-table">
            <thead>
                <tr>
                    <th>お名前</th>
                    <th>性別</th>
                    <th>メールアドレス</th>
                    <th>お問い合わせの種類</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                <tr>
                    <td>{{ "{$contact->last_name} {$contact->first_name}" }}</td>
                    <td>{{ $contact->gender }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->category->content }}</td>
                    <td>
                        <button type="button" class="detail-btn"
                            data-id="{{ $contact->id }}"
                            data-name="{{ $contact->last_name }} {{ $contact->first_name }}"
                            data-gender="{{ $contact->gender }}"
                            data-email="{{ $contact->email }}"
                            data-tel="{{ $contact->tel }}"
                            data-address='@json($contact->address ?? "")'
                            data-building='@json($contact->building ?? "")'
                            data-category="{{ $contact->category->content }}"
                            data-detail='@json($contact->detail ?? "")'>詳細</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div id="detailModal" class="modal">
            <div class="modal-content">
                <span id="closeModalBtn" class="close">&times;</span>
                <h2>お問い合わせ詳細</h2>
                <p><strong>お名前:</strong> <span id="modalName"></span></p>
                <p><strong>性別:</strong> <span id="modalGender"></span></p>
                <p><strong>メールアドレス:</strong> <span id="modalEmail"></span></p>
                <p><strong>電話番号:</strong> <span id="modalTel"></span></p>
                <p><strong>住所:</strong> <span id="modalAddress"></span></p>
                <p><strong>建物名:</strong> <span id="modalBuilding"></span></p>
                <p><strong>お問い合わせの種類:</strong> <span id="modalCategory"></span></p>
                <p><strong>お問い合わせ内容:</strong> <span id="modalDetail"></span></p>
                <button type="button" id="deleteBtn" class="btn btn-danger">削除</button>
            </div>
        </div>
        @endif
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>