// Aguarda o DOM ser completamente carregado antes de executar o script
document.addEventListener('DOMContentLoaded', () => {

    // --- Interação do botão "Fazer Pergunta" ---
    const askQuestionBtn = document.getElementById('ask-question-btn');
    if (askQuestionBtn) {
        askQuestionBtn.addEventListener('click', () => {
            alert('Funcionalidade "Fazer Pergunta" ainda não implementada. Aqui abriria um modal ou uma nova página.');
        });
    }

    // --- Interação dos filtros de conteúdo ---
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove a classe 'active' de todos os botões
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Adiciona a classe 'active' apenas ao botão clicado
            button.classList.add('active');
            
            // Log para simular a ação de filtragem
            console.log(`Filtrando por: ${button.textContent}`);
            // Aqui você adicionaria a lógica para recarregar as perguntas com o filtro selecionado
        });
    });

    // --- Interação dos links de navegação ---
    const navLinks = document.querySelectorAll('.navigation a');
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Impede a navegação padrão do link
            
            navLinks.forEach(nav => nav.classList.remove('active'));
            link.classList.add('active');
            
            console.log(`Navegando para: ${link.textContent.trim()}`);
            // Aqui você adicionaria a lógica para carregar o conteúdo da página correspondente
        });
    });

    console.log("Interface Q&A carregada e pronta!");
});