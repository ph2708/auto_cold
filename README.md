# 🚗 Auto Cold - Sistema de Gestão para Auto Elétrica & Ar Condicionado

Sistema completo desenvolvido em **Laravel 12 (PHP 8.2+)**, preparado para execução via **Docker**, **VPS / Oracle Cloud** e **Vercel Serverless + Neon Postgres**.

Projetado especificamente para oficinas automotivas com foco em **Auto Elétrica e Ar Condicionado** (**Auto Cold** / **Juliano Ribeiro**), com fluxo simplificado em 1 tela, rastreamento de peças encomendadas (Mercado Livre, Shopee), ordens de serviço, emissão de orçamentos e painel administrativo com personalização visual em tempo real.

---

## 🌟 Principais Recursos & Módulos

### 1. 🌐 Landing Page Pública & Branding Dinâmico (`/` e `/site`)
- Página de apresentação moderna e responsiva com foco em conversão para WhatsApp.
- Mostruário de serviços (Ar Condicionado, Alternadores, Motores de Partida, Injeção Eletrônica e Diagnóstico).
- Mapa interativo com localização da oficina (`R. Monte Alegre, 271 - Jardim Liberdade, Itumbiara - GO`).
- **Painel de Configurações Administrativas (`/settings`)**:
  - Upload de logomarca (clara e escura) com controle de altura do logo via slider.
  - Customização do nome do negócio, slogan, cores primária/secundária, WhatsApp e endereço sem tocar no código.

### 2. 📋 Nova Entrada & Abertura de OS em 1 Tela (`/service_orders/create`)
- **Sem listas suspensas travadas**: Digitação direta da placa do veículo e do nome do cliente.
- **Autopreenchimento inteligente**: Ao digitar a placa, localiza o histórico do carro e dados do cliente instantaneamente.
- **Botão 1-Clique "Cliente Avulso"**: Atendimento rápido de balcão sem burocracia.
- **Alternador de Modo**: Criação com 1 clique de **Orçamento Prévio** ou **Ordem de Serviço (OS)** em execução.

### 3. 🛒 Cotação em Tempo Real vs Peças do Estoque Físico
- **Cotação Externa (Lojas / Mercado Livre)**: Permite inserir itens cotados em tempo real no orçamento sem exigir cadastro prévio e sem descontar do estoque físico.
- **Uso de Estoque Físico**: Baixa automática e atômica com Kardex quando uma peça do estoque da oficina é aplicada.
- **Impressão Pronta**: Layout limpo e profissional para impressão e geração de PDF de Orçamento e Ordem de Serviço com valores detalhados e termos de garantia.

### 4. 🚚 Rastreamento de Peças a Chegar (`/purchase_orders`)
- Controle de compras feitas na internet (Mercado Livre, Shopee, Distribuidoras).
- Apenas digite a peça e o código de rastreio para acompanhar prazos e chegada na oficina.
- Botão rápido de recebimento que incorpora o item ao estoque ou à OS correspondente.

### 5. 👥 Clientes, CPF Opcional & Veículos (`/customers`)
- Campo de CPF **opcional** com verificação em tempo real (AJAX) para alertar duplicidades sem travar novos cadastros.
- Cadastro e histórico unificado por cliente e por placa.

### 6. 📊 Painel Geral & Gestão Financeira (`/dashboard`)
- Faturamento bruto e recebíveis em aberto.
- Total investido em compras/encomendas no mês.
- DRE e margem de ganho real da oficina (Mão de Obra 100% líquida + margem sobre peças).

---

## 🚀 Como Executar Localmente com Docker

### 1. Iniciar os Contêineres
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

## ☁️ Deploy na Vercel com Neon Postgres

O projeto já conta com [vercel.json](file:///Users/phelipesc/Documents/projetos/auto_cold/vercel.json) e o entrypoint serverless [api/index.php](file:///Users/phelipesc/Documents/projetos/auto_cold/api/index.php) configurados.

1. **Importar o repositório na Vercel**: Conecte o repositório `ph2708/auto_cold`.
2. **Integrar o Neon Database**: Conecte a integração Neon na Vercel (injeta `POSTGRES_URL` / `DATABASE_URL` automaticamente).
3. **Variáveis de Ambiente na Vercel**:
   - `APP_KEY`: Gere uma chave segura com `php artisan key:generate --show` ou use a chave da sua aplicação.
4. **Executar Migrations no Neon**:
```bash
DATABASE_URL="sua_connection_string_neon" php artisan migrate --force
DATABASE_URL="sua_connection_string_neon" php artisan db:seed --force
```

---

## 🔑 Usuários Pré-Cadastrados para Teste

| Papel / Perfil | Usuário (`username` ou `email`) | Senha | Descrição de Acesso |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin` ou `admin@autocold.com.br` | `admin123` | Acesso total a todas as telas, configurações, usuários e relatórios |
| **Eletricista** | `carlos.eletrica` | `eletrica123` | Consulta catálogo, especificações elétricas e baixa de peças |
| **Estoquista** | `marcos.estoque` | `estoque123` | Entrada/saída de mercadorias, fornecedores e almoxarifado |

---

## 🧪 Testes Automatizados

Para rodar a suíte completa de testes automatizados com PHPUnit:
```bash
docker compose exec app php artisan test
```
