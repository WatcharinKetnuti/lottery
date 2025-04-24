<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotteryController extends Controller
{
    public function index()
    {
        return view('lottery', [
            'prizes' => session('latestPrizes')
        ]);
    }

    public function generate(Request $request)
    {
        $prizes = $this->generatePrizes();
        session(['latestPrizes' => $prizes]);
        
        return response()->json($prizes);
    }

    public function check(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|min:0|max:999'
        ]);

        $ticket = str_pad($request->number, 3, '0', STR_PAD_LEFT);
        $prizes = session('latestPrizes', []);
        $results = [];

        if (empty($prizes)) {
            return response()->json(['result' => 'ยังไม่ได้ดำเนินการสุ่มรางวัล']);
        }

        if ($ticket === $prizes['first']) {
            $results[] = 'รางวัลที่ 1';
        }

        if (in_array($ticket, $prizes['second'])) {
            $results[] = 'รางวัลที่ 2';
        }

        if (in_array($ticket, $prizes['adjacent'])) {
            $results[] = 'รางวัลเลขข้างเคียง';
        }

        if (substr($ticket, -2) === $prizes['lastTwo']) {
            $results[] = 'รางวัลเลขท้าย 2 ตัว';
        }

        $message = empty($results) 
            ? 'ไม่ถูกรางวัลใดเลย' 
            : 'คุณถูกรางวัล: ' . implode(', ', $results);

        return response()->json(['result' => $message]);
    }

    private function generatePrizes()
    {
        $first = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $firstNum = intval($first);

        $prev = str_pad(($firstNum - 1 + 1000) % 1000, 3, '0', STR_PAD_LEFT);
        $next = str_pad(($firstNum + 1) % 1000, 3, '0', STR_PAD_LEFT);

        $second = [];
        while (count($second) < 3) {
            $num = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            if (!in_array($num, $second) && $num !== $first) {
                $second[] = $num;
            }
        }

        $lastTwo = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);

        return [
            'first' => $first,
            'adjacent' => [$prev, $next],
            'second' => $second,
            'lastTwo' => $lastTwo,
        ];
    }

    public function clearSession(Request $request)
    {
        $request->session()->forget('latestPrizes');
        return response()->json(['message' => 'Session cleared successfully']);
    }
}