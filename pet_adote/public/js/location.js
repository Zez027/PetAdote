document.addEventListener("DOMContentLoaded", function () {

    const estados = {
        "ES": [
            'Alegre'
        ]
    };

    const estadoSelect = document.getElementById("estado");
    const cidadeSelect = document.getElementById("cidade");

    if (!estadoSelect || !cidadeSelect) return;

    function carregarEstados(estadoSelecionado = "") {
        estadoSelect.innerHTML = '<option value="">Selecione...</option>';

        Object.keys(estados).forEach(sigla => {
            let option = document.createElement("option");
            option.value = sigla;
            option.textContent = sigla;

            if (sigla === estadoSelecionado) {
                option.selected = true;
            }

            estadoSelect.appendChild(option);
        });
    }

    function carregarCidades(estado, cidadeSelecionada = "") {
        cidadeSelect.innerHTML = '<option value="">Selecione...</option>';

        if (!estado || !estados[estado]) return;

        estados[estado].forEach(cidade => {
            let option = document.createElement("option");
            option.value = cidade;
            option.textContent = cidade;

            if (cidade === cidadeSelecionada) {
                option.selected = true;
            }

            cidadeSelect.appendChild(option);
        });
    }

    // Quando trocar o estado
    estadoSelect.addEventListener("change", function () {
        carregarCidades(this.value, "");
    });

    // Carregar valores antigos vindos do Blade (old() ou valores do PET)
    const estadoAntigo = estadoSelect.getAttribute("data-value") || "";
    const cidadeAntiga = cidadeSelect.getAttribute("data-value") || "";

    carregarEstados(estadoAntigo);

    if (estadoAntigo !== "") {
        carregarCidades(estadoAntigo, cidadeAntiga);
    }
});
