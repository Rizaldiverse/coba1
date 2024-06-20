<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Ongkos Kirim</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .total, .shippingCost {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hitung Ongkos Kirim</h1>
        <form id="shippingForm">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="nomorWhatsapp">Nomor WhatsApp:</label>
            <input type="text" id="nomorWhatsapp" name="nomorWhatsapp" required>

            <label for="ukuranBanner">Ukuran Banner (cm):</label>
            <select id="ukuranBanner" name="ukuranBanner" required>
                <option value="100x200">100x200</option>
                <option value="200x300">200x300</option>
                <option value="300x400">300x400</option>
            </select>

            <label for="kota">Kota:</label>
            <select id="kota" name="kota" required>
                <!-- Kota akan diisi secara dinamis dari API RajaOngkir -->
            </select>

            <button type="button" onclick="calculateTotalCost()">Hitung Ongkos Kirim</button>

            <div class="total" id="total"></div>
            <div class="shippingCost" id="shippingCost"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const apiKey = 'aa01e82b3abbdd4958a0c3857d0a1a97';

        async function getKota() {
            const url = `https://api.rajaongkir.com/starter/city`;
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'key': apiKey
                }
            });
            const data = await response.json();
            return data.rajaongkir.results;
        }

        async function getOngkosKirim(origin, destination, weight) {
            const url = `https://api.rajaongkir.com/starter/cost`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'key': apiKey
                },
                body: JSON.stringify({
                    origin: origin,
                    destination: destination,
                    weight: weight,
                    courier: 'jne'
                })
            });
            const data = await response.json();
            return data.rajaongkir.results[0].costs[0].cost[0].value;
        }

        async function populateKotaDropdown() {
            const kotaDropdown = document.getElementById('kota');
            const kotas = await getKota();

            kotas.forEach(kota => {
                const option = document.createElement('option');
                option.value = kota.city_id;
                option.text = kota.city_name;
                kotaDropdown.add(option);
            });

            $('#kota').select2();
        }

        async function calculateTotalCost() {
            const origin = '501'; // Kode kota/kabupaten asal (misalnya, Semarang)
            const destination = document.getElementById('kota').value;
            const weight = 1000; // Berat paket dalam gram (disesuaikan dengan berat aktual)
            const productCost = 20000; // Contoh harga produk (disesuaikan dengan harga aktual)

            const shippingCost = await getOngkosKirim(origin, destination, weight);
            const totalCost = productCost + shippingCost;

            document.getElementById('total').innerText = `Total: Rp ${totalCost}`;
            document.getElementById('shippingCost').innerText = `Ongkos Kirim: Rp ${shippingCost}`;
        }

        // Panggil fungsi untuk mengisi dropdown kota ketika halaman dimuat
        document.addEventListener('DOMContentLoaded', populateKotaDropdown);
    </script>
</body>
</html>
VERSI8
