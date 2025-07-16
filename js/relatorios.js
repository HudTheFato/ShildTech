const gerenciadorRelatorios = {
    dadosFiltrados: [], // Armazena os dados do relatório atual

    init() {
        this.configurarFormulario();
        this.configurarPeriodo();
    },

    configurarPeriodo() {
        const periodo = document.getElementById('periodo');
        const datasPersonalizadas = document.getElementById('datas-personalizadas');
        
        if (periodo && datasPersonalizadas) {
            periodo.addEventListener('change', (e) => {
                if (e.target.value === 'personalizado') {
                    datasPersonalizadas.style.display = 'block';
                } else {
                    datasPersonalizadas.style.display = 'none';
                    this.definirDatasPeriodo(e.target.value);
                }
            });
        }
    },

    definirDatasPeriodo(periodo) {
        const hoje = new Date();
        const dataInicio = document.getElementById('data-inicio');
        const dataFim = document.getElementById('data-fim');

        if (!dataInicio || !dataFim) return;

        switch(periodo) {
            case 'hoje':
                dataInicio.value = this.formatarData(hoje);
                dataFim.value = this.formatarData(hoje);
                break;
            case 'ontem':
                const ontem = new Date(hoje);
                ontem.setDate(ontem.getDate() - 1);
                dataInicio.value = this.formatarData(ontem);
                dataFim.value = this.formatarData(ontem);
                break;
            case 'semana':
                const semanaPassada = new Date(hoje);
                semanaPassada.setDate(semanaPassada.getDate() - 7);
                dataInicio.value = this.formatarData(semanaPassada);
                dataFim.value = this.formatarData(hoje);
                break;
            case 'mes':
                const mesPassado = new Date(hoje);
                mesPassado.setMonth(mesPassado.getMonth() - 1);
                dataInicio.value = this.formatarData(mesPassado);
                dataFim.value = this.formatarData(hoje);
                break;
        }
    },

    formatarData(data) {
        return data.toISOString().split('T')[0];
    },

    gerarRelatorio(tipo, dataInicio, dataFim, filtros = {}) {
        const dados = this.obterDados(tipo);
        this.dadosFiltrados = this.filtrarDados(dados, dataInicio, dataFim, filtros);
        this.exibirInformacoes(this.dadosFiltrados, tipo);
        this.exibirRelatorio(this.dadosFiltrados, tipo);
    },

    filtrarDados(dados, inicio, fim, filtros) {
        let resultado = this.filtrarPorData(dados, inicio, fim);

        if (filtros.busca) {
            const busca = filtros.busca.toLowerCase();
            resultado = resultado.filter(item => 
                (item.nome && item.nome.toLowerCase().includes(busca)) ||
                (item.moradorVisitado && item.moradorVisitado.toLowerCase().includes(busca)) ||
                (item.residencia && item.residencia.toString().includes(busca)) ||
                (item.cpf && item.cpf.toLowerCase().includes(busca)) ||
                (item.email && item.email.toLowerCase().includes(busca))
            );
        }

        if (filtros.ordenacao) {
            resultado = this.ordenarDados(resultado, filtros.ordenacao);
        }

        return resultado;
    },

    ordenarDados(dados, ordenacao) {
        return [...dados].sort((a, b) => {
            switch(ordenacao) {
                case 'data-desc':
                    return new Date(b.entrada || b.dataCadastro || b.dataAdmissao || 0) - new Date(a.entrada || a.dataCadastro || a.dataAdmissao || 0);
                case 'data-asc':
                    return new Date(a.entrada || a.dataCadastro || a.dataAdmissao || 0) - new Date(b.entrada || b.dataCadastro || b.dataAdmissao || 0);
                case 'nome':
                    return (a.nome || '').localeCompare(b.nome || '');
                case 'residencia':
                    return (a.residencia || '').toString().localeCompare((b.residencia || '').toString());
                default:
                    return 0;
            }
        });
    },

    exibirInformacoes(dados, tipo) {
        const info = document.getElementById('relatorio-info');
        if (!info) return;

        const total = dados.length;
        let texto = `Total de registros: ${total}`;

        switch(tipo) {
            case 'visitantes':
                const visitasAtivas = dados.filter(v => !v.saida).length;
                texto += ` | Visitas em andamento: ${visitasAtivas}`;
                break;
            case 'moradores':
                const residencias = new Set(dados.map(m => m.residencia)).size;
                const comAnimais = dados.filter(m => m.animal && m.animal.nome).length;
                const comVeiculos = dados.filter(m => m.veiculo && m.veiculo.placa).length;
                texto += ` | Residências: ${residencias} | Com animais: ${comAnimais} | Com veículos: ${comVeiculos}`;
                break;
            case 'funcionarios':
                const cargosUnicos = new Set(dados.map(f => f.cargo)).size;
                texto += ` | Cargos diferentes: ${cargosUnicos}`;
                break;
            case 'cargos':
                const totalSalarios = dados.reduce((sum, c) => sum + (parseFloat(c.salario) || 0), 0);
                texto += ` | Total em salários: R$ ${totalSalarios.toFixed(2)}`;
                break;
            case 'resumo':
                texto = this.gerarResumoGeral(dados);
                break;
        }

        info.textContent = texto;
    },

    gerarResumoGeral(dados) {
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        const visitasHoje = dados.filter(v => 
            v.entrada && new Date(v.entrada) >= hoje
        ).length;

        const mediaVisitas = dados.length > 0 ? Math.round(dados.length / 30) : 0; // média mensal
        
        return `Visitas hoje: ${visitasHoje} | Média mensal: ${mediaVisitas} visitas`;
    },

    exportarCSV() {
        if (!this.dadosFiltrados.length) {
            alert('Nenhum dado para exportar');
            return;
        }

        const headers = Object.keys(this.dadosFiltrados[0]);
        const csv = [
            headers.join(','),
            ...this.dadosFiltrados.map(row => 
                headers.map(field => JSON.stringify(row[field] || '')).join(',')
            )
        ].join('\n');

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `relatorio_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    },

    imprimir() {
        window.print();
    },

    obterDados(tipo) {
        switch(tipo) {
            case 'visitantes':
                return JSON.parse(localStorage.getItem('visitantes')) || [];
            case 'moradores':
                return JSON.parse(localStorage.getItem('moradores')) || [];
            case 'funcionarios':
                return JSON.parse(localStorage.getItem('funcionarios')) || [];
            case 'cargos':
                return JSON.parse(localStorage.getItem('cargos')) || [];
            case 'resumo':
                // Para resumo, vamos usar dados de visitantes
                return JSON.parse(localStorage.getItem('visitantes')) || [];
            default:
                return [];
        }
    },

    filtrarPorData(dados, inicio, fim) {
        if (!inicio || !fim) return dados;

        const dataInicio = new Date(inicio);
        const dataFim = new Date(fim);
        dataFim.setHours(23, 59, 59, 999); // Incluir todo o dia final

        return dados.filter(item => {
            const data = new Date(item.entrada || item.dataCadastro || item.dataAdmissao || 0);
            return data >= dataInicio && data <= dataFim;
        });
    },

    exibirRelatorio(dados, tipo) {
        const container = document.getElementById('resultado-relatorio');
        if (!container) return;

        container.innerHTML = '';

        if (dados.length === 0) {
            container.innerHTML = '<p>Nenhum registro encontrado para os filtros selecionados.</p>';
            return;
        }

        const tabela = document.createElement('table');
        tabela.className = 'tabela-relatorio';

        // Cabeçalho da tabela
        const thead = document.createElement('thead');
        thead.innerHTML = this.obterCabecalhoTabela(tipo);
        tabela.appendChild(thead);

        // Corpo da tabela
        const tbody = document.createElement('tbody');
        dados.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = this.obterLinhaTabela(item, tipo);
            tbody.appendChild(tr);
        });
        tabela.appendChild(tbody);

        container.appendChild(tabela);
    },

    obterCabecalhoTabela(tipo) {
        switch(tipo) {
            case 'visitantes':
                return `
                    <tr>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Morador Visitado</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>Status</th>
                    </tr>
                `;
            case 'moradores':
                return `
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Residência</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Animal</th>
                        <th>Veículo</th>
                    </tr>
                `;
            case 'funcionarios':
                return `
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Cargo</th>
                        <th>Data Admissão</th>
                        <th>Telefone</th>
                    </tr>
                `;
            case 'cargos':
                return `
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Salário</th>
                        <th>Carga Horária</th>
                    </tr>
                `;
            case 'resumo':
                return `
                    <tr>
                        <th>Nome</th>
                        <th>Morador Visitado</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                    </tr>
                `;
            default:
                return '';
        }
    },

    obterLinhaTabela(item, tipo) {
        switch(tipo) {
            case 'visitantes':
                return `
                    <td>${item.nome || '-'}</td>
                    <td>${item.documento || '-'}</td>
                    <td>${item.moradorVisitado || '-'}</td>
                    <td>${item.entrada ? new Date(item.entrada).toLocaleString() : '-'}</td>
                    <td>${item.saida ? new Date(item.saida).toLocaleString() : '-'}</td>
                    <td>${item.saida ? 'Saiu' : 'Presente'}</td>
                `;
            case 'moradores':
                return `
                    <td>${item.nome || '-'}</td>
                    <td>${item.cpf || '-'}</td>
                    <td>${item.residencia || '-'}</td>
                    <td>${item.telefone || '-'}</td>
                    <td>${item.email || '-'}</td>
                    <td>${item.animal && item.animal.nome ? `${item.animal.nome} (${item.animal.especie})` : 'Não possui'}</td>
                    <td>${item.veiculo && item.veiculo.placa ? `${item.veiculo.marca} ${item.veiculo.modelo} - ${item.veiculo.placa}` : 'Não possui'}</td>
                `;
            case 'funcionarios':
                return `
                    <td>${item.nome || '-'}</td>
                    <td>${item.cpf || '-'}</td>
                    <td>${item.cargo || '-'}</td>
                    <td>${item.dataAdmissao ? new Date(item.dataAdmissao).toLocaleDateString() : '-'}</td>
                    <td>${item.telefone || '-'}</td>
                `;
            case 'cargos':
                return `
                    <td>${item.nome || '-'}</td>
                    <td>${item.descricao || '-'}</td>
                    <td>R$ ${item.salario ? parseFloat(item.salario).toFixed(2) : '0,00'}</td>
                    <td>${item.cargaHoraria || 0}h</td>
                `;
            case 'resumo':
                return `
                    <td>${item.nome || '-'}</td>
                    <td>${item.moradorVisitado || '-'}</td>
                    <td>${item.entrada ? new Date(item.entrada).toLocaleString() : '-'}</td>
                    <td>${item.saida ? new Date(item.saida).toLocaleString() : '-'}</td>
                `;
            default:
                return '';
        }
    },

    configurarFormulario() {
        const form = document.getElementById('form-filtros');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.gerarRelatorio(
                form['tipo-relatorio'].value,
                form['data-inicio'].value,
                form['data-fim'].value,
                {
                    ordenacao: form['ordenacao'].value,
                    busca: form['busca'].value
                }
            );
        });

        // Configurar botão de submit se não estiver no form
        const btnSubmit = document.querySelector('button[type="submit"]');
        if (btnSubmit && !btnSubmit.form) {
            btnSubmit.addEventListener('click', (e) => {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            });
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    gerenciadorRelatorios.init();
});