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
    ],
        // Adicione mais se quiser
    };

    const estadoInput = document.getElementById('estado');
    const estadoList = document.getElementById('lista-estados');
    const cidadeInput = document.getElementById('cidade');
    const cidadeList = document.getElementById('lista-cidades');

    // Popula estados (fixo Brasil)
    estadosPorPais['Brasil'].forEach(estado => {
        const option = document.createElement('option');
        option.value = estado;
        estadoList.appendChild(option);
    });

    // Popula cidades conforme estado selecionado
    function popularCidades(estado) {
        cidadeList.innerHTML = '';
        cidadeInput.value = '';

        if (cidadesPorEstado[estado]) {
            cidadesPorEstado[estado].forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade;
                cidadeList.appendChild(option);
            });
        }
    }

    estadoInput.addEventListener('input', function () {
        popularCidades(this.value);
    });

    // Se já estiver preenchido (em edição), carrega cidades
    if (estadoInput.value) {
        popularCidades(estadoInput.value);
    }
});
