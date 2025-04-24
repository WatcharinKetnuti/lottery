<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบสุ่มรางวัลล็อตเตอรี่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="mt-4 container">
        <h1 class="text-center mb-4 animate__animated animate__fadeInDown">Diversition ล็อตเตอรี่</h1>
        
        <button class="btn btn-warning  animate__animated animate__pulse animate__infinite" onclick="generatePrizes()">ดำเนินการสุ่มรางวัล</button>
        
        <table class="mt-4 table table-bordered prize-table animate__animated animate__fadeInUp ">
            <thead class="table-light">
                <tr>
                    <th>ประเภท</th>
                    <th>หมายเลขรางวัล</th>
                </tr>
            <tbody id="prizeTable">
            <tr>
                <th>รางวัลที่ 1</th>
                <td id="firstPrize">{{ $prizes['first'] ?? '-' }}</td>
            </tr>
            <tr>
                <th>รางวัลที่ 2</th>
                <td id="secondPrizes">{{ isset($prizes['second']) ? implode(', ', $prizes['second']) : '-' }}</td>
            </tr>
            <tr>
                <th>รางวัลเลขข้างเคียง</th>
                <td id="adjacentPrizes">{{ isset($prizes['adjacent']) ? implode(', ', $prizes['adjacent']) : '-' }}</td>
            </tr>
            <tr>
                <th>รางวัลเลขท้าย 2 ตัว</th>
                <td id="lastTwoPrize">{{ $prizes['lastTwo'] ?? '-' }}</td>
            </tr>
            </tbody>
        </table>

        <div class="card animate__animated animate__fadeInUp">  
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">กรอกหมายเลขล็อตเตอรี่ (3 หลัก)</label>
            <input type="number" class="form-control" id="ticketNumber" min="0" max="999">
        </div>
        <button class="btn btn-primary animate__animated animate__heartBeat" onclick="checkPrize()">ตรวจรางวัล</button>
        <button class="btn btn-danger ms-2 " onclick="clearResult()">ล้างผลลัพธ์</button>
        <div id="checkResult" class="mt-3 text-center animate__animated animate__fadeIn" style="font-size: 1.5rem; color: #333;"></div>
    </div>
</div>

<script>
    function generatePrizes() {
        fetch('/generate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('firstPrize').textContent = data.first;
            document.getElementById('secondPrizes').textContent = data.second.join(', ');
            document.getElementById('adjacentPrizes').textContent = data.adjacent.join(', ');
            document.getElementById('lastTwoPrize').textContent = data.lastTwo;
        });
    }

    function checkPrize() {
        const number = document.getElementById('ticketNumber').value.padStart(3, '0').slice(-3);
        
        fetch('/check', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ number: number })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('checkResult').textContent = data.result;
        });
    }

    function clearResult() {

        document.getElementById('checkResult').textContent = '';

        
        document.getElementById('ticketNumber').value = '';

        document.getElementById('firstPrize').textContent = '-';
        document.getElementById('secondPrizes').textContent = '-';
        document.getElementById('adjacentPrizes').textContent = '-';
        document.getElementById('lastTwoPrize').textContent = '-';


        fetch('/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message); 
        });
    }
</script>
</body>
</html>