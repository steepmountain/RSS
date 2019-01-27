<?php

function fetch($url)
{
    $rss = new SimpleXmlElement(file_get_contents($url));
    foreach ($rss->channel->item as $article) {
        $title = $article->title;
        $link = $article->guid; //GUID for permalink

        if ($article->children('media', true)->content) {
            $imageAttributes = $article->children('media', true)->content->attributes();
            $imageUrl = $imageAttributes && $imageAttributes->medium == 'image'
                ? $imageAttributes->url 
                : '';
        }

        $imageDescription = $article->children('media', true)->description;
        $imageCredit = $article->children('media', true)->credit;
        $description = $article->description;
        $author = $article->children('dc', true);
        $publishDate = strtotime($article->pubDate);
        $categories = array();

        foreach ($article->category as $category) {
            $categories[] = $category;
        }

        echo '<div style="border: 1px solid black">';
        if ($imageUrl != '') {
            echo "
                    <img src='$imageUrl' />
                    <sub>Credit: $imageCredit</sub>
                    <p>$imageDescription</p>";
        }

        echo "<div>
                <a href='$link'>$title</a>
                <p>$description</p>
                <p>Author: $author</p>
                <p>Published: $publishDate</p>";

        echo "Categories: <ul>";
        foreach ($article->category as $category) {
            echo "<li>$category</li>";
            echo $category['domain'];
            //$categories[] = $category;
        }
        echo "</ul>";

        echo '</div>';
    
    }
}

fetch("http://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml");
