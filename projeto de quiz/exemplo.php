<?php
session_start();

// Perguntas do quiz
if (!isset($_SESSION['questions'])) {
    $_SESSION['questions'] = [
        // Nível fácil
        [
            "question" => "Qual é a capital da França?",
            "options" => ["Paris", "Londres", "Berlim", "Madri"],
            "answer" => 0,
            "difficulty" => "fácil"
        ],
        [
            "question" => "Quem escreveu 'Dom Casmurro'?",
            "options" => ["Machado de Assis", "José de Alencar", "Clarice Lispector", "Jorge Amado"],
            "answer" => 0,
            "difficulty" => "fácil"
        ],
        // Adicione mais perguntas conforme necessário...
    ];
}

// Adicionando mais perguntas para atingir 100
for ($i = 0; $i < 75; $i++) {
    $_SESSION['questions'][] = [
        "question" => "Pergunta " . ($i + 1 + count($_SESSION['questions'])),
        "options" => ["Opção A", "Opção B", "Opção C", "Opção D"],
        "answer" => rand(0, 3), // Resposta aleatória
        "difficulty" => ($i % 3 === 0) ? "fácil" : (($i % 3 === 1) ? "médio" : "difícil")
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['current_question'])) {
        $_SESSION['current_question'] = 0;
    }

    $questions = $_SESSION['questions'];
    $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;

    // Verifica a resposta da pergunta atual
    if (isset($_POST["question"])) {
        $currentQuestion = $_SESSION['current_question'];
        if ($_POST["question"] == $questions[$currentQuestion]['answer']) {
            $score++;
        }
        $_SESSION['score'] = $score;
        $_SESSION['current_question']++;
    }

    // Verifica se ainda há perguntas
    if ($_SESSION['current_question'] >= count($questions)) {
        $totalQuestions = count($questions);
        echo "<h1>Resultado do Quiz</h1>";
        echo "<p>Você acertou $score de $totalQuestions perguntas.</p>";
        echo '<a href="quiz.php">Tentar Novamente</a>';
        session_destroy(); // Destrói a sessão para reiniciar o quiz
        exit;
    }
    // Atualiza a pergunta atual
    $currentQuestion = $_SESSION['current_question'];
} else {
    $_SESSION['current_question'] = 0; // Reinicia o quiz
    $_SESSION['score'] = 0; // Reinicia a pontuação
    $currentQuestion = 0;
}

// Pergunta atual
$question = $_SESSION['questions'][$currentQuestion];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Conhecimentos Gerais</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .quiz-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .question {
            margin-bottom: 20px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h1>Quiz de Conhecimentos Gerais</h1>
        <form id="quizForm" method="post" action="">
            <div class="question">
                <p><?php echo ($currentQuestion + 1) . ". " . $question['question']; ?></p>
                <?php foreach ($question['options'] as $i => $option): ?>
                    <label>
                        <input type="radio" name="question" value="<?php echo $i; ?>" required>
                        <?php echo $option; ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
            <button type="submit">Próxima Pergunta</button>
        </form>
    </div>
</body>
</html>
