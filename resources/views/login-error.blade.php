<script>
    setTimeout(() => {
        const emailInput = document.querySelector('input[type="email"]');

        @if(session('filament_login_error'))
            if (emailInput) {
                // isi email
                emailInput.value = "{{ session('filament_login_email') }}";

                // buat error message
                let error = document.createElement('p');
                error.innerText = "{{ session('filament_login_error') }}";
                error.style.color = 'red';
                error.style.fontSize = '12px';
                error.style.marginTop = '5px';

                emailInput.parentElement.appendChild(error);

                // highlight input
                emailInput.style.borderColor = 'red';
            }
        @endif
    }, 200);
</script>