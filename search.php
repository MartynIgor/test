<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchQuery = $_POST['query'];
    $faqData = $_POST['faq'];
    $filteredQuestions = array_filter($faqData, function ($item) use ($searchQuery) {
        $questions = array_column($item['lesQuestions'], 'question');
        return preg_grep('/' . $searchQuery . '/i', $questions);
    });
    echo json_encode(array_values($filteredQuestions));
}
?>