<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Termo de Adoção Responsável</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 30px; color: #333; }
        h1 { text-align: center; color: #2c3e50; font-size: 20px; margin-bottom: 30px; text-transform: uppercase; border-bottom: 2px solid #2c3e50; padding-bottom: 10px;}
        h2 { font-size: 14px; color: #555; background: #f4f4f4; padding: 5px 10px; margin-top: 25px; border-left: 4px solid #3498db; }
        p, li { margin: 8px 0; font-size: 13px; text-align: justify; }
        .info-grid { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-grid td { padding: 5px; border-bottom: 1px dashed #ccc; font-size: 13px; }
        .signatures { margin-top: 60px; text-align: center; width: 100%; }
        .signature-box { width: 45%; display: inline-block; text-align: center; margin-top: 40px; }
        .signature-line { border-top: 1px solid #000; padding-top: 5px; font-size: 12px; margin: 0 20px; }
        .footer { text-align: center; margin-top: 50px; font-size: 11px; color: #777; font-style: italic; }
    </style>
</head>
<body>

    <h1>Termo de Adoção Responsável</h1>

    <p>Pelo presente instrumento, as partes abaixo qualificadas firmam este compromisso, referente à transferência definitiva de guarda do animal descrito a seguir, garantindo o seu bem-estar e posse responsável.</p>

    <h2>1. Dados do Animal (Pet)</h2>
    <table class="info-grid">
        <tr>
            <td><strong>Nome:</strong> {{ $adoptionRequest->pet->nome }}</td>
            <td><strong>Espécie/Raça:</strong> {{ $adoptionRequest->pet->raca ?? 'Não definida' }}</td>
        </tr>
        <tr>
            <td><strong>Porte:</strong> {{ $adoptionRequest->pet->porte }}</td>
            <td><strong>Idade e Gênero:</strong> {{ $adoptionRequest->pet->idade }} / {{ $adoptionRequest->pet->genero }}</td>
        </tr>
    </table>

    <h2>2. Dados do Doador (Guarda Atual)</h2>
    <table class="info-grid">
        <tr>
            <td colspan="2"><strong>Nome:</strong> {{ $adoptionRequest->pet->user->name }}</td>
        </tr>
        <tr>
            <td><strong>E-mail:</strong> {{ $adoptionRequest->pet->user->email }}</td>
            <td><strong>Telefone:</strong> {{ $adoptionRequest->pet->user->telefone ?? 'Não informado' }}</td>
        </tr>
    </table>

    <h2>3. Dados do Adotante (Nova Guarda)</h2>
    <table class="info-grid">
        <tr>
            <td colspan="2"><strong>Nome:</strong> {{ $adoptionRequest->user->name }}</td>
        </tr>
        <tr>
            <td><strong>CPF:</strong> {{ $adoptionRequest->user->cpf ?? '_______________' }}</td>
            <td><strong>Telefone:</strong> {{ $adoptionRequest->user->contato ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Endereço:</strong> {{ $adoptionRequest->user->cidade ?? '____________' }} - {{ $adoptionRequest->user->estado ?? '___' }}</td>
        </tr>
    </table>

    <h2>4. Termo de Compromisso e Declarações</h2>
    <p>O(A) <strong>ADOTANTE</strong>, ao assinar este termo, declara ter plena consciência e concorda que:</p>
    <ul>
        <li>O animal não poderá ser mantido acorrentado, em gaiolas, ou em espaços minúsculos sem condições de higiene.</li>
        <li>Compromete-se a fornecer alimentação de qualidade, água fresca, abrigo contra sol/chuva e cuidados veterinários contínuos (vacinas anuais, vermífugos).</li>
        <li>O animal adotado jamais será utilizado para fins de reprodução comercial, lutas (rinhas), pesquisa ou guarda severa.</li>
        <li>Em caso de mudança de endereço ou impossibilidade de manter o pet, o adotante <strong>não poderá abandoná-lo</strong> na rua nem repassá-lo a terceiros sem prévia comunicação e aprovação do <strong>DOADOR</strong>.</li>
    </ul>

    <div class="footer">
        Documento gerado eletronicamente pelo sistema PetAdote em {{ $adoptionRequest->updated_at->format('d/m/Y H:i') }}.
    </div>

    <div class="signatures">
        <div class="signature-box" style="float: left;">
            <div class="signature-line">
                Assinatura do(a) Doador(a)<br>
                <strong>{{ $adoptionRequest->pet->user->name }}</strong>
            </div>
        </div>
        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                Assinatura do(a) Adotante<br>
                <strong>{{ $adoptionRequest->user->name }}</strong>
            </div>
        </div>
    </div>

</body>
</html>