document.addEventListener('DOMContentLoaded', function () {
    // Verifica se jQuery está disponível
    if (window.jQuery) {
        $('#cpf').mask('000.000.000-00');
        $('#contato').mask('(00) 00000-0000');
    }

    // Alterna visibilidade do campo de senha
    window.togglePassword = function (id) {
        const input = document.getElementById(id);
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    };
});
