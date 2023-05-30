<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>FAQ</h1>
        <input type="text" id="searchInput" placeholder="Rechercher - Écrivez ici">
    </header>

    <div class="container">
    <div class="categories"></div>
    <?php
        $jsonString = '...'; // votre chaîne JSON
        $data = json_decode($jsonString, true);

        foreach ($data as $category) {
            $categoryIcon = $category['category-icon']; // valeur de 'category-icon' depuis JSON
            $categoryIconWhite = $category['icon_white']; // valeur de 'icon_white' depuis JSON
            
            echo "<div class='category-icon'>$categoryIcon</div>";
            echo "<div class='category-icon-white'>$categoryIconWhite</div>";
        }            
    ?>
    <div class="questions"></div>
    <div class="faq-container"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="script.js"></script>


<script>
    // Chargement des données depuis le fichier JSON
    $.getJSON('faq.json', function (data) {
        // Formation de la liste des catégories
        var categories = data.map(function (item) {
            return item['category-name'];
        });
        var uniqueCategories = [...new Set(categories)];
        uniqueCategories.forEach(function (category) {
            $('.categories').append('<div class="category">' + category + '</div>');
        });

        // Affichage des questions pour la catégorie sélectionnée
        $('.category').click(function () {
            var selectedCategory = $(this).text();
            var questions = data.filter(function (item) {
                return item['category-name'] === selectedCategory;
            })[0]['lesQuestions'];
            $('.questions').empty();
            questions.forEach(function (question) {
                $('.questions').append('<div class="question">' + question['question'] + '</div>');
            });
        });

        // Affichage des réponses à la question sélectionnée
        $('.questions').on('click', '.question', function () {
            var selectedQuestion = $(this).text();
            var answers = data.map(function (item) {
                return item['lesQuestions'];
            }).flat();
            var selectedAnswer = answers.filter(function (answer) {
                return answer['question'] === selectedQuestion;
            })[0]['reponse'];
            $('.faq-container').html(selectedAnswer);
        });

        // Recherche de questions
        $('#searchInput').keyup(function () {
            var searchQuery = $(this).val().toLowerCase();
            $.ajax({
                url: 'search.php',
                method: 'POST',
                data: {
                    query: searchQuery,
                    faq: data
                },
                success: function (response) {
                    var filteredQuestions = JSON.parse(response);
                    $('.questions').empty();
                    filteredQuestions.forEach(function (question) {
                        $('.questions').append('<div class="question">' + question['question'] + '</div>');
                    });
                }
            });
        });
    });
</script>

</body>

</html>