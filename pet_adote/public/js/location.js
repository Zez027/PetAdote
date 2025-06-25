document.addEventListener('DOMContentLoaded', function () {
    const estadosPorPais = {
        Brasil: [
            'Acre', 'Alagoas', 'Amapá', 'Amazonas', 'Bahia', 'Ceará', 'Distrito Federal',
            'Espírito Santo', 'Goiás', 'Maranhão', 'Mato Grosso', 'Mato Grosso do Sul',
            'Minas Gerais', 'Pará', 'Paraíba', 'Paraná', 'Pernambuco', 'Piauí',
            'Rio de Janeiro', 'Rio Grande do Norte', 'Rio Grande do Sul', 'Rondônia',
            'Roraima', 'Santa Catarina', 'São Paulo', 'Sergipe', 'Tocantins'
        ]
    };

    const cidadesPorEstado = {
        'São Paulo': ['São Paulo', 'Campinas', 'Santos', 'São José dos Campos', 'Sorocaba'],
        'Rio de Janeiro': ['Rio de Janeiro', 'Niterói', 'Duque de Caxias', 'Nova Iguaçu'],
        'Minas Gerais': ['Belo Horizonte', 'Uberlândia', 'Contagem', 'Juiz de Fora'],
        'Bahia': ['Salvador', 'Feira de Santana', 'Vitória da Conquista'],
        'Paraná': ['Curitiba', 'Londrina', 'Maringá'],
        'Espírito Santo': [
            'Afonso Cláudio', 'Água Doce do Norte', 'Águia Branca', 'Alegre', 'Alfredo Chaves',
            'Alto Rio Novo', 'Anchieta', 'Apiacá', 'Aracruz', 'Atilio Vivacqua',
            'Baixo Guandu', 'Barra de São Francisco', 'Boa Esperança', 'Bom Jesus do Norte', 'Brejetuba',
            'Cachoeiro de Itapemirim', 'Cariacica', 'Castelo', 'Colatina', 'Conceição da Barra',
            'Conceição do Castelo', 'Divino de São Lourenço', 'Domingos Martins', 'Dores do Rio Preto',
            'Ecoporanga', 'Fundão', 'Governador Lindenberg', 'Guaçuí', 'Guarapari',
            'Ibatiba', 'Ibiraçu', 'Ibitirama', 'Iconha', 'Irupi',
            'Itaguaçu', 'Itapemirim', 'Itarana', 'Iúna', 'Jaguaré',
            'Jerônimo Monteiro', 'João Neiva', 'Laranja da Terra', 'Linhares', 'Mantenópolis',
            'Marataízes', 'Marechal Floriano', 'Marilândia', 'Mimoso do Sul', 'Montanha',
            'Mucurici', 'Muniz Freire', 'Muqui', 'Nova Venécia', 'Pancas',
            'Pedro Canário', 'Pinheiros', 'Piúma', 'Ponto Belo', 'Presidente Kennedy',
            'Rio Bananal', 'Rio Novo do Sul', 'Santa Leopoldina', 'Santa Maria de Jetibá', 'Santa Teresa',
            'São Domingos do Norte', 'São Gabriel da Palha', 'São José do Calçado', 'São Mateus', 'São Roque do Canaã',
            'Serra', 'Sooretama', 'Vargem Alta', 'Venda Nova do Imigrante', 'Viana',
            'Vila Pavão', 'Vila Valério', 'Vila Velha', 'Vitória'
        ]
        // Adicione mais estados e cidades conforme necessário
    };

    const estadoInput = document.getElementById('estado');
    const estadoList = document.getElementById('lista-estados');
    const cidadeInput = document.getElementById('cidade');
    const cidadeList = document.getElementById('lista-cidades');

    // Preenche a lista de estados para o datalist
    function preencherEstados() {
        if (!estadoList) return;
        estadoList.innerHTML = ''; // Limpa antes de preencher para evitar duplicados
        estadosPorPais['Brasil'].forEach(estado => {
            const option = document.createElement('option');
            option.value = estado;
            estadoList.appendChild(option);
        });
    }

    // Popula as cidades conforme o estado e mantém ou não o valor do input cidade
    function popularCidades(estado, manterValor = true) {
        if (!cidadeList) return;
        cidadeList.innerHTML = '';

        if (!manterValor) {
            cidadeInput.value = '';
        }

        if (cidadesPorEstado[estado]) {
            cidadesPorEstado[estado].forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade;
                cidadeList.appendChild(option);
            });
        }
    }

    preencherEstados();

    if (estadoInput && cidadeInput) {
        // Guarda o estado atual para comparação
        let estadoAtual = estadoInput.value;
        cidadeInput.setAttribute('data-estado-atual', estadoAtual);

        // Se o estado já estiver preenchido no carregamento, popula as cidades mantendo o valor da cidade
        if (estadoAtual) {
            popularCidades(estadoAtual, true);
        }

        estadoInput.addEventListener('input', function () {
            if (this.value !== cidadeInput.getAttribute('data-estado-atual')) {
                // Estado mudou, popula cidades limpando o valor atual da cidade
                popularCidades(this.value, false);
                cidadeInput.setAttribute('data-estado-atual', this.value);
            }
        });
    }
});
