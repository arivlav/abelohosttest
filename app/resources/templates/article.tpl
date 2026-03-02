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
{include file='layout/footer.tpl'}
