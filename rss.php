<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>RSS</title>
</head>
<body>
<?php

$rss = new SimpleXmlElement(file_get_contents("http://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml"));
$feed = array();
foreach ($rss->channel->item as $article) {

    $title = (string) $article->title;
    $link = (string) $article->guid; //GUID for permalink

    // check if item has media:content node before trying to access its attributes
    $imageUrl = '';
    if ($article->children('media', true)->content) {
        $imageAttributes = $article->children('media', true)->content->attributes();
        if ($imageAttributes && $imageAttributes->medium == 'image') {
            $imageUrl = (string) $imageAttributes->url;
        }
    }

    $imageDescription = (string) $article->children('media', true)->description;
    $imageCredit = (string) $article->children('media', true)->credit;
    $description = (string) $article->description;
    $author = (string) $article->children('dc', true);
    $publishDate = strtotime($article->pubDate);
    $categories = array();

    foreach ($article->category as $category) {
        $categories[] = (string) $category;
    }

    $item = (object) [
        'title' => $title,
        'link' => $link,
        'imageUrl' => $imageUrl,
        'imageDescription' => $imageDescription,
        'imageCredit' => $imageCredit,
        'description' => $description,
        'author' => $author,
        'publishDate' => $publishDate,
        'categories' => $categories,
    ];

    $feed[] = $item;
}

echo '<pre>';
echo var_dump($feed);
echo '</pre>';

?>
</body>
</html>