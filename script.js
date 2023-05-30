$(document).ready(function() {
    const categoriesContainer = $('.categories');
    const questionsContainer = $('.questions');
    const faqContainer = $('.faq-container');
    const searchInput = $('#searchInput');

    // Chargement des données FAQ
    $.getJSON('faq.json', function(data) {
        const faqData = data;

        // Chargement des données depuis JSON
        loadData();

        // Génération et affichage des icônes des catégories
        function loadData() {
            const categories = [];
            const questions = [];

            // Traitement des données depuis JSON
            for (let i = 0; i < faqData.length; i++) {
                const category = faqData[i];
                categories.push(category);

                for (let j = 0; j < category.lesQuestions.length; j++) {
                    const question = category.lesQuestions[j];
                    questions.push(question);
                }
            }

            // Génération des catégories
            for (let i = 0; i < categories.length; i++) {
                const category = categories[i];
                const categoryElement = $('<div class="category">' + category['category-name'] + '</div>');
                categoriesContainer.append(categoryElement);

                // Ajout des icônes aux catégories
                const icon = category['category-icon'];
                categoryElement.prepend(icon);

                categoryElement.data('category', category['category-name']);
            }

            // Génération des questions
            for (let i = 0; i < questions.length; i++) {
                const question = questions[i];
                const questionElement = $('<div class="question"><h3 class="question-title">' + question.question + '</h3><p class="question-answer">' + question.reponse + '</p></div>');
                questionsContainer.append(questionElement);

                questionElement.data('category', question.category);
                questionElement.data('index', question.index);
            }

            // Gestion du clic sur une catégorie
            categoriesContainer.on('click', '.category', function() {
                const category = $(this).data('category');

                $('.question').hide();
                $('.question[data-category="' + category + '"]').show();
            });

            // Gestion du clic sur une question
            questionsContainer.on('click', '.question', function() {
                const category = $(this).data('category');
                const index = $(this).data('index');
                const question = faqData.find(function(item) {
                    return item['category-name'] === category;
                }).lesQuestions.find(function(item) {
                    return item.index === index;
                });

                faqContainer.html('<h3>' + question.question + '</h3><p>' + question.reponse + '</p>');
                faqContainer.addClass('show');
            });

            // Gestion de la saisie dans la barre de recherche
            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase();

                questionsContainer.find('.question').each(function() {
                    const question = $(this).text().toLowerCase();

                    if (question.indexOf(searchTerm) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Remplacement du contenu de .category-icon et .category-icon-white
            $('.category-icon').each(function(index) {
                const icon = $(this).html();
                $(this).html(icon);
            });

            $('.category-icon-white').each(function(index) {
                $(this).html(icon);
            });
        }
    });
});