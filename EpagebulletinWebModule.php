<?php

class EpagebulletinWebModule extends WebModule
{
    protected static $defaultModel = 'EpagebulletinDataModel';
    protected $id='epagebulletin';
    protected $feeds=array();

/*
protected function initialize() {
$feeds = $this->loadFeedData();

if ($feed = $this->getArg('feed')) {
if (!isset($feeds[$feed])) {
throw new Exception("Invalid feed $feed");
}
$feeds = array($feed=>$feeds[$feed]);
}
}
 */

    protected function getSubscribeURLs()
    {
        $feeds = $this->getModuleSections('feeds');
        $links = array();

        if($feeds)
        {
            foreach($feeds as $feed)
            {
                $links[] = $feed['BASE_URL'];
            }

            return $links;
        }

        return false;
    }

    protected function initializeForPage()
    {
        //instantiate controller
        $this->controller = DataRetriever::factory('EpagebulletinDataRetriever', array());

        switch ($this->page)
        {
        case 'index':
/*
$user = 'kurogofwk';

//get the tweets
$tweets = $this->controller->tweets($user);

//prepare the list
$tweetList = array();
foreach ($tweets as $tweetData)
{
$tweet = array(
'title'=> $tweetData['text'],
'subtitle'=> $tweetData['created_at'],
'url'=> $this->buildBreadcrumbURL('detail', array('id'=>$tweetData['id_str']))
);
$tweetList[] = $tweet;
}

//assign the list to the template
$this->assign('tweetList', $tweetList);
 */

            $tabs = array();

            $urls = $this->getSubscribeURLs();
            $feeds = $this->getModuleSections('feeds');

            $items = array();
            $i = 0;
            foreach($feeds as $feed)
            {
                foreach ($this->controller->getItems($feed['BASE_URL']) as $item)
                {
                    $items[$i][] = array (
                        'title'=> $item['name'],
                        'subtitle'=> '',
                        'url'=> $this->buildBreadcrumbURL('story', array('storyID'=>$item['href']))
                    );
                }

                if(!empty($items[$i]))
                {
                    $tabs[$i] = $feed['TITLE'];
                    $i++;
                }
            }
//            print_r($this->controller->getItems("http://ymadm1.ym.edu.tw/event/ymbulletin.asp"));
            $this->assign('tweetList', $items);
            $this->assign('tabs', $tabs);
            $this->enableTabs($tabs);
            break;
        case 'story':
            if ($url = $this->getArg('storyID', false))
            {
                $html = file_get_contents($url);

                $tidy = new tidy();
                $conf = array(
                    'output-xhtml'=>true,
                    'drop-empty-paras'=>FALSE,
                    'join-classes'=>TRUE,
                    'show-body-only'=>TRUE,
                    'output-encoding' => 'raw',
                );
                $html = $tidy->repairString($html,$conf,'utf8');

                $dom = new DOMDocument;
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html);

                $find_time = 0;
                foreach($dom->getElementsByTagName('div') as $div)
                {
                    if ( ! $div->hasAttribute('class'))
                    {
                        continue;
                    }

                    $class = explode(' ', $div->getAttribute('class'));

                    if (in_array('ptcontent', $class))
                    {
                        //$div_content = $div->nodeValue; // no html
                        //echo $div->nodeValue . "<br>123<br>";
                        $div_content = $dom->saveXML($div);
                        //$pos = strpos($div_content, mb_substr($story->getDescription(),0,2,'utf8'));

                        $content = $div_content;
/*
if($pos > 0)
{
if($find_time == 0)
{
$find_time++;
}
else
{
$content = $div_content;
}
}
 */
                    }
                }
                if(!$content)
                {
                    $content = $story->getDescription();
                }
                //Kurogo::redirectToURL($url);
            }

            $this->assign('title', 'TITLE');
            $this->enablePager($content, 'utf-8', 0);
            break;
        case 'detail':
            $id = $this->getArg('id');
            if ($tweet = $this->controller->getItem($id)) {
                $this->assign('tweetText', $tweet['text']);
                $this->assign('tweetPost', $tweet['created_at']);
            } else {
                $this->redirectTo('index');
            }
            break;
        }
    }
}
