<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.2
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class News {
	
	private $_configFile = 'news';
	private $_enableShortNews = false;
	private $_shortNewsCharLimit = 100;
	
	private $_id;
	private $_language;
	private $_title;
	private $_content;
	
	function __construct() {
		
		$config = loadConfigurations($this->_configFile);
		$this->_enableShortNews = $config['news_short'];
		$this->_shortNewsCharLimit = $config['news_short_char_limit'];
		
	}
	
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) return;
		$this->_id = $id;
	}
	
	public function setLanguage($language) {
		if(!check_value($language)) return;
		$languagesList = getInstalledLanguagesList();
		if(!is_array($languagesList)) return;
		if(!in_array($language, $languagesList)) return;
		$this->_language = $language;
	}
	
	public function setTitle($title) {
		if(!check_value($title)) return;
		$this->_title = $title;
	}
	
	public function setContent($content) {
		if(!check_value($content)) return;
		$this->_content = $content;
	}
	
	function addNews($title,$content,$author='Administrator',$comments=1) {
		$this->db = Connection::Database('Me_MuOnline');
		if(check_value($title) && check_value($content) && check_value($author)) {
			if($this->checkTitle($title)) {
				if($this->checkContent($content)) {
					// make sure comments is 1 or 0
					if($comments < 0 || $comments > 1) {
						$comments = 1;
					}
				
					// collect data
					$news_data = array(
						base64_encode($title),
						$author,
						time(),
						base64_encode($content),
						$comments
					);
					
					// add news
					$add_news = $this->db->query("INSERT INTO ".WEBENGINE_NEWS." (news_title,news_author,news_date,news_content,allow_comments) VALUES (?,?,?,?,?)", $news_data);
					
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
		$this->db = Connection::Database('Me_MuOnline');
		if(Validator::Number($id)) {
			if($this->newsIdExists($id)) {
				$remove = $this->db->query("DELETE FROM ".WEBENGINE_NEWS." WHERE news_id = ?", array($id));
				if($remove) {
					
					$this->setId($id);
					$this->_deleteAllNewsTranslations();
					
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
		$this->db = Connection::Database('Me_MuOnline');
		if(check_value($id) && check_value($title) && check_value($content) && check_value($author) && check_value($comments) && check_value($date)) {
			if(!$this->newsIdExists($id)) { return false; }
			if($this->checkTitle($title) && $this->checkContent($content)) {
				$editData = array(
					base64_encode($title),
					base64_encode($content),
					$author,
					strtotime($date),
					$comments,
					$id
				);
				$query = $this->db->query("UPDATE ".WEBENGINE_NEWS." SET news_title = ?, news_content = ?, news_author = ?, news_date = ?, allow_comments = ? WHERE news_id = ?", $editData);
				if($query) {
					message('success', 'News successfully edited.');
				} else {
					message('error', lang('error_99'));
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
		$this->db = Connection::Database('Me_MuOnline');
		$news = $this->db->query_fetch("SELECT * FROM ".WEBENGINE_NEWS." ORDER BY news_id DESC");
		if(is_array($news)) {
			
			foreach($news as $id => $data) {
				$news[$id]['news_title'] = base64_decode($data['news_title']);
				$news[$id]['news_content'] = base64_decode($data['news_content']);
			}
			
			return $news;
		} else {
			return null;
		}
	}
	
	function newsIdExists($id) {
		if(!Validator::UnsignedNumber($id)) return;
		$cachedNews = loadCache('news.cache');
		if(!is_array($cachedNews)) return;
		foreach($cachedNews as $cacheData) {
			if($cacheData['news_id'] == $id) return true;
		}
		return;
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
			$this->deleteNewsFiles();
			if(is_array($news_list)) {
				foreach($news_list as $news) {
					$handle = fopen(__PATH_NEWS_CACHE__."news_".$news['news_id'].".cache", "a");
					fwrite($handle, $news['news_content']);
					fclose($handle);
					
					if($this->_enableShortNews) {
						$handle2 = fopen(__PATH_NEWS_CACHE__."news_".$news['news_id']."_s.cache", "a");
						fwrite($handle2, $this->_getShortVersion($news['news_content']));
						fclose($handle2);
					}
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
		$this->db = Connection::Database('Me_MuOnline');
		$news = $this->db->query_fetch("SELECT news_id,news_title,news_author,news_date,allow_comments FROM ".WEBENGINE_NEWS." ORDER BY news_id DESC");
		if(is_array($news)) {
			return $news;
		} else {
			return null;
		}
	}
	
	function updateNewsCacheIndex() {
		$newsList = $this->retrieveNewsDataForCache();
		if(!is_array($newsList)) {
			updateCacheFile('news.cache', '');
			return true;
		}
		
		foreach($newsList as $key => $row) {
			$this->setId($row['news_id']);
			$row['news_title'] = base64_decode($row['news_title']);
			$row['news_content'] = base64_decode($row['news_content']);
			$newsTranslations = $this->getNewsTranslationsDataList();
			if(!is_array($newsTranslations)) continue;
			foreach($newsTranslations as $translation) {
				$newsList[$key]['translations'][$translation['news_language']] = $translation['news_title'];
			}
		}
		
		$cacheData = encodeCache($newsList);
		$updateCache = updateCacheFile('news.cache', $cacheData);
		if(!$updateCache) return;
		return true;
	}
	
	function LoadCachedNews($shortVersion=false) {
		if(!check_value($this->_id)) return;
		if(!Validator::UnsignedNumber($this->_id)) return;
		
		// Load news translation cache
		if(check_value($this->_language)) {
			$newsTranslationFile = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'.cache';
			$newsTranslationFileShort = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'_s.cache';
			if($shortVersion) {
				if(file_exists($newsTranslationFileShort)) {
					$content = file_get_contents($newsTranslationFileShort);
					if($content) {
						return $content;
					}
				}
			} else {
				if(file_exists($newsTranslationFile)) {
					$content = file_get_contents($newsTranslationFile);
					if($content) {
						return $content;
					}
				}
			}
		}
		
		// Load regular news cache (short)
		if($shortVersion) {
			$shortVersion = __PATH_NEWS_CACHE__ . 'news_'.$this->_id.'_s.cache';
			if(file_exists($shortVersion)) {
				$content = file_get_contents($shortVersion);
				if($content) {
					return $content;
				}
			}
		}
		
		// Load regular news cache
		$file = __PATH_NEWS_CACHE__ . 'news_'.$this->_id.'.cache';
		if(!file_exists($file)) return;
		$content = file_get_contents($file);
		if(!$content) return;
		return $content;
	}
	
	function loadNewsData($id) {
		$this->db = Connection::Database('Me_MuOnline');
		if(check_value($id) && $this->newsIdExists($id)) {
			$query = $this->db->query_fetch_single("SELECT * FROM ".WEBENGINE_NEWS." WHERE news_id = ?", array($id));
			if($query && is_array($query)) {
				
				$query['news_title'] = base64_decode($query['news_title']);
				$query['news_content'] = base64_decode($query['news_content']);
				
				return $query;
			}
		}
	}
	
	public function getNewsTranslations() {
		$this->db = Connection::Database('Me_MuOnline');
		if(!check_value($this->_id)) return;
		$newsTranslations = $this->db->query_fetch("SELECT * FROM ".WEBENGINE_NEWS_TRANSLATIONS." WHERE news_id = ?", array($this->_id));
		if(!is_array($newsTranslations)) return;
		foreach($newsTranslations as $translation) {
			$result[] = $translation['news_language'];
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	public function addNewsTransation() {
		$this->db = Connection::Database('Me_MuOnline');
		
		if(!check_value($this->_id)) throw new Exception('The provided news id is not valid.');
		if(!check_value($this->_language)) throw new Exception('The provided news language is not valid.');
		if(!check_value($this->_title)) throw new Exception('The provided news title is not valid.');
		if(!check_value($this->_content)) throw new Exception('The provided news content is not valid.');
		
		$newsTranslations = $this->getNewsTranslations();
		if(is_array($newsTranslations)) {
			if(in_array($this->_language, $newsTranslations)) throw new Exception('A translation for this language already exists, please use the edit news translation module.');
		}
		
		$result = $this->db->query("INSERT INTO ".WEBENGINE_NEWS_TRANSLATIONS." (news_id, news_language, news_title, news_content) VALUES (?, ?, ?, ?)", array($this->_id, $this->_language, base64_encode($this->_title), base64_encode($this->_content)));
		if(!$result) throw new Exception('Could not add the news translation.');
		
		$newsTranslationFile = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'.cache';
		$newsTranslationFileShort = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'_s.cache';
		
		$handle = fopen($newsTranslationFile, 'w');
		fwrite($handle, $this->_content);
		fclose($handle);
		
		if($this->_enableShortNews) {
			$handle2 = fopen($newsTranslationFileShort, 'w');
			fwrite($handle2, $this->_getShortVersion($this->_content));
			fclose($handle2);
		}
	}
	
	public function updateNewsTransation() {
		$this->db = Connection::Database('Me_MuOnline');
		
		if(!check_value($this->_id)) throw new Exception('The provided news id is not valid.');
		if(!check_value($this->_language)) throw new Exception('The provided news language is not valid.');
		if(!check_value($this->_title)) throw new Exception('The provided news title is not valid.');
		if(!check_value($this->_content)) throw new Exception('The provided news content is not valid.');
		
		$result = $this->db->query("UPDATE ".WEBENGINE_NEWS_TRANSLATIONS." SET news_title = ?, news_content = ? WHERE news_id = ? AND news_language = ?", array(base64_encode($this->_title), base64_encode($this->_content), $this->_id, $this->_language));
		if(!$result) throw new Exception('Could not update the news translation.');
		
		$newsTranslationFile = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'.cache';
		$newsTranslationFileShort = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'_s.cache';
		
		$handle = fopen($newsTranslationFile, 'w');
		fwrite($handle, $this->_content);
		fclose($handle);
		
		if($this->_enableShortNews) {
			$handle2 = fopen($newsTranslationFileShort, 'w');
			fwrite($handle2, $this->_getShortVersion($this->_content));
			fclose($handle2);
		}
	}
	
	public function deleteNewsTranslation() {
		$this->db = Connection::Database('Me_MuOnline');
		if(!check_value($this->_id)) throw new Exception('The provided news id is not valid.');
		if(!check_value($this->_language)) throw new Exception('The provided news language is not valid.');
		
		$result = $this->db->query("DELETE FROM ".WEBENGINE_NEWS_TRANSLATIONS." WHERE news_id = ? AND news_language = ?", array($this->_id, $this->_language));
		if(!$result) throw new Exception('Could not delete news translation.');
		
		$newsTranslationFile = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'.cache';
		$newsTranslationFileShort = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'_s.cache';
		
		if(file_exists($newsTranslationFile)) {
			unlink($newsTranslationFile);
		}
		
		if(file_exists($newsTranslationFileShort)) {
			unlink($newsTranslationFileShort);
		}
	}
	
	public function loadNewsTranslationData() {
		$this->db = Connection::Database('Me_MuOnline');
		if(!check_value($this->_id)) return;
		if(!check_value($this->_language)) return;
		$result = $this->db->query_fetch_single("SELECT * FROM ".WEBENGINE_NEWS_TRANSLATIONS." WHERE news_id = ? AND news_language = ?", array($this->_id, $this->_language));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function loadNewsTranslationCache() {
		if(!check_value($this->_id)) return;
		if(!check_value($this->_language)) return;
		
		$newsTranslationFile = __PATH_NEWS_TRANSLATIONS_CACHE__.'news_'.$this->_id.'_'.$this->_language.'.cache';
		if(!file_exists($newsTranslationFile)) return;
		
		$cacheContent = file_get_contents($newsTranslationFile);
		if($cacheContent == false) return;
		
		return $cacheContent;
	}
	
	public function getNewsTranslationsDataList() {
		$this->db = Connection::Database('Me_MuOnline');
		if(!check_value($this->_id)) return;
		$result = $this->db->query_fetch("SELECT * FROM ".WEBENGINE_NEWS_TRANSLATIONS." WHERE news_id = ?", array($this->_id));
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _deleteAllNewsTranslations() {
		$this->db = Connection::Database('Me_MuOnline');
		if(!check_value($this->_id)) return;
		
		$newsTranslations = $this->getNewsTranslations();
		if(!is_array($newsTranslations)) return;

		foreach($newsTranslations as $translation) {
			try {
				$this->setLanguage($translation);
				$this->deleteNewsTranslation();
			} catch(Exception $ex) {
				continue;
			}
		}
		
		return true;
	}
	
	// SnakeDrak
	// https://stackoverflow.com/a/39569929
	private function _getShortVersion($newsData) {
		$value = html_entity_decode($newsData);
		if(mb_strwidth($value,'UTF-8') <= $this->_shortNewsCharLimit) {
			return $value;
		}
		do {
			$len = mb_strwidth( $value, 'UTF-8' );
			$len_stripped = mb_strwidth( strip_tags($value), 'UTF-8' );
			$len_tags = $len - $len_stripped;
			$value = mb_strimwidth($value, 0, $this->_shortNewsCharLimit+$len_tags, '', 'UTF-8');
		} while( $len_stripped > $this->_shortNewsCharLimit);
		$dom = new DOMDocument();
		@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $value, LIBXML_HTML_NODEFDTD);
		$value = $dom->saveHtml($dom->getElementsByTagName('body')->item(0));
		$value = mb_strimwidth($value, 6, mb_strwidth($value, 'UTF-8') - 13, '', 'UTF-8');
		return preg_replace('/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:"[^"]*"|"[^"]*"|[\w\-.:]+))?)*\s*\/?>\s*<\/\1\s*>/', '', $value);
	}

}