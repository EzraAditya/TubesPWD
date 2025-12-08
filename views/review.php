<?php
session_start();
include '../actions/connection.php';

// Cek Login
if (!isset($_SESSION['id_user'])) { 
    header("Location: login.php"); 
    exit; 
}

$pageTitle = "Review Kamar";
include '../includes/header.php';

// Menangkap ID Kamar dari URL (Misal: review.php?id_kamar=101)
// Jika tidak ada di URL, kita set default atau kosong (sesuaikan kebutuhan)
$id_kamar_aktif = isset($_GET['id_kamar']) ? $_GET['id_kamar'] : 1; 
?>

<style>
    .star-rating {
        direction: rtl;
        display: inline-block;
        font-size: 1.5rem;
    }
    .star-rating input { display: none; }
    .star-rating label {
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    /* Logika hover dan checked untuk bintang */
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107; /* Warna Kuning Emas */
    }
    .card-review {
        border-left: 5px solid #0d6efd; /* Aksen biru di kiri */
    }
</style>

<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Ulasan Kamar</h2>
            <p class="text-muted">Menampilkan ulasan untuk ID Kamar: <strong><?php echo $id_kamar_aktif; ?></strong></p>
        </div>
        <div class="text-end">
             <h3 class="text-warning fw-bold mb-0">
                <span id="avg-rating">0.0</span> <i class="fas fa-star"></i>
            </h3>
            <button class="btn btn-primary mt-2" onclick="openModalTambah()">
                <i class="fas fa-plus"></i> Buat Ulasan
            </button>
        </div>
    </div>

    <div id="review-container">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div id="empty-state" style="display:none; text-align:center; padding:40px; background:#f9f9f9; border-radius:8px;">
        <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
        <p class="text-muted">Belum ada ulasan untuk kamar ini.</p>
        <button class="btn btn-outline-primary" onclick="openModalTambah()">Jadilah yang pertama!</button>
    </div>

</div>

<div class="modal fade" id="modalReview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tulis Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formReview">
                    <input type="hidden" id="action_type" value="tambah">
                    <input type="hidden" id="review_id" name="id_review">
                    <input type="hidden" id="input_id_kamar" name="id_kamar" value="<?php echo $id_kamar_aktif; ?>">
                    
                    <div class="mb-3 text-center">
                        <label class="form-label d-block fw-bold">Rating</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1"><i class="fas fa-star"></i></label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea class="form-control" id="komentar" name="komentar" rows="3" placeholder="Bagaimana pengalaman menginap Anda?" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // URL Backend (Sesuaikan dengan lokasi file PHP handler Anda)
    const API_URL = '../actions/review_handler.php'; 
    const ID_KAMAR = <?php echo $id_kamar_aktif; ?>;
    const ID_USER_LOGIN = <?php echo $_SESSION['id_user']; ?>; // Untuk validasi tombol edit/hapus

    $(document).ready(function() {
        loadReviews();
        loadAvgRating();
    });

    // 1. TAMPILKAN DATA (READ)
    function loadReviews() {
        $.ajax({
            url: API_URL,
            type: 'GET',
            data: { action: 'tampil', id_kamar: ID_KAMAR },
            dataType: 'json',
            success: function(response) {
                let html = '';
                if(response.length > 0) {
                    $('#empty-state').hide();
                    $.each(response, function(i, item) {
                        // Logic Bintang
                        let stars = '';
                        for(let j=1; j<=5; j++) {
                            stars += j <= item.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
                        }

                        // Tombol Aksi (Hanya muncul jika review milik user yang sedang login)
                        let buttons = '';
                        if (item.id_user == ID_USER_LOGIN) {
                            buttons = `
                                <div class="mt-2 text-end">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editReview(${item.id}, '${item.rating}', '${item.komentar}')"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteReview(${item.id})"><i class="fas fa-trash"></i></button>
                                </div>
                            `;
                        }

                        html += `
                        <div class="card mb-3 shadow-sm card-review">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title fw-bold">${item.nama}</h5>
                                    <small class="text-muted">Rating: ${item.rating}/5</small>
                                </div>
                                <div class="mb-2">${stars}</div>
                                <p class="card-text">${item.komentar}</p>
                                ${buttons}
                            </div>
                        </div>`;
                    });
                    $('#review-container').html(html);
                } else {
                    $('#review-container').html('');
                    $('#empty-state').show();
                }
            },
            error: function() {
                $('#review-container').html('<p class="text-danger">Gagal memuat data.</p>');
            }
        });
    }

    // 2. TAMPILKAN RATA-RATA
    function loadAvgRating() {
        $.ajax({
            url: API_URL,
            type: 'GET',
            data: { action: 'rating', id_kamar: ID_KAMAR },
            success: function(res) {
                $('#avg-rating').text(res);
            }
        });
    }

    // 3. SIMPAN DATA (CREATE / UPDATE)
    $('#formReview').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi Rating
        if(!$('input[name="rating"]:checked').val()) {
            Swal.fire('Peringatan', 'Mohon pilih bintang rating', 'warning');
            return;
        }

        let actionType = $('#action_type').val(); 
        let url = API_URL + '?action=' + actionType;
        let formData = $(this).serialize(); // Mengambil semua data form

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#modalReview').modal('hide'); // Tutup modal (Bootstrap 5 syntax might vary slightly depending on version, this is jQuery style)
                // Jika pakai Bootstrap 5 native JS:
                // const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalReview'));
                // modalInstance.hide();
                
                Swal.fire('Berhasil', response, 'success');
                loadReviews();
                loadAvgRating();
            }
        });
    });

    // 4. HAPUS DATA (DELETE)
    function deleteReview(id) {
        Swal.fire({
            title: 'Hapus review?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_URL + '?action=hapus',
                    type: 'POST',
                    data: { id_review: id },
                    success: function(response) {
                        Swal.fire('Terhapus', response, 'success');
                        loadReviews();
                        loadAvgRating();
                    }
                });
            }
        });
    }

    // HELPER: Buka Modal Tambah
    function openModalTambah() {
        $('#formReview')[0].reset();
        $('#action_type').val('tambah');
        $('#modalTitle').text('Tulis Ulasan Baru');
        $('#modalReview').modal('show');
    }

    // HELPER: Buka Modal Edit
    function editReview(id, rating, komentar) {
        $('#action_type').val('edit');
        $('#review_id').val(id);
        $('#komentar').val(komentar);
        $(`input[name="rating"][value="${rating}"]`).prop('checked', true);
        
        $('#modalTitle').text('Edit Ulasan');
        $('#modalReview').modal('show');
    }
</script>