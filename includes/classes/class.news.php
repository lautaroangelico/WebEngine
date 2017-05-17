<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class News {
	function addNews($title,$content,$author='Administrator',$comments=1) {
		global $dB;
		if(check_value($title) && check_value($content) && check_value($author)) {
			if($this->checkTitle($title)) {
				if($this->checkContent($content)) {
					// make sure comments is 1 or 0
					if($comments < 0 || $comments > 1) {
						$comments = 1;
					}
				
					// collect data
					$news_data = array(
						htmlentities($title),
						$author,
						time(),
						$content,
						$comments
					);
					
					// add news
					$add_news = $dB->query("INSERT INTO WEBENGINE_NEWS (news_title,news_author,news_date,news_content,allow_comments) VALUES (?,?,?,?,?)", $news_data);
					
					if($add_news) {
						// success message
						message('success', lang('success_15',true));
					} else {
						message('error', lang('error_23',true));
					}
					
				} else {
					message('error', lang('error_43',true));
				}
			} else {
				message('error', lang('error_42',true));
			}
		} else {
			message('error', lang('error_41',true));
		}
	}
	
	function removeNews($id) {
		global $dB;
		if(Validator::Number($id)) {
			if($this->newsIdExists($id)) {
				$remove = $dB->query("DELETE FROM WEBENGINE_NEWS WHERE news_id = ?", array($id));
				if($remove) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function editNews($id,$title,$content,$author,$comments,$date) {
		global $dB;
		if(check_value($id) && check_value($title) && check_value($content) && check_value($author) && check_value($comments) && check_value($date)) {
			if(!$this->newsIdExists($id)) { return false; }
			if($this->checkTitle($title) && $this->checkContent($content)) {
				$editData = array(
					$title,
					$content,
					$author,
					strtotime($date),
					$comments,
					$id
				);
				$query = $dB->query("UPDATE WEBENGINE_NEWS SET news_title = ?, news_content = ?, news_author = ?, news_date = ?, allow_comments = ? WHERE news_id = ?", $editData);
				if($query) {
					message('success', 'News successfully edited.');
				} else {
					message('error', 'There was an error while editing the news.');
				}
			}
		}
	}
	
	function checkTitle($title) {
		if(check_value($title)) {
			if(strlen($title) < 4 || strlen($title) > 80) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	function checkContent($content) {
		if(check_value($content)) {
			if(strlen($content) < 4) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	function retrieveNews() {
		global $dB;
		$news = $dB->query_fetch("SELECT * FROM WEBENGINE_NEWS ORDER BY news_id DESC");
		if(is_array($news)) {
			return $news;
		} else {
			return null;
		}
	}
	
	function newsIdExists($id) {
		global $dB;
		if(Validator::Number($id)) {
			$id_exists = $dB->query_fetch_single("SELECT * FROM WEBENGINE_NEWS WHERE news_id = ?", array($id));
			if(is_array($id_exists)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function deleteNewsFiles() {
		$files = glob(__PATH_NEWS_CACHE__.'*');
		foreach($files as $file) {
			if(is_file($file)) {
				unlink($file);
			}
		}
	}
	
	function cacheNews() {
		if($this->isNewsDirWritable()) {
			$news_list = $this->retrieveNews();
			if(is_array($news_list)) {
				$this->deleteNewsFiles();
				foreach($news_list as $news) {
					$handle = fopen(__PATH_NEWS_CACHE__."news_".$news['news_id'].".cache", "a");
					fwrite($handle, $news['news_content']);
					fclose($handle);
				}
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function isNewsDirWritable() {
		if(is_writable(__PATH_NEWS_CACHE__)) {
			return true;
		} else {
			return false;
		}
	}
	
	function retrieveNewsDataForCache() {
		global $dB;
		$news = $dB->query_fetch("SELECT news_id,news_title,news_author,news_date,allow_comments FROM WEBENGINE_NEWS ORDER BY news_id DESC");
		if(is_array($news)) {
			return $news;
		} else {
			return null;
		}
	}
	
	function updateNewsCacheIndex() {
		$news_list = $this->retrieveNewsDataForCache();
		$cacheDATA = BuildCacheData($news_list);
		$updateCache = UpdateCache('news.cache',$cacheDATA);
		if($updateCache) {
			return true;
		} else {
			return false;
		}
	}
	
	function LoadCachedNews($id) {
		if(Validator::Number($id)) {
			if($this->newsIdExists($id)) {
				$file = __PATH_NEWS_CACHE__ . 'news_' . $id . '.cache';
				if(file_exists($file) && is_readable($file)) {
					return file_get_contents($file);
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function loadNewsData($id) {
		global $dB;
		if(check_value($id) && $this->newsIdExists($id)) {
			$query = $dB->query_fetch_single("SELECT * FROM WEBENGINE_NEWS WHERE news_id = ?", array($id));
			if($query && is_array($query)) {
				return $query;
			}
		}
	}

}