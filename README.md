# 🚗 Auto Cold - Sistema de Gestão para Auto Elétrica & Ar Condicionado

Sistema completo desenvolvido em **Laravel 12 (PHP 8.2+)**, otimizado para deploy serverless na **Vercel** com banco de dados em nuvem **Neon Postgres**.

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

## ☁️ Deploy & Arquitetura na Vercel + Neon Postgres

O projeto está 100% preparado para rodar em arquitetura serverless de alta performance:
- [vercel.json](file:///Users/phelipesc/Documents/projetos/auto_cold/vercel.json): Configuração de rotas, assets e runtime serverless.
- [api/index.php](file:///Users/phelipesc/Documents/projetos/auto_cold/api/index.php): Entrypoint adaptado com suporte a `/tmp` e detecção automática do Neon Database.

### Passo a Passo de Instalação:

1. **Importar o repositório na Vercel**: Conecte o repositório `ph2708/auto_cold`.
2. **Integrar o Neon Database**: No dashboard da Vercel, adicione a integração Neon Postgres (injeta `POSTGRES_URL` / `DATABASE_URL` automaticamente).
3. **Variáveis de Ambiente na Vercel (`Project Settings -> Environment Variables`)**:
   - `APP_KEY`: Chave gerada para a aplicação (ex: `php artisan key:generate --show`).
4. **Executar Migrations e Seeders no Neon**:
```bash
DATABASE_URL="sua_connection_string_neon" php artisan migrate:fresh --seed --force
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

Para rodar a suíte completa de testes automatizados:
```bash
php artisan test
```
