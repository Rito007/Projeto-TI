//É utilizado jquery e datatables.js
//É adicionado as logs à datatable fazendo de uso da api
$.getJSON("api/api.php?valoresSensoresLog", function (resposta) {
    let tbody = '';
    let id = 1;
   

    
    resposta.forEach(sensor => {
        const logs = sensor.logs.split('\n');
        logs.forEach(log => {
            if(log.trim() === "")
                return;
            const [valor, dataHora] = log.split(',');
            let valorFinal = valor;
            sensor.unidade === "VF" ? (valor === "1" ? valorFinal= "Ativo" :valorFinal= "Inativo") 
            : valorFinal =`${valor}${sensor.unidade}`
        
            tbody += `<tr>
                <td>${id++}</td>
                <td>${sensor.nome}</td>
                <td>${valorFinal}</td>
                <td>${dataHora}</td>
            </tr>`;
        });
    });
    

    $('#historico-body').html(tbody);
    let tabela = $('#tabelaHistorico').DataTable({
        language: {
            url: 'assets/dataTablesLang.json'
        },
        order: [[3, 'desc']]
    });
    const valorSelecionado = $('#filtroEstado').val();
    tabela.column(1).search(valorSelecionado).draw();

    $('#filtroEstado').on('change', function () {
        const valorSelecionado = $(this).val();
        tabela.column(1).search(valorSelecionado).draw();
      });
    
});
