// Funções para a página inicial
function registrarEntrada() {
    window.location.href = 'pages/visitantes.html';
}

function registrarSaida() {
    window.location.href = 'pages/visitantes.html';
}

function novoMorador() {
    window.location.href = 'pages/cadastro_moradores.html';
}

// Atualizar contadores do dashboard
function atualizarDashboard() {
    const visitantesHoje = parseInt(localStorage.getItem('visitantesHoje')) || 0;
    const moradoresPresentes = parseInt(localStorage.getItem('moradoresPresentes')) || 0;
    const totalResidencias = parseInt(localStorage.getItem('totalResidencias')) || 0;

    const elementoVisitantes = document.getElementById('visitantes-hoje');
    const elementoMoradores = document.getElementById('moradores-presentes');
    const elementoResidencias = document.getElementById('total-residencias');

    if (elementoVisitantes) elementoVisitantes.textContent = formatarNumero(visitantesHoje);
    if (elementoMoradores) elementoMoradores.textContent = formatarNumero(moradoresPresentes);
    if (elementoResidencias) elementoResidencias.textContent = formatarNumero(totalResidencias);

    // Atualiza a cada 30 segundos
    setTimeout(atualizarDashboard, 30000);
}

// Formata números para exibição
function formatarNumero(numero) {
    return numero.toLocaleString('pt-BR');
}

// Função para mostrar notificações
function showNotification(message, type = 'success') {
    // Remove notificações existentes
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Função para gerar dados dos gráficos
function gerarDadosGraficos() {
    const visitantes = JSON.parse(localStorage.getItem('visitantes')) || [];
    const moradores = JSON.parse(localStorage.getItem('moradores')) || [];
    
    // Dados dos últimos 6 meses
    const meses = [];
    const visitantesPorMes = [];
    const moradoresPorMes = [];
    
    for (let i = 5; i >= 0; i--) {
        const data = new Date();
        data.setMonth(data.getMonth() - i);
        const mesAno = data.toLocaleDateString('pt-BR', { month: 'short', year: 'numeric' });
        meses.push(mesAno);
        
        // Contar visitantes do mês
        const visitantesMes = visitantes.filter(v => {
            const dataVisita = new Date(v.entrada);
            return dataVisita.getMonth() === data.getMonth() && 
                   dataVisita.getFullYear() === data.getFullYear();
        }).length;
        visitantesPorMes.push(visitantesMes);
        
        // Contar moradores cadastrados no mês
        const moradoresMes = moradores.filter(m => {
            const dataCadastro = new Date(m.dataCadastro || Date.now());
            return dataCadastro.getMonth() === data.getMonth() && 
                   dataCadastro.getFullYear() === data.getFullYear();
        }).length;
        moradoresPorMes.push(moradoresMes);
    }
    
    return { meses, visitantesPorMes, moradoresPorMes };
}

// Função para criar gráfico simples com Canvas
function criarGrafico() {
    const canvas = document.getElementById('grafico-movimentacao');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const { meses, visitantesPorMes, moradoresPorMes } = gerarDadosGraficos();
    
    // Configurações do gráfico
    const width = canvas.width;
    const height = canvas.height;
    const padding = 60;
    const chartWidth = width - 2 * padding;
    const chartHeight = height - 2 * padding;
    
    // Limpar canvas
    ctx.clearRect(0, 0, width, height);
    
    // Encontrar valores máximos
    const maxVisitantes = Math.max(...visitantesPorMes, 1);
    const maxMoradores = Math.max(...moradoresPorMes, 1);
    const maxValue = Math.max(maxVisitantes, maxMoradores);
    
    // Desenhar eixos
    ctx.strokeStyle = '#ccc';
    ctx.lineWidth = 1;
    
    // Eixo Y
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.stroke();
    
    // Eixo X
    ctx.beginPath();
    ctx.moveTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.stroke();
    
    // Desenhar linhas de grade
    ctx.strokeStyle = '#f0f0f0';
    for (let i = 1; i <= 5; i++) {
        const y = padding + (chartHeight / 5) * i;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.stroke();
    }
    
    // Desenhar labels do eixo Y
    ctx.fillStyle = '#666';
    ctx.font = '12px Arial';
    ctx.textAlign = 'right';
    for (let i = 0; i <= 5; i++) {
        const value = Math.round((maxValue / 5) * (5 - i));
        const y = padding + (chartHeight / 5) * i + 4;
        ctx.fillText(value.toString(), padding - 10, y);
    }
    
    // Desenhar labels do eixo X
    ctx.textAlign = 'center';
    meses.forEach((mes, index) => {
        const x = padding + (chartWidth / (meses.length - 1)) * index;
        ctx.fillText(mes, x, height - padding + 20);
    });
    
    // Função para desenhar linha
    function desenharLinha(dados, cor) {
        ctx.strokeStyle = cor;
        ctx.lineWidth = 3;
        ctx.beginPath();
        
        dados.forEach((valor, index) => {
            const x = padding + (chartWidth / (dados.length - 1)) * index;
            const y = height - padding - (valor / maxValue) * chartHeight;
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
            
            // Desenhar ponto
            ctx.fillStyle = cor;
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, 2 * Math.PI);
            ctx.fill();
        });
        
        ctx.stroke();
    }
    
    // Desenhar linhas dos dados
    desenharLinha(visitantesPorMes, '#3498db');
    desenharLinha(moradoresPorMes, '#2ecc71');
    
    // Desenhar legenda
    ctx.font = '14px Arial';
    ctx.textAlign = 'left';
    
    // Visitantes
    ctx.fillStyle = '#3498db';
    ctx.fillRect(width - 150, 20, 15, 15);
    ctx.fillStyle = '#333';
    ctx.fillText('Visitantes', width - 130, 32);
    
    // Moradores
    ctx.fillStyle = '#2ecc71';
    ctx.fillRect(width - 150, 45, 15, 15);
    ctx.fillStyle = '#333';
    ctx.fillText('Moradores', width - 130, 57);
    
    // Título
    ctx.font = 'bold 16px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Movimentação Mensal', width / 2, 25);
}

// Inicializar dashboard e configurar atualização automática
document.addEventListener('DOMContentLoaded', () => {
    atualizarDashboard();
    criarGrafico();
    
    // Configurar menu mobile
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');
    
    if (menuToggle && menu) {
        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
    
    // Atualizar gráfico a cada 5 minutos
    setInterval(criarGrafico, 5 * 60 * 1000);
});