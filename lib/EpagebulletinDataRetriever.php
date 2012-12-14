<?php

class EpagebulletinDataRetriever extends URLDataRetriever
{
    //protected $DEFAULT_PARSER_CLASS = 'JSONDataParser';

    /*
    public function tweets($user) {
        $this->setBaseURL('http://api.twitter.com/1/statuses/user_timeline.json');
        $this->addParameter('screen_name', $user);
        $data = $this->getData();
        return $data;
    }

    // retrieves a tweet based on its id
    public function getItem($id) {
            $this->setBaseURL('http://api.twitter.com/1/statuses/show.json');
                $this->addParameter('id', $id);
                $data = $this->getData();
                    return $data;
    }
     */

    public function getItems($url)
    {
        $items = array();
        if ($url)
        {
            //$html = file_get_contents($url);
            $this->setBaseURL($url);
            $html = $this->getData();

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

            foreach($dom->getElementsByTagName('table') as $table)
            {
                if ( ! $table->hasAttribute('class'))
                {
                    continue;
                }

                $class = explode(' ', $table->getAttribute('class'));

                if (in_array('list_TIDY', $class) || in_array('baseTB', $class) || in_array('listTB', $class) || in_array('md_middle', $class))
                {
                    $rows = $table->getElementsByTagName("tr");

                    foreach ($rows as $row) {
                        foreach($row->getElementsByTagName('a') as $a)
                        {
                            if($a->nodeValue)
                            {
                                $items[] = array(
                                    'name' => $a->nodeValue,
                                    'href' => $a->getAttribute('href')
                                );
                            }
                        }
                    }
                    //print_r($items);
                    //echo "<br><br>";
                }
            }

            if(empty($items))
            {
                foreach($dom->getElementsByTagName('table') as $table) {
                    if ( ! $table->hasAttribute('class'))
                    {
                        continue;
                    }

                    $class = explode(' ', $table->getAttribute('class'));

                    if (in_array('bignews', $class) || in_array('listmod_4', $class))
                    {
                        $rows = $table->getElementsByTagName("tr");

                        foreach ($rows as $row) {
                            foreach($row->getElementsByTagName('a') as $a)
                            {
                                if($a->nodeValue)
                                {
                                    $items[] = array(
                                        'name' => $a->nodeValue,
                                        'href' => $a->getAttribute('href')
                                    );
                                }
                            }
                        }
                    }
                }
            }

            return $items;
        }
    }
}
