class Morador {
    constructor(nome, cpf, rg, telefone, email, residencia, animal = null, veiculo = null) {
        this.id = Date.now() + Math.random();
        this.nome = nome;
        this.cpf = cpf;
        this.rg = rg;
        this.telefone = telefone;
        this.email = email;
        this.residencia = residencia;
        this.animal = animal;
        this.veiculo = veiculo;
        this.dataCadastro = new Date().toISOString();
    }
}

class Animal {
    constructor(nome, especie, raca, idade, cor, peso) {
        this.nome = nome;
        this.especie = especie;
        this.raca = raca;
        this.idade = idade;
        this.cor = cor;
        this.peso = peso;
    }
}

class Veiculo {
    constructor(marca, modelo, placa, cor, ano, tipo) {
        this.marca = marca;
        this.modelo = modelo;
        this.placa = placa;
        this.cor = cor;
        this.ano = ano;
        this.tipo = tipo;
    }
}

const gerenciadorMoradores = {
    moradores: [],

    init() {
        this.moradores = JSON.parse(localStorage.getItem('moradores')) || [];
        this.atualizarLista();
        this.configurarFormulario();
        this.atualizarContadores();
    },

    adicionar(morador) {
        if (!this.validarDados(morador)) {
            alert('Por favor, preencha todos os campos obrigatórios do morador corretamente.');
            return false;
        }

        if (this.moradores.some(m => m.cpf === morador.cpf)) {
            alert('CPF já cadastrado.');
            return false;
        }

        this.moradores.push(morador);
        this.salvar();
        this.atualizarLista();
        this.atualizarContadores();
        return true;
    },

    editar(id, dadosAtualizados) {
        if (!this.validarDados(dadosAtualizados)) {
            alert('Por favor, preencha todos os campos obrigatórios do morador corretamente.');
            return false;
        }

        if (this.moradores.some(m => m.cpf === dadosAtualizados.cpf && m.id !== id)) {
            alert('CPF já cadastrado em outro morador.');
            return false;
        }

        const index = this.moradores.findIndex(m => m.id === id);
        if (index !== -1) {
            this.moradores[index] = { ...this.moradores[index], ...dadosAtualizados };
            this.salvar();
            this.atualizarLista();
            this.atualizarContadores();
            return true;
        }
        return false;
    },

    remover(id) {
        if (confirm('Tem certeza que deseja excluir este morador?')) {
            this.moradores = this.moradores.filter(m => m.id !== id);
            this.salvar();
            this.atualizarLista();
            this.atualizarContadores();
        }
    },

    salvar() {
        localStorage.setItem('moradores', JSON.stringify(this.moradores));
    },

    validarDados(morador) {
        // Validar campos obrigatórios
        if (!morador.nome || !morador.cpf || !morador.rg || 
            !morador.telefone || !morador.email || !morador.residencia) {
            return false;
        }

        // Validar formato do email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(morador.email)) {
            return false;
        }

        return true;
    },

    atualizarLista() {
        const lista = document.getElementById('lista-moradores');
        if (!lista) return;

        lista.innerHTML = '';
        
        if (this.moradores.length === 0) {
            const semRegistros = document.createElement('div');
            semRegistros.className = 'sem-registros';
            semRegistros.innerHTML = `
                <i class="fas fa-user-slash"></i>
                <p>Nenhum morador cadastrado</p>
            `;
            lista.appendChild(semRegistros);
            return;
        }

        this.moradores.forEach(morador => {
            const div = document.createElement('div');
            div.classList.add('morador-item');
            div.innerHTML = `
                <div class="morador-info">
                    <h3><i class="fas fa-user"></i> ${morador.nome}</h3>
                    <p><i class="fas fa-id-card"></i> <strong>CPF:</strong> ${morador.cpf}</p>
                    <p><i class="fas fa-id-badge"></i> <strong>RG:</strong> ${morador.rg}</p>
                    <p><i class="fas fa-phone"></i> <strong>Telefone:</strong> ${morador.telefone}</p>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> ${morador.email}</p>
                    <p><i class="fas fa-home"></i> <strong>Residência:</strong> ${morador.residencia}</p>
                </div>
                ${morador.animal ? `
                <div class="animal-info">
                    <h4><i class="fas fa-paw"></i> Animal de Estimação</h4>
                    <p><strong>Nome:</strong> ${morador.animal.nome}</p>
                    <p><strong>Espécie:</strong> ${morador.animal.especie}</p>
                    <p><strong>Raça:</strong> ${morador.animal.raca || 'Não informado'}</p>
                    <p><strong>Idade:</strong> ${morador.animal.idade || 'Não informado'} anos</p>
                    <p><strong>Cor:</strong> ${morador.animal.cor || 'Não informado'}</p>
                    <p><strong>Peso:</strong> ${morador.animal.peso || 'Não informado'} kg</p>
                </div>
                ` : ''}
                ${morador.veiculo ? `
                <div class="veiculo-info">
                    <h4><i class="fas fa-car"></i> Veículo</h4>
                    <p><strong>Marca/Modelo:</strong> ${morador.veiculo.marca} ${morador.veiculo.modelo}</p>
                    <p><strong>Placa:</strong> ${morador.veiculo.placa}</p>
                    <p><strong>Cor:</strong> ${morador.veiculo.cor}</p>
                    <p><strong>Ano:</strong> ${morador.veiculo.ano}</p>
                    <p><strong>Tipo:</strong> ${morador.veiculo.tipo}</p>
                </div>
                ` : ''}
                <div class="botoes-acao">
                    <button onclick="gerenciadorMoradores.carregarParaEdicao(${morador.id})" class="btn-editar">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button onclick="gerenciadorMoradores.remover(${morador.id})" class="btn-excluir">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </div>
            `;
            lista.appendChild(div);
        });
    },

    atualizarContadores() {
        // Atualiza o total de residências
        const totalResidencias = new Set(this.moradores.map(m => m.residencia)).size;
        localStorage.setItem('totalResidencias', totalResidencias);

        // Atualiza o contador de moradores presentes
        localStorage.setItem('moradoresPresentes', this.moradores.length);
    },

    configurarFormulario() {
        const formMorador = document.getElementById('form-morador');
        const formAnimal = document.getElementById('form-animal');
        const formVeiculo = document.getElementById('form-veiculo');
        
        if (!formMorador) return;

        const btnSubmit = formMorador.querySelector('button[type="submit"]') || 
                         formMorador.querySelector('.btn-primary');
        let moradorEmEdicao = null;

        // Configurar máscaras
        const cpfInput = formMorador.querySelector('#cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
                    e.target.value = value;
                }
            });
        }

        const telefoneInput = formMorador.querySelector('#telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                    e.target.value = value;
                }
            });
        }

        // Máscara para placa do veículo
        const placaInput = formVeiculo ? formVeiculo.querySelector('#placa') : null;
        if (placaInput) {
            placaInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
                if (value.length <= 7) {
                    value = value.replace(/^([A-Z]{3})(\d{4}).*/, '$1-$2');
                    e.target.value = value;
                }
            });
        }

        // Configurar envio do formulário
        formMorador.addEventListener('submit', (e) => {
            e.preventDefault();

            const dadosMorador = {
                nome: formMorador.nome.value,
                cpf: formMorador.cpf.value,
                rg: formMorador.rg.value,
                telefone: formMorador.telefone.value,
                email: formMorador.email.value,
                residencia: formMorador.residencia.value
            };

            // Verificar se há dados do animal
            if (formAnimal) {
                const nomeAnimal = formAnimal.querySelector('#nome-animal');
                if (nomeAnimal && nomeAnimal.value) {
                    dadosMorador.animal = new Animal(
                        nomeAnimal.value,
                        formAnimal.querySelector('#especie')?.value || '',
                        formAnimal.querySelector('#raca')?.value || '',
                        parseInt(formAnimal.querySelector('#idade-animal')?.value) || 0,
                        formAnimal.querySelector('#cor-animal')?.value || '',
                        parseFloat(formAnimal.querySelector('#peso-animal')?.value) || 0
                    );
                }
            }

            // Verificar se há dados do veículo
            if (formVeiculo) {
                const marcaVeiculo = formVeiculo.querySelector('#marca');
                if (marcaVeiculo && marcaVeiculo.value) {
                    dadosMorador.veiculo = new Veiculo(
                        marcaVeiculo.value,
                        formVeiculo.querySelector('#modelo')?.value || '',
                        formVeiculo.querySelector('#placa')?.value || '',
                        formVeiculo.querySelector('#cor-veiculo')?.value || '',
                        parseInt(formVeiculo.querySelector('#ano')?.value) || 0,
                        formVeiculo.querySelector('#tipo-veiculo')?.value || ''
                    );
                }
            }

            if (moradorEmEdicao) {
                if (this.editar(moradorEmEdicao, dadosMorador)) {
                    moradorEmEdicao = null;
                    if (btnSubmit) btnSubmit.textContent = 'Cadastrar Morador';
                    formMorador.reset();
                    if (formAnimal) formAnimal.reset();
                    if (formVeiculo) formVeiculo.reset();
                    alert('Morador atualizado com sucesso!');
                }
            } else {
                const morador = new Morador(
                    dadosMorador.nome,
                    dadosMorador.cpf,
                    dadosMorador.rg,
                    dadosMorador.telefone,
                    dadosMorador.email,
                    dadosMorador.residencia,
                    dadosMorador.animal,
                    dadosMorador.veiculo
                );
                if (this.adicionar(morador)) {
                    formMorador.reset();
                    if (formAnimal) formAnimal.reset();
                    if (formVeiculo) formVeiculo.reset();
                    alert('Morador cadastrado com sucesso!');
                }
            }
        });

        // Configurar botão de limpar
        const btnLimpar = formMorador.querySelector('button[type="reset"]') || 
                         formMorador.querySelector('.btn-secondary');
        if (btnLimpar) {
            btnLimpar.addEventListener('click', () => {
                moradorEmEdicao = null;
                if (btnSubmit) btnSubmit.textContent = 'Cadastrar Morador';
                if (formAnimal) formAnimal.reset();
                if (formVeiculo) formVeiculo.reset();
            });
        }

        // Método para carregar dados para edição
        this.carregarParaEdicao = (id) => {
            const morador = this.moradores.find(m => m.id === id);
            if (morador) {
                moradorEmEdicao = morador.id;
                formMorador.nome.value = morador.nome;
                formMorador.cpf.value = morador.cpf;
                formMorador.rg.value = morador.rg;
                formMorador.telefone.value = morador.telefone;
                formMorador.email.value = morador.email;
                formMorador.residencia.value = morador.residencia;

                if (formAnimal && morador.animal) {
                    const nomeAnimal = formAnimal.querySelector('#nome-animal');
                    const especie = formAnimal.querySelector('#especie');
                    const raca = formAnimal.querySelector('#raca');
                    const idade = formAnimal.querySelector('#idade-animal');
                    const cor = formAnimal.querySelector('#cor-animal');
                    const peso = formAnimal.querySelector('#peso-animal');
                    
                    if (nomeAnimal) nomeAnimal.value = morador.animal.nome || '';
                    if (especie) especie.value = morador.animal.especie || '';
                    if (raca) raca.value = morador.animal.raca || '';
                    if (idade) idade.value = morador.animal.idade || '';
                    if (cor) cor.value = morador.animal.cor || '';
                    if (peso) peso.value = morador.animal.peso || '';
                } else if (formAnimal) {
                    formAnimal.reset();
                }

                if (formVeiculo && morador.veiculo) {
                    const marca = formVeiculo.querySelector('#marca');
                    const modelo = formVeiculo.querySelector('#modelo');
                    const placa = formVeiculo.querySelector('#placa');
                    const cor = formVeiculo.querySelector('#cor-veiculo');
                    const ano = formVeiculo.querySelector('#ano');
                    const tipo = formVeiculo.querySelector('#tipo-veiculo');
                    
                    if (marca) marca.value = morador.veiculo.marca || '';
                    if (modelo) modelo.value = morador.veiculo.modelo || '';
                    if (placa) placa.value = morador.veiculo.placa || '';
                    if (cor) cor.value = morador.veiculo.cor || '';
                    if (ano) ano.value = morador.veiculo.ano || '';
                    if (tipo) tipo.value = morador.veiculo.tipo || '';
                } else if (formVeiculo) {
                    formVeiculo.reset();
                }

                if (btnSubmit) btnSubmit.textContent = 'Salvar Alterações';
                formMorador.scrollIntoView({ behavior: 'smooth' });
            }
        };
    }
};

// Inicializar o gerenciador quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    gerenciadorMoradores.init();
});