// Função para ocultar as mensagens de sucesso/erro após 5 segundos
window.onload = function() {  // Garante que o script só será executado após a página ser totalmente carregada
    setTimeout(function() {
        var successMessage = document.getElementById('alert-success');
        var errorMessage = document.getElementById('alert-error');
        
        // Se a mensagem de sucesso estiver visível
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s ease';  // Aplica a transição suave
            successMessage.style.opacity = '0';  // Desaparece com a transição de opacidade
            setTimeout(function() {
                successMessage.style.display = 'none';  // Remove o elemento após a animação de opacidade
            }, 500);  // A animação leva 0.5 segundos
        }

        // Se a mensagem de erro estiver visível
        if (errorMessage) {
            errorMessage.style.transition = 'opacity 0.5s ease';  // Aplica a transição suave
            errorMessage.style.opacity = '0';  // Desaparece com a transição de opacidade
            setTimeout(function() {
                errorMessage.style.display = 'none';  // Remove o elemento após a animação de opacidade
            }, 500);  // A animação leva 0.5 segundos
        }
    }, 3000);  // Espera 3 segundos para esconder a mensagem
};
