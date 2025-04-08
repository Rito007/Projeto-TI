$.getJSON("/Projeto%20TI/api/api.php?valoresSensoresLog", function (resposta) {
    let tbody = '';
    let id = 1;

    resposta.forEach(sensor => {
        const logs = sensor.logs.split('\n');
        logs.forEach(log => {
            const [valor, dataHora] = log.split(',');
            const unidade = valor.includes('%') ? '%' : valor.includes('C') ? '°C' : '';
            const valorLimpo = valor.replace(/[^\d.,-]/g, '');
            tbody += `<tr>
                <td>${id++}</td>
                <td>${sensor.nome}</td>
                <td>${valorLimpo}</td>
                <td>${unidade}</td>
                <td>${dataHora}</td>
            </tr>`;
        });
    });

    $('#historico-body').html(tbody);
    $('#tabelaHistorico').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json'
        },
        order: [[4, 'desc']]
    });
});