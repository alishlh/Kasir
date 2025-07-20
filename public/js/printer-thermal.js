const connectButton = document.getElementById('connect-button');

if (connectButton) {
    connectButton.addEventListener('click', async () => {
        window.connectedPrinter = await getPrinter();

        if (window.connectedPrinter) {
            console.log("Berhasil menyambungkan ke printer.");
        } else {
            console.error("Gagal menyambungkan ke printer.");
        }
    });
}

// fungsi ini untuk mencari jaringan printer bluetooth
async function getPrinter() {
    try {
        const device = await navigator.bluetooth.requestDevice({
            filters: [{
                    namePrefix: "RPP"
                },
                {
                    namePrefix: "Thermal"
                },
                {
                    namePrefix: "POS"
                }
            ],
            optionalServices: ["000018f0-0000-1000-8000-00805f9b34fb"]
        });

        alert("Perangkat berhasil tersambung:", device.name);
        return device;
    } catch (e) {
        alert("Perangkat gagal tersambung");
        console.error("Perangkat gagal tersambung, Erorr :", e);
        return null;
    }
}

// Fungsi ini di lempar dari livewire untuk ngeprint dan mengerimkan data
document.addEventListener('livewire:init', () => {
    Livewire.on('doPrintReceipt', async (data) => {
        console.log(data)
        await printThermalReceipt(data);
    });
});


// Fungsi ini untuk mencetak struk
async function printThermalReceipt(data) {
    try {
        if (!window.connectedPrinter) {
            window.connectedPrinter = await getPrinter();
        }

        console.log("Menyambungkan ke printer...");
        const server = await window.connectedPrinter.gatt.connect();
        const service = await server.getPrimaryService("000018f0-0000-1000-8000-00805f9b34fb");
        const characteristic = await service.getCharacteristic("00002af1-0000-1000-8000-00805f9b34fb");

        console.log("Printer siap, mengirim struk...");

        const encoder = new TextEncoder(); 

        let receipt = "\x1B\x40"; // Reset printer
        receipt += "\x1B\x61\x01"; // Perataan Tengah
        receipt += "\x1B\x21\x10"; // Text tebal dan besar
        receipt += data.store.name + "\n";
        receipt += "\x1B\x21\x00"; // Normal text
        receipt += data.store.address + "\n";
        receipt += "Telp: " + data.store.phone + "\n";
        receipt += "================================\n";
        receipt += "\x1B\x61\x00"; // Kembalikan ke rata kiri

        // Detail Transaksi
        receipt += "Kode Transaksi: " + data.order.transaction_number + "\n";
        receipt += "Pembayaran: " + data.order.payment_method.name + "\n";
        receipt += "Tanggal: " + data.date + "\n";
        receipt += "================================\n";
        receipt += formatRow("Nama Barang", "Qty", "Harga") + "\n";
        receipt += "--------------------------------\n";

        let total = 0;
        data.items.forEach(item => {
            receipt += formatRow(item.product.name, item.quantity, formatRibuan(item.product.price)) + "\n";
            total += item.quantity * item.product.price;
        });

        receipt += "--------------------------------\n";
        receipt += formatRow("Total", "", formatRibuan(total)) + "\n";
        receipt += formatRow("Nominal Bayar", "", formatRibuan(data.order.cash_received)) + "\n";
        receipt += formatRow("Kembalian", "", formatRibuan(data.order.change)) + "\n";
        receipt += "================================\n";
        receipt += "\x1B\x61\x01"; // Perataan Tengah
        receipt += "Terima Kasih!\n";
        receipt += "================================\n";
        receipt += "\x1B\x61\x00"; // Kembalikan ke rata kiri

        receipt += "\x1D\x56\x00"; // ESC/POS cut paper
        await sendChunks(characteristic, encoder.encode(receipt));

        console.log("Sukses mencetak struk");
    } catch (e) {
        console.error("Failed to print thermal", e);
    }
}

//  Fungsi untuk Mengirim Data dalam Potongan Kecil agar tidak ada batasan print
async function sendChunks(characteristic, data) {
    const chunkSize = 180; // BLE limit
    let offset = 0;

    while (offset < data.length) {
        let chunk = data.slice(offset, offset + chunkSize);
        await characteristic.writeValue(chunk);
        offset += chunkSize;
    }
}

function formatRibuan(number) {
    return number.toLocaleString("id-ID");
}

//  Fungsi untuk Format Teks agar Rapi
function formatRow(name, qty, price) {
    const nameWidth = 16,
        qtyWidth = 6,
        priceWidth = 10;
    let nameLines = name.match(new RegExp('.{1,' + nameWidth + '}', 'g')) || [];
    let output = '';

    for (let i = 0; i < nameLines.length - 1; i++) {
        output += nameLines[i].padEnd(32) + "\n";
    }

    let lastLine = nameLines[nameLines.length - 1].padEnd(nameWidth);
    qty = qty.toString().padStart(qtyWidth);
    price = price.toString().padStart(priceWidth);
    output += lastLine + qty + price;

    return output;
}



