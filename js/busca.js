// Selecionando os elementos através do id
const formBusca = document.querySelector("#form-busca");
const campoBusca = document.querySelector("#campo-busca");
const divResultados = document.querySelector("#resultados");

// Escondendo a div antes da digitação no campo
divResultados.classList.add("visually-hidden");

// Monitorando o evento de digitação no campo
campoBusca.addEventListener("input", async function(){
    // Verificando se o campo não está vazio
    if(campoBusca.value !== ""){
        try {
            // Enviando os dados do formulário para o PHP
            const resposta = await fetch("resultados.php", {
                method: "POST",
                body: new FormData(formBusca)
            });
    
            // Aguardando e capturando a resposta do PHP
            const dados = await resposta.text();

            // Removendo a classe de esconder a div e exibindo os resultados
            divResultados.classList.remove("visually-hidden");

            // Adicionando os resultados na div
            divResultados.innerHTML = dados;
        } catch (error) {
            console.log("Deu ruim na busca: "+error);
        }
    } else {
        // Se o campo estiver vazio, escondendo a div novamente
        divResultados.classList.add("visually-hidden");
        divResultados.innerHTML = "";
    }
});



