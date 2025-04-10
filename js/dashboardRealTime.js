document.addEventListener("DOMContentLoaded", function () {
    let primeiroCarregamento = true;

    //Atualiza todos os valores da tabela dos sensores exceto o nome
    function atualizarTabelaSensores(data) {
        data.forEach(sensor => {
            let row = document.querySelector(`[data-sensor="${sensor.nome}t"]`);
            if (row) {
                let valorCell = row.querySelector("td:nth-child(2)");
                let dataCell = row.querySelector("td:nth-child(3)");
                let estadoCell = row.querySelector("td:nth-child(4) .badge");

                if (valorCell && dataCell && estadoCell) {
                    
                    dataCell.innerHTML = sensor.data_de_atualizacao;
                    valorCell.innerHTML = sensor.valor + sensor.unidade;
                    let estadoClasse = 'text-bg-primary';
                    let estadoTexto = 'Normal';
        
                    if (sensor.nome === 'Temperatura' && parseFloat(sensor.valor) > 22) {
                        estadoClasse = 'text-bg-danger';
                        estadoTexto = 'Elevada';
                    } 
                    else if (sensor.unidade === 'VF') {
                        estadoClasse = 'text-bg-success';
                        sensor.valor == 1 ? estadoTexto = "Ativo" : estadoTexto= "Inativo";
                        sensor.valor == 1 ? estadoClasse = "text-bg-success" : estadoClasse= "text-bg-danger";
                  
                        valorCell.innerHTML = estadoTexto;
                    }
                    
                    estadoCell.className = `badge rounded-pill ${estadoClasse}`;
                    estadoCell.innerHTML = estadoTexto;
                }
            }
        });
    }
    //Atualiza os valores dos cartões dos sensores
    function atualizarCardsSensores(data) {
        data.forEach(sensor => {
            let card = document.querySelector(`[data-sensor="${sensor.nome}"]`);
            //faz uma animação quando é alterado o valor
            card.classList.add("animAlteracao");
            setTimeout(() => card.classList.remove("animAlteracao"), 1500);   

            if (card) {
                console.log(sensor.unidade)
                card.querySelector(".card-header").innerHTML = 
                `<b>${sensor.nome}: ${
                    sensor.unidade === "VF" 
                        ? (sensor.valor === "1" || sensor.valor === 1 ? "Ativo" : "Inativo") 
                        : `${sensor.valor}${sensor.unidade}`
                }</b>`;
                card.querySelector(".card-body img").src = sensor.imagem;
                let href = card.querySelector(".card-footer span a").getAttribute('href');
                card.querySelector(".card-footer span").innerHTML = `<b>Atualização:</b> ${sensor.data_de_atualizacao} - <a href="${href}">Histórico</a>`;
            }
        });
    }

    function atualizarLotacaoBus()
    {

        //Como a api dos valores sensores não retorna o valor de pessoas no autocarro é feito outro fetch
        fetch('api/api.php?lotacaoBus')
        .then(response => {
            console.log(response)
            if (!response.ok) {

                throw new Error('Erro na resposta da API');
            }
            return response.json();
        })
        .then(data => {
            //altera o valor
           document.getElementById('lotacaoBus').innerText= `Quantidade Pessoas Autocarro: ${data}`
            
        })
        .catch(error => console.error("Erro ao atualizar sensores:", error));
    }

    //Recebe os valores dos sensores pela api url: api.php?valoresSensores
    function atualizarValoresSensores() {
        fetch('api/api.php?valoresSensores')
            .then(response => {
                console.log(response)
                if (!response.ok) {

                    throw new Error('Erro na resposta da API');
                }
                return response.json();
            })
            .then(data => {
                //Autoexplicativo
                atualizarTabelaSensores(data);
                atualizarCardsSensores(data);
                atualizarLotacaoBus();
            })
            .catch(error => console.error("Erro ao atualizar sensores:", error));
    }
    if(primeiroCarregamento)
    {
        //O php apenas adiciona os objetos, aqui é feita as alterações dos valores desses objetos
        atualizarValoresSensores()
        primeiroCarregamento = false;    
    }
    //Timer de autoatualização de 5 segundos
    setInterval(atualizarValoresSensores, 5000);
});
