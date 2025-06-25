document.addEventListener('DOMContentLoaded', function () {
    const racasPorTipo = {
        'Cachorro': [
            'Labrador', 'Poodle', 'Bulldog', 'Beagle', 'Pastor Alemão', 'Rottweiler',
            'Golden Retriever', 'Yorkshire', 'Dachshund', 'Shih Tzu', 'Pitbull',
            'Doberman', 'Chihuahua', 'Boxer', 'Akita', 'Husky Siberiano'
        ],
        'Gato': [
            'Siamês', 'Persa', 'Maine Coon', 'Sphynx', 'Angorá', 'Bengal',
            'Azul Russo', 'British Shorthair', 'Ragdoll', 'Himalaio', 'Savannah'
        ],
        'Pássaro': [
            'Canário', 'Periquito', 'Papagaio', 'Calopsita', 'Agapornis', 'Arara'
        ],
        'Coelho': [
            'Mini Lop', 'Lionhead', 'Angorá', 'Rex', 'Fuzzy Lop', 'Hotot'
        ],
        'Hamster': [
            'Sírio', 'Anão Russo', 'Chinês', 'Roborovski'
        ],
        'Réptil': [
            'Jabuti', 'Iguana', 'Gecko', 'Corn Snake', 'Dragão Barbudo'
        ],
        'Outro': [
            'Porquinho-da-índia', 'Furão', 'Tartaruga', 'Cavalo', 'Ovelha'
        ]
    };

    const tipoInput = document.getElementById('tipo');
    const racaInput = document.getElementById('raca');
    const racaList = document.getElementById('lista-racas');

    function preencherRacas(tipoSelecionado) {
        if (!racaList) return;
        racaList.innerHTML = '';

        if (racasPorTipo[tipoSelecionado]) {
            racasPorTipo[tipoSelecionado].forEach(raca => {
                const option = document.createElement('option');
                option.value = raca;
                racaList.appendChild(option);
            });
        }
    }

    if (tipoInput && racaInput && racaList) {
        preencherRacas(tipoInput.value); // Inicializa

        tipoInput.addEventListener('input', function () {
            preencherRacas(this.value);
        });
    }
});
