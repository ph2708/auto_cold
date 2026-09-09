# 🚗 Auto Cold - Sistema de Gestão para Auto Elétrica & Ar Condicionado

Sistema completo desenvolvido em **Laravel 12 (PHP 8.2+)** com banco de dados **MySQL 8.0** e armazenamento local permanente de fotos (`storage/app/public`), perfeito para deploy em hospedagens como **Hostinger** (cPanel / hPanel / VPS) ou servidores dedicados.

Focado em oficinas automotivas (**Auto Cold** / **Juliano Ribeiro**), com fluxo simplificado em 1 tela, rastreamento de compras externas (Mercado Livre, Shopee), ordens de serviço, upload de fotos do veículo antes/durante/depois, emissão de orçamentos e painel administrativo com personalização visual em tempo real.

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

### 3. 📸 Fotos do Veículo & Laudo Técnico
- Upload de múltiplas fotos em alta resolução por etapa (**Antes da Manutenção**, **Diagnóstico na Bancada** e **Após Conclusão / Teste de Entrega**).
- Armazenamento em disco local (`storage/app/public/os_photos`).

### 4. 🛒 Cotação em Tempo Real vs Peças do Estoque Físico
- **Cotação Externa (Lojas / Mercado Livre)**: Permite inserir itens cotados em tempo real no orçamento sem exigir cadastro prévio e sem descontar do estoque físico.
- **Uso de Estoque Físico**: Baixa automática e atômica com Kardex quando uma peça do estoque da oficina é aplicada.
- **Impressão Pronta**: Layout limpo e profissional para impressão e geração de PDF de Orçamento e Ordem de Serviço com valores detalhados e termos de garantia.

### 5. 🚚 Rastreamento de Peças a Chegar (`/purchase_orders`)
- Controle de compras feitas na internet (Mercado Livre, Shopee, Distribuidoras).
- Apenas digite a peça e o código de rastreio para acompanhar prazos e chegada na oficina.
- Botão rápido de recebimento que incorpora o item ao estoque ou à OS correspondente.

### 6. 👥 Clientes, CPF Opcional & Veículos (`/customers`)
- Campo de CPF **opcional** com verificação em tempo real (AJAX) para alertar duplicidades sem travar novos cadastros.
- Cadastro e histórico unificado por cliente e por placa.

### 7. 📊 Painel Geral & Gestão Financeira (`/dashboard`)
- Faturamento bruto e recebíveis em aberto.
- Total investido em compras/encomendas no mês.
- DRE e margem de ganho real da oficina (Mão de Obra 100% líquida + margem sobre peças).

---

## 🚀 Como Fazer o Deploy na Hostinger

### 1. Criar o Banco de Dados MySQL
1. No painel da Hostinger (hPanel / cPanel), acesse **Bancos de Dados MySQL**.
2. Crie um novo banco (ex: `u123456_autocold`), usuário e senha.

### 2. Configurar o `.env`
No gerenciador de arquivos da Hostinger ou via SSH, configure o seu `.env`:
```env
APP_NAME="Auto Cold"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_criado
DB_USERNAME=usuario_do_banco
DB_PASSWORD=senha_do_banco
```

### 3. Comandos de Inicialização (SSH no Terminal da Hostinger):
```bash
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

*(Caso use Hospedagem Compartilhada sem SSH, aponte a pasta raiz do domínio para a pasta `public/`).*

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
php artisan test
```
