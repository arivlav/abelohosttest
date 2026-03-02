{include file='layout/header.tpl' title=$title}
<section class="category category_single">
    <h1 class="category__title">{$category.name}</h1>
    {if $category.description}
        <p class="category__description">{$category.description}</p>
    {/if}

    <div class="category__controls">
        <div class="category__sort">
            Order by:
            <a href="?sort=published_at&direction={$pagination['direction']}">Publish</a>
            |
            <a href="?sort=views&direction={$pagination['direction']}">Views</a>
        </div>
        <div class="category__sort">
            Sort by:
            <a href="?sort={$pagination['sort']}&direction=desc">Newest</a>
            |
            <a href="?sort={$pagination['sort']}&direction=desc">Most viewed</a>
        </div>
    </div>

    <div class="category__articles">
        {foreach $articles as $article}
            <div class="articles__item">
                <img class="articles__item__image" src="{$article.image}" alt="{$article.name|escape}"/>
                <div class="articles__item__wrapper">
                    <div class="articles__item__name">
                        {$article.name}
                    </div>
                    <div class="articles__item__publish">
                        {$article.published_at}
                    </div>
                    <div class="articles__item__description">
                        {$article.description}
                    </div>
                    <div class="articles__item__description">
                        Views: {$article.views}
                    </div>
                    <a class="articles__item__link" href="/articles/{$article.id}">
                        Continue Reading
                    </a>
                </div>
            </div>
        {foreachelse}
            <p>No articles in this category yet.</p>
        {/foreach}
    </div>

    {if $pagination.pages > 1}
        <nav class="pagination">
            {if $pagination.page > 1}
                <a class="pagination__prev"
                   href="?page={$pagination.page-1}&sort={$pagination.sort}&direction={$pagination.direction}">Previous</a>
            {/if}
            <span class="pagination__info">
                Page {$pagination.page} of {$pagination.pages}
            </span>
            {if $pagination.page < $pagination.pages}
                <a class="pagination__next"
                   href="?page={$pagination.page+1}&sort={$pagination.sort}&direction={$pagination.direction}">Next</a>
            {/if}
        </nav>
    {/if}
</section>
<p class="category__back"><a href="/">← Back to main</a></p>
{include file='layout/footer.tpl'}

