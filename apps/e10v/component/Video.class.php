<?php
/**
 * 视频插件
 * 
 * Created		: 2011-07-06
 * Modified		: 2011-07-06 
 * @link		: http://e10v.com
 * @copyright	: (C) 2011-2013 Qixbo Inc.
 * @version		: 0.1.0
 * @author		: Joseph Chen (jsph.chen@gmail.com)
 * @see http://t.qq.com/asyn/validvideo.php?url=http://v.youku.com/v_show/id_XMjQ4OTI2NzA0.html
 * 视频站开放接口
 * http://open.tv.sohu.com/ 搜狐
 * http://api.tudou.com 土豆
 * http://dev.ku6.com/ 酷六
 * http://dev.youku.com/ 优酷
 */
class Video
{
	
	public static function getVideoInfo($video_url)
	{
		$info = parse_url($video_url);
		if (!$info && !$info['host']) {
			return false;
		}
		$host = $info['host'];
		switch ($host) {
			case 'v.youku.com':
				preg_match("/id\_(\w+)[=.]/", $video_url, $matches);
				break;
				
			case 'www.tudou.com' :
				preg_match("#view/([-\w]+)/#", $video_url, $matches);
				break;
				
			case 'my.tv.sohu.com':
				preg_match("#vw/(\d+)#", $video_url, $matches);
				break;
				
			case 'v.ku6.com':
				//http://v.ku6.com/fetchVideo4Player/3WnCo_I4PdH-IzpQ.html
				preg_match("#/([-\w]+)\.html#", $video_url, $matches);
				break;
				
			case 'www.56.com':
				preg_match("#/v_(\w+)\.html#", $video_url, $matches);
				break;
			
			// http://v.qq.com/cover/p/phy570o6slympvb.html?vid=8KBAmweEKQl
			case 'v.qq.com':
				if (!isset($info['query'])) {
					return false;
				}
				parse_str($info['query'], $q);
				if (!isset($q['vid'])) {
					return false;
				}
				$id = $q['vid'];
				break;
		}
		if (empty($matches[1]) && empty($id)) {
			return false;
		} elseif (empty($id)) {
			$id = $matches[1];
		}
		
		$return = array(
			'id'	=> $id,
			'tag'	=> ''
		);
		switch ($host) {
			case 'v.youku.com':
				$link = 'http://v.youku.com/player/getPlayList/VideoIDS/' .
						$id . '/timezone/+08/version/5/source/out?password=&ran=2513&n=3';
				$json = Url::getContents($link);
				$json = json_decode($json);
				if (!isset($json->data[0])) {
					$return = array();
					break;
				}
				foreach($json->data[0]->tags as $tag){
					if ($return['tag'] == '') {
						$return['tag'] = $tag;
					} else {
						$return['tag'] .= ','.$tag;
					}
				}
				$return['title']	= $json->data[0]->title;
				$return['minipic']	= $json->data[0]->logo;
				$return['time']		= round($json->data[0]->seconds);
				break;
			case 'www.tudou.com':
				$link='http://www.tudou.com/v/'.$id.'/v.swf';
//				$link = 'http://api.tudou.com/v3/gw?method=item.info.get'
//						.'&appKey=myKey&format=json&itemCodes='.$id;
				$url = self::http_get_location($link);
				$arr = parse_url($url);
				parse_str($arr['query'], $arr);
				if (!$arr['iid']) {
					$return = array();
					break;
				}
				$return['title']	= $arr['title'];
				$return['minipic']	= $arr['snap_pic'];
				$return['tag']		= $arr['tag'];
				$return['time']		= $arr['totalTime']/100;
				break;
			case 'my.tv.sohu.com':
				$link = 'http://v.blog.sohu.com/videinfo.jhtml?m=view&id='.$id
						.'&outType=3&from=10&block=0&time='.time().'703';
				$json	= Url::getContents($link);
				$json	= json_decode($json);
				if (!$json->data) {
					$return = array();
					break;
				}
				$return['title']	= $json->data->title;
				$return['minipic']	= $json->data->cutCoverURL;
				$return['bigimg']	= $json->data->cusCoverURL;
				$return['tag']		= $json->data->tag;
				$return['time']		= $json->data->videoLength;
				break;
			case 'v.ku6.com':
				$link = 'http://v.ku6.com/fetchVideo4Player/'.$id.'.html';
				$json = Url::getContents($link);
				$json = json_decode($json);
				if ($json->status!=1) {
					$return = array();
					break;
				}
				preg_match_all('/t\|(.*?)\;/', urldecode($json->data->ad), $m);
				if (isset($m[1])) {
					$return['tag']	= join(',', $m[1]);
				}
				$return['title']	= $json->data->t;
				$return['minipic']	= $json->data->picpath;
				$return['bigimg']	= $json->data->bigpicpath;
				$return['time']		= $json->data->vtime;
				break;
			case 'www.56.com':
				$link = 'http://vxml.56.com/json/'.$id.'/?src=out';
				$json = Url::getContents($link);
				$json = json_decode($json);
				if ($json->status!=1) {
					$return = array();
					break;
				}
				$return['title']	= $json->info->Subject;
				$return['minipic']	= $json->info->img;
				$return['bigimg']	= $json->info->bimg;
				$return['time']		= $json->info->duration/1000;
				$return['tag']		= $json->info->tags;
				break;
				
			case 'v.qq.com':
				$contents = Url::getContents($video_url);
				$pattern = '~<li id="li_'.$id.'">[\s]*<img src="(?<minipic>[^"]+)" '
							.'width="[\d]+px" height="[\d]+px"/>[\s]*'
							.'<p>[\s]*<a href="javascript:;" id="'.$id.'" sv="'.$id.'" '
							.'tl="(?<time>[\d]+)" ut="[^"]+" tags="(?<tag>[^"]+)" '
							.'source="[^"]*" ptw="[^"]*" tp="[^"]*">'
							.'(?<title>[\s\S]*?)</a>[\s]*<span>.*?</span>[\s]*</p>[\s]*</li>'
							.'~is';
				
				preg_match($pattern, $contents, $matches);
				
				$return['title']	= $matches['title'];
				$return['minipic']	= $matches['minipic'];
				$return['time']		= $matches['time'];
				$return['tag']		= $matches['tag'];
				break;
		}
		return $return;
	}
	
	/**
	 * 显示视频的播放地址信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function showVideo($id)
	{
		
	}
	
	/**
	 * 获取页面HEADER中location的信息
	 * @param string $url
	 */
	public static function http_get_location($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		$pos = strpos($content, 'Location:');
		$content = substr($content, $pos+10);
		$content = substr($content, 0, strpos($content, "\r\n"));
		$content = urldecode($content);
		return $content;
	} 
}



