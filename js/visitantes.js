class Visitante {
    constructor(nome, documento, telefone, email, foto, dataNasc, moradorVisitado, motivo) {
        this.id = Date.now();
        this.nome = nome;
        this.documento = documento;
        this.telefone = telefone;
        this.email = email;
        this.foto = foto;
        this.dataNasc = dataNasc;
        this.moradorVisitado = moradorVisitado;
        this.motivo = motivo;
        this.entrada = new Date();
        this.saida = null;
    }
}

const gerenciadorVisitantes = {
    visitantes: [],

    init() {
        this.visitantes = JSON.parse(localStorage.getItem('visitantes')) || [];
        this.atualizarLista();
        this.configurarFormulario();
        this.carregarMoradores();
        this.atualizarContadores();
    },

    adicionar(visitante) {
        this.visitantes.push(visitante);
        this.salvar();
        this.atualizarLista();
        this.atualizarContadores();
    },

    registrarSaida(id) {
        const visitante = this.visitantes.find(v => v.id === id);
        if (visitante) {
            visitante.saida = new Date();
            this.salvar();
            this.atualizarLista();
            this.atualizarContadores();
        }
    },

    salvar() {
        localStorage.setItem('visitantes', JSON.stringify(this.visitantes));
    },

    atualizarLista() {
        const lista = document.getElementById('lista-visitantes');
        if (!lista) return;

        lista.innerHTML = '';

        const visitantesPresentes = this.visitantes.filter(v => !v.saida);

        if (visitantesPresentes.length === 0) {
            const semRegistros = document.createElement('div');
            semRegistros.className = 'sem-registros';
            semRegistros.innerHTML = `
                <i class="fas fa-user-slash"></i>
                <p>Nenhum visitante presente</p>
            `;
            lista.appendChild(semRegistros);
            return;
        }

        visitantesPresentes.forEach(visitante => {
            const div = document.createElement('div');
            div.className = 'lista-item';
            div.innerHTML = `
                <h4>${visitante.nome}</h4>
                <p><strong>Documento:</strong> ${visitante.documento}</p>
                <p><strong>Telefone:</strong> ${visitante.telefone}</p>
                <p><strong>Visitando:</strong> ${visitante.moradorVisitado}</p>
                <p><strong>Motivo:</strong> ${visitante.motivo}</p>
                <p><strong>Entrada:</strong> ${new Date(visitante.entrada).toLocaleString()}</p>
                <button onclick="gerenciadorVisitantes.registrarSaida(${visitante.id})" class="btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Registrar Saída
                </button>
            `;
            lista.appendChild(div);
        });
    },

    carregarMoradores() {
        const moradores = JSON.parse(localStorage.getItem('moradores')) || [];
        const select = document.getElementById('morador-visita');
        if (!select) return;
        
        // Limpar opções existentes exceto a primeira
        select.innerHTML = '<option value="">Selecione um morador</option>';
        
        moradores.forEach(morador => {
            const option = document.createElement('option');
            option.value = morador.nome;
            option.textContent = `${morador.nome} - Residência ${morador.residencia}`;
            select.appendChild(option);
        });
    },

    configurarFormulario() {
        const form = document.getElementById('form-visitante');
        if (!form) return;

        // Configurar máscara para CPF
        const documentoInput = form.querySelector('#documento');
        if (documentoInput) {
            documentoInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
                    e.target.value = value;
                }
            });
        }

        // Configurar máscara para telefone
        const telefoneInput = form.querySelector('#telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                    e.target.value = value;
                }
            });
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const visitante = new Visitante(
                form['nome-visitante'].value,
                form.documento.value,
                form.telefone.value,
                form.email.value,
                form.foto.value,
                form.data_nasc.value,
                form['morador-visita'].value,
                form.motivo.value
            );
            
            this.adicionar(visitante);
            form.reset();
            alert('Visitante registrado com sucesso!');
        });

        // Configurar botão de submit se não estiver no form
        const btnSubmit = document.querySelector('button[type="submit"]');
        if (btnSubmit && !btnSubmit.form) {
            btnSubmit.addEventListener('click', (e) => {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            });
        }
    },

    atualizarContadores() {
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        // Conta visitantes de hoje
        const visitantesHoje = this.visitantes.filter(v => {
            const dataEntrada = new Date(v.entrada);
            dataEntrada.setHours(0, 0, 0, 0);
            return dataEntrada.getTime() === hoje.getTime();
        }).length;

        localStorage.setItem('visitantesHoje', visitantesHoje);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    gerenciadorVisitantes.init();
});