document.addEventListener("DOMContentLoaded", function () {

    function atualizarTabelaSensores(data) {
        data.forEach(sensor => {
            let row = document.querySelector(`[data-sensor="${sensor.nome}t"]`);
            console.log(row)
            if (row) {
                let valorCell = row.querySelector("td:nth-child(2)");
                let dataCell = row.querySelector("td:nth-child(3)");
                let estadoCell = row.querySelector("td:nth-child(4) .badge");

                if (valorCell && dataCell && estadoCell) {
                    valorCell.innerHTML = sensor.valor;
                    dataCell.innerHTML = sensor.data_de_atualizacao;

                    let estadoClasse = 'text-bg-primary';
                    let estadoTexto = 'Normal';

                    if (sensor.estado === 'Elevada') {
                        estadoClasse = 'text-bg-danger';
                        estadoTexto = 'Elevada';
                    } else if (sensor.estado === 'Ativo') {
                        estadoClasse = 'text-bg-success';
                        estadoTexto = 'Ativo';
                    }

                    estadoCell.className = `badge rounded-pill ${estadoClasse}`;
                    estadoCell.innerHTML = estadoTexto;
                }
            }
        });
    }

    function atualizarCardsSensores(data) {
        data.forEach(sensor => {
            let card = document.querySelector(`[data-sensor="${sensor.nome}"]`);

            if (card) {
                card.querySelector(".card-header").innerHTML = `<b>${sensor.nome}: ${sensor.valor}</b>`;
                card.querySelector(".card-body img").src = sensor.imagem;
                card.querySelector(".card-footer span").innerHTML = `<b>Atualização:</b> ${sensor.data_de_atualizacao} - <a href="#">Histórico</a>`;
            }
        });
    }

    function atualizarValoresSensores() {
        fetch('api/api.php?valoresSensores')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta da API');
                }
                return response.json();
            })
            .then(data => {
                atualizarTabelaSensores(data);
                atualizarCardsSensores(data);
            })
            .catch(error => console.error("Erro ao atualizar sensores:", error));
    }

    setInterval(atualizarValoresSensores, 5000);
});
