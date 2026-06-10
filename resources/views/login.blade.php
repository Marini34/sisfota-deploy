<form id="loginForm">
    @csrf
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <br>
    <button type="button" onclick="loginMahasiswa()">Login</button>
</form>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    function loginMahasiswa() {
    var formData = $('#loginForm').serialize(); // Mengambil data formulir

    $.ajax({
        type: 'POST',
        url: 'https://presensi.untan.ac.id/api/v2/mahasiswa/login-mahasiswa', // Sesuaikan dengan path rute API Anda
        data: formData,
        success: function(response) {
            console.log(response);
            // Manipulasi atau tampilkan respons sesuai kebutuhan Anda
        },
        error: function(error) {
            console.error('Gagal melakukan request:', error);
        }
    });
}
</script>