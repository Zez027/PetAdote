/**
 * Dicionário Completo de Raças de Animais
 * Como não há uma API unificada do governo (como o IBGE) para raças em PT-BR,
 * esta lista serve como uma base de dados interna robusta.
 */
const racasPorEspecie = {
    'Cachorro': [
        'SRD (Vira-lata)',
        'Akita', 'Basset Hound', 'Beagle', 'Border Collie', 'Boxer', 'Bulldog Francês', 
        'Bulldog Inglês', 'Bull Terrier', 'Chihuahua', 'Chow Chow', 'Cocker Spaniel', 
        'Dachshund (Salsicha)', 'Dálmata', 'Doberman', 'Dogo Argentino', 'Fila Brasileiro', 
        'Golden Retriever', 'Husky Siberiano', 'Labrador Retriever', 'Lulu da Pomerânia (Spitz Alemão)', 
        'Maltês', 'Mastim Napolitano', 'Pastor Alemão', 'Pastor Belga', 'Pastor Maremano', 
        'Pequinês', 'Pinscher', 'Pitbull', 'Pointer Inglês', 'Poodle', 'Pug', 
        'Rottweiler', 'Samoeida', 'São Bernardo', 'Schnauzer', 'Shar-Pei', 'Shih Tzu', 
        'Staffordshire Bull Terrier', 'Yorkshire Terrier', 
        'Outra'
    ],
    'Gato': [
        'SRD (Sem Raça Definida)',
        'Abissínio', 'Angorá', 'Ashera', 'Azul Russo', 'Bengal', 'Bobtail Americano', 
        'British Shorthair', 'Burmês', 'Chartreux', 'Cornish Rex', 'Himalaio', 
        'Maine Coon', 'Munchkin', 'Norueguês da Floresta', 'Pelo Curto Americano', 
        'Persa', 'Ragdoll', 'Savannah', 'Scottish Fold', 'Siamês', 'Siberiano', 
        'Sphynx', 
        'Outra'
    ],
    'Pássaro': [
        'SRD (Sem Raça Definida)',
        'Agapornis', 'Arara', 'Azulão', 'Bem-te-vi', 'Cacatua', 'Calopsita', 
        'Canário da Terra', 'Canário do Reino', 'Codorna', 'Coleiro', 'Curió', 
        'Diamante de Gould', 'Galo/Galinha', 'Ganso', 'Mainá', 'Mandarim', 
        'Maritaca', 'Papagaio', 'Pato', 'Pavão', 'Periquito Australiano', 
        'Pomba', 'Trinca-Ferro', 'Tucano', 
        'Outra'
    ],
    'Coelho': [
        'SRD (Sem Raça Definida)',
        'Angorá', 'Borboleta', 'Cabeça de Leão (Lionhead)', 'Chinchila', 
        'Flandres', 'Fuzzy Lop', 'Gigingante', 'Holandês', 'Mini Lop', 
        'Mini Rex', 'Nova Zelândia', 
        'Outra'
    ],
    'Roedor': [
        'Chinchila', 'Esquilo da Mongólia (Gerbil)', 'Furão (Ferret)', 
        'Hamster Anão Russo', 'Hamster Chinês', 'Hamster Roborovski', 
        'Hamster Sírio', 'Porquinho-da-índia', 'Rato Twister', 'Topolino', 
        'Outro'
    ],
    'Réptil': [
        'Cágado', 'Cobra Corn Snake', 'Cobra Jiboia', 'Dragão Barbudo', 
        'Gecko', 'Iguana', 'Jabuti', 'Lagarto Teiú', 'Tartaruga Tigre D\'Água', 
        'Outro'
    ],
    'Equino': [
        'SRD (Sem Raça Definida)',
        'Andaluz', 'Appaloosa', 'Árabe', 'Bretão', 'Campolina', 'Crioulo', 
        'Lusitano', 'Mangalarga', 'Mangalarga Marchador', 'Pampa', 
        'Pangaré', 'Pônei', 'Quarto de Milha', 
        'Outra'
    ],
    'Outros': [
        'Espécie Específica', 
        'Outra'
    ]
};

/**
 * Função global chamada pelos formulários de Create e Edit
 * @param {string} racaPreSelecionada - Raça que já vem do banco de dados (no caso de edição)
 */
window.atualizarRacas = function(racaPreSelecionada = '') {
    const especieSelect = document.getElementById('especie');
    const racaSelect = document.getElementById('raca');
    
    // Se os elementos não existirem na página, interrompe a função
    if (!especieSelect || !racaSelect) return;

    const especie = especieSelect.value;
    
    // Limpa o select de raças e coloca a opção padrão
    racaSelect.innerHTML = '<option value="">Selecione a raça...</option>';
    
    if (racasPorEspecie[especie]) {
        racasPorEspecie[especie].forEach(raca => {
            const option = document.createElement('option');
            option.value = raca;
            option.textContent = raca;
            
            // Se for a tela de Edit (ou erro de validação), já deixa a raça correta selecionada
            if (raca === racaPreSelecionada) {
                option.selected = true;
            }
            
            racaSelect.appendChild(option);
        });
    }
};

// Quando a página terminar de carregar, verifica se já existe uma espécie selecionada.
// (Isso é muito útil se a página recarregar com erro de validação ou ao abrir a página de edição)
document.addEventListener("DOMContentLoaded", function() {
    const especieSelect = document.getElementById('especie');
    if (especieSelect && especieSelect.value) {
        // A função atualizarRacas já é chamada no Blade usando o "old('raca', $pet->raca)"
        // Mas podemos forçar uma atualização vazia aqui se necessário.
    }
});