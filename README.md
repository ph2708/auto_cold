# 🚗 Auto Cold - Sistema de Gestão para Auto Elétrica & Mecânica

Sistema completo desenvolvido em **Laravel (PHP 8.2+)**, banco de dados **MySQL 8.0** e containerizado com **Docker**. O sistema é focado em oficinas automotivas com ênfase em **auto elétrica** (alternadores, motores de partida, baterias, relés, fusíveis, chicotes e sensores), controle de estoque com **Kardex**, gestão de **fornecedores** e controle de **acessos por usuário e perfil (ACL)**.

---

## 🚀 Como Executar o Projeto com Docker

### 1. Iniciar os Containers
Os containers já estão configurados e podem ser gerenciados com o Docker Compose:
```bash
docker compose up -d
```

### 2. Acessos aos Serviços
- **Aplicação Web:** [http://localhost:8000](http://localhost:8000)
- **phpMyAdmin (Banco de Dados):** [http://localhost:8088](http://localhost:8088)
  - Servidor: `db`
  - Usuário: `root`
  - Senha: `root_secret`
- **Porta MySQL Direta:** `localhost:3307` (banco: `auto_cold`, usuário: `auto_cold_user`, senha: `auto_cold_secret`)

---

## 🔑 Usuários Pré-Cadastrados para Teste

| Papel / Perfil | Usuário (`username` ou `email`) | Senha | Descrição de Acesso |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin` ou `admin@autocold.com.br` | `admin123` | Acesso total a todas as telas, relatórios, usuários e cadastros |
| **Eletricista** | `carlos.eletrica` | `eletrica123` | Consulta catálogo, especificações elétricas e baixa de peças |
| **Estoquista** | `marcos.estoque` | `estoque123` | Entrada/saída de mercadorias, fornecedores e localização de almoxarifado |

---

## 🛠️ Módulos & Recursos Implementados

### 1. Autenticação & Controle de Acessos (ACL)
- Login seguro por **e-mail** ou **nome de usuário** com senha criptografada (`bcrypt`).
- Middleware `CheckRole` para proteção de rotas administrativas e bloqueio de usuários inativos.
- Gestão completa de usuários (`/users`) com atribuição de funções (Administrador, Gerente, Eletricista, Estoquista).

### 2. Gestão de Fornecedores (`/suppliers`)
- Cadastro completo com Razão Social, Nome Fantasia, CNPJ/CPF, Inscrição Estadual, Contato/Vendedor, WhatsApp, E-mail e Endereço.
- Rastreamento e contagem de itens fornecidos por distribuidor (ex: Bosch, Moura, DNI, Osram).

### 3. Catálogo de Peças & Auto Elétrica (`/products`)
- Campos técnicos dedicados para componentes elétricos:
  - **Tensão / Voltagem:** 12V, 24V, Bivolt.
  - **Amperagem / Potência:** Ex: 60Ah, 90A, 55W, 1.4kW.
  - **Pinagem / Conectores:** Ex: 4 pinos, 5 pinos, chicotes.
  - **Compatibilidade Veicular:** Ex: Gol G5/G6, Uno Fire, Onix, etc.
  - **Códigos:** SKU interno, Código Original / OEM da montadora, Código de Barras EAN.
  - **Localização Almoxarifado:** Prateleira, gaveta ou caixa.
- Alertas visuais de **Estoque Baixo** (≤ estoque mínimo) e **Estoque Zerado**.

### 4. Controle de Estoque & Kardex (`/stock`)
- **Entrada de Estoque (`/stock/entry`):**
  - Vinculação com fornecedor e Nota Fiscal / Comprovante.
  - Atualização transacional e atômica do saldo e do preço de custo.
- **Saída / Baixa de Peças (`/stock/exit`):**
  - Baixa por Ordem de Serviço (OS), Aplicação em Veículo (com registro de Placa), Venda Balcão, Uso Interno ou Perda/Avaria.
  - Bloqueio automático com validação de saldo insuficiente.
- **Kardex Completo:** Histórico de todas as movimentações auditável por data, usuário, motivo, placa do veículo e valor financeiro.

### 5. Dashboard Operacional (`/dashboard`)
- Total de peças e unidades em estoque.
- Capital total investido em peças (custo médio).
- Alerta em tempo real de itens que necessitam reposição urgente.
- Feed com as últimas entradas e saídas.

---

## 🧪 Testes Automatizados
Para executar a bateria de testes automatizados com PHPUnit:
```bash
docker compose exec app php artisan test
```
