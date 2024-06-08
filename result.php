<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BasedDictionary</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();

if(isset($_SESSION['word']) && isset($_SESSION['definitions'])){
    echo "<h1>Word: {$_SESSION['word']}</h1>";
    echo "<h2>Audio:</h2>";
    foreach($_SESSION['audio'] as $audio) {
        echo "<audio controls> <source src='{$audio}' type='audio/mpeg'> Your browser does not support the audio element.</audio>";
    }
    echo "<h2>Definitions:</h2>";
    echo "<ol>";
    foreach($_SESSION['definitions'] as $definition) {
        echo "<li>";
        echo "<p><strong>Part of Speech:</strong> {$definition['partOfSpeech']}</p>";
        echo "<p><strong>Definition:</strong> {$definition['definition']}</p>";
        
        if(isset($definition['example']) && !empty($definition['example'])){
            echo "<p><strong>Example:</strong> {$definition['example']}</p>";
        }

        if(isset($definition['synonyms']) && !empty($definition['synonyms'])){
            echo "<p><strong>Synonyms:</strong> {$definition['synonyms']}</p>";
        }

        if(isset($definition['antonyms']) && !empty($definition['antonyms'])){
            echo "<p><strong>Antonyms:</strong> {$definition['antonyms']}</p>";
        }
        
        echo "</li>";
    }
    echo "</ol>";
} else {
    echo "Word details not found.";
}
?>
<button onclick="window.location.href='index.html'">Go Back</button>
</body>
</html>
