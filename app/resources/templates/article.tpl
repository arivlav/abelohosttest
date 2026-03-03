{include file='layout/header.tpl' title=$title}
<article class="articles__item articles__item_single">
    <h1 class="articles__item__name">{$article['name']}</h1>
    <div class="article__meta">
        {if $article['published_at']}
            <time class="articles__item__publish">{$article['published_at']}</time>
        {/if}
        <div class="article__views">views: {$article['views']} </div>
    </div>
    {if $article['image']}
        <img class="articles__item__image_small articles__item__image" src="{$article['image']}" alt="{$article['name']|escape}"/>
    {/if}
    {if $article['description']}
        <p class="articles__item__description">{$article['description']}</p>
    {/if}
    <div class="article__content">
        {$article['content']}
    </div>
</article>
<p class="articles__item__link"><a href="/">← Back to main</a></p>

<section class="category">
    <h2 class="category__title">Similar articles</h2>
    <div class="category__articles">
        {foreach $similarArticles as $item}
            <div class="articles__item">
                <img class="articles__item__image" src="{$item.image}" alt="{$item.name|escape}"/>
                <div class="articles__item__wrapper">
                    <div class="articles__item__name">
                        {$item.name}
                    </div>
                    <div class="articles__item__publish">
                        {$item.published_at}
                    </div>
                    <div class="articles__item__description">
                        {$item.description}
                    </div>
                    <a class="articles__item__link" href="/articles/{$item.id}">
                        Continue Reading
                    </a>
                </div>
            </div>
            {foreachelse}
            <p>No articles in this category yet.</p>
        {/foreach}
    </div>
</section>
{include file='layout/footer.tpl'}
