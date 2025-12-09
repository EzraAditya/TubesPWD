// --- FUNGSI GESER (Dipanggil oleh Onclick di HTML) ---
function geserKanan() {
    var galeri = document.getElementById('kotakGambar');
    if (galeri) {
        galeri.scrollBy({ left: 300, behavior: 'smooth' });
    }
}

function geserKiri() {
    var galeri = document.getElementById('kotakGambar');
    if (galeri) {
        galeri.scrollBy({ left: -300, behavior: 'smooth' });
    }
}

// --- FUNGSI SAPAAN DIGANTI MENJADI "Selamat Datang!" ---
document.addEventListener("DOMContentLoaded", function() {
    var elemenSapaan = document.getElementById('teksSapaan');
    if (elemenSapaan) {
        elemenSapaan.innerText = "Selamat Datang!";
    }
});
