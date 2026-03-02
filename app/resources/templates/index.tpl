{include file='layout/header.tpl' title=$title}
{foreach $categories as $category}
    <div class="category">
        <div class="category__first-line">
            <h2 class="category__title">{$category['name']}</h2>
            <a class="category__all-link" href="/categories/{$category['id']}">View All</a>
        </div>
        <div class="category__articles">
            {foreach $articles[$category['id']] as $article}
                <div class="articles__item">
                    <img class="articles__item__image" src="{$article['image']}" alt="category"/>
                    <div class="articles__item__wrapper">
                        <div class="articles__item__name">
                            {$article['name']}
                        </div>
                        <div class="articles__item__publish">
                            {$article['published_at']}
                        </div>
                        <div class="articles__item__description">
                            {$article['description']}
                        </div>
                        <a class="articles__item__link" href="/articles/{$article['id']}">
                            Continue Reading
                        </a>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/foreach}
{include file='layout/footer.tpl'}