<?php
// Inclui sua classe de conexão
require_once 'Connection.php';

// Obtém a instância da conexão PDO
$pdo = Connection::getConn();

// --- SQL CORRIGIDO ---
// Seleciona apenas as colunas que existem: o texto e o código da pergunta,
// e o nome do tema, que vem da outra tabela através do JOIN.
$sql_perguntas = "
    SELECT
        p.codigo,
        p.texto,
        t.nome AS tema_nome
    FROM
        perguntas p
    JOIN
        temas t ON p.tema_id = t.id
    ORDER BY
        t.nome, p.codigo;
";

// Executa a consulta
$stmt = $pdo->query($sql_perguntas);


try {
    $sql = "SELECT id, codigo, texto, tema_id FROM perguntas";
    $stmt = $pdo->query($sql);
} catch (PDOException $e) {
    echo "Erro ao buscar perguntas: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIGA Bonito - Painel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>
  <style>

    /* --- Variáveis de Cor e Configurações Globais --- */
:root {
    --bg-primary: #2a786e;
    --bg-secondary: #1fa094;
    --bg-tertiary: #76a7a1;
    --color-primary: #40c4bc;
    --color-secondary: #ffffff;
    --color-text-primary: #ffffff;
    --color-text-secondary: #d0f0eb;
    --color-border: #54d4c4;
    --font-family: 'Poppins', sans-serif;
    --border-radius: 8px;
}

/* Mantém os estilos base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-family);
    background-color: var(--bg-primary);
    color: var(--color-text-primary);
    overflow-x: hidden;
}

/* Layout principal */
.app-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.app-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-color: var(--bg-secondary);
    border-bottom: 1px solid var(--color-border);
}

.app-header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.app-icon i {
    font-size: 1.8rem;
    color: var(--color-primary);
}

.app-name {
    font-size: 1.2rem;
    font-weight: 600;
}

.search-wrapper {
    position: relative;
}

.search-input {
    background-color: var(--bg-tertiary);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    padding: 0.6rem 1rem 0.6rem 2.5rem;
    color: var(--color-text-primary);
    min-width: 300px;
    transition: all 0.3s ease;
}

.search-input::placeholder {
    color: var(--color-text-secondary);
}

.search-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(64, 196, 188, 0.3);
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
}

.app-header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background-color: var(--color-primary);
    color: var(--color-secondary);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(64, 196, 188, 0.4);
}

.profile-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    color: var(--color-text-primary);
    cursor: pointer;
}

.profile-btn img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid var(--color-border);
}

/* Sidebar + Conteúdo */
.app-main {
    display: flex;
    flex: 1;
    padding: 2rem;
    gap: 2rem;
}

.sidebar {
    width: 200px;
    flex-shrink: 0;
}

.navigation a {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.8rem 1rem;
    margin-bottom: 0.5rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--color-text-secondary);
    font-weight: 500;
    transition: all 0.3s ease;
}

.navigation a.active,
.navigation a:hover {
    background-color: var(--bg-tertiary);
    color: var(--color-secondary);
}

.navigation a i {
    width: 20px;
    text-align: center;
}

