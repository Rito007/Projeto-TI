document.addEventListener("DOMContentLoaded", function () {
    let primeiroCarregamento = true;

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

    function atualizarCardsSensores(data) {
        data.forEach(sensor => {
            let card = document.querySelector(`[data-sensor="${sensor.nome}"]`);
            if (card) {
                card.classList.add("animAlteracao");
                setTimeout(() => card.classList.remove("animAlteracao"), 1500);

                card.querySelector(".card-header").innerHTML = 
                    `<b>${sensor.nome}: ${
                        sensor.unidade === "VF" 
                            ? (sensor.valor === "1" || sensor.valor === 1 ? "Ativo" : "Inativo") 
                            : `${sensor.valor}${sensor.unidade}`
                    }</b>`;

                card.querySelector(".card-body img").src = sensor.imagem;

                let href = card.querySelector(".card-footer span a").getAttribute('href');
                card.querySelector(".card-footer span").innerHTML = `<b>Atualização:</b> ${sensor.data_de_atualizacao} - <a href="${href}">Histórico</a>`;

                const btnAtivacao = card.querySelector("input#BotaoAtivacao");
                if (btnAtivacao && sensor.unidade === "VF") {
                    if (sensor.valor == 1) {
                        btnAtivacao.value = "Desativar";
                        btnAtivacao.style.backgroundColor = "#dc3545";
                        btnAtivacao.style.color = "#fff";
                    } else {
                        btnAtivacao.value = "Ativar";
                        btnAtivacao.style.backgroundColor = "#198754";
                        btnAtivacao.style.color = "#fff";
                    }
                }
            }
        });
    }

    function atualizarLotacaoBus() {
        fetch('api/api.php?lotacaoBus')
        .then(response => {
            if (!response.ok) throw new Error('Erro na resposta da API');
            return response.json();
        })
        .then(data => {
            document.getElementById('lotacaoBus').innerText= `Quantidade Pessoas Autocarro: ${data}`;
        })
        .catch(error => console.error("Erro ao atualizar sensores:", error));
    }

    function getHoraAtualFormatada() {
        const now = new Date();
        const pad = n => String(n).padStart(2, "0");
        const dd = pad(now.getDate());
        const mm = pad(now.getMonth() + 1);
        const yyyy = now.getFullYear();
        const hh = pad(now.getHours());
        const min = pad(now.getMinutes());
        const ss = pad(now.getSeconds());
        return `${dd}/${mm}/${yyyy} ${hh}:${min}:${ss}`;
    }

    function enviarAtualizacao(sensorNome, novoValor) {
        const dados = new URLSearchParams();
        dados.append("nome", sensorNome);
        dados.append("valor", novoValor);
        dados.append("hora", getHoraAtualFormatada());
        fetch("api/api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: dados.toString()
        })
        .then(res => {
            if (!res.ok) console.log(res.text);
            return res.text();
        })
        .then(data => {
            console.log("Resposta API:", data);
            atualizarValoresSensores();
        })
        .catch(err => console.error(err));
    }

    document.body.addEventListener("click", function (e) {
        const btn = e.target;

        if (btn.tagName === "BUTTON" && (btn.textContent.trim() === "+" || btn.textContent.trim() === "−" || btn.textContent.trim() === "-")) {
            const card = btn.closest(".card[data-sensor]");
            if (!card) return;
            const sensorNome = card.getAttribute("data-sensor");
            const display = card.querySelector(".temp-display");
            if (!display) return;
            let valorAtual = parseFloat(display.textContent);

            if (btn.textContent.trim() === "+") {
                valorAtual += 1;
            } else if (btn.textContent.trim() === "−" || btn.textContent.trim() === "-") {
                valorAtual -= 1;
            }

            display.textContent = valorAtual;
            enviarAtualizacao(sensorNome, valorAtual);
        }

        if (btn.matches("input#BotaoAtivacao")) {
            const card = btn.closest(".card[data-sensor]");
            if (!card) return;
            const sensorNome = card.getAttribute("data-sensor");

            const valorAtual = (btn.value === "Ativar" ? 0 : 1);
            const novoValor = valorAtual === 1 ? 0 : 1;

            enviarAtualizacao(sensorNome, novoValor);
        }
    });

    function atualizarValoresSensores() {
        fetch('api/api.php?valoresSensores')
            .then(response => {
                if (!response.ok) throw new Error('Erro na resposta da API');
                return response.json();
            })
            .then(data => {
                atualizarTabelaSensores(data);
                atualizarCardsSensores(data);
                atualizarLotacaoBus();
            })
            .catch(error => console.error("Erro ao atualizar sensores:", error));
    }

    if (primeiroCarregamento) {
        atualizarValoresSensores();
        primeiroCarregamento = false;
    }

    setInterval(atualizarValoresSensores, 5000);
});
