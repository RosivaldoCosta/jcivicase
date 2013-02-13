<?php
/**
 * Tine 2.0
 *
 * @package     Felamimail
 * @subpackage  Controller
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Message.php 18995 2011-02-01 16:48:47Z p.schuele@metaways.de $
 */

/**
 * cache controller for Felamimail messages
 *
 * @package     Felamimail
 * @subpackage  Controller
 */
class Felamimail_Controller_Cache_Message extends Felamimail_Controller_Message
{
    /**
     * number of imported messages in one caching step
     *
     * @var integer
     */
    protected $_importCountPerStep = 50;
    
    /**
     * number of fetched messages for one step of flag sync
     *
     * @var integer
     */
    protected $_flagSyncCountPerStep = 1000;
    
    /**
     * initial cache status (used by updateCache and helper funcs)
     * 
     * @var string
     */
    protected $_initialCacheStatus = NULL;

    /**
     * message sequence in cache (used by updateCache and helper funcs)
     * 
     * @var integer
     */
    protected $_cacheMessageSequence = NULL;

    /**
     * message sequence on imap server (used by updateCache and helper funcs)
     * 
     * @var integer
     */
    protected $_imapMessageSequence = NULL;

    /**
     * start of cache update in seconds+microseconds/unix timestamp (used by updateCache and helper funcs)
     * 
     * @var float
     */
    protected $_timeStart = NULL;
    
    /**
     * time elapsed in seconds (used by updateCache and helper funcs)
     * 
     * @var integer
     */
    protected $_timeElapsed = 0;

    /**
     * time for update in seconds (used by updateCache and helper funcs)
     * 
     * @var integer
     */
    protected $_availableUpdateTime = 0;
    
    /**
     * holds the instance of the singleton
     *
     * @var Felamimail_Controller_Cache_Message
     */
    private static $_instance = NULL;
    
    /**
     * the constructor
     *
     * don't use the constructor. use the singleton
     */
    private function __construct() {
        $this->_backend = new Felamimail_Backend_Cache_Sql_Message();
        $this->_currentAccount = Tinebase_Core::getUser();
    }
    
    /**
     * don't clone. Use the singleton.
     *
     */
    private function __clone() 
    {        
    }
    
    /**
     * the singleton pattern
     *
     * @return Felamimail_Controller_Cache_Message
     */
    public static function getInstance() 
    {
        if (self::$_instance === NULL) {
            self::$_instance = new Felamimail_Controller_Cache_Message();
        }
        
        return self::$_instance;
    }
    
    /**
     * update message cache
     * 
     * @param string $_folder
     * @param integer $_time in seconds
     * @return Felamimail_Model_Folder folder status (in cache)
     * @throws Felamimail_Exception
     */
    public function updateCache($_folder, $_time = 10)
    {
        $oldMaxExcecutionTime = ini_get('max_execution_time');
        
        Tinebase_Core::setExecutionLifeTime(300); // 5 minutes
        
        // always read folder from database
        $folder = Felamimail_Controller_Folder::getInstance()->get($_folder);
        
        if ($this->_doNotUpdateCache($folder)) {
            return $folder;
        }
        
        $imap = Felamimail_Backend_ImapFactory::factory($folder->account_id);
        
        $this->_availableUpdateTime = $_time;
        
        $this->_expungeCacheFolder($folder, $imap);
        $this->_initUpdate($folder);
        $this->_updateMessageSequence($folder, $imap);
        $this->_deleteMessagesInCache($folder, $imap);
        $this->_addMessagesToCache($folder, $imap);
        $this->_checkForMissingMessages($folder, $imap);
        $this->_updateFolderStatus($folder);
        $this->updateFlags($folder);
        
        // reset max execution time to old value
        Tinebase_Core::setExecutionLifeTime($oldMaxExcecutionTime);
        
        return $folder;
    }
    
