{include file="findInclude:common/templates/header.tpl"}

<div class="news">
    {block name="slugline"}
  <h1 class="slugline">{$title}</h1>
  {/block}

  <div id="storysubhead">
    {include file="findInclude:common/templates/share.tpl" shareURL={$storyURL} shareRemark={$shareRemark} shareEmailURL={$shareEmailURL}}
            
    {if $pager['pageNumber'] == 0}
        <p class="byline">
          {block name="byline"}
              
            {if $author}
              <span class="credit author">{"AUTHOR_CREDIT"|getLocalizedString:$author}</span><br />
            {/if}
    
            <span class="postdate">{$date}</span>
          {/block}
        </p>    
    {/if}        
  </div><!--storysubhead-->
  
  <div id="story">
    {block name="body"}    
    <span id="storybody">
      {include file="findInclude:common/templates/pager.tpl"}
    </span>
    {/block}
    {if $showLink}
    {block name="morelink"}
    <div id="showmore">
    <a href="{$link}">{"READ_MORE"|getLocalizedString}</a>
    </div>
    {/block}
    {/if}
  </div><!--story-->
</div>

{include file="findInclude:common/templates/footer.tpl"}
