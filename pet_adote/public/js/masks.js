// MÁSCARA DE CPF: 122.135.047-17
function mascaraCPF(i) {
    let v = i.value.replace(/\D/g, ""); // Remove tudo que não é número
    
    v = v.replace(/(\d{3})(\d)/, "$1.$2"); 
    v = v.replace(/(\d{3})(\d)/, "$1.$2"); 
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); 
    
    i.value = v;
}

// MÁSCARA DE TELEFONE: (27) 9 9621 - 0936
function mascaraTelefone(i) {
    let v = i.value.replace(/\D/g, ""); // Remove tudo que não é número
    
    if (v.length > 0) {
        v = v.replace(/^(\d{2})(\d)/, "($1) $2"); // (27) 9
    }
    if (v.length > 3) {
        v = v.replace(/^(\d{2})(\d{1})(\d)/, "($1) $2 $3"); // (27) 9 9
    }
    if (v.length > 7) {
        v = v.replace(/^(\d{2})(\d{1})(\d{4})(\d)/, "($1) $2 $3 - $4"); // (27) 9 9621 - 0
    }
    
    i.value = v;
}