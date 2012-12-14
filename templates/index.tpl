{include file="findInclude:common/templates/header.tpl"}

{$tabBodies = array()}
{$i = 0}
{foreach from=$tweetList item=tweet}
  {capture name="newsTab_{$i}" assign="newsTab_{$i}"}
    {include file="findInclude:common/templates/results.tpl" results=$tweet}
  {/capture}
  {$tabBodies[$tabs[$i]] = ${"newsTab_{$i}"}}
  {$i = $i+1}
{/foreach}

{block name="tabs"}
<div id="tabscontainer">
{include file="findInclude:common/templates/tabs.tpl" tabBodies=$tabBodies smallTabs=true}
</div>
{/block}

{include file="findInclude:common/templates/footer.tpl"}