    /**
     * checks if cache update should not commence / fencing
     * 
     * @return boolean
     */
    protected function _doNotUpdateCache(Felamimail_Model_Folder $_folder)
    {
        if ($_folder->is_selectable == false) {
            // nothing to be done
            return FALSE;
        }
        
        if (Felamimail_Controller_Cache_Folder::getInstance()->updateAllowed($_folder) !== true) {
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " update of folder {$_folder->globalname} currently not allowed. do nothing!");
            return FALSE;
        }
    }
    
    /**
     * expunge cache folder
     * 
     * @param Felamimail_Model_Folder $_folder
     * @param Felamimail_Backend_ImapProxy $_imap
     * @throws Felamimail_Exception_IMAPFolderNotFound
     */
    protected function _expungeCacheFolder(Felamimail_Model_Folder $_folder, Felamimail_Backend_ImapProxy $_imap)
    {
        try {
            $_imap->expunge(Felamimail_Model_Folder::encodeFolderName($_folder->globalname));
        } catch (Zend_Mail_Storage_Exception $zmse) {
            if (Tinebase_Core::isLogLevel(Zend_Log::INFO)) Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' Removing no longer existing folder ' . $_folder->globalname . ' from cache. ' .$zmse->getMessage() );
            Felamimail_Controller_Cache_Folder::getInstance()->delete($_folder->getId());
            throw new Felamimail_Exception_IMAPFolderNotFound('Folder not found: ' . $_folder->globalname);
        }
    }
    
    /**
     * init cache update process
     * 
     * @param Felamimail_Model_Folder $_folder
     * @return void
     */
    protected function _initUpdate(Felamimail_Model_Folder $_folder)
    {
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " status of folder {$_folder->globalname}: {$_folder->cache_status}");
        
        $this->_initialCacheStatus = $_folder->cache_status;
        
        // reset cache counter when transitioning from Felamimail_Model_Folder::CACHE_STATUS_COMPLETE or 
        if ($_folder->cache_status == Felamimail_Model_Folder::CACHE_STATUS_COMPLETE || $_folder->cache_status == Felamimail_Model_Folder::CACHE_STATUS_EMPTY) {
            $_folder->cache_job_actions_estimate = 0;
            $_folder->cache_job_actions_done     = 0;
            $_folder->cache_job_startuid         = 0;
        }
        
        $_folder = Felamimail_Controller_Cache_Folder::getInstance()->getIMAPFolderCounter($_folder);
        
        if (isset($_folder->cache_uidvalidity) && $_folder->imap_uidvalidity != $_folder->cache_uidvalidity) {
            if (Tinebase_Core::isLogLevel(Zend_Log::TRACE)) Tinebase_Core::getLogger()->trace(__METHOD__ . '::' . __LINE__ . ' uidvalidity changed => clear cache: ' . print_r($_folder->toArray(), TRUE));
            $_folder = $this->clear($_folder);
        }
        
        if ($_folder->imap_totalcount == 0 && $_folder->cache_totalcount > 0) {
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " folder is empty on imap server => clear cache of folder {$_folder->globalname}");
            $_folder = $this->clear($_folder);
        }
        
        $_folder->cache_status    = Felamimail_Model_Folder::CACHE_STATUS_UPDATING;
        $_folder->cache_timestamp = Tinebase_DateTime::now();
        //$_folder->cache_uidnext   = $_folder->imap_uidnext;
        
        $this->_timeStart = microtime(true);
    }
    
    /**
     * at which sequence is the message with the highest messageUid (cache + imap)?
     * 
     * @param Felamimail_Model_Folder $_folder
     * @param Felamimail_Backend_ImapProxy $_imap
     */
    protected function _updateMessageSequence(Felamimail_Model_Folder $_folder, Felamimail_Backend_ImapProxy $_imap)
    {
        if ($_folder->imap_totalcount > 0) { 
            $transactionId = Tinebase_TransactionManager::getInstance()->startTransaction(Tinebase_Core::getDb());
        
            $lastFailedUid   = null;
            $messageSequence = null;
            $decrementMessagesCounter = 0;
            $decrementUnreadCounter   = 0;
            
            while ($messageSequence === null) {
                $latestMessageUidArray = $this->_getLatestMessageUid($_folder);
                
                if (is_array($latestMessageUidArray)) {
                    $latestMessageId  = key($latestMessageUidArray);
                    $latestMessageUid = current($latestMessageUidArray);
                    
                    if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " $latestMessageId  $latestMessageUid");
                    
                    if ($latestMessageUid === $lastFailedUid) {
                        throw new Felamimail_Exception('failed to delete invalid messageuid from cache');
                    }
                    
                    if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " Check messageUid {$latestMessageUid} in folder " . $_folder->globalname);
                    
                    try {
                        $this->_imapMessageSequence  = $_imap->resolveMessageUid($latestMessageUid);
                        $this->_cacheMessageSequence = $_folder->cache_totalcount;
                        $messageSequence             = $this->_imapMessageSequence + 1;
                    } catch (Zend_Mail_Protocol_Exception $zmpe) {
                        // message does not exist on imap server anymore, remove from local cache
                        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . " messageUid {$latestMessageUid} not found => remove from cache");
                        
                        $lastFailedUid = $latestMessageUid;
                        
                        $latestMessage = $this->_backend->get($latestMessageId);
                        $this->_backend->delete($latestMessage);
                        
                        $_folder->cache_totalcount--;
                        $decrementMessagesCounter++;
                        if (! $latestMessage->hasSeenFlag()) {
                            $decrementUnreadCounter++;
                        }
                    }
                } else {
                    $this->_imapMessageSequence = 0;
                    $messageSequence = 1;
                }
                
                if (! $this->_timeLeft()) {
                    $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_INCOMPLETE;
                    break;
                }
            }
            
            Tinebase_TransactionManager::getInstance()->commitTransaction($transactionId);
            
            Felamimail_Controller_Folder::getInstance()->updateFolderCounter($_folder, array(
                'cache_totalcount'  => "-$decrementMessagesCounter",
                'cache_unreadcount' => "-$decrementUnreadCounter",
            ));
        }
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . " Cache status cache total count: {$_folder->cache_totalcount} imap total count: {$_folder->imap_totalcount} cache sequence: $this->_cacheMessageSequence imap sequence: $this->_imapMessageSequence");
    }
    
    /**
     * get message with highest messageUid from cache 
     * 
     * @param  mixed  $_folderId
     * @return Felamimail_Model_Message
     */
    protected function _getLatestMessage($_folderId) 
    {
        $folderId = ($_folderId instanceof Felamimail_Model_Folder) ? $_folderId->getId() : $_folderId;
        
        $filter = new Felamimail_Model_MessageFilter(array(
            array(
                'field'    => 'folder_id', 
                'operator' => 'equals', 
                'value'    => $folderId
            )
        ));
        $pagination = new Tinebase_Model_Pagination(array(
            'limit' => 1,
            'sort'  => 'messageuid',
            'dir'   => 'DESC'
        ));
        
        $result = $this->_backend->search($filter, $pagination);
        
        if(count($result) === 0) {
            return null;
        }
        
        return $result[0];
    }
    
    /**
     * get message with highest messageUid from cache 
     * 
     * @param  mixed  $_folderId
     * @return Felamimail_Model_Message
     */
    protected function _getLatestMessageUid($_folderId) 
    {
        $folderId = ($_folderId instanceof Felamimail_Model_Folder) ? $_folderId->getId() : $_folderId;
        
        $filter = new Felamimail_Model_MessageFilter(array(
            array(
                'field'    => 'folder_id', 
                'operator' => 'equals', 
                'value'    => $folderId
            )
        ));
        $pagination = new Tinebase_Model_Pagination(array(
            'limit' => 1,
            'sort'  => 'messageuid',
            'dir'   => 'DESC'
        ));
        
        $result = $this->_backend->searchMessageUids($filter, $pagination);
        
        if(count($result) === 0) {
            return null;
        }
        
        return $result;
    }
    
    /**
     * do we have time left for update (updates elapsed time)?
     * 
     * @return boolean
     */
    protected function _timeLeft()
    {
        $this->_timeElapsed = round(((microtime(true)) - $this->_timeStart));
        return ($this->_timeElapsed < $this->_availableUpdateTime);
    }
    
    /**
     * delete messages in cache
     * 
     *   - if the latest message on the cache has a different sequence number then on the imap server
     *     then some messages before the latest message(from the cache) got deleted
     *     we need to remove them from local cache first
     *     
     *   - $folder->cache_totalcount equals to the message sequence of the last message in the cache
     * 
     * @param Felamimail_Model_Folder $_folder
     * @param Felamimail_Backend_ImapProxy $_imap
     */
    protected function _deleteMessagesInCache(Felamimail_Model_Folder $_folder, Felamimail_Backend_ImapProxy $_imap)
    {
        if ($_folder->imap_totalcount > 0 && $this->_cacheMessageSequence > $this->_imapMessageSequence) {

            $messagesToRemoveFromCache = $this->_cacheMessageSequence - $this->_imapMessageSequence;
            
            if ($this->_initialCacheStatus == Felamimail_Model_Folder::CACHE_STATUS_COMPLETE || $this->_initialCacheStatus == Felamimail_Model_Folder::CACHE_STATUS_EMPTY) {
                $_folder->cache_job_actions_estimate += $messagesToRemoveFromCache;
            }        
            
            $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_INCOMPLETE;
            
            if ($this->_timeElapsed < $this->_availableUpdateTime) {
            
                $begin = $_folder->cache_job_startuid > 0 ? $_folder->cache_job_startuid : $_folder->cache_totalcount;
                // legacy handling => can be removed for next major release after Neele
                if ($begin > $_folder->cache_totalcount) {
                    $begin = $_folder->cache_totalcount;
                }
                
                $firstMessageSequence = 0;
                 
                if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " $messagesToRemoveFromCache message to remove from cache. starting at $begin");
                
                for ($i=$begin; $i > 0; $i -= $this->_importCountPerStep) {
                    $firstMessageSequence = ($i-$this->_importCountPerStep) >= 0 ? $i-$this->_importCountPerStep : 0;
                    if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " fetching from $firstMessageSequence");
                    $cachedMessageUids = $this->_getCachedMessageUidsChunked($_folder, $firstMessageSequence);

                    // $cachedMessageUids can be empty if we fetch the last chunk
                    if (count($cachedMessageUids) > 0) {
                        $messageUidsOnImapServer = $_imap->messageUidExists($cachedMessageUids);
                        
                        $difference = array_diff($cachedMessageUids, $messageUidsOnImapServer);
                        
                        if (Tinebase_Core::isLogLevel(Zend_Log::TRACE)) Tinebase_Core::getLogger()->trace(__METHOD__ . '::' . __LINE__ .  print_r($difference, true));
                        
                        if (count($difference) > 0) {
                            $decrementMessagesCounter = 0;
                            $decrementUnreadCounter   = 0;
                            
                            #$cachedMessages->addIndices(array('messageuid'));
                            
                            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  
                                ' Delete ' . count($difference) . ' messages'
                            );
                            
                            $messagesToBeDeleted = $this->_backend->getMultiple(array_keys($difference));
                            
                            $transactionId = Tinebase_TransactionManager::getInstance()->startTransaction(Tinebase_Core::getDb());
                            
                            foreach ($messagesToBeDeleted as $messageToBeDeleted) {
                                $this->_backend->delete($messageToBeDeleted);
                                
                                $_folder->cache_job_actions_done++;
                                $_folder->cache_totalcount--;
                                $decrementMessagesCounter++;
                                if (! $messageToBeDeleted->hasSeenFlag()) {
                                    $decrementUnreadCounter++;
                                }
                                
                                $messagesToRemoveFromCache--;
                            }
                            
                            Tinebase_TransactionManager::getInstance()->commitTransaction($transactionId);
                            
                            Felamimail_Controller_Folder::getInstance()->updateFolderCounter($_folder, array(
                                'cache_totalcount'  => "-$decrementMessagesCounter",
                                'cache_unreadcount' => "-$decrementUnreadCounter",
                            ));
                            
                            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " Cache status cache total count: {$_folder->cache_totalcount} imap total count: {$_folder->imap_totalcount} messages to remove: $messagesToRemoveFromCache");
                        }
                        
                        if ($messagesToRemoveFromCache <= 0) {
                            $_folder->cache_job_startuid = 0;
                            $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_UPDATING;
                            break;
                        }
                    }
                    
                    if (! $this->_timeLeft()) {
                        $_folder->cache_job_startuid = $i;
                        break;
                    }
                }
                
                if ($firstMessageSequence === 0) {
                    $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_UPDATING;
                }
            }
        }
        
        $this->_cacheMessageSequence = $_folder->cache_totalcount;
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . " Cache status cache total count: {$_folder->cache_totalcount} imap total count: {$_folder->imap_totalcount} cache sequence: $this->_cacheMessageSequence imap sequence: $this->_imapMessageSequence");
                
        Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Folder cache status: ' . $_folder->cache_status);      
        Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Folder cache actions to be done yet: ' . ($_folder->cache_job_actions_estimate - $_folder->cache_job_actions_done));        
    }
    
    /**
     * get message with highest messageUid from cache 
     * 
     * @param  mixed  $_folderId
     * @return array
     */
    protected function _getCachedMessageUidsChunked($_folderId, $_firstMessageSequnce) 
    {
        $folderId = ($_folderId instanceof Felamimail_Model_Folder) ? $_folderId->getId() : $_folderId;
        
        $filter = new Felamimail_Model_MessageFilter(array(
            array(
                'field'    => 'folder_id', 
                'operator' => 'equals', 
                'value'    => $folderId
            )
        ));
        $pagination = new Tinebase_Model_Pagination(array(
            'start' => $_firstMessageSequnce,
            'limit' => $this->_importCountPerStep,
            'sort'  => 'messageuid',
            'dir'   => 'ASC'
        ));
        
        $result = $this->_backend->searchMessageUids($filter, $pagination);
        
        return $result;
    }
    
    /**
     * add new messages to cache
     * 
     * @param Felamimail_Model_Folder $_folder
     * @param Felamimail_Backend_ImapProxy $_imap
     */
    protected function _addMessagesToCache(Felamimail_Model_Folder $_folder, Felamimail_Backend_ImapProxy $_imap)
    {
        // add new messages to cache
        if ($_folder->imap_totalcount > 0 && $this->_imapMessageSequence < $_folder->imap_totalcount) {
                        
            if ($this->_initialCacheStatus == Felamimail_Model_Folder::CACHE_STATUS_COMPLETE || $this->_initialCacheStatus == Felamimail_Model_Folder::CACHE_STATUS_EMPTY) {
                $_folder->cache_job_actions_estimate += ($_folder->imap_totalcount - $this->_imapMessageSequence);
            }
            
            $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_INCOMPLETE;
            
            if ($this->_timeLeft()) {
                $messageSequenceStart = $this->_imapMessageSequence + 1;
                
                // add new messages
                if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " Retrieve message from $messageSequenceStart to {$_folder->imap_totalcount}");
                
                while ($messageSequenceStart <= $_folder->imap_totalcount) {
                    
                    $messageSequenceEnd = (($_folder->imap_totalcount - $messageSequenceStart) > $this->_importCountPerStep ) ? $messageSequenceStart+$this->_importCountPerStep : $_folder->imap_totalcount;
                    
                    if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " Fetch message from $messageSequenceStart to $messageSequenceEnd $this->_timeElapsed / $this->_availableUpdateTime");
                    
                    $messages = $_imap->getSummary($messageSequenceStart, $messageSequenceEnd, false);

                    $this->_addMessagesToCacheAndIncreaseCounters($messages, $_folder);
                    
                    $messageSequenceStart = $messageSequenceEnd + 1;
                    
                    if (! $this->_timeLeft()) {
                        break;
                    }
                    Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Folder cache status: ' . $_folder->cache_status);           
                }
                
                if ($messageSequenceEnd == $_folder->imap_totalcount) {
                    $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_UPDATING;
                }
            }
        }
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . " Cache status cache total count: {$_folder->cache_totalcount} imap total count: {$_folder->imap_totalcount} cache sequence: $this->_cacheMessageSequence imap sequence: $this->_imapMessageSequence");
    }

    /**
     * add imap messages to cache and increase counters
     * 
     * @param array $_messages
     * @param Felamimail_Model_Folder $_folder
     * @return Felamimail_Model_Folder
     */
    protected function _addMessagesToCacheAndIncreaseCounters($_messages, $_folder)
    {
        $incrementMessagesCounter = 0;
        $incrementUnreadCounter   = 0;
        
        $transactionId = Tinebase_TransactionManager::getInstance()->startTransaction(Tinebase_Core::getDb());
        
        foreach ($_messages as $uid => $message) {
            if (Tinebase_Core::isLogLevel(Zend_Log::TRACE)) Tinebase_Core::getLogger()->trace(__METHOD__ . '::' . __LINE__ .  " Add message $uid to cache");
            $addedMessage = $this->addMessage($message, $_folder, false);
            
            if ($addedMessage) {
                $_folder->cache_job_actions_done++;
                $incrementMessagesCounter++;
                if (! $addedMessage->hasSeenFlag()) {
                    $incrementUnreadCounter++;
                }
            }
        }
        
        Tinebase_TransactionManager::getInstance()->commitTransaction($transactionId);
        
        Felamimail_Controller_Folder::getInstance()->updateFolderCounter($_folder, array(
            'cache_totalcount'  => "+$incrementMessagesCounter",
            'cache_unreadcount' => "+$incrementUnreadCounter",
        ));
    }
    
    /**
     * maybe there are some messages missing before $this->_imapMessageSequence
     * 
     * @param Felamimail_Model_Folder $_folder
     * @param Felamimail_Backend_ImapProxy $_imap
     */
    protected function _checkForMissingMessages(Felamimail_Model_Folder $_folder, Felamimail_Backend_ImapProxy $_imap)
    {
        if ($_folder->imap_totalcount > 0 && $_folder->cache_totalcount < $_folder->imap_totalcount) {
            
            if ($this->_initialCacheStatus == Felamimail_Model_Folder::CACHE_STATUS_COMPLETE || $this->_initialCacheStatus == Felamimail_Model_Folder::CACHE_STATUS_EMPTY) {
                $_folder->cache_job_actions_estimate += ($_folder->imap_totalcount - $_folder->cache_totalcount);
            }
            
            $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_INCOMPLETE;
            
            if ($this->_timeLeft()) { 
                // add missing messages
                if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " Retrieve message from {$_folder->imap_totalcount} to 1");
                
                $begin = $_folder->cache_job_lowestuid > 0 ? $_folder->cache_job_lowestuid : $this->_imapMessageSequence;
                
                for ($i = $begin; $i > 0; $i -= $this->_importCountPerStep) { 
                    
                    $messageSequenceStart = (($i - $this->_importCountPerStep) > 0 ) ? $i - $this->_importCountPerStep : 1;
                    
                    if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .  " Fetch message from $messageSequenceStart to $i $this->_timeElapsed / $this->_availableUpdateTime");
                    
                    $messageUidsOnImapServer = $_imap->resolveMessageSequence($messageSequenceStart, $i);
                    
                    $missingUids = $this->_getMissingMessageUids($_folder, $messageUidsOnImapServer);
                    
                    if (count($missingUids) != 0) {
                        $messages = $_imap->getSummary($missingUids);
                        $this->_addMessagesToCacheAndIncreaseCounters($messages, $_folder);
                    }
                    
                    if ($_folder->cache_totalcount == $_folder->imap_totalcount || $messageSequenceStart == 1) {
                        $_folder->cache_job_lowestuid = 0;
                        $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_UPDATING;
                        break;
                    }
                    
                    if (! $this->_timeLeft()) {
                        $_folder->cache_job_lowestuid = $messageSequenceStart;
                        break;
                    }
                    Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Folder cache status: ' . $_folder->cache_status);           
                }
                
                if (defined('messageSequenceStart') && $messageSequenceStart === 1) {
                    $_folder->cache_status = Felamimail_Model_Folder::CACHE_STATUS_UPDATING;
                }
            }
        }
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . " Cache status cache total count: {$_folder->cache_totalcount} imap total count: {$_folder->imap_totalcount} cache sequence: $this->_cacheMessageSequence imap sequence: $this->_imapMessageSequence");
                
        Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Folder cache status: ' . $_folder->cache_status);        
    }
    
    /**
     * add one message to cache
     * 
     * @param  array                    $_message
     * @param  Felamimail_Model_Folder  $_folder
     * @param  bool                     $_updateFolderCounter
     * @return Felamimail_Model_Message|bool
     */
    public function addMessage(array $_message, Felamimail_Model_Folder $_folder, $_updateFolderCounter = true)
    {
        if (! array_key_exists('header', $_message) || ! is_array($_message['header'])) {
            if (Tinebase_Core::isLogLevel(Zend_Log::NOTICE)) Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' Email uid ' . $_message['uid'] . ' has no headers. Skipping ...');
            return FALSE;
        }
        
        $messageToCache = new Felamimail_Model_Message(array(
            'account_id'    => $_folder->account_id,
            'messageuid'    => $_message['uid'],
            'folder_id'     => $_folder->getId(),
            'timestamp'     => Tinebase_DateTime::now(),
            'received'      => Felamimail_Message::convertDate($_message['received']),
            'size'          => $_message['size'],
            'flags'         => $_message['flags'],
        ));

        $messageToCache->parseStructure($_message['structure']);
        $messageToCache->parseHeaders($_message['header']);
        $messageToCache->parseBodyParts();
        
        $attachments = $this->getAttachments($messageToCache);
        
        $messageToCache->has_attachment = (count($attachments) > 0) ? true : false;
        
        //if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($messageToCache->toArray(), TRUE));
        
        try {
            $result = $this->_backend->create($messageToCache);
            // only cache message headers if received during the last day
            if ( $messageToCache->received->isLater(Tinebase_DateTime::now()->subDay(1)) ) {
                $cacheId = 'getMessageHeaders' . $result->getId();
                Tinebase_Core::getCache()->save($_message['header'], $cacheId, array('getMessageHeaders'));
            }
        } catch (Zend_Db_Statement_Exception $zdse) {
            // perhaps we already have this message in our cache (duplicate)
            if (Tinebase_Core::isLogLevel(Zend_Log::NOTICE)) Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' ' . $zdse->getMessage());
            $result = $this->_backend->getByProperty($messageToCache->messageuid, 'messageuid');
            $_updateFolderCounter = FALSE;
        }
        
        if ($_updateFolderCounter == TRUE) {
            Felamimail_Controller_Folder::getInstance()->updateFolderCounter($_folder, array(
                'cache_totalcount'  => '+1',
                'cache_unreadcount' => (! $messageToCache->hasSeenFlag())   ? '+1' : '+0',
            ));
        }
        
        /*
        // store in local cache if received during the last day
        // disabled again for performance reason
        if($createdMessage->received->compare(Tinebase_DateTime::now()->subDay(1)) == 1) {
            if (Tinebase_Core::isLogLevel(Zend_Log::INFO)) Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . 
                ' prefetch imap message to local cache ' . $createdMessage->getId()
            );            
            $this->getCompleteMessage($createdMessage);
        }
        */

        return $result;
    }
    
    /**
     * update folder status and counters
     * 
     * @param Felamimail_Model_Folder $_folder
     */
    protected function _updateFolderStatus(Felamimail_Model_Folder $_folder)
    {
        if ($_folder->cache_status == Felamimail_Model_Folder::CACHE_STATUS_UPDATING) {
            $_folder->cache_status               = Felamimail_Model_Folder::CACHE_STATUS_COMPLETE;
            //$_folder->cache_uidnext              = $_folder->imap_uidnext;
            $_folder->cache_job_actions_estimate = 0;
            $_folder->cache_job_actions_done     = 0;
            $_folder->cache_job_lowestuid        = 0;
            $_folder->cache_job_startuid         = 0;
        }
        
        if ($_folder->cache_status == Felamimail_Model_Folder::CACHE_STATUS_COMPLETE && ($_folder->cache_totalcount != $_folder->imap_totalcount)) {
            if (Tinebase_Core::isLogLevel(Zend_Log::NOTICE)) Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . 
                " something went wrong while in/decrementing counters => recalculate cache counters by counting rows in database. Cache status cache total count: {$_folder->cache_totalcount} imap total count: {$_folder->imap_totalcount}"
            );
                        
            $updatedCounters = Felamimail_Controller_Cache_Folder::getInstance()->getCacheFolderCounter($_folder);
            Felamimail_Controller_Folder::getInstance()->updateFolderCounter($_folder, $updatedCounters);
            
            if ($updatedCounters['cache_totalcount'] != $_folder->imap_totalcount) {
                // there are still messages missing in the cache
                $_folder->cache_status == Felamimail_Model_Folder::CACHE_STATUS_INCOMPLETE;
            }
        }
        
        $_folder = Felamimail_Controller_Folder::getInstance()->update($_folder);
        
        if (Tinebase_Core::isLogLevel(Zend_Log::TRACE)) Tinebase_Core::getLogger()->trace(__METHOD__ . '::' . __LINE__ . ' Folder values after import of all messages: ' . print_r($_folder->toArray(), TRUE));
    }
    
    /**
     * get message with highest messageUid from cache 
     * 
     * @param  mixed  $_folderId
     * @return array
     */
    protected function _getMissingMessageUids($_folderId, array $_messageUids) 
    {
        $folderId = ($_folderId instanceof Felamimail_Model_Folder) ? $_folderId->getId() : $_folderId;
        
        $filter = new Felamimail_Model_MessageFilter(array(
            array(
                'field'    => 'folder_id', 
                'operator' => 'equals', 
                'value'    => $folderId
            ),
            array(
                'field'    => 'messageuid', 
                'operator' => 'in', 
                'value'    => $_messageUids
            )
        ));
        
        $messageUidsInCache = $this->_backend->search($filter)->messageuid;
        
        $result = array_diff($_messageUids, $messageUidsInCache);
        
        return $result;
    }
    
    /**
     * this function returns all messsageUids which are not yet in the local cache
     * 
     * @param  Felamimail_Model_Folder  $_folder
     * @param  array                    $_uids
     * @return array  the missing messageUids in local cache
     */
    protected function _getMissingUids(Felamimail_Model_Folder $_folder, $_uids)
    {
        $uids = (array) $_uids;
        
        if (empty($uids)) {
            return $uids;
        }
        
        $filter = new Felamimail_Model_MessageFilter(array(
            array(
                'field'    => 'messageuid',
                'operator' => 'in',
                'value'    => $uids
            ),
            array(
                'field'    => 'folder_id',
                'operator' => 'equals',
                'value'    => $_folder->getId()
            )
        ));
        
        $foundUids = $this->_backend->search($filter)->messageuid;
        
        $missingUids = array_diff($_uids, $foundUids);
        
        if (Tinebase_Core::isLogLevel(Zend_Log::TRACE)) Tinebase_Core::getLogger()->trace(__METHOD__ . '::' . __LINE__ . ' missing uids in local cache: ' . print_r($missingUids, TRUE));
        
        return $missingUids;
    }
    
    /**
     * remove all cached messages for this folder and reset folder values / folder status is updated in the database
     *
     * @param string|Felamimail_Model_Folder $_folder
     * @return Felamimail_Model_Folder
     */
    public function clear($_folder)
    {
        $folder = ($_folder instanceof Felamimail_Model_Folder) ? $_folder : Felamimail_Controller_Folder::getInstance()->get($_folder);
        
        Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' Clearing cache of ' . $folder->globalname);
        
        $this->deleteByFolder($folder);
        
        $folder->cache_timestamp        = Tinebase_DateTime::now();
        //$folder->cache_uidnext          = 1;
        $folder->cache_status           = Felamimail_Model_Folder::CACHE_STATUS_EMPTY;
        $folder->cache_job_actions_estimate = 0;
        $folder->cache_job_actions_done = 0;
        
        Felamimail_Controller_Folder::getInstance()->updateFolderCounter($folder, array(
            'cache_totalcount'  => 0,
            'cache_recentcount' => 0,
            'cache_unreadcount' => 0
        ));
        
        $folder = Felamimail_Controller_Folder::getInstance()->update($folder);
        
        return $folder;
    }
    
    /**
     * update/synchronize flags
     * 
     * @param string|Felamimail_Model_Folder $_folder
     * @param integer $_time
     * @return Felamimail_Model_Folder
     * 
     * @todo only get flags of current batch of messages from imap?
     * @todo add status/progress to start at later messages when this is called next time?
     */
    public function updateFlags($_folder, $_time = 60)
    {
        // always read folder from database
        $folder = Felamimail_Controller_Folder::getInstance()->get($_folder);
        
        if ($folder->cache_status !== Felamimail_Model_Folder::CACHE_STATUS_COMPLETE) {
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . 
                ' Do not update flags of incomplete folder ' . $folder->globalname
            );
            return;
        }
        
        if ($this->_availableUpdateTime == 0) {
            $this->_availableUpdateTime = $_time;
            $this->_timeStart = microtime(true);
        }
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . 
            ' Updating flags of folder ' . $folder->globalname 
            . ' / start time: ' . Tinebase_DateTime::now()->toString() 
            . ' / available seconds: ' . ($this->_availableUpdateTime - $this->_timeElapsed)
        );

        // get all flags for folder
        $imap = Felamimail_Backend_ImapFactory::factory($folder->account_id);
        $imap->selectFolder(Felamimail_Model_Folder::encodeFolderName($folder->globalname));
        $flags = $imap->getFlags(1, INF);
        
        $unreadcount = $folder->cache_unreadcount;
        for ($i = $folder->cache_totalcount; $i > 0; $i -= $this->_flagSyncCountPerStep) {
            $firstMessageSequence = ($i - $this->_flagSyncCountPerStep) >= 0 ? $i - $this->_flagSyncCountPerStep : 0;
            $flagsOfCachedMessages = $this->_backend->getFlagsForFolder($folder->getId(), $firstMessageSequence, $this->_flagSyncCountPerStep);
            $this->_setFlagsOnCache($flagsOfCachedMessages, $flags, $unreadcount, $folder->getId());
            
            if(! $this->_timeLeft()) {
                break;
            }
        }
        
        Felamimail_Controller_Folder::getInstance()->updateFolderCounter($folder, array(
            'cache_unreadcount' => $unreadcount,
        ));
        
        return $folder;
    }
    
    /**
     * set flags on cache if different
     * 
     * @param array $_messages
     * @param array $_flags
     * @param integer $_unreadcount
     * @param string $_folderId
     */
    protected function _setFlagsOnCache($_messages, $_flags, &$_unreadcount, $_folderId)
    {
        $transactionId = Tinebase_TransactionManager::getInstance()->startTransaction(Tinebase_Core::getDb());
        
        $updateCount = 0;
        foreach ($_messages as $id => $cachedMessage) {
            if (array_key_exists($cachedMessage['messageuid'], $_flags)) {
                $newFlags = $_flags[$cachedMessage['messageuid']]['flags'];
                $diff1 = array_diff($cachedMessage['flags'], $newFlags);
                $diff2 = array_diff($newFlags, $cachedMessage['flags']);
                if (count($diff1) > 0 || count($diff2) > 0) {
                    if (in_array(Zend_Mail_Storage::FLAG_SEEN, $diff1)) {
                        $_unreadcount++;
                    } else if (in_array(Zend_Mail_Storage::FLAG_SEEN, $diff2)) {
                        $_unreadcount--;
                    }
                    
                    $this->_backend->setFlags(array($id), $newFlags, $_folderId);
                    $updateCount++;
                }
            }
        }
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Updated ' . $updateCount . ' flags. / new unreadcount: ' . $_unreadcount);
        
        Tinebase_TransactionManager::getInstance()->commitTransaction($transactionId);        
    }
}
