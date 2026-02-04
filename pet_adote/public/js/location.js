document.addEventListener("DOMContentLoaded", function () {
    const estadoSelect = document.getElementById("estado");
    const cidadeSelect = document.getElementById("cidade");

    // Se não houver os campos na tela, para a execução
    if (!estadoSelect || !cidadeSelect) return;

    const selectedEstado = estadoSelect.getAttribute("data-selected");
    const selectedCidade = cidadeSelect.getAttribute("data-selected");

    // 1. Busca os estados na API do IBGE
    fetch("https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome")
        .then(response => response.json())
        .then(estados => {
            // Limpa o "Carregando..." e põe "Selecione..."
            estadoSelect.innerHTML = '<option value="">Selecione...</option>';

            estados.forEach(estado => {
                const option = document.createElement("option");
                option.value = estado.sigla; // Ex: SP
                option.textContent = estado.nome; // Ex: São Paulo
                
                // Mantém selecionado se for edição ou erro de validação
                if (selectedEstado && selectedEstado === estado.sigla) {
                    option.selected = true;
                }
                estadoSelect.appendChild(option);
            });

            // Se já tiver estado selecionado (edição/erro), carrega as cidades dele
            if (selectedEstado) {
                carregarCidades(selectedEstado, selectedCidade);
            }
        })
        .catch(error => console.error("Erro ao carregar estados:", error));

    // 2. Quando o usuário troca o estado
    estadoSelect.addEventListener("change", function () {
        const uf = this.value;
        if (uf) {
            carregarCidades(uf, null);
        } else {
            cidadeSelect.innerHTML = '<option value="">Selecione o estado...</option>';
            cidadeSelect.disabled = true;
        }
    });

    // 3. Função que busca as cidades
    function carregarCidades(uf, cidadePreSelecionada) {
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.disabled = true;

        fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios?orderBy=nome`)
            .then(response => response.json())
            .then(cidades => {
                cidadeSelect.innerHTML = '<option value="">Selecione...</option>';
                
                cidades.forEach(cidade => {
                    const option = document.createElement("option");
                    option.value = cidade.nome; 
                    option.textContent = cidade.nome;

                    if (cidadePreSelecionada && cidadePreSelecionada === cidade.nome) {
                        option.selected = true;
                    }
                    cidadeSelect.appendChild(option);
                });

                cidadeSelect.disabled = false;
            })
            .catch(error => {
                console.error("Erro ao carregar cidades:", error);
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar</option>';
            });
    }
});