<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function confirm(ContactRequest $request)
    {
        $inputs = $request->all();

        $categories = [
            1 => '商品のお届けについて',
            2 => '商品の交換について',
            3 => '商品トラブル',
            4 => 'ショップへのお問い合わせ',
            5 => 'その他'
        ];

        return view('confirm', compact('inputs', 'categories'));
    }

    public function store(Request $request)
    {
        if ($request->input('action') === 'back') {
            return redirect('/')->withInput($request->except('action'));
        }

        $contact = $request->only(['category_id', 'first_name', 'last_name', 'gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building', 'detail']);

        $contact['tel'] = "{$contact['tel1']}-{$contact['tel2']}-{$contact['tel3']}";

        Contact::create('$contact');

        return redirect('/thanks');
    }

    public function show()
    {
        $contacts = Contact::with('category')
            ->select(
                'id',
                'last_name',
                'first_name',
                'email',
                'gender',
                'category_id',
                'tel',
                'address',
                'building',
                'detail'
            )  // ← モーダル用に追加
            ->paginate(7);

        return view('admin', compact('contacts'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['ok' => true]);
    }


    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $query = Contact::with('category')
            ->select('Last_name', 'first_name', 'email', 'gender', 'category_id', 'created_at', 'tel', 'address', 'building', 'detail');

        if ($q !== '') {
            $isExact = preg_match('/^" +"$/u', $q);
            if ($isExact) {
                $q = trim($q, '"');
            }

            $query->where(function ($qr) use ($q, $isExact) {
                $parts = preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY);

                if ($isExact) {
                    if (count($parts) >= 2) {
                        $qr->where('last_name', $parts[0])->where('first_name', $parts[1]);
                    } else {
                        $qr->where('last_name', $q)
                            ->orWhere('first_name', $q)
                            ->orWhereRaw("CONCAT(last_name, first_name) = ?", [$q]);
                    }
                    $qr->orWhere('email', $q);
                } else {
                    $like = "%{$q}%";
                    if (count($parts) >= 2) {
                        $qr->where('last_name', 'like', "%{$parts[0]}%")
                            ->where('first_name', 'like', "%{$parts[1]}%");
                    } else {
                        $qr->where('last_name', 'like', $like)
                            ->orWhere('first_name', 'like', $like)
                            ->orWhereRaw("CONCAT(last_name, first_name) like ?", [$like])
                            ->orWhereRaw("CONCAT(last_name, '', first_name) like ?", [$like]);
                    }
                    $qr->orWhere('email', 'like', $like);
                }
            });
        }

        $gender = $request->input('gender', 'all');
        if (in_array($gender, ['男性', '女性', 'その他'], true)) {
            $query->where('gender', $gender);
        }

        $categoryIdRaw = $request->input('category_id', '');

        if ($categoryIdRaw !== '' && ctype_digit((string) $categoryIdRaw)) {
            $categoryId = (int) $categoryIdRaw;
            if (in_array($categoryId, [1, 2, 3, 4, 5], true)) {
                $query->where('category_id', $categoryId);
            }
        }

        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $contacts = $query->orderByDesc('created_at')->get();

        return view('admin', compact('contacts'));
    }

    public function export(Request $request): StreamedResponse
    {
        // === ここで $query を、search() と同じ条件で組み立てる ===
        $query = Contact::with('category')
            ->select(
                'id',
                'last_name',
                'first_name',
                'gender',
                'email',
                'tel',
                'address',
                'building',
                'category_id',
                'detail',
                'created_at'
            );

        // ▼ 以下はあなたの search() と同じロジックを移植してください（要約）
        // キーワード（名前・メール、完全一致or部分一致）
        if (($q = trim((string)$request->input('q', ''))) !== '') {
            $isExact = preg_match('/^".+"$/u', $q);
            if ($isExact) $q = trim($q, '"');
            $query->where(function ($qr) use ($q, $isExact) {
                $parts = preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY);
                if ($isExact) {
                    if (count($parts) >= 2) {
                        $qr->where('last_name', $parts[0])->where('first_name', $parts[1]);
                    } else {
                        $qr->where('last_name', $q)
                            ->orWhere('first_name', $q)
                            ->orWhereRaw("CONCAT(last_name, first_name) = ?", [$q])
                            ->orWhereRaw("CONCAT(last_name, ' ', first_name) = ?", [$q]);
                    }
                    $qr->orWhere('email', $q);
                } else {
                    $like = "%{$q}%";
                    if (count($parts) >= 2) {
                        $qr->where('last_name', 'like', "%{$parts[0]}%")
                            ->where('first_name', 'like', "%{$parts[1]}%");
                    } else {
                        $qr->where('last_name', 'like', $like)
                            ->orWhere('first_name', 'like', $like)
                            ->orWhereRaw("CONCAT(last_name, first_name) like ?", [$like])
                            ->orWhereRaw("CONCAT(last_name, ' ', first_name) like ?", [$like]);
                    }
                    $qr->orWhere('email', 'like', $like);
                }
            });
        }

        // 性別
        $gender = $request->input('gender', 'all');
        if (in_array($gender, ['男性', '女性', 'その他'], true)) {
            $query->where('gender', $gender);
        }

        // 種別
        $categoryIdRaw = $request->input('category_id', '');
        if ($categoryIdRaw !== '' && ctype_digit((string)$categoryIdRaw)) {
            $query->where('category_id', (int)$categoryIdRaw);
        }

        // 日付
        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // === ここからCSVストリーム ===
        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

        // Excel互換を優先するなら Shift_JIS、UTF-8で良ければ BOM 付きにする
        $useShiftJis = true;

        $headers = [
            'Content-Type'        => $useShiftJis ? 'text/csv; charset=Shift_JIS' : 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($query, $useShiftJis) {
            $out = fopen('php://output', 'w');

            // UTF-8で出す場合はBOMを付ける（Excel対策）
            if (!$useShiftJis) {
                fwrite($out, "\xEF\xBB\xBF");
            }

            // ヘッダ行
            $header = ['ID', 'お名前', '性別', 'メールアドレス', '電話番号', '住所', '建物名', 'お問い合わせの種類', 'お問い合わせ内容', '作成日'];
            if ($useShiftJis) mb_convert_variables('SJIS-win', 'UTF-8', $header);
            fputcsv($out, $header);

            // 大量データでもOKなようにチャンクで書き出し
            $query->orderBy('id')->chunk(1000, function ($rows) use ($out, $useShiftJis) {
                foreach ($rows as $c) {
                    $row = [
                        $c->id,
                        "{$c->last_name} {$c->first_name}",
                        $c->gender,
                        $c->email,
                        $c->tel,
                        $c->address,
                        $c->building,
                        optional($c->category)->content,
                        $c->detail,
                        optional($c->created_at)?->format('Y-m-d H:i:s'),
                    ];
                    if ($useShiftJis) {
                        mb_convert_variables('SJIS-win', 'UTF-8', $row);
                    }
                    fputcsv($out, $row);
                }
            });

            fclose($out);
        }, 200, $headers);
    }
}
