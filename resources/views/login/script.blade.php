<script>
    setTimeout(() => {
        const email = document.querySelector('input[type="email"]');
        const password = document.querySelector('input[type="password"]');

        if (email) email.placeholder = 'Contoh : you@polbeng.ac.id';
        if (password) password.placeholder = 'Masukkan password Anda';
    }, 200);
</script>