.content {
    flex: 1;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.content-title {
    font-size: 1.8rem;
    font-weight: 600;
}

.filter-btn {
    background: none;
    border: 1px solid var(--color-border);
    color: var(--color-text-secondary);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn.active,
.filter-btn:hover {
    background-color: var(--color-primary);
    color: var(--color-secondary);
    border-color: var(--color-primary);
}

/* Cards de Pergunta */
.question-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.question-card {
    display: flex;
    gap: 1.5rem;
    padding: 1.5rem;
    background-color: var(--bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
}

.question-stats {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
    width: 80px;
    color: var(--color-text-secondary);
}

.stat {
    text-align: center;
}

.stat-count {
    display: block;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.stat-label {
    font-size: 0.8rem;
}

.answer-stat {
    border: 1px solid var(--color-text-secondary);
    padding: 0.5rem;
    border-radius: var(--border-radius);
    min-width: 90px;
}

.answer-stat.accepted {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}
.answer-stat.accepted .stat-count {
    color: white;
}

.question-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    flex-grow: 1;
}

.question-title {
    font-size: 1.2rem;
    font-weight: 500;
}

.question-title a {
    text-decoration: none;
    color: var(--color-text-primary);
    transition: color 0.3s ease;
}

.question-title a:hover {
    color: var(--color-primary);
}

.question-tags {
    display: flex;
    gap: 0.5rem;
}

.tag {
    background-color: var(--bg-tertiary);
    color: var(--color-text-secondary);
    padding: 0.3rem 0.7rem;
    border-radius: 5px;
    font-size: 0.8rem;
}

.question-author {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--color-text-secondary);
}

.question-author img {
    width: 24px;
    height: 24px;
    border-radius: 50%;
}

/* Responsivo */
@media (max-width: 768px) {
    .app-main {
        flex-direction: column;
        padding: 1rem;
    }
    .sidebar {
        width: 100%;
        display: flex;
        overflow-x: auto;
    }
    .navigation {
        display: flex;
        gap: 0.5rem;
    }
    .app-header {
        flex-direction: column;
        gap: 1rem;
    }
    .search-wrapper {
        width: 100%;
    }
    .search-input {
        min-width: auto;
        width: 100%;
    }
}


.footer {
    background-color: transparent; /* fundo sem cor */
    padding: 1.5rem;
    text-align: center;
    color: white;
    margin-top: 3rem;
    border-top: 1px solid #cdd;
}


.footer-logos {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.footer-logo {
    height: 60px;
    max-width: 160px;
    object-fit: contain;
}

.dev-info {
    font-size: 0.95rem;
    margin-bottom: 0.8rem;
}

.footer-social a {
    margin: 0 10px;
    font-size: 1.4rem;
    color: white;
    transition: transform 0.2s, opacity 0.2s;
}

.footer-social a:hover {
    transform: translateY(-2px);
    opacity: 0.8;
}

  </style>

<div class="app-container">
  <header class="app-header">
    <div class="app-header-left">
      <span class="app-icon"><i class="fa-solid fa-city"></i></span>
      <p class="app-name">SIGA Bonito</p>
      <div class="search-wrapper">
        <input class="search-input" type="text" placeholder="Pesquisar perguntas, temas...">
        <i class="fa-solid fa-search search-icon"></i>
      </div>
    </div>
    <div class="app-header-right">
      <button class="btn btn-primary" id="nova-pergunta-btn"><i class="fa-solid fa-plus"></i> Nova Entrada</button>
      <button class="profile-btn">
        <img src="https://i.pravatar.cc/300" alt="Avatar do Usuário" />
        <span>Administrador</span>
      </button>
    </div>
  </header>

  <main class="app-main">
    <aside class="sidebar">
      <nav class="navigation">
        <a href="painel.php" class="nav-item active"><i class="fa-solid fa-home"></i> Painel</a>
        <a href="perguntas.php" class="nav-item"><i class="fa-solid fa-question"></i> Perguntas</a>
        <a href="#" class="nav-item"><i class="fa-solid fa-lightbulb"></i> Estratégias</a>
        <a href="#" class="nav-item"><i class="fa-solid fa-share-nodes"></i> Ramificações</a>
        <a href="#" class="nav-item"><i class="fa-solid fa-map-location-dot"></i> Mapa Urbano</a>
        <a href="#" class="nav-item"><i class="fa-solid fa-users"></i> Colaboradores</a>
        <a href="#" class="nav-item"><i class="fa-solid fa-gears"></i> Configurações</a>
        <a href="guia.pdf" target="_blank" class="nav-item"><i class="fa-solid fa-file-pdf"></i> Guia Oficial</a>
      </nav>
    </aside>

    <section class="content">
      <div class="content-header">
        <h1 class="content-title">Guia de Urbanismo Inteligente</h1>
        <div class="content-filters">
          <button class="filter-btn active">Todas</button>
          <button class="filter-btn">Respondidas</button>
          <button class="filter-btn">Em Análise</button>
        </div>
      </div>
<div class="question-list">
  <?php while ($pergunta = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="question-card">
      <div class="question-stats">
        <div class="stat">
          <span class="stat-count">#<?= htmlspecialchars($pergunta['codigo']) ?></span>
          <span class="stat-label">Código</span>
        </div>
        <div class="stat">
          <span class="stat-count"><?= htmlspecialchars($pergunta['id']) ?></span>
          <span class="stat-label">ID</span>
        </div>
        <div class="stat">
          <span class="stat-count">✔</span>
          <span class="stat-label">Ativo</span>
        </div>
      </div>

      <div class="question-details">
        <h2 class="question-title">
          <?= htmlspecialchars($pergunta['texto']) ?>
        </h2>
        <div class="question-tags">
          <span class="tag">Tema ID: <?= htmlspecialchars($pergunta['tema_id']) ?></span>
        </div>
        <div class="question-author">
          <img src="https://i.pravatar.cc/150?img=1" alt="Autor" />
          <p>Inserido por <strong>Sistema</strong> em <?= date('d/m/Y') ?></p>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<footer class="footer">
    <div class="footer-logos">
        <img src="logo-ivig-branca.png" alt="Logo IVIG" class="footer-logo">
    </div>
    <p class="dev-info">Desenvolvido por <strong>Brayan R Campos</strong></p>
    <div class="footer-social">
        <a href="https://github.com/devbrayan2007" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
        <a href="https://wa.me/5521979625780" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        <a href="https://instagram.com/_brayanbr07" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>
<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>