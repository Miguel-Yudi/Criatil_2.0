/*
let data = new Date(); // Obtém a data atual
let inputData = document.getElementById("nascimento"); // Seleciona o input

// Formata a data para o padrão YYYY-MM-DD
let ano = data.getFullYear() - 18;
let mes = String(data.getMonth() + 1).padStart(2, '0'); // Mês começa do 0, então adicionamos +1
let dia = String(data.getDate()).padStart(2, '0');

// Define o valor do input
let dataMax = `${ano}-${mes}-${dia}`;
let dataMin = `${"1920"}-${"01"}-${"01"}`;

inputData.setAttribute("max", dataMax)
inputData.setAttribute("min", dataMin)
*/