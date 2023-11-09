<?php

namespace LiquidedgeApp\Octoapp\app\app\http;


class social_link_builder extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    public $url;
    public $title;
    public $image;
    public $desc;
    public $body;
    public $app_id;
    public $redirect_url;
    public $via;
    public $hash_tags;
    public $provider;
    public $language;
    public $user_id;
    public $category;
    public $phone_number;
    public $email_address;
    public $cc_email_address;
    public $bcc_email_address;

    protected $return_arr = [];

    //--------------------------------------------------------------------------------
    public function __construct() {
        $this->url = \LiquidedgeApp\Octoapp\app\app\http\http::get_current_url();
        $this->title = \core::$app->get_instance()->get_title();
        $this->body = "Thought you might enjoy this: {$this->url}";
    }
    //--------------------------------------------------------------------------------
    public function get_display_name($id) {
        $data_arr = [
            'blogger'=>'Blogger',
            'diaspora'=>'Diaspora',
            'douban'=>'Douban',
            'email'=>'EMail',
            'evernote'=>'EverNote',
            'getpocket'=>'Pocket',
            'facebook'=>'FaceBook',
            'flipboard'=>'FlipBoard',
            'google.bookmarks'=>'GoogleBookmarks',
            'instapaper'=>'InstaPaper',
            'line.me'=>'Line.me',
            'linkedin'=>'LinkedIn',
            'livejournal'=>'LiveJournal',
            'gmail'=>'GMail',
            'hacker.news'=>'HackerNews',
            'ok.ru'=>'OK.ru',
            'pinterest.com'=>'Pinterest',
            'qzone'=>'QZone',
            'reddit'=>'Reddit',
            'renren'=>'RenRen',
            'skype'=>'Skype',
            'sms'=>'SMS',
            'telegram.me'=>'Telegram.me',
            'threema'=>'Threema',
            'tumblr'=>'Tumblr',
            'twitter'=>'Twitter',
            'vk'=>'VK',
            'weibo'=>'Weibo',
            'whatsapp'=>'WhatsApp',
            'xing'=>'Xing',
            'yahoo'=>'Yahoo',
        ];

        return isset($data_arr[$id]) ? $data_arr[$id] : false;
    }
    //--------------------------------------------------------------------------------
    public function get($id){

        if(!$this->return_arr) $this->build();

        if(isset($this->return_arr[$id])){
            return $this->return_arr[$id];
        }

    }
    //--------------------------------------------------------------------------------
    public function build_link($url, array $parts = []){

        $url_arr = [];
        $url_arr[] = $url;
        if($parts){
            $url_arr[] = strpos($url, "?") === false ? "?" : "&";
            $url_arr[] = http_build_query($parts);
        }

        return implode("", $url_arr);
    }
    //--------------------------------------------------------------------------------
    public function build() {

        $this->return_arr["blogger"] = $this->build_link("https://www.blogger.com/blog-this.g", ["url" => $this->url, "title" => $this->title, "t" => $this->desc]);
        $this->return_arr["google.bookmarks"] = $this->build_link("https://www.google.com/bookmarks/mark", ["op" => "edit", "bkmk" => $this->url, "title" => $this->title, "annotation" => $this->title, "labels" => $this->hash_tags]);
        $this->return_arr["instapaper"] = $this->build_link("http://www.instapaper.com/edit", ["url" => $this->url, "title" => $this->title, "description" => $this->desc]);
        $this->return_arr["flipboard"] = $this->build_link("https://share.flipboard.com/bookmarklet/popout", ["v" => 2,"url" => $this->url, "title" => $this->title]);
        $this->return_arr["livejournal"] = $this->build_link("http://www.livejournal.com/update.bml", ["event" => $this->url, "subject" => $this->title]);
        $this->return_arr["hacker.news"] = $this->build_link("https://news.ycombinator.com/submitlink", ["u" => $this->url, "t" => $this->title]);
        $this->return_arr["ok.ru"] = $this->build_link("https://connect.ok.ru/dk", ["st.shareUrl" => $this->url, "st.cmd" => "WidgetSharePreview"]);
        $this->return_arr["tumblr"] = $this->build_link("https://www.tumblr.com/widgets/share/tool", ["canonicalUrl" => $this->url, "tags" => $this->hash_tags, "caption" => $this->desc, "title" => $this->title]);
        $this->return_arr["twitter"] = $this->build_link("https://twitter.com/intent/tweet", ["url" => $this->url, "hashtags" => $this->hash_tags, "via" => $this->via, "text" => $this->title]);

        $this->return_arr["diaspora"] = $this->build_link("https://share.diasporafoundation.org/", ["url" => $this->url, "title" => $this->title]);
        $this->return_arr["douban"] = $this->build_link("http://www.douban.com/recommend/", ["url" => $this->url, "title" => $this->title]);
        $this->return_arr["evernote"] = $this->build_link("https://www.evernote.com/clip.action", ["url" => $this->url, "title" => $this->title]);
        $this->return_arr["reddit"] = $this->build_link("https://reddit.com/submit", ["url" => $this->url, "title" => $this->title]);
        $this->return_arr["line.me"] = $this->build_link("https://lineit.line.me/share/ui", ["url" => $this->url, "text" => $this->title]);
        $this->return_arr["skype"] = $this->build_link("https://web.skype.com/share", ["url" => $this->url, "text" => $this->title]);

        $this->return_arr["pinterest"] = $this->build_link("http://pinterest.com/pin/create/button", ["url" => $this->url]);
        $this->return_arr["getpocket"] = $this->build_link("https://getpocket.com/edit", ["url" => $this->url]);
        $this->return_arr["facebook"] = $this->build_link("https://www.facebook.com/sharer/sharer.php", ["u" => $this->url]);
        $this->return_arr["linkedin"] = $this->build_link("https://www.linkedin.com/sharing/share-offsite", ["url" => $this->url]);
        $this->return_arr["qzone"] = $this->build_link("http://pinterest.com/pin/create/button", ["url" => $this->url]);

        $this->return_arr["sms"] = $this->build_link("sms:{$this->phone_number}", ["body" => str_replace('+', ' ', $this->body)]);
        $this->return_arr["email"] = $this->build_link("mailto:{$this->email_address}", ["subject" => $this->title])."&body={$this->body}";
        $this->return_arr["gmail"] = $this->build_link("https://mail.google.com/mail", ["view" => "cm", "to" => $this->email_address, "su" => $this->title, "body" => $this->body, "bcc" => $this->bcc_email_address, "cc" => $this->cc_email_address]);
        $this->return_arr["whatsapp"] = $this->build_link("https://wa.me/", ["text" => "{$this->title} {$this->url}"]);
        $this->return_arr["yahoo"] = $this->build_link("http://compose.mail.yahoo.com/", ["to" => $this->email_address, "subject" => $this->title, "body" => $this->body]);

        return $this->return_arr;

    }
    //--------------------------------------------------------------------------------
}